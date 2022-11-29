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
    echo '<script> alert ("El mensaje se ha enviado"); 
    window.location.replace("http://luifereduardoo.com/contacto.html");
    </script>';
} 
else {
    echo '<script> alert ("Se ha producido un error, por favor intente de nuevo");
    window.location.replace("http://luifereduardoo.com/contacto.html");
    </script>';
}
}

?>