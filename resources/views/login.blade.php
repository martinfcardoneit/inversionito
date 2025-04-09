<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
</head>
<body class="bodylog">
<section class="loginbody">
    

    <div class="logincuadro">
   <h2 style="padding-bottom: 2%; padding-top:5%">Login</h2>
   <h4 style="padding-bottom: 5 px; padding-top:5px">I N V E R S I O N I T O </h4>
   <form  method="post" action="/login" class="formul">
    @method('POST') <!--  recordar poner esto porque laravel solo acepta get y put , asique hay que forzar al post *el router no debe tener espacios-->
    @csrf <!--  recordar poner siempre en los formularios post el @csrf -->
    <div >
        <input type="email" id="email" name="email" placeholder="Email usuario">
        @error('email')
    <div> {{$message}}</div>
        @enderror
    </div>
    <div>
        <input type="password" id="password" name="password" placeholder="Password">
        @error('password')
    <div> {{$message}}</div>
        @enderror
    </div>
    <div class="control">
    <button class="verde"> Login </button>
    </div>
    @if(session('success'))
    <div class="mensajes">
        {{ session ('success')['mensaje']}}
    </div>
    @endif

    @error('login')
    <div> {{$message}}</div>
        @enderror
   </form> 
   <a class="login" href="/registration"> No eres usuario? Registrate aqui</a>
</div>
</section>
</body>
</html>

