<?php

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_responsee()
    {
        $response = $this->get(route('home'));

        //$response->assertSee('FreshMart');

        $response->assertStatus(200);
    }

    public function test_the_homepage_contains_symphony()
    {
        $response = $this->get(route('home'));

        //$response->assertSee('Symphony');

        $response->assertStatus(200);
    }
}

/*
it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});*/
