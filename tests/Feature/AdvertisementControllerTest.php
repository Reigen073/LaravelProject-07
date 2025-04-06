<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Advertisement;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Controller\AdvertisementController;
use \App\Models\Bidding;
use routes\web;


class AdvertisementControllerTest extends DuskTestCase
{
    use RefreshDatabase;

    private user $user;

    protected function setUp(): void {
        parent::setUp();

        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        Storage::fake('public');
        parent::tearDown();
    }

    public function test_user_can_delete_their_own_advertisement()
    {
        $user = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
        ->delete(route('advertisements.destroy', $advertisement));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('advertisements', ['id' => $advertisement->id]);
    }

    public function test_user_can_view_advertisements_index()
    {
        $advertisements = Advertisement::factory()->count(3)->create();

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertViewIs('homepage');
        $response->assertViewHas('advertisements');
    }

    public function test_authenticated_user_can_create_advertisement()
    {
        $this->withoutMiddleware();
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('product.jpg');

        $response = $this->post(route('advertisements.store'), [
            'title' => 'Test Ad',
            'description' => 'Test description',
            'price' => 100,
            'category' => 'test',
            'wear_rate' => 0.2,
            'type' => 'buy',
            'status' => 'available',
            'condition' => 'new',
            'image' => $file,
            'expires_at' => now()->addDays(5)->toDateString(),
        ]);

        $response->assertRedirect('dashboard');
        $this->assertDatabaseHas('advertisements', [
            'title' => 'Test Ad',
            'user_id' => $user->id,
        ]);
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    public function test_user_can_edit_their_own_advertisement()
    {
        $user = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get(route('advertisements.edit', $advertisement->id));

        $response->assertStatus(200);
        $response->assertViewIs('advertisements.edit');
        $response->assertViewHas('advertisement');
    }

    public function test_user_cannot_edit_other_users_advertisement()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);
        $response = $this->get(route('advertisements.edit', $advertisement->id));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_place_a_bid_on_an_advertisement()
    {
        $user = User::factory()->create(['id' => 998]);
        $advertisementOwner = User::factory()->create(['id' => 999]);
        $advertisement = Advertisement::factory()->create([
            'user_id' => $advertisementOwner->id,
            'type' => 'bidding'
        ]);

        $response = $this->actingAs($user)->post(route('advertisements.bidding', $advertisement->id), [
            'bid_amount' => 50,
        ]);

        $response->assertRedirect(route('advertisements.info', $advertisement->id));
        $this->assertDatabaseHas('biddings', [
            'user_id' => $user->id,
            'advertisement_id' => $advertisement->id,
            'bid_amount' => 50,
        ]);
    }

    public function test_user_can_buy_an_advertisement()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $seller->id, 'status' => 'available']);

        $response = $this->actingAs($buyer)->post(route('advertisements.buy', $advertisement));

        $response->assertRedirect(route('advertisements.info', ['id' => $advertisement->id]));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('advertisements', [
            'id' => $advertisement->id,
            'status' => 'sold',
            'acquirer_user_id' => $buyer->id,
        ]);
    }

    public function test_user_cannot_buy_own_advertisement()
    {
        $user = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('advertisements.buy', $advertisement));

        $response->assertRedirect(route('advertisements.info', ['id' => $advertisement->id]));
        $response->assertSessionHas('error');
    }

    public function test_user_can_rent_an_advertisement()
    {
        $renter = User::factory()->create();
        $owner = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $owner->id, 'status' => 'available']);

        $response = $this->actingAs($renter)->post(route('advertisements.rent', $advertisement));

        $response->assertRedirect(route('advertisements.info', $advertisement->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('advertisements', [
            'id' => $advertisement->id,
            'status' => 'rented',
            'acquirer_user_id' => $renter->id,
        ]);
    }

    public function test_owner_can_accept_a_bid()
    {
        $owner = User::factory()->create();
        $bidder = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $owner->id]);
        $bidding = Bidding::factory()->create([
            'advertisement_id' => $advertisement->id,
            'user_id' => $bidder->id,
        ]);

        $response = $this->actingAs($owner)->post(route('advertisements.biddingAccept', $bidding->id));

        $response->assertRedirect(route('advertisements.agenda'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('biddings', ['id' => $bidding->id, 'status' => 'accepted']);
        $this->assertDatabaseHas('advertisements', ['id' => $advertisement->id, 'status' => 'sold']);
    }

    public function test_owner_can_reject_a_bid()
    {
        $owner = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $owner->id]);
        $bidding = Bidding::factory()->create(['advertisement_id' => $advertisement->id]);

        $response = $this->actingAs($owner)->post(route('advertisements.biddingReject', $bidding->id));

        $response->assertRedirect(route('advertisements.agenda'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('biddings', ['id' => $bidding->id, 'status' => 'rejected']);
    }

}
