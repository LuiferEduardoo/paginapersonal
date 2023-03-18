<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="Css/Blog/blog.css">
    <link rel="stylesheet" href="Css/Menu/menu.css">
    <link rel="stylesheet" href="Css/Footer/footer.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  </head>
  <body>
    <nav class="menu">
          <div class="menu_MobileIcon">
            <img src="https://i.ibb.co/B2HNMRx/icons8-men-redondeado-32.png" alt="">
          </div>
            <a href="index" class="menu_logo">
              <img src="img/Iconos/logo-black.png"class="logo" alt="Logo" srcset="">
            </a>
          <ul class="menu_Desktop">
            <li>
              <a href="index">Home</i></a>
            </li>
            <li>
              <a href="portafolio">Portafolio</a>
            </li>
            <li>
              <a href="blog.php">Blog</a>
            </li>
            <li>
              <a href="contacto">Contacto</a>
            </li>
          </ul>
          <div class="menu_Mobile" >
            <i class="las la-times close"></i>
            <ul>
              <li>
                <a href="index"><i class="las la-home icon"></i></i>Home</a>
              </li>
              <li>
                <a href="portafolio"><i class="las la-briefcase icon"></i>Portafolio</a>
              </li>
              <li>
                <a href="blog.php"><i class="las la-blog icon"></i></i>Blog</a>
              </li>
              <li>
                <a href="contacto"><i class="las la-envelope-open-text icon"></i>Contacto</a>
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
        <div class="footer-conteiner">
          <section class="footer-conteiner-infor-mobile">
            <div class="footer-conteiner-infor-logo">
              <img src="img/Iconos/logo-white.png" alt="Logo" srcset="">
            </div>
            <div class="footer-conteiner-infor-social-media">
              <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i class="lab la-twitter"></i></a>
              <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i class="lab la-instagram"></i></a>
              <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i class="lab la-facebook"></i></a>
              <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i class="lab la-linkedin-in"></i></a>
              <a href="https://github.com/LuiferEduardoo" target="_blank"><i class="lab la-github"></i></a>
            </div>
          </section>
          <section class="footer-conteiner-contact">
            <p>CORREO</p>
            <div class="footer-container-contact-email">
              <i class="las la-envelope-open"></i>
              <a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a>
            </div>
          </section>
          <section class="footer-conteiner-infor">
            <div class="footer-conteiner-infor-logo">
              <img src="img/Iconos/logo-white.png" alt="Logo" srcset="">
            </div>
            <div class="footer-conteiner-infor-social-media">
              <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i class="lab la-twitter"></i></a>
              <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i class="lab la-instagram"></i></a>
              <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i class="lab la-facebook"></i></a>
              <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i class="lab la-linkedin-in"></i></a>
              <a href="https://github.com/LuiferEduardoo" target="_blank"><i class="lab la-github"></i></a>
            </div>
          </section>
          <section class="footer-conteiner-link">
            <p>ENLACES</p>
            <ul>
              <li>
                <a href="sobre-mi">Home</a>
              </li>
              <li>
                <a href="portafolio">Portafolio</a>
              </li>
              <li>
                <a href="blog.php">Blog</a>
              </li>
              <li>
                <a href="contacto">Contacto</a>
              </li>
            </ul>
          </section>
        </div>
        <div class="footer-copyright">
          <p>Luifer Eduardo Ortega ©2023</p>
        </div>
      </footer>
      <script src="Scripts/nav/nav.js"></script>
  </body>
  </html>