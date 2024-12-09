<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener las mesas
$id_mesa = $_GET['id'];  // Obtener el ID de la mesa desde la URL

$query_mesa = "SELECT * FROM tbl_mesa WHERE id_mesa = :id_mesa";
$stmt_mesa = $conn->prepare($query_mesa);
$stmt_mesa->bindParam(':id_mesa', $id_mesa);
$stmt_mesa->execute();
$mesa = $stmt_mesa->fetch(PDO::FETCH_ASSOC);

// Obtener todas las salas
$query_salas = "SELECT * FROM tbl_sala";
$stmt_salas = $conn->prepare($query_salas);
$stmt_salas->execute();
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $numero_sillas = $_POST['numero_sillas'];
    $id_sala = $_POST['id_sala'];

    // Actualizar los datos de la mesa en la base de datos
    $query_update = "UPDATE tbl_mesa SET id_sala = :id_sala, numero_sillas_mesa = :numero_sillas WHERE id_mesa = :id_mesa";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bindParam(':id_sala', $id_sala);
    $stmt_update->bindParam(':numero_sillas', $numero_sillas);
    $stmt_update->bindParam(':id_mesa', $id_mesa);

    if ($stmt_update->execute()) {
        header('Location: ../../../view/admin.php'); 
        exit();
    } else {
        $error = "Hubo un error al editar la mesa.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
</head>
<body>
    <div class="container">
        <h2>Editar Mesa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="numero_sillas" class="form-label">Número de Sillas</label>
                <input type="number" name="numero_sillas" id="numero_sillas" class="form-control" value="<?= $mesa['numero_sillas_mesa'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala</label>
                <select name="id_sala" id="id_sala" class="form-control" required>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>" <?= ($mesa['id_sala'] == $sala['id_sala']) ? 'selected' : '' ?>>
                            <?= $sala['ubicacion_sala'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>
