<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */



    public function testExample()
    {
        $this->call('GET', 'posts');
        $this->assertTrue(true);
    }

}
