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
            <img class = "profile_logo"src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="profile" srcset="">
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
            <img src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="Foto" srcset="">
            <div class="photo">
                <p id="photo">Editar foto</p>
            </div>
            <form action="#" method="post" class = "form-photo">
                <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                <input id = "input_file" type="file" name="photo" required>
                <button>Actualizar foto</button>
            </form>
            <div class="container-principal-informacion-personal">
               <h2>Tus datos</h2> 
               <p>Username: <img id="username" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
                <form action="#" method="post" class = "form-username">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input type="text" id = "input_username"placeholder="Escribe el nuevo username" required>
                    <button>Cambiar username</button>
                </form>
               <p>Nombre: <img id="nombre" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
                <form action="#" method="post" class = "form-nombre">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input id = "input_name" type="text" placeholder="Escribe el nuevo nombre" required>
                    <button>Cambiar nombre</button>
                </form>
                <p>Email: <img id="email" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" alt="Editar" srcset=""></p>
               <form action="#" method="post" class = "form-email">
                    <img id = "cerrarIcon" class="cerrarIcon" src="https://cdn-icons-png.flaticon.com/512/54/54972.png" alt="cerrar" srcset="">
                    <input id = "input_email" type="text" placeholder="Escribe el nuevo email" required>
                    <input id = "input_password" type="password" placeholder="Escriba la contraseña" required>
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
        </section>
    </div>
    <script src="../Scripts/blog/profile-menu.js"></script>
    <script src="../Scripts/blog/my-account.js"></script>
</body>
</html>