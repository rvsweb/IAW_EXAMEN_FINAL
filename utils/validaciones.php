<?php
// Archivo con funciones de validación y limpieza de datos para toda la aplicación
require __DIR__ . '/datos.php';

/**
 *  Limpia un texto de entrada para evitar problemas de seguridad como XSS.
 *  Elimina espacios en blanco al inicio y al final, y convierte caracteres especiales a entidades
 */
function limpiarTexto(string $texto): string
{
    // Elimina espacios en blanco y convierte caracteres especiales a entidades HTML
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida el nombre, asegurándose de que no esté vacío y tenga entre 3 y 100 caracteres.
 */
function validarNombre(string $nombre): array
{
    // Creo un array para almacenar errores de validación
    $errores = [];
    // Limpia el nombre de entrada
    $nombre = trim($nombre);
    // Si el nombre está vacío, agrega un error al array
    if ($nombre === '') {
        // Si el nombre está vacío, agrega un error al array
        $errores[] = 'El nombre es obligatorio.';
        // Si el nombre no tiene entre 3 y 100 caracteres, agrega un error al array
    } elseif (strlen($nombre) < 3 || strlen($nombre) > 100) {
        // Si el nombre no tiene entre 3 y 100 caracteres, agrega un error al array
        $errores[] = 'El nombre debe tener entre 3 y 100 caracteres.';
    }
    // Devuelve el array de errores (vacío si no hay errores)
    return $errores;
}

/**
 *  Valida la descripción, asegurándose de que no esté vacía y tenga entre 5 y 255 caracteres.
 */
function validarDescripcion(string $descripcion): array
{
    // Creo un array para almacenar errores de validación
    $errores = [];
    //  Limpia la descripción de entrada
    $descripcion = trim($descripcion);
    // Si la descripción está vacía, agrega un error al array 
    if ($descripcion === '') {
        // Si la descripción está vacía, agrega un error al array 
        $errores[] = 'La descripción es obligatoria.';
        //  Si la descripción no tiene entre 5 y 255 caracteres, agrega un error al array
    } elseif (strlen($descripcion) < 5 || strlen($descripcion) > 255) {
        // Si la descripción no tiene entre 5 y 255 caracteres, agrega un error al array
        $errores[] = 'La descripción debe tener entre 5 y 255 caracteres.';
    }
    //  Devuelve el array de errores (vacío si no hay errores) 
    return $errores;
}

/**
 * Valida la categoría, asegurándose de que sea una de las categorías válidas definidas en $clCategorias.
 */
function validarCategoria(string $categoria, array $clCategorias): array
{
    //  Creo un array para almacenar errores de validación 
    $errores = [];
    if (!isset($clCategorias[$categoria])) {
        $errores[] = 'Categoría no válida.';
    }
    return $errores;
}

/**
 * Valida el campo de disponibilidad, asegurándose de que solo acepte "SI" o "NO".
 */
function validarDisponible(string $disponible): array
{
    // Creo un array para almacenar errores de validación
    $errores = [];
    // Si el valor de disponibilidad no es "SI" ni "NO", agrega un error al array
    if ($disponible !== 'SI' && $disponible !== 'NO') {
        // Si el valor de disponibilidad no es "SI" ni "NO", agrega un error al array
        $errores[] = 'Indica si está disponible (SI o NO).';
    }
    return $errores;
}

/**
 * Valida el precio, asegurándose de que es un número válido mayor que cero.
 * Permite tanto puntos como comas como separadores decimales.
 */
function validarPrecio(string $precio): array
{
    //   Creo un array para almacenar errores de validación
    $errores = [];
    //  Reemplaza comas por puntos y limpia el precio de entrada
    $precio = str_replace(',', '.', trim($precio));
    // Si el precio está vacío o no es un número válido mayor que cero, agrega un error al array
    if ($precio === '' || $precio <= 0) {
        // Si el precio está vacío o no es un número válido mayor que cero, agrega un error al array 
        $errores[] = 'Introduce un precio válido mayor que cero.';
    }
    return $errores;
}
