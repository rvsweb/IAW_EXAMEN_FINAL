<?php

session_start();

require __DIR__ . '/../utils/validaciones.php';
require __DIR__ . '/../basedatos/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    mysqli_close($conexion);
    header('Location: index.php');
    exit;
}

// Procesar el formulario POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se han enviado todos los campos necesarios, los limpio y valido
    if (isset($_POST['nombre'], $_POST['descripcion'], $_POST['categoria'], $_POST['disponible'], $_POST['precio'])) {
        $nombre      = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $categoria   = trim($_POST['categoria']);
        $disponible  = trim($_POST['disponible']);
        $precio      = str_replace(',', '.', trim($_POST['precio']));
// Valido cada campo usando las funciones de validación y almaceno los errores en el array $errores
        $errores = array_merge(
            validarNombre($nombre),
            validarDescripcion($descripcion),
            validarCategoria($categoria, $clCategorias),
            validarDisponible($disponible),
            validarPrecio($precio)
        );

        //  Si no hay errores de validación, actualizo el videojuego en la base de datos
    if (empty($errores)) {
        // Comprobar duplicado de nombre excluyendo el registro actual
        $qCheck = "SELECT id FROM videojuegos WHERE nombre = ? AND id != ?";
        // Preparo y ejecuto la consulta SQL para verificar si existe otro videojuego con el mismo nombre, excluyendo el registro actual
        $stmt = mysqli_stmt_init($conexion);
        mysqli_stmt_prepare($stmt, $qCheck);
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        // si ya existe otro videojuego con el mismo nombre, agrego un error al array de errores, si no existe otro videojuego con el mismo nombre, actualizo el videojuego en la base de datos usando una consulta preparada para evitar inyecciones SQL
        $existe = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);

        // Si ya existe otro videojuego con el mismo nombre, agrego un error al array de errores, si no existe otro videojuego con el mismo nombre, actualizo el videojuego en la base de datos usando una consulta preparada para evitar inyecciones SQL
        if ($existe) {
            // Si ya existe otro videojuego con el mismo nombre, agrego un error al array de errores
            $errores[] = 'Ya existe otro videojuego con ese nombre.';
        } else {
            // Si no existe otro videojuego con el mismo nombre, actualizo el videojuego en la base de datos usando una consulta preparada para evitar inyecciones SQL 
            $qUpdate = "UPDATE videojuegos SET nombre=?, descripcion=?, categoria=?, disponible=?, precio=? WHERE id=?";
            //  Preparo y ejecuto la consulta SQL para actualizar el videojuego en la base de datos usando una consulta preparada para evitar inyecciones SQL
            $stmt = mysqli_stmt_init($conexion);
            mysqli_stmt_prepare($stmt, $qUpdate);
            mysqli_stmt_bind_param($stmt, 'ssssdi', $nombre, $descripcion, $categoria, $disponible, $precio, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conexion);
            // Creo un mensaje de éxito en la sesión indicando que se actualizó el videojuego con el nombre especificado, redirijo al index y salgo del script
            $_SESSION['mensaje'] = "Videojuego \"$nombre\" actualizado correctamente.";
            // Redirijo al index y salgo del script
            header('Location: index.php');
            exit;
        }
    }

    // Si hay errores mantenemos los valores del POST para repintar el formulario
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria   = trim($_POST['categoria'] ?? '');
    $disponible  = trim($_POST['disponible'] ?? '');
    $precio      = trim($_POST['precio'] ?? '');

} else {
    // Carga inicial: obtener datos actuales del registro
    $q = "SELECT nombre, descripcion, categoria, disponible, precio FROM videojuegos WHERE id = ?";
     // Preparo y ejecuto la consulta SQL para obtener los datos actuales del videojuego con el id especificado usando una consulta preparada para evitar inyecciones SQL
    $stmt = mysqli_stmt_init($conexion);
    // Preparo la consulta SQL con el id como parámetro
    mysqli_stmt_prepare($stmt, $q);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre, $descripcion, $categoria, $disponible, $precio);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    // Cierro la conexión a la base de datos después de obtener los datos actuales del videojuego
    $errores = [];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Editar videojuego</title>
</head>

<body class="bg-purple-100 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow-xl rounded-xl p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-purple-800">Editar videojuego</h2>
            <a href="index.php" class="text-purple-600 hover:underline">Volver</a>
        </div>

        <form method="post" class="space-y-4">
            <div>
                <label class="block text-purple-700 font-semibold mb-1">Nombre</label>
                <input type="text" name="nombre" value="<?= limpiarTexto($nombre) ?>" required
                    class="w-full border border-purple-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Descripción</label>
                <textarea name="descripcion" rows="4" required
                    class="w-full border border-purple-300 rounded px-3 py-2"><?= limpiarTexto($descripcion) ?></textarea>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Categoría</label>
                <select name="categoria" class="w-full border border-purple-300 rounded px-3 py-2">
                    <?php foreach (array_keys($clCategorias) as $cat): ?>
                        <option value="<?= $cat ?>" <?= $categoria === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Disponible</label>
                <select name="disponible" class="w-full border border-purple-300 rounded px-3 py-2">
                    <?php foreach (array_keys($clDisponible) as $op): ?>
                        <option value="<?= $op ?>" <?= $disponible === $op ? 'selected' : '' ?>><?= $op ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Precio (€)</label>
                <input type="text" name="precio" value="<?= limpiarTexto($precio) ?>" required
                    class="w-full border border-purple-300 rounded px-3 py-2">
            </div>

            <div class="flex gap-4 pt-4">
                <a href="index.php" class="bg-gray-200 px-4 py-2 rounded">Cancelar</a>
                <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>

    <?php if (!empty($errores)): ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Errores en el formulario",
                html: "<?= implode('<br>', $errores) ?>",
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "<?= $_SESSION['mensaje'] ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
</body>

</html>
