<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inversionito Evolución </title>
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>

</head>
<body>
    
    @php 
    $rsiValues=[];
    $precioValues=[];
    if(!empty($valoresSeguimiento)&& is_array($valoresSeguimiento)){
    foreach($valoresSeguimiento as $key => $value){
        if(str_starts_with($key, 'rsi')){
            $rsiValues[]=$value;
        } elseif (str_starts_with($key, 'precio')){
            $precioValues[]=$value;
        }}
    }

    @endphp
    <div style="text-align: right; margin-right:20px"> 
        <a class="login" href="{{ url()->previous() }}"> Volver a atrás</a>
        <br>
        <a class="logout" href="/logout"> Logout</a>
    </div>
    <div style="text-align: center;">
        <h2 style="color: white;">I N V E R S I O N I T O</h2>
    </div>
    <div style="text-align: center;">
    <h2 style="color: grey;">Evolucion de la accion (Actualiza a 30 min de cierre diario)</h2>
    
    </div>
    <canvas id="rsiChart" width="100%" max-width= 33vw margin=auto ></canvas>
    <canvas id="precioChart" width="200" height="100"></canvas>


    
    <script>
       const rsiData= {!! json_encode($rsiValues) !!};
       const precioData= {!! json_encode($precioValues) !!};
       const labels= rsiData.map((_, index) => index);
       labels[0]="Inicio";
       labels[labels.length-1]= "Hoy";


       const rsiCtx = document.getElementById('rsiChart').getContext('2d');
       new Chart(rsiCtx, {
        type:'line',
        data: {
            labels: labels,
            datasets:[{
                label:"Evolucion del Rsi",
                data: rsiData,
                borderColor: 'blue',
                backgroundColor: 'rgba(90, 40, 198, 0.16)',
                fill:true,
            }]
        },

            options: {
                responsive:true,
                scales: {
                    x:{
                        title: {
                            display:true,
                            text:'Dias'
                        }
                    },
                    y:{
                        title: {
                            display: true,
                            text:'RSI'
                        },
                        min:10,
                        max:70,
                        
                    }
                },
                plugins: {
                    annotation: {
                        annotations: {
                            areaSombreada: {
                                type: 'box',
                                yMin: 20,
                                yMax: 60,
                                backgroundColor: 'rgba(139, 205, 235, 0.25)',
                                borderWidth:0,
                                drawTime: 'beforeDatasetsDraw'
                            },
                            etiquetaMin: {
                                type: 'label',
                                yValue: 20,
                                xValue: rsiData.length -1,
                                content: 'Minimo',
                                color: 'red',
                                font: {weigth: 'bold',size:14},
                                position: 'end'
                            },
                            etiquetaMax: {
                                type: 'label',
                                yValue: 60,
                                xValue: rsiData.length -1,
                                content: 'Maximo',
                                color: 'green',
                                font: {weigth: 'bold', size:14},
                                position: 'end'
                            },
                        }
                    }
                    
                },
            },
            
       });

       const rsiCtx2 = document.getElementById('precioChart').getContext('2d');
       new Chart(rsiCtx2, {
        type:'line',
        data: {
            labels: labels,
            datasets:[{
                label:"Evolucion del Precio",
                data: precioData,
                borderColor: 'blue',
                backgroundColor: 'rgba(0,0,255,0.2)',
                fill:true,
            }]
        },

            options: {
                responsive:true,
                scales: {
                    x:{
                        title: {
                            display:true,
                            text:'Dias'
                        }
                    },
                    y:{
                        title: {
                            display: true,
                            text:'Precio'
                        },
                        
                    }
                },
            },
            
       });

    
        

    </script>
</body>
</html>