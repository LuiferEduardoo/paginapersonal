<?php
// Incluimos el archivo con la clase Database
include("config/database.php");

// Iniciamos la sesión
session_start();

// Verificamos que se hayan enviado el nombre de usuario y la contraseña
if(isset($_POST['username']) && isset($_POST['password'])){
  // Almacenamos el nombre de usuario y la contraseña en variables
  $username = $_POST['username'];
  $password = sha1($_POST['password']); // Encriptamos la contraseña con sha1

  // Creamos una nueva instancia de la clase Database
  $conex = new Database();

  // Realizamos una consulta a la tabla usuarios para verificar si existe un registro con ese nombre de usuario y contraseña
  $query = $conex->connect()->prepare("SELECT * FROM usuarios WHERE username = :username AND password = :password");
  $query->execute(['username' => $username, 'password' => $password]);

  // Almacenamos el resultado de la consulta en una variable
  $row = $query->fetch(PDO::FETCH_NUM);

  // Si la consulta devolvió un resultado
  if($row == true){
    // Almacenamos el valor del campo 'rol' en una variable
    $rol = $row[3];
    // Guardamos el valor del rol en la sesión
    $_SESSION['rol'] = $rol;
    if($rol == 1)
    {
      header('location:post');
      // Redirigimos al usuario a la página 'post'
    }
  }
  // Si la consulta no devolvió un resultado
  else{
    // Mostramos el formulario de login y un mensaje de error
    ?>
    <?php
    include("login.php");
  
  ?>
  <h1 class="bad">Nombre de usuario o contraseña incorrecto</h1>
  <?php
  }
}