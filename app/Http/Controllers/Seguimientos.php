<?php

namespace App\Http\Controllers;

use App\Models\AltaAccion;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Seguimientos extends Controller
{
    
    public function iniciarseguimiento(Request $request){

        $datosFormulario = $request->all();
        //dd($datosFormulario);
        //ALTA DE USUARIO (si validator no fails)
        AltaAccion::alta($datosFormulario);

        //RECARGAR LA VISTA CON MENSAJE
        $datos['mensaje']= 'Alta de accion efectuada';
        return redirect('iniciouser') ->with('success', $datos);

        /*$accion= $request->input('accion');
        $iduser= $request->input('iduser');
        dd($iduser,$accion);*/
    }

    public function verseguimiento(Request $request){
        $datosFormularioseguimiento= $request->all();
        function obtenerDiaSemana(){
            Carbon::setLocale('es');
            return Carbon::now()->translatedFormat('l');
        }
        $diaDeLaSemana= obtenerDiaSemana();

        $fechaAnteriorconHora= $datosFormularioseguimiento['fechaalta'];
        $FechaAnteriorSinHora= Carbon::parse($fechaAnteriorconHora)->startOfDay();
        
        $fechaHoy=Carbon::now()->startOfDay();
        $nowNY= Carbon::now(new CarbonTimeZone('America/New_York'));
        if($nowNY->format('H:i') < '18:30'){
            $fechaHoy= $fechaHoy->subDay();
        }

        

        function diferenciaDias($FechaAnteriorSinHora){
            $fechaActual=Carbon::now()->startOfDay();
            return $fechaActual->diffInDays($FechaAnteriorSinHora, false)*-1;
        }
        
        date_default_timezone_set('America/New_York');
        $horaActual = date ('H:i');
        $diferenciaDias = diferenciaDias($FechaAnteriorSinHora);
        


        function calculardiasfinde($FechaAnteriorSinHora,$fechaHoy){
        $sumaFinDeSemana=0;
        $tempFecha= clone $FechaAnteriorSinHora;

        while ($tempFecha <= $fechaHoy){
            $diaSemana= $tempFecha->format('N');
            //dd($diaSemana);
            if ($diaSemana==6){
                $sumaFinDeSemana++;
            } else if (
                $diaSemana==7){
                    $sumaFinDeSemana++;}
                $tempFecha->modify('+1 day');}
                    return $sumaFinDeSemana;
            };
        
        function calcularFechasHabiles($FechaAnteriorSinHora,$fechaHoy){
                $fechasHabiles=[];
                $tempFecha= clone $FechaAnteriorSinHora;
        
                while ($tempFecha <= $fechaHoy){
                    $diaSemana= $tempFecha->format('N');
                    //dd($diaSemana);
                    if ($diaSemana!=6 && $diaSemana !=7){
                        $fechasHabiles[]=clone $tempFecha;}

                        $tempFecha->modify('+1 day');}
                            return $fechasHabiles;
                    };
        
        //dd(calcularFechasHabiles($FechaAnteriorSinHora,$fechaHoy));
        
        //echo(Calculardiasfinde($FechaAnteriorSinHora,$fechaHoy));
        
        if($diaDeLaSemana=='sábado'){      
            $diferenciaDias -=1;
        } elseif ($diaDeLaSemana=='domingo'){
            $diferenciaDias -=2;
        } elseif ($diaDeLaSemana=='lunes'&& $horaActual <= '18:31'){
            $diferenciaDias -=3;
        } else { 
            $diferenciaDias -=Calculardiasfinde($FechaAnteriorSinHora,$fechaHoy);
        }  //$diferenciaDias -=Calculardiasfinde($FechaAnteriorSinHora,$fechaHoy);

    $diashabiles= floor($diferenciaDias);
    $arrayDiasHabiles=count(calcularFechasHabiles($FechaAnteriorSinHora,$fechaHoy));
        //si hoy es lunes despues de las 18.32 a viernes restale  calculardiasFinde
        //$diashabiles= $diferenciaDias;//-Calculardiasfinde($FechaAnteriorSinHora,$fechaHoy);
    //echo ($diashabiles);

    //HACER UN GET PARA SABER CUANTOS VALORES TIENE EL USUARIO YA CARGADOS 
    $datosFormulario= $request->all();
    $simbolo= $datosFormulario['simbolo'];
    $idusuario= $datosFormulario['usuario'];
    $idseguimiento= $datosFormulario['idseguimiento'];
    //dd($simbolo, $idusuario, $diashabiles);

    //consulta seguimiento de acciones del usuario en db
    function diasConsultados($simbolo, $idusuario ,$arrayDiasHabiles,$idseguimiento){
        if ($arrayDiasHabiles <= 25){
        $consultaDiasAlmacenados = AltaAccion::where('nombretecnico', $simbolo)
                                            ->where('idusuario', $idusuario)
                                            ->where(function($query) use ($arrayDiasHabiles){
                                                //dd($arrayDiasHabiles);
                                                for ($i=0; $i <= $arrayDiasHabiles; $i++){
                                                    $query->orWhereNull ("rsi{$i}");
                                                }
                                            }) ->get(); 
                                        return $consultaDiasAlmacenados;} else {
                                            $accion= AltaAccion::where('id', $idseguimiento)->first();
                                            if ($accion) {
                                                $accion->delete();
                                                //mensaje de alerta
                                                session()->flash('mensaje', 'Este seguimiento superó los 25 días hábiles y se eliminó de la base de datos. Por favor inicia un nuevo seguimiento');
                                            }
                                            
                                            return redirect('iniciouser');}}

    //dd(diasConsultados($simbolo, $idusuario, $arrayDiasHabiles));

    //OOOOOOOOOOJOOOOOOOOOOOOO SI ESTA SIGNOO DE ADMIRACIONNNN PARA VERSION PRUEBA SI SE ACTUALIZARON YA LOS DATOS DEL DIA!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    if (diasConsultados($simbolo, $idusuario, $arrayDiasHabiles,$idseguimiento)){

     //dd(diasConsultados($simbolo, $idusuario, $arrayDiasHabiles));   

         //hacer la solicitud HTTP
    //construir la url
    $baseUrl= env('ALPHAVANTAGE_BASE_URL');
    $apiKey= env('ALPHAVANTAGE_API_KEY'); 
    $datosFormularioseguimiento= $request->all();
    // extraemos de la peticion GET el input 'simbolo'
    $symbol= $datosFormularioseguimiento['simbolo'];   
    
    $fechasHabiles=calcularFechasHabiles($FechaAnteriorSinHora,$fechaHoy);
    $fechasHabilesEnFormat= array_map(fn($fecha)=>$fecha->format('Y-m-d'), $fechasHabiles);
    $valueData=[];
    $fechasHabilesEnFormato=[];
    
    foreach ($fechasHabilesEnFormat as $fecha) {
        if ($fecha !== '2025-05-26' && $fecha !== '2026-01-01' && $fecha !== '2025-06-19' && $fecha !== '2025-07-03' &&
        $fecha !== '2025-07-04' && $fecha !== '2025-09-01' && $fecha !== '2025-11-27' && $fecha !== '2025-11-28'&& $fecha !== '2025-12-24'
        && $fecha !== '2025-12-25' && $fecha !== '2026-01-20' && $fecha !== '2026-02-17') {
            $fechasHabilesEnFormato[]= $fecha;
        }
    }

    //dd ($fechasHabilesEnFormato);
        
    //ACA DEBERIA RECORRER EL ARRAY Y SI ENCUENTRA ALGUN FERIADO ELIMINARLO POR EJ 2025-05-26



    $response= Http::get($baseUrl, [
        'function' => 'RSI',
        'symbol' => $symbol,
        'interval' => 'daily',
        'time_period'=> '14',
        'series_type'=> 'close',
        'apikey' => $apiKey,
    ]);
    if ($response->successful()) {
        $data = $response->json();
        //dd($response);
        $rsidata=[];
        if (isset($data ["Information"])){ return view('limiteDiario');} 

        foreach ($fechasHabilesEnFormato as $fecha ){
            $rsidata['accion'][]=$data["Technical Analysis: RSI"]["$fecha"]["RSI"];}
         ;
    };

    //$datoRespuestaHoy=$rsidata['accion'][]=$data["Technical Analysis: RSI"]["$fechaHoy"]["RSI"];
    //dd($datoRespuestaHoy);

    $response2= Http::get($baseUrl, [
        'function' => 'TIME_SERIES_DAILY',
        'symbol' => $symbol,
        'apikey' => $apiKey,
    ]);
    if ($response2->successful()) {
        $data2 = $response2->json();
        $valueData=[];
        if (isset($data ["Information"])){ return view('limiteDiario');}
        foreach ($fechasHabilesEnFormato as $fecha ){
            $valueData['accion'][]=$data2["Time Series (Daily)"][$fecha]["4. close"] ?? "No disponible";
        }
      
    };

    
    
    //SENTENCIA MYSQL
    AltaAccion::modificar($idseguimiento,$rsidata,$valueData,$fechasHabilesEnFormato);
    //dd($rsidata['accion']);
    // CONSULTAR SEGUIMIENTO
    $valoresSeguimiento= AltaAccion::presentarSeguimiento($idseguimiento);
    //dd($valoresSeguimiento);
    $preciovalues=[];
    $rsivalues=[];
    //dd($preciovalues, $rsivalues);

    //dd($evolucionAccion);
   return view('chart')->with('valoresSeguimiento', $valoresSeguimiento);
   
    //dd($preciovalues, $rsivalues);
    //$numeroIndice=count($valoresSeguimiento)/2;
    //dd($numeroIndice);

} else if ($arrayDiasHabiles<=25){ //aqui puse el if para que si han pasado mas de 15 dias no muestre el chart al que seguiría la funcion

    // FUNCION GET
    $valoresSeguimiento= AltaAccion::presentarSeguimiento($idseguimiento);

    //dd($valoresSeguimiento);
    return view('chart')->with('valoresSeguimiento', $valoresSeguimiento);

    //$numeroIndice=count($valoresSeguimiento)/2;
    //dd($numeroIndice);

    return "No Faltan dias";
;

} else {return redirect('iniciouser');}}

//BAJA DE ACCION
public function destroy($id){
    
    //dd($id);
    $accion= AltaAccion::where('id', $id)->delete();
    //$accion->delete();
    return redirect('iniciouser');

}

}