<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Advertisement;
use App\Models\ReturnRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ReturnControllerTest extends TestCase
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

    /** @test */
    public function user_can_view_return_request(){
        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create([
            'acquirer_user_id' => $this->user->id,
        ]);

        $returnRequest = ReturnRequest::factory()->create([
            'advertisement_id' => $advertisement->id,
            'user_id' => $this->user->id,
            'reason' => 'Product is beschadigd',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get(route('returns.index'));

        $response->assertStatus(200);
        $response->assertViewIs('returns.index');
    }

    /** @test */
    public function user_can_create_return_request_with_image()
    {
        Storage::fake('public');

        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create([
            'acquirer_user_id' => $this->user->id,
        ]);

        $image = \Illuminate\Http\UploadedFile::fake()->image('return_image.jpg');

        $response = $this->actingAs($this->user)->post('/returns/' . $advertisement->id, [
            'reason' => 'Product is beschadigd',
            'image' => $image,
        ]);

        $response->assertSessionHas('success', 'Retourverzoek ingediend!');
        $this->assertDatabaseHas('return_requests', [
            'advertisement_id' => $advertisement->id,
            'user_id' => $this->user->id,
            'reason' => 'Product is beschadigd',
            'status' => 'pending',
        ]);

        Storage::disk('public')->assertExists('returns/' . $image->hashName());
    }


    /** @test */
    public function user_cannot_create_return_request_for_non_owned_product()
    {
        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create([
            'acquirer_user_id' => 999,
        ]);

        $response = $this->actingAs($this->user)->post('/returns/' . $advertisement->id, [
            'reason' => 'Ik wil het product terugsturen',
        ]);

        $response->assertSessionHas('error', 'Je kunt alleen producten retourneren die je hebt gekocht.');
    }

    /** @test */
    public function user_can_approve_return_request()
    {
        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create([
            'acquirer_user_id' => $this->user->id,
        ]);

        $returnRequest = ReturnRequest::factory()->create([
            'advertisement_id' => $advertisement->id,
            'user_id' => $this->user->id, 
            'reason' => 'Product is beschadigd',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post('/returns/' . $returnRequest->id . '/approve');

        $response->assertSessionHas('success', 'Retourverzoek goedgekeurd! Advertentie is nu weer beschikbaar.');
        $this->assertDatabaseHas('return_requests', [
            'id' => $returnRequest->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function user_can_reject_return_request()
    {
        $this->withoutMiddleware();
        $this->user = User::factory()->create(); 
        $advertisement = Advertisement::factory()->create([
            'acquirer_user_id' => $this->user->id, 
        ]);

        $returnRequest = ReturnRequest::factory()->create([
            'advertisement_id' => $advertisement->id,
            'user_id' => $this->user->id, 
            'reason' => 'Product is beschadigd',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post('/returns/' . $returnRequest->id . '/reject');

        $response->assertSessionHas('error', 'Retourverzoek afgekeurd!');
        $this->assertDatabaseHas('return_requests', [
            'id' => $returnRequest->id,
            'status' => 'rejected',
        ]);
    }


}
