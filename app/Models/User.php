<?php

namespace App\Models;

use App\Models\Blog;
use App\Models\BlogLike;
use App\Models\BlogComment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User authored blogs
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Relationship: Blog Likes
     */
    public function blogLikes(): HasMany
    {
        return $this->hasMany(BlogLike::class, 'author_id');
    }

    /**
     * Relationship: Blog Comments
     */
    public function blogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'author_id');
    }

    /**
     * Accessor: User status text
     */
    public function getStatusAttribute($value): string
    {
        return $value ? 'Active' : 'Inactive';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === 1;
    }

    /**
     * Many-to-Many Relationship: Blogs the user liked
     */
    public function likedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_likes', 'author_id', 'blog_id');
    }
}
