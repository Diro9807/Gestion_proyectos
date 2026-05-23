<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id'
    ];
    public function getAuthIdentifierName(){

        return 'id_user';
    }

    public function role()
    {
        return $this->belongsTo(Rol::class, 'roles_id', 'id_rol');
    }

    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_users',
            'user_id',
            'project_id',
            'id_user',
            'id_project'
        )->withPivot('role');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id_user');
    }


}


