<?php

// Inicio la sesión para mostrar mensajes de éxito o error
session_start();

// Importo las variables de clases para categorías y disponibilidad
require __DIR__ . '/../utils/validaciones.php';
require __DIR__ . '/../basedatos/conexion.php';

// Creo un array para almacenar errores de validación
$errores = [];

//  Si el formulario se ha enviado por POST, proceso los datos
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

        // Si no hay errores de validación, inserto el nuevo videojuego en la base de datos
        if (empty($errores)) {
            $stmt = mysqli_prepare($conexion, "SELECT id FROM videojuegos WHERE nombre = ?");
            mysqli_stmt_bind_param($stmt, 's', $nombre);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            // Si ya existe un videojuego con el mismo nombre, agrego un error al array de errores
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errores[] = 'Ya existe un videojuego con ese nombre.';
            } else {
                // Si no existe un videojuego con el mismo nombre, inserto el nuevo videojuego en la base de datos usando una consulta preparada para evitar inyecciones SQL
                $stmt = mysqli_prepare($conexion, "INSERT INTO videojuegos (nombre, descripcion, categoria, disponible, precio) VALUES (?, ?, ?, ?, ?)");
                // Enlazo los parámetros a la consulta preparada y ejecuto la consulta para insertar el nuevo videojuego en la base de datos
                mysqli_stmt_bind_param($stmt, 'ssssd', $nombre, $descripcion, $categoria, $disponible, $precio);
                // Enlazo los parámetros a la consulta preparada y ejecuto la consulta para insertar el nuevo videojuego en la base de datos
                mysqli_stmt_execute($stmt);
                
                // Creo un mensaje de éxito en la sesión indicando que se creó el videojuego con el nombre especificado, cierro la conexión a la base de datos, redirijo al index y salgo del script
                $_SESSION['mensaje'] = "Videojuego \"$nombre\" creado correctamente.";
                mysqli_close($conexion);
                header('Location: index.php');
                exit;
            }
            // Cierro la declaración preparada para la consulta de verificación de nombre
            mysqli_stmt_close($stmt);
        }
    } else {
        // Si faltan campos por enviar, agrego un error al array de errores
        $errores[] = 'Datos incompletos';
    }
}
// Cierro la conexión a la base de datos al finalizar el procesamiento del formulario
mysqli_close($conexion);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Nuevo videojuego</title>
</head>

<body class="bg-purple-100 p-8">
    <div class="max-w-2xl mx-auto bg-white shadow-xl rounded-xl p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-purple-800">Nuevo videojuego</h2>
            <a href="index.php" class="text-purple-600 hover:underline">Volver</a>
        </div>

        <form method="post" class="space-y-4">
            <div>
                <label class="block text-purple-700 font-semibold mb-1">Nombre</label>
                <input type="text" name="nombre" value="<?= limpiarTexto($_POST['nombre'] ?? '') ?>" required
                    class="w-full border border-purple-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Descripción</label>
                <textarea name="descripcion" rows="4" required
                    class="w-full border border-purple-300 rounded px-3 py-2"><?= limpiarTexto($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Categoría</label>
                <select name="categoria" class="w-full border border-purple-300 rounded px-3 py-2">
                    <?php foreach (array_keys($clCategorias) as $cat): ?>
                        <option value="<?= $cat ?>" <?= (($_POST['categoria'] ?? '') === $cat) ? 'selected' : '' ?>>
                            <?= $cat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Disponible</label>
                <select name="disponible" class="w-full border border-purple-300 rounded px-3 py-2">
                    <?php foreach (array_keys($clDisponible) as $op): ?>
                        <option value="<?= $op ?>" <?= (($_POST['disponible'] ?? '') === $op) ? 'selected' : '' ?>>
                            <?= $op ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-purple-700 font-semibold mb-1">Precio (€)</label>
                <input type="text" name="precio" value="<?= limpiarTexto($_POST['precio'] ?? '') ?>" required
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
</body>

</html>
