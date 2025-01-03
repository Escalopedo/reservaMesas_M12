<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener la sala a editar
$id_sala = $_GET['id'];  // Obtener el ID de la sala desde la URL

$query_sala = "SELECT * FROM tbl_sala WHERE id_sala = :id_sala";
$stmt_sala = $conn->prepare($query_sala);
$stmt_sala->bindParam(':id_sala', $id_sala);
$stmt_sala->execute();
$sala = $stmt_sala->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $ubicacion_sala = $_POST['ubicacion_sala'];

    // Verificar si se ha subido una nueva imagen
    if (isset($_FILES['imagen_fondo']) && $_FILES['imagen_fondo']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen_fondo'];
        $nombreArchivo = basename($imagen['name']);
        $rutaDestino = "../../../img/salas/" . $nombreArchivo;
        $rutaRelativa = "img/salas/" . $nombreArchivo; // Ruta relativa para la base de datos

        // Mover la imagen al directorio de destino
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            $query_update = "UPDATE tbl_sala 
                             SET ubicacion_sala = :ubicacion_sala, imagen_fondo = :imagen_fondo 
                             WHERE id_sala = :id_sala";
            $stmt_update = $conn->prepare($query_update);
            $stmt_update->bindParam(':imagen_fondo', $rutaRelativa);
        } else {
            $error = "Error al subir la nueva imagen.";
        }
    } else {
        // Si no hay nueva imagen, conservar la existente
        $query_update = "UPDATE tbl_sala 
                         SET ubicacion_sala = :ubicacion_sala 
                         WHERE id_sala = :id_sala";
        $stmt_update = $conn->prepare($query_update);
    }

    // Actualizar la base de datos
    if (!isset($error)) {
        $stmt_update->bindParam(':ubicacion_sala', $ubicacion_sala);
        $stmt_update->bindParam(':id_sala', $id_sala);

        if ($stmt_update->execute()) {
            header('Location: ../../../view/admin.php'); 
            exit();
        } else {
            $error = "Hubo un error al actualizar la sala.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
    <script src="../../../js/editsala.js" defer></script>
</head>
<body>
    <div class="container">
        <h2>Editar Sala</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="ubicacion_sala" class="form-label">Ubicación de la Sala</label>
                <input type="text" name="ubicacion_sala" id="ubicacion_sala" class="form-control" 
                       value="<?= htmlspecialchars($sala['ubicacion_sala']) ?>">
                <span id="ubicacion_sala_error" class="text-danger"></span>
            </div>
            <div class="mb-3">
                <label for="imagen_fondo" class="form-label">Imagen de Fondo</label>
                <input type="file" name="imagen_fondo" id="imagen_fondo" class="form-control" accept="image/*">
                <span id="imagen_fondo_error" class="text-danger"></span>

                <?php if (!empty($sala['imagen_fondo'])): ?>
                    <p class="mt-2">Imagen actual:</p>
                    <img src="../../../<?= htmlspecialchars($sala['imagen_fondo']) ?>" alt="Imagen de la sala" 
                         style="max-width: 300px; max-height: 200px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Sala</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>