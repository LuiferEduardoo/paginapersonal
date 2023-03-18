<?php
ini_set('display_errors', 0);
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$mensaje = $_POST['mensaje'];
$para = 'contacto@luifereduardoo.com';
$titulo = 'Nuevo correo de la web';
 
$msjCorreo = "Nombre: $nombre\n E-Mail: $email\n Mensaje:\n $mensaje";
 
if ($_POST['submit'])
{
    if (mail ($para, $titulo, $msjCorreo)) {
        ?>
        <div class="message">
            <p>¡El mensaje se ha enviado con exito! Muy pronto respondere su mensaje.</p>
            <img class = "message_img" src="https://cdn-icons-png.flaticon.com/512/1828/1828778.png" alt="cerrar" srcset="">
        </div>
        <?php
    } 
    else {
        ?>
        <div class="message_error">
            <p>Se ha producido un error a la hora de enviar el mensaje. Intente de nuevo, o comuniquese directamente a el siguiente correo: <br><a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a></p>
            <img class = "message_error_img" src="https://cdn-icons-png.flaticon.com/512/1828/1828778.png" alt="cerrar" srcset="">
        </div>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactame</title>
    <link rel="stylesheet" href="Css/Contacto/contacto.css">
    <link rel="stylesheet" href="Css/Menu/menu.css">
    <link rel="stylesheet" href="Css/Footer/footer.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="icon" type="image/png" href="https://i.ibb.co/pnb03Fv/Likepng.png"/>
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
      <section class="principal">
        <h1>CONTACTAME</h1>
        <form class="principal--formulario-contacto" method="post" action="#" onsubmit="return validarFormulario()">
            <label for="nombre">Nombre:</label>
            <input id="nombre" name="nombre" placeholder="Nombre completo" required>
            <label for="email">Email:</label>
            <input id="email" name="email" type="email" placeholder="ejemplo@email.com" required>
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" placeholder="Escriba su mensaje aquí" required></textarea>
            <input id="submit" name="submit" type="submit" value="Enviar">
        </form>
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
    <script src="Scripts\Contacto/contacto.js"></script>
    <script src="Scripts/nav/nav.js"></script>
  </body>
  </html>