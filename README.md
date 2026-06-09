# Proyecto EXAMEN FINAL — Desarrollo de Aplicaciones Web

Implementación de un CRUD en PHP utilizando mysqli, TailwindCSS y SweetAlert2

## 1. Descripción general

El objetivo de este examen es desarrollar una aplicación web completa en PHP que permita gestionar la información de la tabla videojuegos mediante un sistema CRUD (Create, Read, Update, Delete). Para ello deberá emplearse:

- PHP y la extensión mysqli
- Consultas preparadas cuando se estime oportuno
- Filter_input para el tratamiento de datos en operaciones sensibles (update y delete)
- El framework de estilos TailwindCSS
- Iconos de Font Awesome
- Mensajes emergentes de SweetAlert2
- Validaciones propias implementadas por el alumno

La aplicación deberá presentar una organización modular, haciendo uso de ficheros auxiliares para la conexión, las utilidades y la plantilla base. La interfaz deberá ser coherente, legible y apoyarse en los estilos de TailwindCSS.

Durante la prueba, el profesor proporcionará:

- Acceso a la base de datos y a la tabla previamente creada
- Algunos registros de ejemplo
- Una URL con un ejemplo resuelto cuyo diseño y estilos podrán consultarse libremente (no se permite copiar código PHP ni lógica de validación)

## 2. Estructura obligatoria del proyecto

El alumno deberá organizar su proyecto siguiendo la estructura exacta indicada a continuación:

```text
├── basedatos
│ └── conexion.php
├── plantilla
│ └── base.html
├── public
│ ├── borrar.php
│ ├── index.php
│ ├── nuevo.php
│ └── update.php
├── sql
│ └── tablas.sql
└── utils
├── datos.php
└── validaciones.php
```

El incumplimiento de esta estructura podrá suponer pérdida de puntuación según la rúbrica.

## 3. Especificación de la base de datos

Se trabajará sobre la siguiente tabla, que estará creada y poblada al inicio del examen:

```sql
CREATE TABLE videojuegos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    categoria   ENUM('Accion', 'Aventura', 'RPG', 'Deportes') NOT NULL,
    disponible  ENUM('SI', 'NO') NOT NULL DEFAULT 'SI',
    precio      DECIMAL(5,2) NOT NULL
);
```

## 4. Ficheros utilitarios obligatorios

En utils/datos.php deberán incluirse los arrays:

```php
$clCategorias = [
    'Accion'   => 'bg-red-200 text-red-700',
    'Aventura' => 'bg-yellow-200 text-yellow-700',
    'RPG'      => 'bg-blue-200 text-blue-700',
    'Deportes' => 'bg-green-200 text-green-700',
];

$clDisponible = [
    'SI' => 'bg-green-500',
    'NO' => 'bg-red-500'
];
```

Estos valores se emplearán para: validación de categorías y disponibilidad aplicación de estilos condicionales en el listado de videojuegos

En utils/validaciones.php deberán implementarse funciones de validación coherentes con el esquema de la tabla (cadenas, números, enumerados…).

5. Funcionalidades obligatorias

5.1. Listado (index.php)

La página principal deberá mostrar un listado completo de videojuegos con todos sus campos. El diseño deberá cumplir:

Estilos condicionales en categoría y disponible aplicando los arrays de utilidades uso de tablas y clases TailwindCSS inclusión de botones de editar y borrar, con iconos adecuados

5.2. Creación de videojuegos (nuevo.php)

Debe implementarse:

Formulario funcional con validación comprobación de duplicados (campo nombre) inserción en la base de datos mensajes de error y éxito mediante SweetAlert2

5.3. Eliminación de videojuegos (borrar.php)

Debe incorporarse:

Recepción del identificador mediante filter_input(INPUT_GET) validación estricta del id borrado de la entrada correspondiente notificación del resultado mediante SweetAlert2

5.4. Edición de videojuegos (update.php)

Debe contemplar:

Recuperación del id mediante filter_input(INPUT_GET) carga de datos para precargar el formulario validaciones completas control de duplicados en nombre actualización del registro mensajes de éxito/error con SweetAlert2

6. Requisitos mínimos de superación

Para obtener una calificación igual o superior a 5, deberán funcionar correctamente las siguientes partes:listado de videojuegos creación de videojuegos eliminación de videojuegos

Si cualquiera de estos elementos no funciona, la nota máxima será 4,9 independientemente del resto de la implementación.
