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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/Blog/post.css">
    <title>New post</title>
</head>
<body>
        <section class="header">
        <div class="profile">
            <img class = "profile_logo"src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="profile" srcset="">
            <div class="profile_menu inactive">
                <ul>
                    <li><a href="#"><img src="https://cdn-icons-png.flaticon.com/512/860/860784.png" alt="" srcset="">Mi cuenta</a></li>
                    <li><a href="singoff"><img src="https://cdn-icons-png.flaticon.com/512/992/992680.png" alt="" srcset="">Cerrar sesión</a></li>
                </ul>
            </div>
            <div class="volver_home">
                <a href="home"><img src="https://cdn-icons-png.flaticon.com/512/61/61022.png" alt="Volver_inicio" srcset=""></a>
            </div>
        </section>
        <div class="principal_container">
            <form class = "principal_container-form"action="insertar" method="post" enctype="multipart/form-data">
                <h1>Crea un nuevo post en el blog</h1>
                <input type="text" name="title" id="input_titulo" placeholder="Ingrese un titulo">
                <input type="text" name="nombre_pagina" id="input_nombre_pagina" placeholder="Ingrese el nombre de la pagina, recuerde separar las palabras con (-)">
                <textarea name="content" id="textare_contenido" placeholder="Ingrese el contenido del blog aquí"></textarea>
                <input type="hidden" name ="MAX_TAM" value="2097152">
                <label for="input_file">Seleccione una imagen con tamaño inferior a 2 MB</label>
                <input type="file" name="imagen" id="input_file" accept="image/*">
                <button>Postear</button>
            </form>
        </div>
        <script src="../Scripts/blog/post.js"></script>
    </body>
</html>