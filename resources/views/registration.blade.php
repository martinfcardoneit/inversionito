<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <title>Registro INVERSIONITOt</title>
</head>
<body class="bodylog">
<section class="loginbody">
<div class="regiscuadro" >
   <h2 style="padding-top: 10px;">Registrate aqui</h2>
   <h4>I N V E R S I O N I T O</h4>
   <form method="post" action="/registrousuario">
    <!-- @method('POST')  recordar poner esto porque laravel solo acepta get y put , asique hay que forzar al post *el router no debe tener espacios-->
    @csrf <!--  recordar poner siempre en los formularios post el @csrf -->
    <div class="control">
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu alias" value="{{old('nombre')}}" >
    </div>
    <div class="control">
        <input type="email" id="email" name="email" placeholder="Email usuario" value="{{old('email')}}">
    </div>
    <div class="control" style="padding-bottom: 10px;">
        <input type="password" id="password" name="password" placeholder="Ingresa una contraseña">
    </div>
    <div class="control">
    <button style="height: 40px;" class="verde"> Registrarse </button>
    </div>
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif
   </form> 
</div>
</section>
</body>
</html>

