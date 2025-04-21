<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory;
    protected $fillable = ['title'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
