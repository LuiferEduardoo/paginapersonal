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
                echo '<div class="error" id="message">
                    <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <p>El tamaño del archivo excede lo permitido por el servidor</p>
                    <img src="https://cdn-icons-png.flaticon.com/512/5799/5799337.png" alt="Limite" srcset="">
                </div>
                <script src="../Scripts/blog/message.js"></script>';
            break; 

            case 2: //Error tamaño archivo que se marca en el formulario
                include ("post.php");
                echo '<div class="error" id="message">
                    <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <p>El tamaño del archivo excede 2 MB</p>
                    <img src="https://cdn-icons-png.flaticon.com/512/6227/6227649.png" alt="Limite" srcset="">
                </div>
                <script src="../Scripts/blog/message.js"></script>';
            break;
            
            case 3: //Error cuando el archivo no fue enviado bien a el servidor 
                include ("post.php");
                echo '<div class="error" id="message">
                    <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <p>El envio del archivo ha producido un error</p>
                    <img src="https://cdn-icons-png.flaticon.com/512/2581/2581972.png" alt="Error" srcset="">
                    <script src="../Scripts/blog/message.js"></script>;
                </div>';
            break;

            case 4: //No hay archivo en el campo de archivo 
                include ("post.php");
                echo '<div class="error" id="message">;
                <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                <p>Ha dejado el campo de archivo vacio</p>
                <img src="https://cdn-icons-png.flaticon.com/512/1380/1380641.png" alt="vacio" srcset="">
            </div>
            <script src="../Scripts/blog/message.js"></script>';
                
            break; 
        }
    }
    else
    {
        if((isset($_FILES['imagen']['name']) && ($_FILES['imagen']['error'] == UPLOAD_ERR_OK)))
        {
            $date = new DateTime();
            $timezone = new DateTimeZone('America/Bogota');
            $date->setTimezone($timezone);
            $destiny_file = "content/img/";
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destiny_file . $_FILES['imagen']['name']);
            $content =$_POST['content']; 
            $title =$_POST['title'];
            $date_ =$date->format('Y-m-d H:i:s');
            $date_legible = $date->format("l, d F Y H:i:s");
            $name_page =$_POST['nombre_pagina'];
            $file =$_FILES['imagen']['name'];
            $consulta = "INSERT INTO publications (titulo, fecha, fecha_legible, contenido, imagen, nombre_pagina) VALUES ('${title}', '${date_}', '${date_legible}', '${content}', '${file}', '${name_page}')";
            $resultado =mysqli_query($conex, $consulta);
            mysqli_close(($conex));
            include("post.php");
            ?>
            <div class="insertado" id="message">
                <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                <p>Se ha enviado la nueva entrada del blog correctamente miralo <a href="content/<?php echo $name_page ?>">Aquí</a></p>
                <img src="https://cdn-icons-png.flaticon.com/512/3125/3125856.png" alt="Correctamente">
            </div>
            <script src="../Scripts/blog/message.js"></script>;
            <?php
            if($resultado)
            {
                include('create.php');
            }
        }
        else
        {
            include("post.php");
            echo '<div class = "error" id="message">
                <img id = "cerrar" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                <p>Se ha producido un error a la hora de enviar la nueva entrada del blog</p>
                <img src="https://cdn-icons-png.flaticon.com/512/2581/2581972.png" alt="Error" srcset="">
            </div>
            <script src="../Scripts/blog/message.js"></script>';
        }
    }
}
else
{
    header("location: login");
}

?>