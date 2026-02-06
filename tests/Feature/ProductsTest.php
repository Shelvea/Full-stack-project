<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;


class ProductsTest extends TestCase {

    use RefreshDatabase;

    private User $admin, $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);

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

    //create many products
    private function createProducts(int $amount): Collection
    {
        return Product::factory($amount)->create();
    }

    //create one product
    private function createProduct(): Product
    {
        return Product::factory()->create();
    }

    public function test_paginated_products_table_doesnt_contain_6th_record(){

        $products = $this->createProducts(6);
        $lastProduct = $products->last();

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);

        $response->assertViewHas('products', function($collection) use ($lastProduct){
            return !$collection->contains($lastProduct);
        });
    }

    public function test_paginated_products_table_contains_non_empty_tables(){

        $product = $this->createProduct();

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));
    
        $response->assertStatus(200);
        $response->assertDontSee('No product found!');
        $response->assertViewHas('products', function($collection) use ($product){
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_contains_empty_table(){

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);

        $response->assertSee('No product found!');
    }

    public function test_customer_can_see_add_to_cart_button(){

        $products = $this->createProducts(3);

        $response = $this->actingAs($this->user)->get(route('products.fruits'));

        $response->assertStatus(200);
        $response->assertSee('Add to Cart');

    }

    public function test_admin_viewing_as_customer_cannot_see_add_to_cart_button(){

        $products = $this->createProducts(3);

        $response = $this->actingAs($this->admin)
        ->withSession(['as_customer' => true])
        ->get(route('products.fruits'));

        $response->assertStatus(200);
        $response->assertDontSee('Add to Cart');

    }

    public function test_admin_can_access_customer_product_fruit_page(){

        $response = $this->actingAs($this->admin)->get(route('products.fruits'));

        $response->assertStatus(200);

    }

    //test gate
    public function test_is_user_gate_can_see_search_product_bar_on_navigation(){

        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertSee('Search');
    }

    public function test_is_admin_gate_cannot_see_search_product_bar_on_navigation(){
        
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);

        $response->assertDontSee('Search');
    }

    public function test_admin_can_access_product_create_page(){

        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_product_create_page(){

        $response = $this->actingAs($this->user)->get(route('admin.products.create'));

        $response->assertStatus(403);
        
    }

    public function test_create_product_successful(){

        $category = Category::first();

        $product = [
            'name' => 'Strawberry',
            'price' => 123,
            'stock' => 5,
            'category_id' => $category->id
        ]; 
    
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $product);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
        
    }

    public function test_product_edit_contains_correct_values(){

        $product = $this->createProduct();//arrange 
        
        $response = $this->actingAs($this->admin)->get(route('admin.products.edit', $product->id));//act

        $response->assertStatus(200);//assert
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product',$product);

    }

    public function test_product_update_validation_error_redirects_back_to_form(){

        $product = $this->createProduct();//arrange 
        
        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product->id), [
            'name' => '',
            'price' => '',
            'stock' => '',
            'category_id' => ''
        ]);//act

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name','price','stock','category_id']);
        $response->assertInvalid(['name','price','stock','category_id']);
                
    }

    public function test_product_delete_successful(){

        $product = $this->createProduct();//arrange 
        
        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product->id));//act

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.products.index'));
        
        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseCount('products', 0);
                
    }

}