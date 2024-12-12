<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: ../php/cerrarSession.php");
    exit();
}

require '../php/conexion.php'; // Asumimos que esta variable $conn ya contiene una conexión PDO
require_once '../php/functions.php';

$id_camarero = $_SESSION['user_id'];
$info_waiter = get_info_waiter_bbdd($conn, $id_camarero);

$conditions = "WHERE o.estado_ocupacion = 'Registrada'";
$order_by = "";
$params = [];
$param_types = "";

// Verificamos que use método POST y use el botón del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_has_var(INPUT_POST, 'filtrosBuscando')) {
    $filters = [];
    
    $fields = [
        'id_reserva' => ['field' => 'o.id_ocupacion', 'type' => 'i'],
        'nombre_camarero' => ['field' => 'c.nombre_usuario', 'type' => 's', 'like' => true],
        'apellido_camarero' => ['field' => 'c.apellidos_usuario', 'type' => 's', 'like' => true],
        'id_mesa' => ['field' => 'm.id_mesa', 'type' => 'i'],
        'ubicacion_sala' => ['field' => 's.ubicacion_sala', 'type' => 's', 'like' => true],
    ];
    
    foreach ($fields as $post_key => $db_field) {
        $input_value = htmlspecialchars($_POST[$post_key]) ?? '';
        if (!empty($input_value)) {
            $operator = $db_field['operator'] ?? '=';
            $like = $db_field['like'] ?? false;
            $filters[] = $db_field['field'] . " " . ($like ? "LIKE" : $operator) . " ?";
            $param_types .= $db_field['type'];
            $params[] = $like ? "%{$input_value}%" : $input_value;
        }
    }

    if (!empty($filters)) {
        $conditions .= " AND " . implode(' AND ', $filters);
    }

    $allowed_columns = ['id_ocupacion', 'nombre_usuario', 'id_mesa', 'ubicacion_sala'];
    $ordenar_nombre_columna = $_POST['column_name'] ?? '';
    $ordenar_por = $_POST['ordenar_registro'] ?? '';

    if (in_array($ordenar_nombre_columna, $allowed_columns)) {
        $order_direction = ($ordenar_por === 'Ascendente') ? 'ASC' : 'DESC';
        $order_by = "ORDER BY {$ordenar_nombre_columna} {$order_direction}";
    }
}

$query = "
    SELECT 
        o.id_ocupacion,
        c.nombre_usuario,
        c.apellidos_usuario,
        m.id_mesa,
        s.ubicacion_sala,
        o.estado_ocupacion
    FROM 
        tbl_ocupacion o
    INNER JOIN 
        tbl_usuarios c ON o.id_usuario = c.id_usuario
    INNER JOIN 
        tbl_mesa m ON o.id_mesa = m.id_mesa
    INNER JOIN 
        tbl_sala s ON m.id_sala = s.id_sala
    $conditions $order_by";

try {
    $stmt_register = $conn->prepare($query);

    if (!empty($params)) {
        $stmt_register->execute($params);
    } else {
        $stmt_register->execute();
    }

    $result = $stmt_register->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error ejecutando la consulta: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Reservas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/mesas.css">
    <link rel="stylesheet" href="../css/historicoResponsive.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Cabecera -->
    <header id="container_header" class="d-flex">
        <div id="container-username" class="d-flex align-items-center">
            <div id="icon_profile_header">
                <img src="../img/logoSinFondo.png" alt="Logo" id="icon_profile" class="img-fluid">
            </div>
            <div id="username_profile_header" class="ms-3">
                <p id="p_username_profile"><?php echo htmlspecialchars($info_waiter['username']); ?></p>
                <span class="span_subtitle"><?php echo htmlspecialchars($info_waiter['name']) . " " . htmlspecialchars($info_waiter['surname']); ?></span>
            </div>
        </div>

        <div id="container_title_header" class="text-center">
            <h1 id="title_header" class="m-0"><strong>Dinner At Westfield</strong></h1>
            <span class="span_subtitle">Gestión de mesas</span>
        </div>

        <nav id="nav_header">
            <a href="./mesas.php" class="btn btn-danger me-2 btn_custom_logOut">Mesas</a>
            <a href="./reservas.php" class="btn btn-danger me-2 btn_custom_logOut">Reservar</a>
            <a href="../php/cerrarSesion.php" class="btn btn-danger btn_custom_logOut m-1">Cerrar sesión</a>
        </nav>
    </header> 

    <!-- Contenido principal -->
    <main class="container mt-5">
        <?php if (empty($result) && !filter_has_var(INPUT_POST, 'filtrosBuscando')): ?>
            <div id="bind_result" class="text-center">
                <h3>Ooops...</h3>
                <h4>Parece que no hay reservas registradas...</h4>
                <div id="bind_img_result">
                    <img src="../img/not_stonks.jpg" alt="Sin resultados" id="img_dinero_perdido" class="img-fluid">
                </div>
            </div>
        <?php else: ?>
            <div id="headerTituloFiltros">
                <h2>Histórico de Reservas Registradas</h2>
                <button class="btn btn-info btn_custom_filter" id="filter_button">Filtros</button>
            </div>
            <table class="table table-striped table-bordered mt-4" id="reservas_table">
                <thead class="table-active">
                    <tr>
                        <th scope="col">ID Reserva</th>
                        <th scope="col">Camarero</th>
                        <th scope="col">Mesa</th>
                        <th scope="col">Ubicación</th>                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <th scope="row"><?php echo $row['id_ocupacion']; ?></th>
                            <td><?php echo htmlspecialchars($row['nombre_usuario'] . ' ' . $row['apellidos_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['id_mesa']); ?></td>
                            <td><?php echo htmlspecialchars($row['ubicacion_sala']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <!-- Script para Filtrar -->
    <script src="../js/filtradoHistorico.js"></script>

    <!-- Formulario para filtrar -->
    <div id="contenedorFiltros" class="container mt-4">
        <form class="form-horizontal" id="formFiltros" action="" method="POST">
            <div id="tituloFiltros">
                <h3>Filtros</h3>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-2" for="id_reserva">Id reserva:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_reserva" name="id_reserva">
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-2" for="nombre_camarero">Nombre Camarero:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre_camarero" name="nombre_camarero">
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-2" for="apellido_camarero">Apellido Camarero:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="apellido_camarero" name="apellido_camarero">
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-2" for="id_mesa">Mesa:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="id_mesa" name="id_mesa">
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-sm-2" for="ubicacion_sala">Ubicación Sala:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ubicacion_sala" name="ubicacion_sala">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="filtrosBuscando">Filtrar</button>
        </form>
    </div>

</body>
</html>
