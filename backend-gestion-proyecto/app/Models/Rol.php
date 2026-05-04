<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
      use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'roles_id', 'id_rol');
    }
}
