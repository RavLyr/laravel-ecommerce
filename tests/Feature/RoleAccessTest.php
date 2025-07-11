<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_endpoint_only()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);

        $this->getJson('/api/admin/dashboard')->assertOk();
        $this->getJson('/api/seller/dashboard')->assertForbidden();
        $this->getJson('/api/customer/dashboard')->assertForbidden();
    }


    /** @test */
    public function seller_can_access_seller_endpoint_only()
    {
        $user = User::factory()->create(['role' => 'seller']);
        Sanctum::actingAs($user);

        $this->getJson('/api/seller/dashboard')->assertOk();
        $this->getJson('/api/admin/dashboard')->assertForbidden();
        $this->getJson('/api/customer/dashboard')->assertForbidden();
    }


    /** @test */
    public function customer_can_access_customer_endpoint_only()
    {
        $user = User::factory()->create(['role' => 'customer']);
        Sanctum::actingAs($user);

        $this->getJson('/api/customer/dashboard')->assertOk();
        $this->getJson('/api/admin/dashboard')->assertForbidden();
        $this->getJson('/api/seller/dashboard')->assertForbidden();
    }
}
