<?php
// Creamos la sesión 
session_start();

// Importamos la conexión a la base de datos y los datos de utilidad
require __DIR__ . '/../basedatos/conexion.php';
require __DIR__ . '/../utils/datos.php';

// Creamos la consulta para obtener todos los videojuegos ordenados por id de forma ascendente
$q = "SELECT id, nombre, descripcion, categoria, disponible, precio FROM videojuegos ORDER BY id ASC";
// Ejecutamos la consulta y almacenamos el resultado en una variable
$videojuegos = mysqli_query($conexion, $q);
// Cerramos la conexión a la base de datos
mysqli_close($conexion);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importar CDN TailwindCSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Importar CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <!-- Importar CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Examen - CRUD - Videojuegos</title>
</head>

<!-- Creamos el cuerpo de la página -->
<body class="bg-purple-100 p-8">
    <!-- Título de la página -->
    <h2 class="text-center text-2xl italic font-semibold mb-6 text-purple-800">Listado de Videojuegos</h2>
    <!-- Creamos el contenedor para el botón de agregar -->
    <div class="flex flex-row-reverse mb-4 gap-2">
        <!-- Agregamos un botón usando la etiqueta de anclaje que redirige a la página nuevo.php  -->
        <a href="nuevo.php"
            class="font-bold inline-flex items-center gap-2 bg-purple-700 text-white px-5 py-2 rounded-full shadow-md hover:bg-purple-800 hover:shadow-lg transition">
            <!-- Añadimos la etiqueta italic para el icono -->
            <i class="fas fa-plus"></i>
            NUEVO
        </a>
    </div>
    <!-- Creamos el contenedor para la tabla -->
    <div class="overflow-x-auto">
        <!-- Usamos la etiqueta table para crear la tabla con los contenidos  -->
        <table class="min-w-full bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- Cabecera de la tabla -->
        <thead class="bg-purple-700 text-white">
            <!-- Creamos la fila de encabezados -->
                <tr>
                    <!-- Añadimos los encabezados de las columnas de la tabla -->
                    <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Categoría</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Disponible</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Precio</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <!-- Añadimos el cuerpo de la tabla con los datos de los videojuegos obtenidos anteriormente y mostrados a traves del bucle foreach -->
            <tbody class="divide-y divide-purple-100">
                <?php foreach ($videojuegos as $item): ?>
                    <!-- Mostramos los datos -->
                    <?php
                    //  Obtenemos las clases CSS para la categoría y disponibilidad usando los arrays de utilidad
                    $catClase  = $clCategorias[$item['categoria']] ?? 'bg-gray-200 text-gray-700';
                    $dispClase = $clDisponible[$item['disponible']] ?? 'bg-gray-400';
                    $precio    = number_format((float)$item['precio'], 2);
                    ?>
                    <!-- Creamos una fila por cada videojuego con sus respectivos datos -->
                    <tr class="hover:bg-purple-50 transition">
                        <!-- Mostramos los datos de cada videojuego en una celda de la tabla -->
                        <td class="py-3 px-4 text-sm"><?= $item['id'] ?></td>
                        <!-- Nombre del videojuego -->
                        <td class="py-3 px-4 text-sm font-semibold text-purple-900"><?= htmlspecialchars($item['nombre']) ?></td>
                        <!-- Descripción del videojuego -->
                        <td class="py-3 px-4 text-sm"><?= htmlspecialchars($item['descripcion']) ?></td>
                        <!-- Categoría del videojuego -->
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $catClase ?>">
                                <!-- Categoría del videojuego -->
                                <?= $item['categoria'] ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white <?= $dispClase ?>">
                                <!-- Disponibilidad del videojuego -->
                                <?= $item['disponible'] ?>
                            </span>
                        </td>
                        <!-- Precio del videojuego -->
                        <td class="py-3 px-4 text-sm font-semibold">€ <?= $precio ?></td>
                        <td class="py-3 px-4 text-center">
                            <!-- Formulario para borrar el videojuego -->
                            <form method="GET" action="borrar.php" class="flex items-center justify-center gap-4">
                                <!-- Campo oculto para el ID del videojuego -->
                                <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                                <!-- Enlaces para editar y borrar -->
                                <a href="update.php?id=<?= $item['id'] ?>" class="text-blue-600 hover:text-blue-800 hover:text-xl">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Botón para borrar el videojuego -->
                                <button class="text-red-600 hover:text-red-800 hover:text-xl" type="submit"
                                    onclick="return confirm('¿Borrar definitivamente el juego?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <!-- Cierre del bucle foreach -->   
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Mostrar mensaje de éxito si existe en la sesión --> -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <!-- Código JavaScript para mostrar el mensaje de éxito -->
        <script>

            Swal.fire({
                icon: "success",
                title: "<?= $_SESSION['mensaje'] ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
        <!-- Fin del mensaje de éxito -->
        <?php unset($_SESSION['mensaje']); ?>
        <!-- Fin del código de mensaje de éxito -->
    <?php endif; ?>
</body>
</html>
