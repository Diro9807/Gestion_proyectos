<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    //protected $primaryKey = 'id_user';
    //public $timestamps = false;

    //public function rol(){
        //return $this->belongsTo(Rol::class, 'roles_id');
    //}

    public function projects(){

        return $this->hasMany(Project::class);
    }
}
