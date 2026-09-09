<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function users()
    {
        return User::with('role')
            ->orderBy('id_user')
            ->get();
    }

    public function changeRole(Request $request, $id)
    {
        $request->validate([
            'roles_id' => 'required|exists:roles,id_rol'
        ]);

        $user = User::findOrFail($id);

        // No permitir eliminar al último administrador
        if ($user->roles_id == 1 && $request->roles_id != 1) {

            $admins = User::where('roles_id', 1)->count();

            if ($admins <= 1) {

                return response()->json([
                    'message' => 'Debe existir al menos un administrador.'
                ], 403);
            }
        }

        // No permitir cambiarse el propio rol
        if ($user->id_user == Auth::id()) {

            return response()->json([
                'message' => 'No puedes cambiar tu propio rol.'
            ], 403);
        }

        $user->roles_id = $request->roles_id;
        $user->save();

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // No permitir borrarse a sí mismo
        if ($user->id_user == Auth::id()) {

            return response()->json([
                'message' => 'No puedes eliminar tu propio usuario.'
            ], 403);
        }

        // Debe quedar al menos un administrador
        if ($user->roles_id == 1) {

            $admins = User::where('roles_id', 1)->count();

            if ($admins <= 1) {

                return response()->json([
                    'message' => 'Debe existir al menos un administrador.'
                ], 403);
            }
        }

        // No eliminar usuarios con tareas asignadas
        if ($user->tasks()->exists()) {

            return response()->json([
                'message' => 'No se puede eliminar este usuario porque tiene tareas asignadas.'
            ], 403);
        }

        // No eliminar usuarios que pertenecen a proyectos
        if ($user->projects()->exists()) {

            return response()->json([
                'message' => 'No se puede eliminar este usuario porque pertenece a uno o más proyectos.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }
}
