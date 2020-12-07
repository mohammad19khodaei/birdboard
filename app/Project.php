<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use RecordActivity;
    
    protected $guarded = [];

    public function url()
    {
        return "/projects/{$this->id}";
    }

    public function addTask(string $taskBody)
    {
        return $this->tasks()->create(['body' => $taskBody]);
    }

    public function invite(User $user)
    {
        $this->members()->syncWithoutDetaching($user);
    }

    public function checkMember(User $user)
    {
        return $this->members->contains($user);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
