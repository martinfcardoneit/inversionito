<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script> src="https://cdn.jsdelivr.net/npm/chart.js"</script>
    <title>Inversionito</title>
</head>
<body>
    <div> 
        <a class="logout" href="/logout"> Logout</a>
    </div>
    <div class="titulo">
        @if(session('user'))
        <h3> Bienvenido {{session('user')->nombre}}</h3>
        @endif
        <h4>I N V E R S I O N I T O</h4>
        <h5> Tu brújula para valorar acciones a corto plazo!</h5>
        <h6>*Inversionito es una herramienta más de análisis, recuerda que tú eres responsable por tus inversiones.</h6>
    </div>
    <div class="encabezado">
    <div class="banner"> <img src="../img/inversionito.jpg" alt=""></div>
    </div>
    <form class="formu" id="myForm" action="" method="get" >
    <input type="text" class="form-control" id="accion" name="nombre" placeholder="Simbolo de la accion"> </input>
    <button id="buscar" type="submit" class="btn btn-primary" value=''>Buscar</button>
    </form>
    <div id="resultadoBusqueda"></div>
    <div>
    @yield ('contenido')
    @yield ('contenido2')
 <!--
    @if (View::getSections()['contenido'] ?? null=== 'show')
    <div>
     <button>Seguir accion {{session('user')->nombre}}</button>
     </div>
    @endif-->
    @yield ('seguimiento')
    <title> Tus seguimientos </title>
    <style>
        table {
            width: 60%;
            margin: 20px auto;
            font-size: 18px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            background-color: white;
        }
        th{
            background-color: #BFA893;
        }
    </style>
    @if (session('mensaje'))
        <div id="alerta" class="alert alert-success" style="text-align: center; margin-top: 10px;">
            {{session ('mensaje') }}
        </div>
    @endif
    <table>
        <tr>
            <th> Nombre de la acción</th>
            <th> Fecha inicio seguimiento </th>
            <th> Accion </th>
        </tr>
    @if (count($acciones))
    @foreach($acciones as $accion)
    <table>
        <tr>
<!--  aqui hubiese sido mejor rcuperar todos los datos y compararlos antes de volver a llamar a la db -->
    <td>{{$accion['nombretecnico']}}</th>
    <td>{{$accion['fechaalta']}}</th>
    <td><form method="get" action="/verseguimiento/{{$accion['nombretecnico']}}">
        <input type="hidden" name="fechaalta" value="{{$accion['fechaalta']}}" >
        <input type="hidden" name="usuario" value="{{$accion['idusuario']}}" >
        <input type="hidden" name="simbolo" value="{{$accion['nombretecnico']}}" >
        <input type="hidden" name="idseguimiento" value="{{$accion['id']}}">
        <button>Ver seguimiento </button></th>
        </form>
        <form  method="post" action="/accion/{{$accion['id']}}">
            @csrf
            @method('DELETE')
            <button >Eliminar</button>
        </form>
    </td>

    @endforeach
    </table>
    @else
    <h4>No tienes acciones en seguimiento </h4>
    @endif
    </div>

    <footer>
        <div style="text-align: center; color:grey;">
            <br>
        <h6>2024 Copyright</h6>
        <h6>Inversionito</h6>
        <br>
        </div>
    </footer>

    <script>
        const form= document.getElementById("myForm");
        const input =document.getElementById("accion");

        form.addEventListener ("submit",function (e){
            e.preventDefault();
            const valore=input.value;
            valor=valore.toUpperCase();
            form.action= "/stock/"+encodeURIComponent(valor);
            form.submit();
            console.log (valor);
        } );

        setTimeout (function(){
            let alerta= document.getElementById('alerta');
            if (alerta) {
                alerta.style.transition = 'opacity 0.5s ease';
                alerta.style.opacity= '0';
                setTimeout(() =>alerta.remove(),500);
            }
        },3000);
    </script>
</body>
</html>