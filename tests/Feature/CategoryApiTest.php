<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');
        Sanctum::actingAs($user);
    }
    /** @test */
    public function can_list_categories()
    {
        Category::factory()->count(5)->create();
        $response = $this->getJson('/api/admin/categories');
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'slug']
        ]);
    }
    /** @test */
    public function can_create_category()
    {
        $data = [
            'name' => 'Elektronics',
        ];
        $response = $this->postJson('/api/admin/categories', $data);
        $response->assertCreated()->assertJsonFragment([
            'name' => 'Elektronics',
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => 'Elektronics',
        ]);
    }
    /** @test */
    public function can_show_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("/api/admin/categories/{$category->id}");
        $response->assertOk()->assertJsonFragment([
            'name' => $category->name,
            'slug' => $category->slug,
        ]);
    }
    /** @test */
    public function can_update_category()
    {
        $category = Category::factory()->create(['name' => 'Old Category']);
        $response = $this->putJson("/api/admin/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);
        $response->assertOk()->assertJsonFragment([
            'name' => 'Updated Category',
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    /** @test */
    public function can_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson("/api/admin/categories/{$category->id}");
        $response->assertOk()->assertJsonFragment(['message' => 'Category deleted successfully']);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
