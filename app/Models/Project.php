<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'manager_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'project_staff');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Scope untuk staff yang hanya bisa lihat project mereka
    public function scopeAssignedToUser($query, $userId)
    {
        return $query->whereHas('staff', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
}
