<?php

namespace Tests\Feature;

use Tests\Support\Traits\RefreshDatabaseDoctrine;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabaseDoctrine;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_one_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_two_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_three_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
