<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Category $category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function (Category $category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
