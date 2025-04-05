<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Advertisement;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CustomLink;

class CustomLinkControllerTest extends DuskTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_custom_link_successfully()
    {
        $this->withoutMiddleware();

        $response = $this->post('/custom-links', [
            'link_name' => 'unieke-link'
        ]);

        $response->assertSessionHas('link_name', 'unieke-link');
        $this->assertDatabaseHas('custom_links', [
            'link_name' => 'unieke-link'
        ]);
    }

    /** @test */
    public function it_fails_to_store_duplicate_custom_link()
    {
        $this->withoutMiddleware();

        CustomLink::create(['link_name' => 'bestaande-link']);

        $response = $this->post('/custom-links', [
            'link_name' => 'bestaande-link'
        ]);

        $response->assertSessionHasErrors('link_name');
    }

}
