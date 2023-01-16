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
    <link rel="stylesheet" href="../../Css/Blog/content.css">
    <link rel="stylesheet" href="../../Css/Menu/menu.css">
    <link rel="stylesheet" href="../../Css/Footer/footer.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
</head>
<body>
  <nav class="menu">
      <div class="menu_MobileIcon">
        <img src="https://i.ibb.co/B2HNMRx/icons8-men-redondeado-32.png" alt="">
      </div>
        <a href="../../index" class="menu_logo">
          <img src="https://i.ibb.co/mJQxt3j/Logotipo-white.png"class="logo" alt="Logo" srcset="">
        </a>
      <ul class="menu_Desktop">
        <li>
          <a href="../../index">HOME</a>
        </li>
        <li>
          <a href="../../sobre-mi">SOBRE MÍ</a>
        </li>
        <li>
          <a href="../../contacto">CONTACTO</a>
        </li>
      </ul>
      <div class="menu_Mobile" >
        <ul>
          <li>
            <a href="../../index"><img src="https://img.icons8.com/wired/64/null/home-page.png" alt="" srcset="">HOME </a>
          </li>
          <li>
            <a href="../../sobre-mi"><img src="https://cdn-icons-png.flaticon.com/512/1443/1443781.png" alt="" srcset="">SOBRE MÍ</a>
          </li>
          <li>
            <a href="../../portaforio"><img src="https://cdn-icons-png.flaticon.com/512/943/943329.png" alt="" srcset="">PORTAFORIO</a>
          </li>
          <li>
            <a href="../../blog.php"><img src="https://cdn-icons-png.flaticon.com/512/4922/4922073.png" alt="">BLOG</a>
          </li>
          <li>
            <a href="../../contacto"><img src="https://cdn-icons-png.flaticon.com/512/2590/2590818.png" alt="" srcset="">CONTACTO</a>
          </li>
        </ul>
      </div>
    </nav>
    <header class="header-blog">
        <h1><?php echo $title_post ?></h1>
        <div class="header-blog-author">
            <img class ="header-blog-author--img"src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="" srcset="">
                <section class="header-blog-author-date">
                    <a href="../../sobre-mi.html"><p class="header-blog-author-date--name">LUIFER EDUARDO ORTEGA</p></a>
                    <p class="header-blog-author-date--date"><?php echo $date_post ?></p>
                </section>
        </div>
    </header>
    <div class="container_principal">
            <img src="img/<?php echo $img_post?>" alt="" srcset="">
            <div class="container_principal-content"><?php echo $content_post?></div>
    </div>
    <footer>
        <div class="footer--conteiner">
          <section class="footer--conteiner__correo">
            <p>CORREO</p>
            <img src="https://i.ibb.co/8609h4Y/correo.png" alt="Correo">
            <a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a>
          </section>
          <section class="footer--conteiner__enlaces">
            <p>ENLACES</p>
            <ul>
              <li>
                <a href="../../sobre-mi"> SOBRE MÍ</a>
              </li>
              <li>
                <a href="../../portaforio">PORTAFORIO</a>
              </li>
              <li>
                <a href="../../blog.php">BLOG</a>
              </li>
              <li>
                <a href="../../contacto">CONTACTO</a>
              </li>
            </ul>
          </section>
          <section class="footer--conteiner__redes-sociales">
            <p>REDES SOCIALES</p>
            <a href="https://www.twitter.com/luifereduardoo"><img src="https://i.ibb.co/kXbdQmC/twitter.png" alt="Twitter"></a>
            <a href="https://www.instagram.com/luifereduardoo"><img src="https://i.ibb.co/F812NGB/instagram.png" alt="Instagram"></a>
            <a href="https://www.facebook.com/luifereduardoo"><img src="https://i.ibb.co/fn6TjWL/facebook.png" alt="Facebook"></a>
            <a href="https://www.linkedin.com/in/luifereduardoo/"><img src="https://i.ibb.co/kxtgH68/linkedin.png" alt="Linkedin"></a>
            <a href="https://github.com/LuiferEduardoo"><img src="https://i.ibb.co/M9dYPNB/github.png" alt="Github"></a>
          </section>
        </div>
        <p class="footer--creditos">Luifer Eduardo Ortega © 2022</p>
      </footer>
      <script src="../../Scripts/nav/nav.js"></script>
</body>
</html>