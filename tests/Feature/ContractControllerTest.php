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

    /** @test */
    public function it_allows_valid_contract_upload()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        $file = \Illuminate\Http\UploadedFile::fake()->create('contract.pdf', 500, 'application/pdf');

        $response = $this->post(route('contracts.upload'), [
            'user_id' => $user->id,
            'contract' => $file,
        ]);

        $response->assertRedirect(route('contracts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contracts', [
            'user_id' => $user->id,
        ]);

        Storage::disk('public')->assertExists('contracts/' . $file->hashName());
    }

    /** @test */
    public function it_fails_validation_when_no_file_uploaded()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        $response = $this->post(route('contracts.upload'), [
            'user_id' => $user->id,
        ]);

        $response->assertSessionHasErrors('contract');
    }

    /** @test */
    public function it_fails_validation_when_wrong_file_type_uploaded()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        $file = \Illuminate\Http\UploadedFile::fake()->create('contract.docx', 500, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->post(route('contracts.upload'), [
            'user_id' => $user->id,
            'contract' => $file,
        ]);

        $response->assertSessionHasErrors('contract');
    }

}
