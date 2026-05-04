<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id_task';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'user_id',
        'project_task_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_task_id', 'id_project');
    }
}