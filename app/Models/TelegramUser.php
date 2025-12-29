<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $guarded = [];

    // Связь с выполненными заданиями
    public function completedTasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks');
    }
}