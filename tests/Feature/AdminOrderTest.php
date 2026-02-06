<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;

//feature test
class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_orders_page(){

        $admin = User::factory()->admin()->create();

        $orders = Order::factory()->count(3)->create();

        $response = $this->actingAs($admin)
                    ->get(route('admin.orders.index'));
    
        $response->assertStatus(200);
        $response->assertViewHas('orders');
        
        $this->assertDatabaseCount('orders', 3);
    }

}

//test('example', function () {
//    $response = $this->get('/');

//    $response->assertStatus(200);
//});
