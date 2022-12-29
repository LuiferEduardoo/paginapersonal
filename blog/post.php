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
    <title>New post</title>
</head>
<body>
    <div class="container">
        <section class="container__header">
        <h1>Crea un nuevo post en el blog</h1>
        <a href="singoff">Cerrar sesion</a>
        </section>
        <form action="insertar" method="post" enctype="multipart/form-data">
            <label for="input_titulo">Titulo</label>
            <input type="text" name="title" id="input_titulo" placeholder="Ingrese un titulo"><br>
            <label for="input_nombre_pagina">Nombre de pagina</label>
            <input type="text" name="nombre_pagina" id="input_nombre_pagina" placeholder="Para escribir una frase, se debe utilizar el guion (-)"><br>
            <label for="textare_contenido">Ingrese el contenido del blog</label><br>
            <textarea name="content" id="textare_contenido" cols="100" rows="30"></textarea><br>
            <input type="hidden" name ="MAX_TAM" value="2097152">
            <label for="input_file">Seleccione una imagen con tamaño inferior a 2 MB</label>
            <input type="file" name="imagen" id="input_file"><b><br>
            <button>Postear</button>
        </form>

    </div>
</body>
</html>