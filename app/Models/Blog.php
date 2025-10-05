<?php

namespace App\Models;

use App\Models\User;
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

    /** Auto handle slug & excerpt */
    protected static function booted()
    {
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title) . '-' . time();
            }

            // if (empty($blog->excerpt)) {
            //     $blog->excerpt = Str::limit(strip_tags($blog->description), 160);
            // }
        });
    }

    /** Relationships */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
{
    return $this->belongsTo(User::class, 'author_id');
}


    /** Related blogs */
    public function relatedBlogs($limit = 5)
    {
        return self::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->where('status', 1)
            ->latest()
            ->take($limit)
            ->get();
    }

    /** === Helpers === */
    public function isActive(): bool
    {
        return $this->status == 1;
    }

    /** Get full image URL */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/default-thumbnail.jpg');
    }
}
