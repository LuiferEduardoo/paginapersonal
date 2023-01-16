<?php
// Incluimos el archivo con la clase Database
include("config/database.php");
ini_set('display_errors', 0);

// Iniciamos la sesión
session_start();

// Verificamos que se hayan enviado el nombre de usuario y la contraseña
if(isset($_POST['username']) && isset($_POST['password'])){
  // Almacenamos el nombre de usuario y la contraseña en variables
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Creamos una nueva instancia de la clase Database
  $conex = new Database();

  $verify = $conex->connect()->prepare("SELECT * FROM usuarios WHERE username = ?");
  $verify->bindParam(1, $username);

  $verify->execute();
  $row = $verify->fetch(PDO::FETCH_NUM);
  $hashed_password = $row[2];

  if (password_verify($password, $hashed_password))
  {
    echo "Contraseña correcta";
    // Almacenamos el valor del campo 'rol' en una variable
    $rol = $row[3];
    $id = $row [0];
    $photo = $row[6];
    // Guardamos el valor del rol en la sesión
    $_SESSION['rol'] = $rol;
    $_SESSION['id'] = $id;
    $_SESSION['profile'] = $photo;
    if($rol == 1)
    {
      header('location:home');
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
  <div class="error">
    <p class="error_p">Nombre de usuario o contraseña incorrecto</p>
  </div>
  <?php
  }
  }