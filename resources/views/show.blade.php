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
        <input type="hidden" name="fechahoy" value="{{$rsidata['fechahoy']}}" >
       
        @for($i=0; $i <= 9; $i++)
        <input type="hidden" name="rsiAnterior{{ $i }}" value="{{ $rsidata['rsiAnterior'][$i] ?? '' }}">
        @endfor
        @for($i=0; $i <= 9; $i++)
        <input type="hidden" name="fechaAnterior{{ $i }}" value="{{ $rsidata['fechaAnterior'][$i] ?? ''  }}">
        @endfor
        @for($i=0; $i <= 9; $i++)
        <input type="hidden" name="precioAnterior{{ $i }}" value="{{ $rsidata['precioAnterior'][$i] ?? '' }}">
        @endfor
    @endif
    <button style="align-items: center;">Seguir accion</button>
    </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>
    <canvas id="rsiChart" width="100%" max-width= 33vw margin=auto ></canvas>
    

<script>
    let valorRSI= {!!json_encode ( $rsidata['accion'])!!};

    const celdaRSI= document.querySelector("RSI");
    const rsi= parseFloat(celdaRSI.textContent);
    celdaRSI.textContent="";
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
<script>  
    document.addEventListener('DOMContentLoaded', function (){
    let dataChart=  @json($rsidata['grafrsi']);
    
    const fechas= dataChart.map (item => item.fecha);
    const rsis= dataChart.map (item => item.RSI);
    console.log(fechas);

    const ctx= document.getElementById('rsiChart').getContext('2d');
    new Chart (ctx, {
        type:'line',
        data: {
            labels: fechas,
            datasets: [{
                label: 'RSI',
                data: rsis,
                borderColor: 'rgba(75, 192,192,1)',
                borderWidth: 2,
                fill: false,
                tension: 0.1,
                pointBackgroundColor: function(ctx){
                    return ctx.dataIndex === rsis.length -rsis.length ? 'red': 'rgba(75,192,192,1)';
                },
                pointRadius: function(ctx) {
                    return ctx.dataIndex === rsis.length -1 ? 5 : 3;
                }
            }]
        },
        options: {
            responsive:true,
            plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context){
                                    if (context.dataIndex === rsis.length -1) {
                                        return 'Hoy: ${context.parsed.y}';
                                    } 
                                    return 'RSI: ${context.parsed.y}';
                                }
                            }
                        },
            annotation: {
                annotations: {
                    band: {
                        type: 'box',
                        yMin:30,
                        yMax:70,
                        backgroundColor: 'rgba(200, 200, 255, 0.2)'
                    },
                    minLine: {
                        type: 'line',
                        yMin:30,
                        yMax:30,
                        borderColor:'green',
                        borderWidth:1,
                        label:{
                            content: 'Min',
                            enabled: true,
                            position: 'start',
                            backgroundColor: 'rgb(0,0,0,0)',
                            color: 'green',
                            font: {
                                weigth: 'bold'
                            }
                        }
                    },
                    maxLine: {
                        type: 'line',
                        yMin:70,
                        yMax:70,
                        borderColor:'orange',
                        borderWidth:1,
                        label:{
                            content: 'Max',
                            enabled: true,
                            position: 'start',
                            backgroundColor: 'rgb(0,0,0,0)',
                            color: 'orange',
                            font: {
                                weigth: 'bold'
                            }
                        }
                    },

                }
            }
            },
            scales: {
                x: {
                    title: {
                        display:true,
                        text: 'Fecha'
                    },
                    reverse: true
                },
                y: {
                    title: {
                        display: true,
                        text: 'RSI'
                    }
                }
            }
        },
        plugins: [Chart.registry.getPlugin('annotation')]
    });
});

</script>


@endsection