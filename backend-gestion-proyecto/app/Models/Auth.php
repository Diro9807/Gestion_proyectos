<?php

namespace App\Models;

use App\Models\Rol;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth extends Authenticatable
{
    use HasFactory,HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    public function rol(){
        return $this->belongsTo(Rol::class, 'roles_id', 'id_rol');
    }
}
