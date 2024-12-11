<?php 
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el nombre de la sala desde el formulario
    $ubicacion_sala = $_POST['ubicacion_sala'];

    // Manejo de la imagen
    if (isset($_FILES['imagen_fondo']) && $_FILES['imagen_fondo']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen_fondo'];
        $nombreArchivo = basename($imagen['name']);
        $rutaDestino = "../../../img/salas/" . $nombreArchivo;

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
            $rutaRelativa = "img/salas/" . $nombreArchivo; // Ruta relativa para almacenar en la base de datos

            // Insertar la sala con la ruta de la imagen en la base de datos
            $query_insert = "INSERT INTO tbl_sala (ubicacion_sala, imagen_fondo) VALUES (:ubicacion_sala, :imagen_fondo)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bindParam(':ubicacion_sala', $ubicacion_sala);
            $stmt_insert->bindParam(':imagen_fondo', $rutaRelativa);

            if ($stmt_insert->execute()) {
                header('Location: ../../../view/admin.php'); 
                exit();
            } else {
                $error = "Hubo un error al añadir la sala.";
            }
        } else {
            $error = "Error al subir la imagen.";
        }
    } else {
        $error = "Debe seleccionar una imagen válida.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
</head>
<body>
    <div class="container">
        <h2>Añadir Sala</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="ubicacion_sala" class="form-label">Ubicación de la Sala</label>
                <input type="text" name="ubicacion_sala" id="ubicacion_sala" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="imagen_fondo" class="form-label">Imagen de Fondo</label>
                <input type="file" name="imagen_fondo" id="imagen_fondo" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Sala</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>
