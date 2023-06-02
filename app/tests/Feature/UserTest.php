<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->post('/api/login', ['email' => 'lukas@mail.com', 'password' => 'Azerty123']);

        $response->assertStatus(200);
    }

    public function test_user()
    {
        $response = $this->post('/api/login', ['email' => 'lukas@mail.com', 'password' => 'Azerty123']);
        $r = $this->get('/api/organisations/1');
        $r->assertStatus(200)
            ->
            assertJson([
                'id' => 1,
                'name' => 'Odisee Hogeschool'
            ]);
        $r = $this->get('/api/organisations/6');
        $r->assertStatus(403);
    }

//    public function test_
}
