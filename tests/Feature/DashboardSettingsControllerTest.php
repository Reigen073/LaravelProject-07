<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DashboardSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_dashboard_settings_for_authenticated_user()
    {
        $this->withoutMiddleware();
        $user = User::factory()->create([
            'role' => 'admin',
        ]);
        $payload = [
            'show_ads' => false,
            'show_favorites' => true,
            'show_intro' => false,
            'show_image' => true,
            'show_custom_link' => false,
            'show_contracts' => true,
            'bg_color' => '#abcdef',
            'text_color' => '#123456',
        ];

        $response = $this->actingAs($user)->postJson('/dashboard/settings', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('dashboard_settings', array_merge(['user_id' => $user->id], $payload));
    }

    /** @test */
    public function it_returns_existing_dashboard_settings()
    {
        $this->withoutMiddleware();
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $settings = DashboardSetting::create([
            'user_id' => $user->id,
            'show_ads' => false,
            'show_favorites' => false,
            'show_intro' => true,
            'show_image' => true,
            'show_custom_link' => true,
            'show_contracts' => false,
            'bg_color' => '#111111',
            'text_color' => '#eeeeee',
        ]);

        $response = $this->actingAs($user)->getJson('/dashboard/settings');

        $response->assertStatus(200)
                 ->assertJson($settings->toArray());
    }

    /** @test */
    public function it_creates_default_settings_if_none_exist()
    {
        $user = User::factory()->create();

        $this->assertDatabaseMissing('dashboard_settings', ['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson('/dashboard/settings');

        $response->assertStatus(200)
                 ->assertJson([
                     'user_id' => $user->id,
                     'show_ads' => true,
                     'show_favorites' => true,
                     'show_intro' => true,
                     'show_image' => true,
                     'show_custom_link' => true,
                     'show_contracts' => true,
                     'bg_color' => '#ffffff',
                     'text_color' => '#000000',
                 ]);

        $this->assertDatabaseHas('dashboard_settings', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_requires_bg_and_text_colors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/dashboard/settings', [
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['bg_color', 'text_color']);
    }
}
