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
        'roles_id'
    ];

    public function role()
    {
        return $this->belongsTo(Rol::class, 'roles_id', 'id_rol');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class,'project_users','user_id','project_id','id_user','id_project')->withPivot('role');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id_user');
    }
}
// USUARIOS //

// email: a@gmail.com pass: 1234567
// email: b@gmail.com pass: 1234567
// email: c@gmail.com pass: 1234567
// email: d@gmail.com pass: 1234567
// email: e@gmail.com pass: 1234567
// email: f@gmail.com pass: 1234567
// email: g@gmail.com pass: 1234567
