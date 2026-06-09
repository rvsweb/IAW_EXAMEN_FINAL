<?php

// Creo una sesión para almacenar mensajes de éxito o error
session_start();

// Importo la conexión a la base de datos
require __DIR__ . '/../basedatos/conexion.php';

// Obtengo el id del videojuego a eliminar desde la URL, validándolo como un entero
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Si el id no es válido, redirijo al index con un mensaje de error
if (!$id) {
    // Si el id no es válido, redirijo al index con un mensaje de error
    $_SESSION['mensaje'] = 'Identificador no válido.';
    // Redirijo al index y salgo del script
    header('Location: index.php');
    // fin del script para evitar que se ejecute el código de eliminación si el id no es válido
    exit;
}

// Preparo y ejecuto la consulta SQL para eliminar el videojuego con el id especificado 
$q = "DELETE FROM videojuegos WHERE id = ?";
// Preparo y ejecuto la consulta SQL para eliminar el videojuego con el id especificado
$stmt = mysqli_stmt_init($conexion);
// Preparo la consulta SQL con el id como parámetro 
mysqli_stmt_prepare($stmt, $q);
// Enlazo el parámetro id a la consulta SQL y ejecuto la consulta para eliminar el videojuego 
mysqli_stmt_bind_param($stmt, 'i', $id);
// Enlazo el parámetro id a la consulta SQL y ejecuto la consulta para eliminar el videojuego
mysqli_stmt_execute($stmt);
// Cierro la declaración y la conexión a la base de datos
mysqli_stmt_close($stmt);
// Cierro la declaración y la conexión a la base de datos
mysqli_close($conexion);

// Almaceno un mensaje de éxito en la sesión indicando que se eliminó el juego con el id especificado, redirijo al index y salgo del script
$_SESSION['mensaje'] = "Se eliminó el juego con id: $id";
// Redirijo al index 
header('Location: index.php');
exit;
