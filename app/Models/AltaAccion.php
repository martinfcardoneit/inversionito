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
    'precio11', 'rsi11','precio12', 'rsi12','precio13', 'rsi13','precio14', 'rsi14','precio15', 'rsi15','precio16', 'rsi16','precio17', 'rsi17', 'precio18','rsi18',
     'precio19', 'rsi19','precio20', 'rsi20','precio21', 'rsi21','precio22', 'rsi22','precio23', 'rsi23','precio24', 'rsi24','precio25', 'rsi25','fecha0','fecha1','fecha2',
    'fecha3','fecha4','fecha5','fecha6','fecha7','fecha8','fecha9','fecha10','fecha11','fecha12','fecha13','fecha14','fecha15','fecha16','fecha17','fecha18',
    'fecha19','fecha20','fecha21','fecha22','fecha23','fecha24','fecha25','fechaS1', 'rsiS1','precioS1','fechaS2', 'rsiS2','precioS2','fechaS3', 'rsiS3','precioS3', 
    'fechaS4', 'rsiS4','precioS4','fechaS5', 'rsiS5','precioS5','fechaS6', 'rsiS6','precioS6','fechaS7', 'rsiS7','precioS7','fechaS8', 'rsiS8','precioS8',
    'fechaS9', 'rsiS9','precioS9', 'fechaS10', 'rsiS10','precioS10'];
    //protected $dates = ['deletes_at'];

    public static function alta($datosFormulario){
        //metodo de elocuent para que laravel construya la sentencia MYSQL  
        AltaAccion::create([
            //array asociativo con los nombres de las columnas de la tabla Mysql y los valores
            'idusuario' => $datosFormulario['iduser'],
            'nombretecnico' => $datosFormulario ['accion'],
            'rsi0' => $datosFormulario ['rsi0'],
            'precio0' => $datosFormulario['precio'],
            'fecha0' => $datosFormulario['fechahoy'],
            'fechaS1' => $datosFormulario['fechaAnterior0'],
            'rsiS1' => $datosFormulario['rsiAnterior0'],
            'precioS1' => $datosFormulario['precioAnterior0'],
            'fechaS2' => $datosFormulario['fechaAnterior1'],
            'rsiS2' => $datosFormulario['rsiAnterior1'],
            'precioS2' => $datosFormulario['precioAnterior1'],
            'fechaS3' => $datosFormulario['fechaAnterior2'],
            'rsiS3' => $datosFormulario['rsiAnterior2'],
            'precioS3' => $datosFormulario['precioAnterior2'],
            'fechaS4' => $datosFormulario['fechaAnterior3'],
            'rsiS4' => $datosFormulario['rsiAnterior3'],
            'precioS4' => $datosFormulario['precioAnterior3'],
            'fechaS5' => $datosFormulario['fechaAnterior4'],
            'rsiS5' => $datosFormulario['rsiAnterior4'],
            'precioS5' => $datosFormulario['precioAnterior4'],
            'fechaS6' => $datosFormulario['fechaAnterior5'],
            'rsiS6' => $datosFormulario['rsiAnterior5'],
            'precioS6' => $datosFormulario['precioAnterior5'],
            'fechaS7' => $datosFormulario['fechaAnterior6'],
            'rsiS7' => $datosFormulario['rsiAnterior6'],
            'precioS7' => $datosFormulario['precioAnterior6'],
            'fechaS8' => $datosFormulario['fechaAnterior7'],
            'rsiS8' => $datosFormulario['rsiAnterior7'],
            'precioS8' => $datosFormulario['precioAnterior7'],
            'fechaS9' => $datosFormulario['fechaAnterior8'],
            'rsiS9' => $datosFormulario['rsiAnterior8'],
            'precioS9' => $datosFormulario['precioAnterior8'],
            'fechaS9' => $datosFormulario['fechaAnterior8'],
            'rsiS9' => $datosFormulario['rsiAnterior8'],
            'precioS9' => $datosFormulario['precioAnterior8'],
            'fechaS10' => $datosFormulario['fechaAnterior9'],
            'rsiS10' => $datosFormulario['rsiAnterior9'],
            'precioS10' => $datosFormulario['precioAnterior9'],
            
        ]);
    }

    public static function modificar($idseguimiento,$rsidata,$valueData,$fechasHabilesEnFormato){
       if (is_array($rsidata) && count($rsidata)===1){
        $rsidata = array_values($rsidata)[0];
       }

        $accion= AltaAccion::where('id',$idseguimiento)->first();
        
    
        if($accion){
            $arrayrsi=[];

            foreach ($rsidata as $index => $rsi){
                    $columnarsi= 'rsi' . (intval($index+1));
                    $arrayrsi[$columnarsi]=$rsi;
            }



        if (is_array($valueData) && count($valueData)===1){
                $valueData = array_values($valueData)[0];
               }
            $arrayprecio=[];
            foreach ($valueData as $index => $precio){
                $columnaprecio= 'precio' . (intval($index+1));
                $arrayprecio[$columnaprecio]=$precio;
        }

        $arrayfechas=[];
        foreach ($fechasHabilesEnFormato as $index=> $fecha){
            $columnafecha='fecha' .(intval($index+1));
             $arrayfechas[$columnafecha]=$fecha;
        }

      
       
            
        $accion->update($arrayrsi);
        $accion->update($arrayprecio);
        $accion->update($arrayfechas); //recordar que si funciona hay que crear en tabla sql local hasta fecha 25, y en tabla de hostinger tambien
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
            if($valor !== null && (str_starts_with($columna, 'precio')|| str_starts_with($columna, 'rsi') || str_starts_with($columna, 'fecha') || 
             str_starts_with($columna, 'nombretecnico'))) {
                $valoresNoNulos[$columna]= $valor;
            }
        }
       //dd($valoresNoNulos);
        return $valoresNoNulos;
        
    }

    public function usuario(){
        return $this->belongsTo(Miembro::class);}
}