<?php
// Iniciamos la sesión
session_start();
ini_set('display_errors', 0);

    // Verificamos si existe una variable de sesión 'rol'
    if(!isset($_SESSION['rol'])){
    // Si no existe, redirigimos al usuario a la página de login
    header('location: ../login');

    }

    // Si existe una variable de sesión 'rol'
    if($_SESSION['rol'] != 1)
    {
        // Verificamos si el valor de la variable de sesión 'rol' es distinto de 1 (admin)
        // Si es así, redirigimos al usuario a la página de login
        header('location: ../login');    
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title_post; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Css/Blog/content.css">
    <link rel="stylesheet" href="Css/Menu/menu.css">
    <link rel="stylesheet" href="Css/Footer/footer.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
</head>
<body>
    <div class="container_principal">
        <h1><?php echo $title_post ?></h1>
        <img src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="" srcset="">
        <section class="container_principal-author--name">
            <p class="container_principal-author">LUIFER EDUARDO ORTEGA</p>
            <p class="container_principal-author--date"><?php echo $date_post ?></p>
        </section>
        <figure class="container_principal--img">
            <img src="img/<?php echo $img_post?>" alt="" srcset="">
        </figure>
        <section class="container_principal--content">
        <p><?php echo $content_post?></p>
        </section>
    </div>
</body>
</html>