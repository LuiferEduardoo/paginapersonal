<?php
// Iniciamos la sesión
session_start();

    // Verificamos si existe una variable de sesión 'rol'
    if(!isset($_SESSION['rol'])){
    // Si no existe, redirigimos al usuario a la página de login
    header('location: login');

    }

    // Si existe una variable de sesión 'rol'
    if($_SESSION['rol'] != 1)
    {
        // Verificamos si el valor de la variable de sesión 'rol' es distinto de 1 (admin)
        // Si es así, redirigimos al usuario a la página de login
        header('location: login');    
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
    <link rel="stylesheet" href="../Css/Blog/login.css">
</head>
<body>
    <div class="container_principal">
        <form class ="form" action="validar" method="post" id="formulario">
            <h1>Login</h1>
            <label for="input_usuario">
                <p>Usuario</p>
            </label>
            <input type="text" placeholder="Ingrese su nombre de usuario" name="username" id="input_usuario" required>
            <label for="input_password">
                <p>Contraseña</p>
            </label>
            <input type="password" placeholder="ingrese su contraseña" name="password" id="input_password" required>
            <input type="submit" value="Ingresar" id="input_sbumit">
            </form> 
    </div>
    <script src="../Scripts/blog/login.js"></script>
</body>
</html>