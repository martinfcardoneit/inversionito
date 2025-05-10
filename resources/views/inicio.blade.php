
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
    <script> src="https://cdn.jsdelivr.net/npm/chart.js"</script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>

    <title>Inversionito</title>
</head>
<body>
    <div class="divlogin" style="text-align: right; margin-right:20px"> 
    
        <a class="login" href="/login"> Login</a>
        <br>
        <a class="login" href="/registration"> No eres usuario? Registrate aqui</a>
    
    </div>
    <div class="titulo">
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
    <br>
    <h6 style="text-align: center;">Ingresa el nombre técnico de la acción, ej: MRNA</h6>
    <br>
    <h6 style="text-align: center;">Para ver modo de prueba: <br> usuario: betocasella@gm.com  <br> contraseña: admin </h6>
    <div>
    @yield ('contenido')
    @yield ('seguimiento')
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
        
    </script>
</body>
</html>