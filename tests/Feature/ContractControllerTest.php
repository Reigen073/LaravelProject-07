<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Advertisement;
use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ContractControllerTest extends TestCase
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
    public function it_shows_contracts_index_page_with_paginated_users()
    {
        User::factory()->count(10)->create();
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('contracts.index'));

        $response->assertViewIs('admin.contracts.index');
        $response->assertViewHas('users');
    }

}
