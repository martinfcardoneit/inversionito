<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Miembro extends Model
{
    use HasFactory;
    //
    //no actualizar timestamps,
    public $timestamps= false;
    protected $table= 'usuarios';
    protected $primaryKey= 'idusuario';
    protected $fillable =['nombre','email','password','is_admin'];

    //metodo para dar de alta miembro
    public static function alta($datosFormulario){
        //metodo de elocuent para que laravel construya la sentencia MYSQL  
        Miembro::create([
            //array asociativo con los nombres de las columnas de la tabla Mysql y los valores
            'nombre' => $datosFormulario['nombre'],
            'email' => $datosFormulario ['email'],
            'password' => bcrypt($datosFormulario ['password'])
        ]);
    }
}
