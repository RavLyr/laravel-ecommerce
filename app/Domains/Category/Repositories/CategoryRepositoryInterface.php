<?php

namespace App\Domains\Category\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Domains\Category\Models\Category;

interface CategoryRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Category;
    public function create(array $data): Category;
    public function update(Category $category, array $data): bool;
    public function delete(Category $category): bool;
}
