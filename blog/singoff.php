<?php
// Iniciamos la sesión
session_start();

// Destruimos la sesión
session_destroy();

// Redirigimos al usuario a la página de login
header('location:login');
