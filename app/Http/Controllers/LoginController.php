<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request){
            $credenciales = $request->all();

            $request-> validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $login = Auth::attempt([
                'email' => $credenciales['email'],
                'password' => $credenciales['password'],
            ]);

            if ($login) {
                $request ->session()->regenerate(); //obligatorio para registrar la sesion , siempre debe ponerse
                $user =Auth::user();
                return redirect('/iniciouser')->with('user',$user); //lleva a vista de inicio

        
            } else {
                throw ValidationException::withMessages(['login'=> 'Credenciales incorrectas']);
    
            }

            //importantisimo en el archivo env tiene que incluirse el AUTH MODEL que indica a que tabla apuntar mediante el modelo, en este caso Usuario
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
