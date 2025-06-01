<?php

namespace App\Http\Controllers;

use App\Models\Acciones;
use App\Models\AltaAccion;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class StockController extends Controller
{
    public function show ($symbol){
        //construir la url
        $baseUrl= env('ALPHAVANTAGE_BASE_URL');
        $apiKey= env('ALPHAVANTAGE_API_KEY');

        //conseguir el ultimo dia habil 

        function obtenerUltimodihabil ($fecha = null){
            date_default_timezone_set('America/New_York');

            try {
                if ($fecha== null){
                    $fecha= date('Y-m-d');
                }
            $fechaObj = new DateTime($fecha);

            $diaSemana = $fechaObj->format('N');

            if ($diaSemana ==6 ){
                $fechaObj->modify('-1 day');
            } elseif ($diaSemana ==7 ){
                $fechaObj->modify('-2 day');
            } elseif ($diaSemana==1){
                $horaActual = date('H:i');
                $horaLimite= '17:30';

                if ($horaActual< $horaLimite){
                    $fechaObj->modify('-3 day');
                }
            } elseif ($diaSemana<6 && $diaSemana>1){
                $horaActual = date('H:i');
                $horaLimite= '17:30';
                if ($horaActual< $horaLimite){
                    $fechaObj->modify('-1 day');
                }
            }
            
            return $fechaObj-> format ('Y-m-d');} catch (Exception $e){
                throw new Exception( 'Formato fecha invalido');
            }};

          $fechaActual= obtenerUltimodihabil();
            
            
        //hacer la solicitud HTTP

    $response= Http::get($baseUrl, [
        'function' => 'RSI',
        'symbol' => $symbol,
        'interval' => 'daily',
        'time_period'=> '14',
        'series_type'=> 'close',
        'apikey' => $apiKey,
    ]);

    $response2= Http::get($baseUrl, [
        'function' => 'TIME_SERIES_DAILY',
        'symbol' => $symbol,
        'apikey' => $apiKey,
    ]);

    if ($response->successful() && $response2->successful()) {
        $data = $response->json();
        $data2= $response2->json();
        if (isset($data ["Information"])){ return view('limiteDiario');} 


        else if (isset ($data["Technical Analysis: RSI"])){;

        //if ($validadorRSI)
        $rsidata['accion']=$data["Technical Analysis: RSI"]["$fechaActual"]["RSI"] ?? null;
        //dd($rsidata['accion']);
        if ($rsidata['accion']===null){
            return view('sinRespuestaApi');
        }
        $rsidata['status'] = "";
        $rsidatadiasanteriores=[];
        //dd($data2);

        //aqui debemos averiguar a tres meses hacia atras la evoluci[on de la acción y cargarla en $rsidatadiasanteriores
        $technicalAnalysis60= $data["Technical Analysis: RSI"] ?? [];

        //limpiarfechas
        $technicalAnalysis60= array_combine(
            array_map('trim', array_keys($technicalAnalysis60)),
            array_values($technicalAnalysis60)
        );
        //ordenar de la mas reciente a la antigua
        krsort($technicalAnalysis60);

        $registros60=array_slice($technicalAnalysis60,0,40,true);

        $arrayRSI60=[];

        foreach ($registros60 as $fecha=>$valor) {
            if (isset($valor['RSI'])){
                $fechasinanio=date('d-m',strtotime($fecha));

                $arrayRSI60[]=[
                    'fecha' =>$fechasinanio,
                    'RSI'=>(float) $valor['RSI'],
                ];
            }
        }
    //    dd($arrayRSI60);
    $rsidata['grafrsi']=$arrayRSI60;
    
    //SE PIDE EL VALOR ACTUAL DE LA ACCION
    $rsidata['precio']=$data2["Time Series (Daily)"][$fechaActual]["4. close"] ?? "No disponible";
      //  dd($rsidata['precio']);
        
    //AQUI SE ARMA LO QUE SE PROYECTA EN PANATALLA 
       if($rsidata['accion']<20) { $rsidata['status']= "superblue"; $rsidata['comentario']= "El momento más optimo para comprar, llegando al valor minimo relativo"; $rsidata['simbolo']= $symbol; $rsidata['precio'];} 
        elseif ($rsidata['accion']<40) { $rsidata['status']= "DIAMANTE" ;$rsidata['comentario']= "DIAMANTE EN BRUTO : Podría resultar buena compra";$rsidata['simbolo']= $symbol; $rsidata['precio'];}
        elseif ($rsidata['accion']<50) { $rsidata['status']= "reloj" ;$rsidata['comentario']= "TIEMPO DE ESPERAR: no sería el mejor momento para comprar ni vender";$rsidata['simbolo']= $symbol; $rsidata['precio'];}
        elseif ($rsidata['accion']<60) { $rsidata['status']= "reloj" ;$rsidata['comentario']= "PODRIA ACERCARSE EL MOMENTO DE VENDER..";$rsidata['simbolo']= $symbol; $rsidata['precio'];}
        elseif ($rsidata['accion']>60) { $rsidata['status']= "MONEY"; $rsidata['comentario']= "PREPARATE PARA SER CASH!!: podría subir un poco más"; $rsidata['simbolo']= $symbol; $rsidata['precio'];}
        elseif ($rsidata['accion']>70) { $rsidata['status']= "MONEY"; $rsidata['comentario']= "MÁXIMOS RELATIVOS: podría estar en el pico de precio relativo";$rsidata['simbolo']= $symbol; $rsidata['precio']; }  
            //return response()->json(['error'=> 'No se pudo obtener datra'], 500);

        //dd($rsidata);
        
        //return response()->json($data);
        //RETORNAR DATOS DE SESION
        if (Auth::check()){
            $rsidata['datosuser']=Auth::id();
        };
        //retornar fecha actual
        $rsidata['fechahoy']=$fechaActual;


        //retornar los valores RSI y precio de los ultimos 10 dias
        //recuperamos de 'data' solo la linea de 'Technical Analysis: RSI'
        $RSI10dias= $data['Technical Analysis: RSI'];
        //convertimos en array secuencial [0,1,2,3...]
        $fechas= array_keys($RSI10dias);
        //excluir la primer fecha
        $diezDiasAnteriores= array_slice($fechas,1,10);

        $index=0;
        $RSI10DiasAnteriores=[];
        foreach ($diezDiasAnteriores as $date){
            $rsidata["rsiAnterior"][$index]= $RSI10dias[$date]['RSI'];
            $rsidata["fechaAnterior"][$index]= $date;
            $index++;
                
            };
        //lo pasamos al rsi al paquete que enviamos a la vista
        //dd ($rsidata['rsiAnterior[0]']);
            //dd($RSI10dias, $RSI10DiasAnteriores);

        //dd($data, $data2);
        //Ahora calculamos el precio de los ultimos 10 dias
        $Precio10Dias= $data2['Time Series (Daily)'];
        //usar'e las fechas del calculo del rsi porque son iguales
        $PRECIO10DiasAnteriores=[];
        $index=0;
        foreach ($diezDiasAnteriores as $date) {
                
                    $rsidata["precioAnterior"][$index]=$Precio10Dias[$date]['4. close'];
                    $index++;
                
        }
        //$rsidata['precioAnterior1']=$PRECIO10DiasAnteriores['PRECIO'][0];

        
        //dd ($rsidata['precioAnterior[0]']);
        $rsidata['simbolo']= $symbol;
        
        return view('show')->with('rsidata', $rsidata);
    }}
        return view('error');
    }

    public function login(){
        return view('login');
    }

    public function iniciouser(){
        if (Auth::check()){
            $iduser['datosuser']=Auth::id();
        };
        //consulta seguimiento de acciones del usuario en db
        $acciones = AltaAccion::where('idusuario', $iduser['datosuser'])->get(); //es un metodo elocuent que permite acceder a la tabla que modifica el modelo alta accion 
        //dd($acciones);
        return view('iniciouser')->with('acciones', $acciones);
    }

    

    public function registration(){
        return view('registration');
    }
   
}

