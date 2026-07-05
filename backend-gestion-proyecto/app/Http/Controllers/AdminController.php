<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function users()
    {
        return User::with('role')
            ->orderBy('id_user')
            ->get();
    }
}
