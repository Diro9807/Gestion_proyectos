<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id_project';
    public $timestamps = true;

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name', 'description'];

    public function users()
{
    return $this->belongsToMany(User::class,'project_users','project_id','user_id','id_project','id_user')->withPivot('role');
}

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_task_id', 'id_project');
    }
}
