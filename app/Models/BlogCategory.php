<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    protected $fillable = [
        'blog_category',
        'status',
        'description',
        'priority',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'time_of_creation',
    ];

    protected $casts = [
        'priority' => 'integer',
    ];

    public function getRandomAttribute()
    {
        return Str::random();
    }

    public function getBlogCountAttribute()
    {
        return $this->blogs->load('user')->count();
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'blog_category_id', 'id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', 'enable');
    }

    public function getTimeOfCreationAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getBlogCategoryAttribute($value)
    {
        return ucfirst($value);
    }
}
