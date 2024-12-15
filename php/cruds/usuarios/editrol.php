<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");  // Redirigir si no es Administrador
    exit();
}

require_once '../../conexion.php';

// Verificar si se pasó el ID del rol
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../view/admin.php");  // Redirigir si no se pasa un ID válido
    exit();
}

$id_rol = $_GET['id'];

// Obtener los datos del rol desde la base de datos
$query = "SELECT * FROM tbl_roles WHERE id_rol = :id_rol";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_rol', $id_rol);
$stmt->execute();
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    header("Location: ../../view/admin.php");  // Redirigir si no se encuentra el rol
    exit();
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_rol = htmlspecialchars($_POST['nombre_rol']);

    try {
        // Actualizar el rol en la base de datos
        $query = "UPDATE tbl_roles SET nombre_rol = :nombre_rol WHERE id_rol = :id_rol";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre_rol', $nombre_rol);
        $stmt->bindParam(':id_rol', $id_rol);
        $stmt->execute();

        // Redirigir a la página de administración
        header('Location: ../../../view/admin.php'); 
        exit();
    } catch (PDOException $e) {
        echo "Error al editar rol: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
    <script src="../../../js/rol.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Editar Rol</h2>
        <form action="editrol.php?id=<?php echo $role['id_rol']; ?>" method="POST" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="nombre_rol" class="form-label">Nombre del Rol:</label>
                <input type="text" name="nombre_rol" id="nombre_rol" class="form-control" value="<?php echo $role['nombre_rol']; ?>">
                <span id="error_nombre_rol" class="text-danger"></span>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Rol</button>
        </form>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>
