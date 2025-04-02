<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Usuarios extends Controller
{
    public function registrarusuario(Request $request){
        //recuperar los datos del objeto request
        $datosFormulario = $request->all();

        //validar datos
        $reglas = [
            'nombre' => 'required|min:3|max:36',
            'email' => 'required|email|max:64|unique:usuarios,email',
            'password' => 'required|min:5|max:24'
        ];

        $mensajes = [
            'nombre.required' => 'Nombre no valido',
            'nombre.min' => 'Nombre de al menos 3 caracteres',
            'nombre.max' => 'Nombre Maximo 36 caracteres',
            'email.required' => 'Email requerido',
            'email.email' => 'Formato email no valido',
            'email.max' => 'Email maximo 64 caracteres',
            'email.unique' => 'Email ya registrado',
            'password.required' => 'Password requerido',
            'password.min' => 'Password minimo 5 caracteres y maximo de 24',
            'password.max' => 'Password minimo 5 caracteres y maximo de 24',

        ];

        $validator = Validator::make($datosFormulario, $reglas ,$mensajes );
        if ($validator->fails()){
            //regresa a la vista
            return redirect('registration')->withErrors($validator)->withInput();
        }

        //ALTA DE USUARIO (si validator no fails)
        Miembro::alta($datosFormulario); //metodo para llamar a MYSQ en el Models--> Miembro

        //RECARGAR LA VISTA CON MENSAJE
        $datos['mensaje']= 'Alta efectuada. Por favor inicie sesion';
        return redirect('iniciouser') ->with('success', $datos);

    }
}
