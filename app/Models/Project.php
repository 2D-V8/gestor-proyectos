<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id'];

    // Relación: un proyecto tiene muchas tareas
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Relación: un proyecto puede compartirse con muchos usuarios
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}
