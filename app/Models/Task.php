<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'workspace_id',
        'deadline',
        'status',
        'priority',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
