<?php

namespace Tests\Feature;

<<<<<<< HEAD
=======
// use Illuminate\Foundation\Testing\RefreshDatabase;
>>>>>>> f017a484a6c468e69098875f0dd98e4f15c46ac3
use Tests\TestCase;

class ExampleTest extends TestCase
{
<<<<<<< HEAD
    public function test_root_redirects_to_assessments(): void
    {
        $this->get('/')->assertRedirect('/assessments');
=======
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
>>>>>>> f017a484a6c468e69098875f0dd98e4f15c46ac3
    }
}
