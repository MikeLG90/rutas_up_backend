<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'contrasena' => 'required|string|min:6',
        ]);
    
   
        $persona = Persona::create([
            'nombre' => $validated['nombre'],
            'ap_paterno' => $validated['ap_paterno'],
            'ap_materno' => $validated['ap_materno'],
        ]);
    
        $usuario = User::create([
            'persona_id' => $persona->persona_id, 
            'email' => $validated['email'],
            'contrasena' => Hash::make($validated['contrasena']),
            'rol_id' => 2,
        ]);
    
        
        return response()->json([
            'usuario' => $usuario,
            'persona' => $persona,
        ], 201);
    }
    

    // login 
 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'contrasena' => 'required'
        ]);
    
        $usuario = User::with(['persona', 'rol'])->where('email', $credentials['email'])->first();
    
        if (!$usuario || !Hash::check($credentials['contrasena'], $usuario->contrasena)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
    
        // Crear el token
        $token = $usuario->createToken('auth_token')->plainTextToken;
    
        $response = [
            'usuario' => [
                'persona_id' => $usuario->persona_id,
                'email' => $usuario->email,
                'rol_id' => $usuario->rol_id,
                'usuario_id' => $usuario->usuario_id,
                'created_at' => $usuario->created_at,
                'updated_at' => $usuario->updated_at,
                'nombre' => $usuario->persona->nombre,
                'ap_paterno' => $usuario->persona->ap_paterno,
                'ap_materno' => $usuario->persona->ap_materno,
                'persona_id' => $usuario->persona->persona_id,
                'rol_nombre' => $usuario->rol->rol,
            ],
            'token' => $token,
        ];
    
        return response()->json($response);
    }


    public function registerChofer(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ap_paterno' => 'required|string|max:255',
            'ap_materno' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'contrasena' => 'required|string|min:6',
        ]);
    
   
        $persona = Persona::create([
            'nombre' => $validated['nombre'],
            'ap_paterno' => $validated['ap_paterno'],
            'ap_materno' => $validated['ap_materno'],
        ]);
    
        $usuario = User::create([
            'persona_id' => $persona->persona_id, 
            'email' => $validated['email'],
            'contrasena' => Hash::make($validated['contrasena']),
            'rol_id' => 3,
        ]);
    
        
        return response()->json([
            'usuario' => $usuario,
            'persona' => $persona,
        ], 201);
    }
    
    
}
