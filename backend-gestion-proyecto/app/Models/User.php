<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id',
        'project_id'
    ];

    public function role()
    {
        return $this->belongsTo(Rol::class, 'roles_id', 'id_rol');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id_project');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id_user');
    }
}