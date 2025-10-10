<?php

namespace App\Models;

use App\Models\User;
use App\Models\BlogLike;
use App\Models\BlogComment;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'thumbnail',
        'status',
        'category_id',
        'author_id',
    ];

    protected static function booted()
    {
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title) . '-' . time();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function likes()
    {
        return $this->hasMany(BlogLike::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function isActive(): bool
    {
        return $this->status == 1;
    }

    public function getThumbnailUrlAttribute(): string
    {
        //
        if ($this->thumbnail && file_exists(public_path('storage/' . $this->thumbnail))) {
            return asset('storage/' . $this->thumbnail);
        }

        // default demo image
        return asset('storage/thumbnails/blogs/demo.jpg');
    }

    public function getExcerptAttribute(): string
    {
        return Str::words(strip_tags($this->description), 20, '...');
    }
}
