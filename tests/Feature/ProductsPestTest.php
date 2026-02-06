<?php

use App\Models\User;
use app\Models\Category;
use app\Models\Product;
use Database\Seeders\CategorySeeder;

beforeEach(function(){

    $this->admin = User::factory()->admin()->create();
    $this->user = createUser();
    $this->seed(CategorySeeder::class);
});

test('paginated products table contains empty table', function () {
        $this->actingAs($this->admin)
        ->get('admin/products')
        ->assertStatus(200)
        ->assertSee('No product found!');

});

test('paginated products table contains non empty tables', function(){
    
        $product = createProduct();
                
    $this->actingAs($this->admin)
        ->get('admin/products')
        ->assertStatus(200)
        ->assertDontSee('No product found!')
        ->assertViewHas('products', function($collection) use ($product){
            return $collection->contains($product);
        });
});

test('create product successful', function(){

        $category = Category::first();

        $product = [
            'name' => 'Strawberry',
            'price' => 123,
            'stock' => 5,
            'category_id' => $category->id
        ]; 
    
        $this->actingAs($this->admin)
        ->post('admin/products', $product)        
        ->assertStatus(302)
        ->assertRedirect('admin/products');
        
        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        expect($lastProduct->name)->toBe($product['name']);
        expect($lastProduct->price)->toBe($product['price']);
        
});