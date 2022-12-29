<?php
include("config/database.php");
session_start();
ini_set('display_errors', 0);

if($_SESSION['rol'] == 1)
{
    // Verificamos si el valor de la variable de sesión 'rol' es distinto de 1 (admin)
    
    if(!$conex)
    {
    echo "La conexión con la base de datos ha fallado: ";
    exit();
    }

    if ($_FILES['imagen']['error'])
    {
        switch ($_FILES['imagen']['error'])
        {
            case 1: //error exceso de tamaño de archivo
                include ("post.php");
                ?>
                <h1>El tamaño del archivo excede lo permitido por el servidor</h1>
                <?php
            break; 

            case 2: //Error tamaño archivo que se marca en el formulario
                include ("post.php");
                ?>
                <h1>El tamaño del archivo excede 2 MB</h1>
                <?php
            break;
            
            case 3: //Error cuando el archivo no fue enviado bien a el servidor 
                include ("post.php");
                ?>
                <h1>El envio del archivo ha producido un error</h1>
                <?php
            break;

            case 4: //No hay archivo en el campo de archivo 
                include ("post.php");
                ?>
                <h1>Ha dejado el campo de archivo vacio</h1>
                <?php
            break; 
        }
    }
    else
    {
        if((isset($_FILES['imagen']['name']) && ($_FILES['imagen']['error'] == UPLOAD_ERR_OK)))
        {
            $destiny_file = "content/img/";
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destiny_file . $_FILES['imagen']['name']);
            $content =$_POST['content']; 
            $title =$_POST['title'];
            $date =date("Y-m-d");
            $name_page =$_POST['nombre_pagina'];
            $file =$_FILES['imagen']['name'];
            $consulta = "INSERT INTO publications (titulo, fecha, contenido, imagen, nombre_pagina) VALUES ('${title}', '${date}', '${content}', '${file}', '${name_page}')";
            $resultado =mysqli_query($conex, $consulta);
            mysqli_close(($conex));
            include("post.php");
            ?>
            <p>Se ha enviado la nueva entrada del blog correctamente</p>
            <?php
            if($resultado)
            {
                include('create.php');
            }
        }
        else
        {
            ?>
            <h1>Se ha producido un error a la hora de enviar la nueva entrada del blog</h1>
            <?php
        }
    }
}
else
{
    header("location: login");
}

?>