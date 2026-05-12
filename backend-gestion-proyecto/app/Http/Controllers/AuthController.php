<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function index()
        {
            $users = User::all();
            return response()->json($users);
        }


    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Has iniciado sesión',
            'access_token' => $token,
            'user' => $user
        ]);
    }

    public function register(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);


            $user = new User();
            $user->name = $request['name'];
            $user->password = Hash::make($request['password']);
            $user->email = $request['email'];
            $user->roles_id = 2;

            $user->save();

            return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user]);
        }
}
