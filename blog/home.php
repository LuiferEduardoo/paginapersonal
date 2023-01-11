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
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/Blog/home.css">
</head>
<body>
    <nav class="menu">
        <ul>
            <li><a href="post"><img src="https://i.ibb.co/DDJp312/001-archivo-nuevo.png" alt="" srcset="">Crear nuevo post</a></li>
            <li><a href="#"><img src="https://i.ibb.co/5W4g6Zn/002-editar.png" alt="" srcset="">Editar post</a></li>
            <li><a href="#"><img src="https://i.ibb.co/5W4g6Zn/002-editar.png" alt="" srcset="">Editar sobre mí</a></li>
            <li><a href="#"><img src="https://i.ibb.co/60cHfhb/003-conversacion.png" alt="" srcset="">Comentarios</a></li>
            <li><a href="create.php"><img src="https://i.ibb.co/prwTKK0/001-actualizar-flecha.png" alt="" srcset="">Actualizar entradas</a></li>
        </ul>
    </nav>
    <div class="container_principal">

        <section class="entradas_blog">
            <div class="entradas_blog_buscador">
                <div class="field" id="searchform">
                    <input type="text" id="searchterm" placeholder="¿Qué post del blog quieres buscar?" />
                    <button type="button" id="search">Buscar</button>
                </div>
            </div>
        <?php
      include("config/database.php");

      if(!$conex)
      {
          echo "La conexión ha fallado";
      }

      $consulta="SELECT * FROM publications ORDER BY ID DESC";

      if($resultado=mysqli_query($conex, $consulta))
      {
        $i = 0;
          while($registro=mysqli_fetch_assoc($resultado))
          {
              $i++;
              $name_page = $registro['nombre_pagina'];
              $image = $registro['imagen'];
              $title = $registro['titulo'];
              $content = $registro['contenido'];
              $date = $registro['fecha_legible'];
              ?>
              <div class="entradas_blog_content">
                  <a href="content/<?php echo $name_page?>">
                    <h1><?php echo $title?></h1>
                    <p class="date"><?php echo $date ?></p>
                    <div class = "p1"><?php echo $content?></div>
                    <img src="content/img/<?php echo $image?>" alt="" srcset="">
                  </a>
              </div>
              <?php
          }
      }
      ?>
        </section>
        <div class="profile">
            <img class = "profile_logo"src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="profile" srcset="">
            <div class="profile_menu inactive">
                <ul>
                    <li><a href="#"><img src="https://cdn-icons-png.flaticon.com/512/860/860784.png" alt="" srcset="">Mi cuenta</a></li>
                    <li><a href="singoff"><img src="https://cdn-icons-png.flaticon.com/512/992/992680.png" alt="" srcset="">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script src="../Scripts/blog/home.js"></script>
</body>