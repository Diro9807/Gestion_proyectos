<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id_project';
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class, 'project_id', 'id_project');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_task_id', 'id_project');
    }
}