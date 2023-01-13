<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Css/Blog/blog.css">
    <link rel="stylesheet" href="Css/Menu/menu.css">
    <link rel="stylesheet" href="Css/Footer/footer.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
  </head>
  <body>
  <nav class="menu">
      <div class="menu_MobileIcon">
        <img src="https://i.ibb.co/B2HNMRx/icons8-men-redondeado-32.png" alt="">
      </div>
        <a href="index" class="menu_logo">
          <img src="https://i.ibb.co/mJQxt3j/Logotipo-white.png"class="logo" alt="Logo" srcset="">
        </a>
      <ul class="menu_Desktop">
        <li>
          <a href="index">HOME</a>
        </li>
        <li>
          <a href="sobre-mi">SOBRE MÍ</a>
        </li>
        <li>
          <a href="portaforio">PORTAFORIO</a>
        </li>
        <li>
          <a href="blog.php">BLOG</a>
        </li>
        <li>
          <a href="contacto">CONTACTO</a>
        </li>
      </ul>
      <div class="menu_Mobile" >
        <ul>
          <li>
            <a href="index"><img src="https://img.icons8.com/wired/64/null/home-page.png" alt="" srcset="">HOME </a>
          </li>
          <li>
            <a href="sobre-mi"><img src="https://cdn-icons-png.flaticon.com/512/1443/1443781.png" alt="" srcset="">SOBRE MÍ</a>
          </li>
          <li>
            <a href="portaforio"><img src="https://cdn-icons-png.flaticon.com/512/943/943329.png" alt="" srcset="">PORTAFORIO</a>
          </li>
          <li>
            <a href="blog.php"><img src="https://cdn-icons-png.flaticon.com/512/4922/4922073.png" alt="">BLOG</a>
          </li>
          <li>
            <a href="contacto"><img src="https://cdn-icons-png.flaticon.com/512/2590/2590818.png" alt="" srcset="">CONTACTO</a>
          </li>
        </ul>
      </div>
    </nav>
    <section class="principal-container">
      <?php
      include("blog/config/baseblog.php");

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
              if($i == 1)
              {
                ?>
                <a href="blog/content/<?php echo $name_page?>">
                <div class="principal-container-full">
                        <img src="blog/content/img/<?php echo $image?>" alt="" srcset="">
                        <div>
                        <h1><?php echo $title?></h1>
                        <div class="p1-full"><?php echo $content?></div>
                        <div class="date"><?php echo $date ?></div>
                        </div>
                </div>
                </a>
                <?php
              }
              else
              {
                ?>
              <div class="principal-container-content">
                  <a href="blog/content/<?php echo $name_page?>">
                      <img src="blog/content/img/<?php echo $image?>" alt="" srcset="">
                      <h1><?php echo $title?></h1>
                      <p class="date"><?php echo $date ?></p>
                      <div class = "p1"><?php echo $content?></div>
                  </a>
              </div>
              <?php
              }
          }
      }
      ?>
      </section>
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
                <a href="sobre-mi"> SOBRE MÍ</a>
              </li>
              <li>
                <a href="portaforio">PORTAFORIO</a>
              </li>
              <li>
                <a href="blog.php">BLOG</a>
              </li>
              <li>
                <a href="contacto">CONTACTO</a>
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
      <script src="Scripts/nav/nav.js"></script>
  </body>
  </html>