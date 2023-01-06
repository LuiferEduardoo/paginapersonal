<?php
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$mensaje = $_POST['mensaje'];
$para = 'contacto@luifereduardoo.com';
$titulo = 'Nuevo correo de la web';
 
$msjCorreo = "Nombre: $nombre\n E-Mail: $email\n Mensaje:\n $mensaje";
 
if ($_POST['submit'])
{
    if (mail ($para, $titulo, $msjCorreo)) {
        include('contacto.html');
        ?>
        <div class="message">
            <p>¡El mensaje se ha enviado con exito! Muy pronto respondere su mensaje.</p>
            <img class = "message_img" src="https://cdn-icons-png.flaticon.com/512/1828/1828778.png" alt="cerrar" srcset="">
        </div>
        <?php
    } 
    else {
        include('contacto.html');
        ?>
        <div class="message_error">
            <p>Se ha producido un error a la hora de enviar el mensaje. Intente de nuevo, o comuniquese directamente a el siguiente correo: <br><a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a></p>
            <img class = "message_error_img" src="https://cdn-icons-png.flaticon.com/512/1828/1828778.png" alt="cerrar" srcset="">
        </div>
        <?php
    }
}

?>