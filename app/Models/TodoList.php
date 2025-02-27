<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TodoList extends Model
{
    use HasFactory;

    protected $table = 'todo_lists';

    protected $fillable = [
        'task',
        'description',
        'is_completed',
        'due_date',
        'admin_id',
        'is_archived'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
