<?php
require_once 'classes/Biblioteca.php';
require_once 'classes/Database.php';


// TODO: Instanciar la clase Biblioteca
$biblioteca = new Biblioteca();

// Verificamos conexión con la Base de Datos
$database = new Database();
$conn = $database->getConnection();

// TODO: Manejar lógica de enrutamiento o acciones (GET/POST)

// Obtener sección actual
$action = $_GET['action'] ?? 'libros';

// ========================================
// PROCESAR ACCIONES POST
// ========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Agregar libro
if (isset($_POST['accion']) && $_POST['accion'] === 'agregar_libro') {

$libro = new Libro(
$_POST['titulo'],
$_POST['autor'],
$_POST['isbn'],
$_POST['cantidad']
);

$biblioteca->agregarLibro($libro);

header("Location: index.php");
exit;
}

// Agregar usuario
if (isset($_POST['accion']) && $_POST['accion'] === 'agregar_usuario') {

$usuario = new Usuario(
$_POST['nombre'],
$_POST['email'],
$_POST['telefono']
);

$biblioteca->agregarUsuario($usuario);

header("Location: index.php?action=usuarios");
exit;
}

// Prestar libro
if (isset($_POST['accion']) && $_POST['accion'] === 'prestar_libro') {

$biblioteca->prestarLibro(
$_POST['libro_id'],
$_POST['usuario_id']
);

header("Location: index.php?action=prestamos");
exit;
}
}

// ========================================
// PROCESAR ACCIONES GET
// ========================================

// Eliminar libro
if (isset($_GET['eliminar_libro'])) {

$biblioteca->eliminarLibro($_GET['eliminar_libro']);

header("Location: index.php");
exit;
}

// Eliminar usuario
if (isset($_GET['eliminar_usuario'])) {

$biblioteca->eliminarUsuario($_GET['eliminar_usuario']);

header("Location: index.php?action=usuarios");
exit;
}

