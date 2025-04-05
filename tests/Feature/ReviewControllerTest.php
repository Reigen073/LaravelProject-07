<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Advertisement;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ReviewControllerTest extends TestCase
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

    public function test_user_can_create_review()
    {
        $this->withoutMiddleware();
    
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'comment' => 'Great product!',
            'rating' => 5,
            'type' => 'advertisement',
        ];

        $response = $this->actingAs($this->user)->post('/reviews/' . $advertisement->id, $data);
    
        $response->assertSessionHas('success', 'Review geplaatst!');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'advertisement_id' => $advertisement->id,
            'comment' => 'Great product!',
            'rating' => 5,
        ]);
        $this->assertDatabaseCount('reviews', 1);
    
        $response->assertRedirect('/advertisements/' . $advertisement->id);
    }    

}
