<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class AltaAccion extends Model
{
    //
    use HasFactory;
    //use SoftDeletes;
    
    public $timestamps= false;
    protected $table= 'acciones';
    protected $primaryKey='idusuario';
    protected $fillable=['id', 'nombretecnico', 'idusuario', 'accion', 'precio0', 'rsi0', 'precio1', 'rsi1', 'precio2', 'rsi2', 'precio3', 'rsi3',
    'precio4', 'rsi4', 'precio5', 'rsi5','precio6', 'rsi6','precio7', 'rsi7','precio8', 'rsi8','precio9', 'rsi9','precio10', 'rsi10',
    'precio11', 'rsi11','precio12', 'rsi12','precio13', 'rsi13','precio14', 'rsi14','precio15', 'rsi15'];
    //protected $dates = ['deletes_at'];

    public static function alta($datosFormulario){
        //metodo de elocuent para que laravel construya la sentencia MYSQL  
        AltaAccion::create([
            //array asociativo con los nombres de las columnas de la tabla Mysql y los valores
            'idusuario' => $datosFormulario['iduser'],
            'nombretecnico' => $datosFormulario ['accion'],
            'rsi0' => $datosFormulario ['rsi0'],
            'precio0' => $datosFormulario['precio'],
        ]);
    }

    public static function modificar($idseguimiento,$rsidata,$valueData){
       if (is_array($rsidata) && count($rsidata)===1){
        $rsidata = array_values($rsidata)[0];
       }

        $accion= AltaAccion::where('id',$idseguimiento)->first();
        
    
        if($accion){
            $arrayrsi=[];

            foreach ($rsidata as $index => $rsi){
                    $columnarsi= 'rsi' . (intval($index));
                    $arrayrsi[$columnarsi]=$rsi;
            }



        if (is_array($valueData) && count($valueData)===1){
                $valueData = array_values($valueData)[0];
               }
            $arrayprecio=[];
            foreach ($valueData as $index => $precio){
                $columnaprecio= 'precio' . (intval($index));
                $arrayprecio[$columnaprecio]=$precio;
        }
            
        $accion->update($arrayrsi);
        $accion->update($arrayprecio);
            return $accion;
        }
        return null;
    }

    public static function presentarSeguimiento($idseguimiento){
        $accion= self::where('id',$idseguimiento)->first();
        if(!$accion){
            return null;
        }
        $valoresNoNulos=[];
        foreach($accion->toArray() as $columna=> $valor){
            if($valor !== null && (str_starts_with($columna, 'precio')|| str_starts_with($columna, 'rsi'))) {
                $valoresNoNulos[$columna]= $valor;
            }
        }
        //dd($valoresNoNulos);
        return $valoresNoNulos;
        
    }

    public function usuario(){
        return $this->belongsTo(Miembro::class);}
}