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
    include ('config/database.php');

    function update_photo()
    {
        $type = $_FILES['photo']['type'];
        if (((strpos($type, "gif") || strpos($type, "jpeg") || strpos($type, "jpg") || strpos($type, "png"))))
        {
            $conex = new Database();
            $destiny_file = "../img/profile/";
            $photo =$_FILES['photo']['name'];
            $query = $conex->connect()->prepare("UPDATE usuarios SET photo = ? WHERE id = ?");
            $query->bindParam(1, $photo);
            $query->bindParam(2, $_SESSION['id']);
            if ($query->execute()) {
                // Mostramos que se cambio la foto
                move_uploaded_file($_FILES['photo']['tmp_name'], $destiny_file . $_FILES['photo']['name']);
                return "Foto actualizada con exito, para verla reflejada tiene que volver a inicar sesión";
              }
              else {
                // Mostrar mensaje de error
                return "Error a la hora de actualizar la foto";
              }
        }
        else
        {
            return "Formato no permitido";
        }
    }
    function update_username_and_name( $input, $type)
    {
        $conex = new Database();
        $var = $_POST[$input];
        $query = $conex->connect()->prepare("UPDATE usuarios SET $input = ? WHERE id = ?");
        $query->bindParam(1, $var);
        $query->bindParam(2, $_SESSION['id']);
        if ($query->execute()) {
            // Mostramos que se cambio el username"
            return "$type actualizado con exito";
        }
        else {
            // Mostrar mensaje de error
            return "Error a la hora de actualizar el $type";
        }
    }
    function password_user()
    {
        $conex = new Database();
        // Preparar la consulta
        $query = $conex->connect()->prepare("SELECT password FROM usuarios WHERE id = ?");
        $query->bindParam(1, $_SESSION['id']);
              
        // Ejecutar la consulta y almacenar el resultado
        $query->execute();
        $row = $query->fetch(PDO::FETCH_NUM);
        return $hashed_password = $row[0];
    }
    function update_email()
    {
        if (password_verify($_POST['password_email'], password_user()))
        {
            $conex = new Database();
            $email = $_POST['email'];
            $query = $conex->connect()->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
            $query->bindParam(1, $email);
            $query->bindParam(2, $_SESSION['id']);
            if ($query->execute()) {
                // Mostramos que se cambio el username"
                return "Email actualizado con exito";
            }
            else {
                // Mostrar mensaje de error
                return "Error a la hora de actualizar el Email";
            } 
        }
        else 
        {
            return "Contraseña incorrecta";
        }
    }
    function update_password()
    {
        if(password_verify($_POST['current_password'], password_user())){
            // Hash de la nueva contraseña
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        
            // Actualizar la contraseña
            $conex = new Database();
            $query = $conex->connect()->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $query->bindParam(1, $new_password);
            $query->bindParam(2, $_SESSION['id']);
        
            if ($query->execute()) {
              // Mostramos que se cambio la contraseña
              return "Contraseña cambiada correctamente";
            }
            else {
              // Mostrar mensaje de error
              return "Error al actualizar la contraseña";
            }
          }
          else {
            // Mostrar mensaje de error
            return "Contraseña incorrecta";
          }
    }

    $conex = new Database();
    $verify = $conex->connect()->prepare("SELECT * FROM usuarios WHERE id = ?");
    $verify->bindParam(1, $_SESSION['id']);
  
    $verify->execute();
    $row = $verify->fetch(PDO::FETCH_NUM);
    $usarname = $row[1];
    $name = $row[5];
    $email = $row[4];
    $message = "";
    if(isset($_FILES['photo']['name'])){
        $message = update_photo();
    }
    else if (isset($_POST['username'])){
        $message = update_username_and_name('username', 'username');
    }
    else if (isset($_POST['name'])){
        $message = update_username_and_name('name', 'Nombre');
    }
    else if (isset($_POST['email']))
    {
        $message = update_email();
    }
    else if (isset($_POST['current_password']) && isset($_POST['new_password']))
    {
        $message = update_password();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/Blog/my-account.css">
    <link rel="stylesheet" href="../Css/Blog/profile-menu.css">
    <title>Mi cuenta</title>
</head>
<body>
    <section class="header">
        <div class="profile">
            <img class = "profile_logo"src="../img/profile/<?php echo $_SESSION['profile']?>" alt="profile" srcset="">
            <div class="profile_menu inactive">
                <ul>
                    <li><a href="singoff"><img src="https://cdn-icons-png.flaticon.com/512/992/992680.png" alt="" srcset="">Cerrar sesión</a></li>
                </ul>
            </div>
            <div class="volver_home">
                <a href="home"><img src="https://cdn-icons-png.flaticon.com/512/61/61022.png" alt="Volver_inicio" srcset=""></a>
            </div>
    </section>
    <div class="container-principal">
        <h1>Editar perfil</h1>
        <section class="container-principal-informacion">
            <img id = "profile_photo"src="../img/profile/<?php echo $_SESSION['profile']?>" alt="Foto" srcset="">
            <div class="photo">
                <p id="photo">Editar foto</p>
            </div>
            <form action="#" method="post" class = "form-photo" enctype="multipart/form-data">
                <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                <input id = "input_file" type="file" name="photo" required>
                <button>Actualizar foto</button>
            </form>
            <div class="container-principal-informacion-personal">
               <h2>Tus datos</h2> 
               <p>Username: <?php echo $usarname ?><img id="username" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
                <form action="#" method="post" class = "form-username">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input type="text" id = "input_username"placeholder="Escribe el nuevo username" name = "username" required>
                    <button>Cambiar username</button>
                </form>
               <p>Nombre: <?php echo $name ?><img id="nombre" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
                <form action="#" method="post" class = "form-nombre">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input id = "input_name" type="text" placeholder="Escribe el nuevo nombre" name = "name" required>
                    <button>Cambiar nombre</button>
                </form>
                <p>Email: <?php echo $email ?> <img id="email" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
               <form action="#" method="post" class = "form-email">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input id = "input_email" type="text" placeholder="Escribe el nuevo email" name="email" required>
                    <input id = "input_password" type="password" placeholder="Escriba la contraseña" name = "password_email"required>
                    <button>Cambiar email</button>
                </form>
                <p id="contraseña">Cambiar contraseña</p>
                <form action="#" method="post" class = "form-contraseña" id = "form3">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input id = "input_currente_password" type="password" name="current_password" id="input_current_password" placeholder="Ingrese su contraseña actual" required>
                    <input id = "input_new_password" type="password" name="new_password" id="input_new_password" placeholder="Ingrese su nueva contraseña" required>
                    <input id = "input_password_replay" type="password" name="new_password_replay" id="input_new_password_replay" placeholder="Repita su contraseña" required>
                    <p id ="message_error">La contraseña debe tener por lo menos 8 caracteres, una minúscula, una mayúscula, al menos un caracter espacial y un numero</p>
                    <button>Cambiar contraseña</button>
                </form>
            </div>
            <div  class ="message">
                <p><?php echo $message ?></p>            
            </div>
        </section>
    </div>
    <script src="../Scripts/blog/profile-menu.js"></script>
    <script src="../Scripts/blog/my-account.js"></script>
</body>
</html>