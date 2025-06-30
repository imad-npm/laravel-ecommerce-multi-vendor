<?php

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can search products by product name', function () {
    Product::factory()->create(['name' => 'Test Product A']);
    Product::factory()->create(['name' => 'Another Product B']);

    $response = $this->getJson('/api/products?search=Test Product');

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonFragment(['name' => 'Test Product A']);
});

it('can search products by store name', function () {
    $store = Store::factory()->create(['name' => 'Test Store']);
    Product::factory()->create(['name' => 'Product C', 'store_id' => $store->id]);
    Product::factory()->create(['name' => 'Product D']);

    $response = $this->getJson('/api/products?search=Test Store');

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonFragment(['name' => 'Product C']);
});

it('can search products by vendor name', function () {
    $vendor = User::factory()->create(['name' => 'Test Vendor']);
    $store = Store::factory()->create(['user_id' => $vendor->id]);
    Product::factory()->create(['name' => 'Product E', 'store_id' => $store->id]);
    Product::factory()->create(['name' => 'Product F']);

    $response = $this->getJson('/api/products?search=Test Vendor');

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonFragment(['name' => 'Product E']);
});

it('returns empty data if no products match search criteria', function () {
    Product::factory()->create(['name' => 'Product G']);

    $response = $this->getJson('/api/products?search=NonExistent');

    $response->assertStatus(200)
             ->assertJsonCount(0, 'data');
});

