<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener todas las salas disponibles para elegir en el formulario
$query_salas = "SELECT * FROM tbl_sala";
$stmt_salas = $conn->prepare($query_salas);
$stmt_salas->execute();
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $numero_sillas = $_POST['numero_sillas'];
    $id_sala = $_POST['id_sala'];

    // Validar el número de sillas
    $valid_sillas = [2, 4, 6, 8];
    if (!in_array($numero_sillas, $valid_sillas)) {
        $error = "No intentes petar el código.";
    } 
    // Validar que el id_sala esté entre las opciones válidas obtenidas de la base de datos
    elseif (!in_array($id_sala, array_column($salas, 'id_sala'))) {
        $error = "Sala no válida. Selecciona una sala existente.";
    } else {
        try {
            $conn->beginTransaction();

            // Insertar la nueva mesa en la base de datos
            $query_insert_mesa = "INSERT INTO tbl_mesa (id_sala, numero_sillas_mesa) VALUES (:id_sala, :numero_sillas)";
            $stmt_insert_mesa = $conn->prepare($query_insert_mesa);
            $stmt_insert_mesa->bindParam(':id_sala', $id_sala);
            $stmt_insert_mesa->bindParam(':numero_sillas', $numero_sillas);
            $stmt_insert_mesa->execute();

            // Obtener el último id_mesa insertado
            $id_mesa = $conn->lastInsertId();

            // Insertar estado inicial en tbl_ocupacion para la nueva mesa
            $query_insert_ocupacion = "INSERT INTO tbl_ocupacion (id_mesa, estado_ocupacion) VALUES (:id_mesa, 'Disponible')";
            $stmt_insert_ocupacion = $conn->prepare($query_insert_ocupacion);
            $stmt_insert_ocupacion->bindParam(':id_mesa', $id_mesa);
            $stmt_insert_ocupacion->execute();

            $conn->commit();

            header('Location: ../../../view/admin.php');
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Hubo un error al añadir la mesa: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Mesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/cuestionarios.css">
</head>
<body>
    <div class="container">
        <h2>Añadir Mesa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="numero_sillas" class="form-label">Número de Sillas</label>
                <select name="numero_sillas" id="numero_sillas" class="form-control" required>
                    <option value="2">2</option>
                    <option value="4">4</option>
                    <option value="6">6</option>
                    <option value="8">8</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_sala" class="form-label">Sala</label>
                <select name="id_sala" id="id_sala" class="form-control">
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>"><?= $sala['ubicacion_sala'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Añadir Mesa</button>
        </form>

        <a href="../../../view/admin.php" class="btn btn-secondary mt-3">Volver a Administración</a>
    </div>
</body>
</html>
