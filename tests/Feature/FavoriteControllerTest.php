<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Advertisement;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteControllerTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_user_can_add_advertisement_to_favorites()
    {
        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create();

        $response = $this->actingAs($this->user)->post('/advertisements/' . $advertisement->id . '/favorite');

        $response->assertSessionHas('success');
        $this->assertTrue($this->user->favorites()->where('advertisement_id', $advertisement->id)->exists());
    }

    public function test_user_can_remove_advertisement_from_favorites()
    {
        $this->withoutMiddleware();
        $this->user = User::factory()->create();
        $advertisement = Advertisement::factory()->create();

        $this->user->favorites()->attach($advertisement->id);

        $response = $this->actingAs($this->user)->post('/advertisements/' . $advertisement->id . '/favorite');

        $response->assertSessionHas('success');
        $this->assertFalse($this->user->fresh()->favorites()->where('advertisement_id', $advertisement->id)->exists());
    }

}
