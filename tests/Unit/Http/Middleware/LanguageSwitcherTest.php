<?php

namespace Tests\Unit\Http\Middlware;

use App\Http\Middleware\LanguageSwitcher;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LanguageSwitcherTest extends TestCase
{
    /**
     * Test LanguageSwitcher middleware.
     *
     * @return void
     */
    public function testLanguageSwitcherMiddleware()
    {
        $request = Request::create('/setlocale/ru', 'GET');
        $middleware = new LanguageSwitcher;
        $result = $middleware->handle($request, function(){
            return 'next';
        });
        $this->assertEquals('next', $result);
    }
}
