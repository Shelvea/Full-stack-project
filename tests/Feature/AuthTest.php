<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertAuthenticatedAs;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_login_redirects_to_dashboard()
    {
        $password = 'password123';
        
        $user = User::factory()->user()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->post(route('login.attempt'),[
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_unauthenticated_customer_cannot_access_product_fruits()
    {
        $response = $this->get(route('products.fruits'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        
    }
}

/*
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});*/
