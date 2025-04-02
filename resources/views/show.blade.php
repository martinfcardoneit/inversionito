@extends ('inicio')
@section('contenido')

<div class="resultado">
    <title> Acción consultada</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
        th{
            background-color: #BFA893;
        }
        .rsi-bajo{
            color: blue;
            font-weight: bold;
        }
        .rsi-normal {
            color: black;
            font-weight: bold;
        }
        .rsi-alto {
            color: green;
            font-weight: bold;
        }
    </style>
    <table>
        <tr>
            <th> Acción</th>
            <th> Precio último cierre</th>
            <th> Valor relativo (RSI)</th>
            <th> Situación</th>
        </tr>
        <tr>
            <th>{{$rsidata['simbolo']}}</th>
            <th>${{$rsidata['precio']}} </th>
            <th class="RSI">{{$rsidata['accion']}} </th>
            <th> {{$rsidata['comentario']}}</th>
        </tr>

    </table>
    <th><img src="../img/{{$rsidata['status']}}.png" alt=""></th>

    
    <form action="/seguiraccion" method="post">
    @csrf 
    @if (isset ($rsidata['datosuser']))
        <input type="hidden" name="accion" value="{{$rsidata['simbolo']}}" >
        <input type="hidden" name="iduser" value="{{$rsidata['datosuser']}}" >
        <input type="hidden" name="rsi0" value="{{$rsidata['accion']}}" >
        <input type="hidden" name="precio" value="{{$rsidata['precio']}}" >
    @endif
    <button>Seguir accion</button>
    </form>
    
    
</div>
<script>
    let valorRSI= {!!json_encode ( $rsidata['accion'])!!};
    const celdaRSI= document.getElementsByClassName("RSI");
    var rsi= parseInt(celdaRSI.textContent);
    celdaRSI.innerHTML="";
    let claseColor= "";
    if (valorRSI <35){
        claseColor= "rsi-bajo";
    } else if (valorRSI >=35 && valorRSI<=55){
        claseColor= "rsi-normal";
    } else if (valorRSI > 55 && valorRSI <=80){
        claseColor= "rsi-alto";
    };
    celdaRSI.classList.add (claseColor);
    

</script>


@endsection