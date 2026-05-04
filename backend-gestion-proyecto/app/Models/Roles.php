<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    // 🔥 Importante: especificar tabla manualmente
    protected $table = 'roles';

    // Campos permitidos
    protected $fillable = ['name'];

    // Relación: un rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
