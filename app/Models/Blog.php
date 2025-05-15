<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Blog extends Model
{
    use HasFactory, HasSpatial;
    protected $table = 'blogs';
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'slug',
        'blog_category_id',
        'content',
        'written_from',
        'comment_enabled',
        'is_featured'
    ];
    protected $hidden = [
        'blog_category_id',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'written_from' => Point::class
    ];
    protected $appends = [
        'test_money'
    ];

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', 'enable');
    }

    public function scopeFindIdSlug($query, $id, $slug)
    {
        return $query->where(['id' => $id, 'slug' => $slug]);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeCommentEnabled($query)
    {
        return $query->where('comment_enabled', 1);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
            // $model->written_from = new Point(51.5032973, -0.1217424);
        });
    }

    public function getTestMoneyAttribute()
    {
        return Money::USD(500);
    }
}
