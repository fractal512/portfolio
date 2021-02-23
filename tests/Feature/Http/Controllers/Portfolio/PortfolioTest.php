<?php

namespace Tests\Feature\Http\Controllers\Portfolio;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PortfolioTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexResponse()
    {
        $response = $this->get('/portfolio');
        $response->assertStatus(200);
    }

    public function testIndexContent()
    {
        $response = $this->get('/portfolio');
        $response->assertSee('<h1 class="text-center">');
        $response->assertSee('<i class="glyphicon glyphicon-calendar"></i>');
        $response->assertSee('<i class="glyphicon glyphicon-time"></i>');
    }
}
