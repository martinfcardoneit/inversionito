<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inversionito Evolución </title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>
    <script> Chart.register(window['chartjs-plugin-annotation']);</script>

</head>
<body>
    
    @php 
    $rsiValues=[];
    $precioValues=[];
    $fechasValue=[];

    if(!empty($valoresSeguimiento)&& is_array($valoresSeguimiento)){
    foreach($valoresSeguimiento as $key => $value){
        for ($i=10; $i>=1; $i--){
            $key= 'fechaS'.$i;
            if(!empty ($valoresSeguimiento[$key])){
                $fechasValue[]=$valoresSeguimiento[$key];
            }
        }

    $n=0;
    while(isset($valoresSeguimiento['fecha'.$n])){
        $key= 'fecha'. $n;
        if (!empty ($valoresSeguimiento[$key])){
            $fechasValue[]= $valoresSeguimiento[$key];
        }
            $n++ ;
    }
    }




        for ($i=10; $i>=1; $i--){
            $key= 'rsiS'.$i;
            if(!empty ($valoresSeguimiento[$key])){
                $rsiValues[]=$valoresSeguimiento[$key];
            }
        }
        for ($i=0; $i<=25; $i++){
            $key= 'rsi'.$i;
            if(!empty ($valoresSeguimiento[$key])){
                $rsiValues[]= floatval($valoresSeguimiento[$key]);
            }
        }
        
        $fechasValue = array_unique($fechasValue);



        for ($i=10; $i>=1; $i--){
            $key= 'precioS'.$i;
            if(!empty ($valoresSeguimiento[$key])){
                $precioValues[]=$valoresSeguimiento[$key];
            }
        }
        for ($i=0; $i<=25; $i++){
            $key= 'precio'.$i;
            if(!empty ($valoresSeguimiento[$key])){
                $precioValues[]= floatval($valoresSeguimiento[$key]);
            }
        }

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
       const fechaData= {!! json_encode($fechasValue) !!};
       const labels= fechaData.slice();
       labels[10]="Inicio Seguimiento";
       labels[labels.length-1]= "Hoy";

       console.log ('RSI:', rsiData);
            console.log ('Precio:', precioData);
            console.log ('Fechas:', fechaData);


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
                pointBackgroundColor: rsiData.map((_, idx) =>idx === 10? 'red': 'blue'),
                pointRadius: rsiData.map ((_, idx) => idx === 10? 6: 3),
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
                            inicioSeguimiento: {
                                type: 'line',
                                xMin: 10,
                                xMax: 10,
                                borderColor: 'red',
                                borderWith: 2,
                                label: {
                                    content: 'Inicio de Seguimiento',
                                    enabled: true,
                                    position: 'bottom',
                                    backgroundColor: 'rgba(255,0,0,0,0.6)',
                                    color: 'white'
                                }
                            },
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
                plugins: {
                    annotation: {
                        annotations: {
                            inicioSeguimiento: {
                                type: 'line',
                                xMin: 10,
                                xMax: 10,
                                borderColor: 'red',
                                borderWith: 2,
                                label: {
                                    content: 'Inicio de Seguimiento',
                                    enabled: true,
                                    position:'center',
                                    backgroundColor: 'rgba(255,0,0,0,0.6)',
                                    color: 'white'
                                }
                            },
                        },
                    },
                },
            },
            
       });

    
        

    </script>

<footer>
        <div style="text-align: center; color:grey;">
            <br>
        <h6>2024 Copyright</h6>
        <h6>Inversionito</h6>
        <br>
        </div>
    </footer>
</body>
</html>