// Devolver libro
if (isset($_GET['devolver'])) {

$biblioteca->devolverLibro($_GET['devolver']);

header("Location: index.php?action=prestamos");
exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Biblioteca</title>
    <style>
        /* TODO: Agregar estilos CSS */
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; background: #eee; padding: 10px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        .container { max-width: 800px; margin: 0 background: white; padding: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            }

            td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            }

            th {
            background-color: #eee;
            }

            form {
            margin-bottom: 30px;
            }

            input,
            select {
            padding: 8px;
            margin: 5px;
            }

            button {
            padding: 8px 15px;
            cursor: pointer;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Biblioteca Mini-App - Marlon Castillo </h1>
        
        <nav>
            <a href="index.php">Inicio / Libros</a>
            <a href="index.php?action=usuarios">Usuarios</a>
            <a href="index.php?action=prestamos">Préstamos</a>
        </nav>

        <div id="content">
            
            <!-- TODO: Mostrar contenido dinámico aquí dependiendo de la sección -->
            

<!–– 
// ========================================
// SECCIÓN LIBROS
// ========================================
––>

<?php if ($action === 'libros'): ?>

<?php $libros = $biblioteca->obtenerLibros(); ?>

<h2>Gestión de Libros</h2>

<h3>Agregar Libro</h3>

<form method="POST">

<input type="hidden"
name="accion"
value="agregar_libro">

<input type="text"
name="titulo"
placeholder="Título"
required>

<input type="text"
name="autor"
placeholder="Autor"
required>

<input type="text"
name="isbn"
placeholder="ISBN"
required>

<input type="number"
name="cantidad"
placeholder="Cantidad"
min="1"
value="1"
required>

<button type="submit">
Agregar Libro
</button>

</form>


<h3>Libros disponibles</h3>

<table>

<thead>

<tr>

<th>ID</th>
<th>Título</th>
<th>Autor</th>
<th>ISBN</th>
<th>Cantidad</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach ($libros as $libro): ?>

<tr>

<td>
<?= htmlspecialchars($libro['id']) ?>
</td>

<td>
<?= htmlspecialchars($libro['titulo']) ?>
</td>

<td>
<?= htmlspecialchars($libro['autor']) ?>
</td>

<td>
<?= htmlspecialchars($libro['isbn']) ?>
</td>

<td>
<?= htmlspecialchars($libro['cantidad']) ?>
</td>

<td>

<a href="index.php?eliminar_libro=<?= $libro['id'] ?>"
onclick="return confirm('¿Eliminar este libro?')">

Eliminar

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<!––
// ========================================
// SECCIÓN USUARIOS
// ========================================
––>

<?php elseif ($action === 'usuarios'): ?>

<?php $usuarios = $biblioteca->obtenerUsuarios(); ?>

<h2>Gestión de Usuarios</h2>

<h3>Agregar Usuario</h3>

<form method="POST">

<input type="hidden"
name="accion"
value="agregar_usuario">

<input type="text"
name="nombre"
placeholder="Nombre"
required>

<input type="email"
name="email"
placeholder="Correo electrónico"
required>

<input type="text"
name="telefono"
placeholder="Teléfono">

<button type="submit">
Agregar Usuario
</button>

</form>


<h3>Usuarios registrados</h3>

<table>

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Email</th>
<th>Teléfono</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach ($usuarios as $usuario): ?>

<tr>

<td>
<?= htmlspecialchars($usuario['id']) ?>
</td>

<td>
<?= htmlspecialchars($usuario['nombre']) ?>
</td>

<td>
<?= htmlspecialchars($usuario['email']) ?>
</td>

<td>
<?= htmlspecialchars($usuario['telefono']) ?>
</td>

<td>

<a href="index.php?action=usuarios&eliminar_usuario=<?= $usuario['id'] ?>"
onclick="return confirm('¿Eliminar este usuario?')">

Eliminar

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<!--
// ========================================
// SECCIÓN PRÉSTAMOS
// ========================================
––>

<?php elseif ($action === 'prestamos'): ?>

<?php $prestamos = $biblioteca->obtenerPrestamosActivos(); 

$libros = $biblioteca->obtenerLibros();

$usuarios = $biblioteca->obtenerUsuarios();

?>

<h2>Gestión de Préstamos</h2>

<h3>Nuevo Préstamo</h3>

<form method="POST">

<input type="hidden"
name="accion"
value="prestar_libro">


<label>Libro:</label>

<select name="libro_id" required>

<option value="">
Seleccionar libro
</option>

<?php foreach ($libros as $libro): ?>

<option value="<?= $libro['id'] ?>">

<?= htmlspecialchars($libro['titulo']) ?>

</option>

<?php endforeach; ?>

</select>


<label>Usuario:</label>

<select name="usuario_id" required>

<option value="">
Seleccionar usuario
</option>

<?php foreach ($usuarios as $usuario): ?>

<option value="<?= $usuario['id'] ?>">

<?= htmlspecialchars($usuario['nombre']) ?>

</option>

<?php endforeach; ?>

</select>


<button type="submit">
Prestar Libro
</button>

</form>


<h3>Préstamos Activos</h3>

<table>

<thead>

<tr>

<th>ID</th>
<th>Libro</th>
<th>Usuario</th>
<th>Fecha Préstamo</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach ($prestamos as $prestamo): ?>

<tr>

<td>
<?= htmlspecialchars($prestamo['id']) ?>
</td>

<td>
<?= htmlspecialchars($prestamo['titulo']) ?>
</td>

<td>
<?= htmlspecialchars($prestamo['usuario']) ?>
</td>

<td>
<?= htmlspecialchars($prestamo['fecha_prestamo']) ?>
</td>

<td>
<?= htmlspecialchars($prestamo['estado']) ?>
</td>

<td>

<a href="index.php?action=prestamos&devolver=<?= $prestamo['id'] ?>"
onclick="return confirm('¿Registrar devolución?')">

Devolver

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

        </div>
    </div>
</body>
</html>
