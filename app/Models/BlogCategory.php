<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public function getStatusAttribute($value): string
    {
        return $value ? 'Active' : 'Inactive';
    }

    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === 1;
    }
}
