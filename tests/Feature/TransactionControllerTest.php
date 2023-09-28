<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;
    // fungsi yang akan di panggil pertama kali sebelum melakukan test
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }
    public function test_req_list_transaction_true(): void
    {
        $token = User::factory()->create()->createToken('authToken')->accessToken;
     
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/transactions');
        
        $response->assertSee('true');
        $response->assertStatus(200);
    }

    public function test_req_list_transaction_false(): void
    {
        $response = $this->get('/api/transactions');
        
        $response->assertSee('false');
        $response->assertStatus(403);
    }

    public function test_req_transaction_true(): void
    {
     
        $token = User::factory()->create()->createToken('authToken')->accessToken;
     
        $header = [
          "Authorization" => "Bearer " . $token,
          "Accept" => "application/json",
        ];

        $costumer_id = Customer::create([
            'costumer_name' => 'test',
        ])->id;
        $payment_method_id = PaymentMethod::create([
            'name' => 'test',
            'is_active' => 1,
        ])->id;

        $product_id = Product::create([
            'name' => 'test',
            'price' => 1000,
            'is_active' => 1,
        ])->id;

        
        $body = [
            "customer_id" => $costumer_id,
            "payment_method_id" => $payment_method_id,
            "products" => [
                [
                    "product_id" => $product_id,
                    "quantity" => "10"
                ]
            ]
        ];

        $response = $this->withHeaders($header)->post('/api/transactions', $body);

        $this->assertDatabaseHas('transactions', [
            'customer_id' => $costumer_id,
        ]);

        $this->assertDatabaseHas('transaction_payment_methods', [
            'payment_method_id' => $payment_method_id,
        ]);

        $this->assertDatabaseHas('transaction_products', [
            'product_id' => $product_id,
            'quantity' => 10,
        ]);
        
        $response->assertSee('true');
        $response->assertStatus(200);
    }

    public function test_req_transaction_false(): void
    {
        $header = [
          "Accept" => "application/json",
        ];

        $costumer_id = Customer::create([
            'costumer_name' => 'test',
        ])->id;
        $payment_method_id = PaymentMethod::create([
            'name' => 'test',
            'is_active' => 1,
        ])->id;

        $product_id = Product::create([
            'name' => 'test',
            'price' => 1000,
            'is_active' => 1,
        ])->id;

        
        $body = [
            "customer_id" => $costumer_id,
            "payment_method_id" => $payment_method_id,
            "products" => [
                [
                    "product_id" => $product_id,
                    "quantity" => "10"
                ]
            ]
        ];

        $response = $this->withHeaders($header)->post('/api/transactions', $body);

        $this->assertDatabaseMissing('transactions', [
            'customer_id' => $costumer_id,
        ]);
        
        $response->assertSee('Unauthenticated.');
        $response->assertStatus(401);
    }

    public function test_req_transaction_false_validation_required(): void
    {
     
        $token = User::factory()->create()->createToken('authToken')->accessToken;
     
        $header = [
          "Authorization" => "Bearer " . $token,
          "Accept" => "application/json",
        ];

        $payment_method_id = PaymentMethod::create([
            'name' => 'test',
            'is_active' => 1,
        ])->id;

        $product_id = Product::create([
            'name' => 'test',
            'price' => 1000,
            'is_active' => 1,
        ])->id;

        
        $body = [
            "payment_method_id" => $payment_method_id,
            "products" => [
                [
                    "product_id" => $product_id,
                    "quantity" => "10"
                ]
            ]
        ];

        $response = $this->withHeaders($header)->post('/api/transactions', $body);
        
        $response->assertSee('false');
        $response->assertStatus(400);
    }

    public function test_req_transaction_false_validation_type_data(): void
    {
     
        $token = User::factory()->create()->createToken('authToken')->accessToken;
     
        $header = [
          "Authorization" => "Bearer " . $token,
          "Accept" => "application/json",
        ];

        $costumer_id = Customer::create([
            'costumer_name' => 'test',
        ])->id;
        $payment_method_id = PaymentMethod::create([
            'name' => 'test',
            'is_active' => 1,
        ])->id;

        $product_id = Product::create([
            'name' => 'test',
            'price' => 1000,
            'is_active' => 1,
        ])->id;

        
        $body = [
            "customer_id" => $costumer_id,
            "payment_method_id" => $payment_method_id,
            "products" => [
                    "product_id" => $product_id,
                    "quantity" => "test"
                ]
        ];

        $response = $this->withHeaders($header)->post('/api/transactions', $body);

        $this->assertDatabaseMissing('transactions', [
            'customer_id' => $costumer_id,
        ]);
        
        $response->assertSee('false');
        $response->assertStatus(400);
    }

}
