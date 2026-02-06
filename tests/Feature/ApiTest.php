<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use App\Models\User;

class ApiTest extends TestCase {

    use RefreshDatabase;

    private User $admin, $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();
        $this->user = $this->createUser();

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function createAdmin(): User
    {
        return User::factory()->admin()->create();
    }

    private function createUser(): User
    {
        return User::factory()->user()->create();
    }

    public function test_api_returns_users_list(){

        Sanctum::actingAs($this->admin);
        
        $response = $this->getJson('api/users');

        $response->assertStatus(200);
        $response->assertJsonFragment([
        'email' => $this->user->email,
    ]);


    }

    public function test_api_user_delete_successful(){

        $user = $this->user;

        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson('/api/users/' . $user->id);
        
        $response->assertStatus(200); // or 204 if no content
        $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);

    }

    public function test_api_user_delete_returns_404_if_user_not_found(){

        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson('/api/users/999');
        
        $response->assertStatus(404); 

    }

    public function test_api_user_delete_requires_authentication()
    {
        $response = $this->deleteJson('/api/users/' . $this->user->id);

        $response->assertStatus(401);
    }

    public function test_api_user_delete_forbidden_for_non_admin()
    {
        Sanctum::actingAs($this->user); // not admin

        $response = $this->deleteJson('/api/users/' . $this->user->id);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_delete_self()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson('/api/users/' . $this->admin->id);

        $response->assertStatus(403);
    }

}
/*
test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});*/
