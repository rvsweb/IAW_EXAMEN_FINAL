<?php
// Archivo con la conexión a la base de datos usando MySQLi
$conexion = mysqli_connect('db', 'root', 'root', 'videojuegos_db');

// Si la conexión falla, muestro un mensaje de error y detengo la ejecución del script
if (!$conexion) {
    // Si la conexión falla, muestro un mensaje de error y detengo la ejecución del script
    die('Error de conexión: ' . mysqli_connect_error());
}

// Establezco el conjunto de caracteres a UTF-8 para evitar problemas con caracteres especiales
mysqli_set_charset($conexion, 'utf8');
