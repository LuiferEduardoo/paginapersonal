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
    if(isset($_POST['current_password']) && isset($_POST['new_password'])){
        include("config/database.php");
        $conex = new Database();
      
        // Preparar la consulta
        $query = $conex->connect()->prepare("SELECT password FROM usuarios WHERE id = ?");
        $query->bindParam(1, $_SESSION['id']);
      
        // Ejecutar la consulta y almacenar el resultado
        $query->execute();
        $row = $query->fetch(PDO::FETCH_NUM);
        $hashed_password = $row[0];
      
        // Verificar la contraseña antigua
        if(password_verify($_POST['current_password'], $hashed_password)){
          // Hash de la nueva contraseña
          $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
      
          // Actualizar la contraseña
          $query = $conex->connect()->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
          $query->bindParam(1, $new_password);
          $query->bindParam(2, $_SESSION['id']);
      
          if ($query->execute()) {
            // Mostramos que se cambio la contraseña
            echo "Contraseña cambiada correctamente";
          }
          else {
            // Mostrar mensaje de error
            echo "Error al actualizar la contraseña";
          }
        }
        else {
          // Mostrar mensaje de error
          echo "Contraseña incorrecta";
        }
      }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New password</title>
</head>
<body>
    <div class="container">
        <section class="container__header">
        <h1>Crea una nueva contraseña</h1>
        <a href="post">Postear</a>
        <a href="singoff">Cerrar sesion</a>
        </section>
        <form action="#" method="post">
            <label for="input_current_password">Contraseña actual</label>
            <input type="password" name="current_password" id="input_current_password" placeholder="Ingrese su contraseña actual" required><br>
            <label for="input_new_password">Nueva contraseña</label>
            <input type="password" name="new_password" id="input_new_password" placeholder="Ingrese su nueva contraseña" required><br>
            <label for="input_new_password_replay">Repite la nueva contraseña</label>
            <input type="password" name="new_password_replay" id="input_new_password_replay" placeholder="Repita su contraseña" required><br>
            <button>Cambiar contraseña</button>
        </form>

    </div>
</body>
</html>