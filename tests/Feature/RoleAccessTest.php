<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'seller']);
        Role::create(['name' => 'customer']);
    }

    /** @test */
    public function admin_can_access_admin_endpoint_only()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user);

        $this->getJson('/api/admin/dashboard')->assertOk();
        $this->getJson('/api/seller/dashboard')->assertForbidden();
        $this->getJson('/api/customer/dashboard')->assertForbidden();
    }


    /** @test */
    public function seller_can_access_seller_endpoint_only()
    {
        $user = User::factory()->create();
        $user->assignRole('seller');
        Sanctum::actingAs($user);

        $this->getJson('/api/seller/dashboard')->assertOk();
        $this->getJson('/api/admin/dashboard')->assertForbidden();
        $this->getJson('/api/customer/dashboard')->assertForbidden();
    }


    /** @test */
    public function customer_can_access_customer_endpoint_only()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');
        Sanctum::actingAs($user);

        $this->getJson('/api/customer/dashboard')->assertOk();
        $this->getJson('/api/admin/dashboard')->assertForbidden();
        $this->getJson('/api/seller/dashboard')->assertForbidden();
    }
}
