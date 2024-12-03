<?php
require '../php/conexion.php';

try {
    // Preparar la consulta
    $sqlRecuperarEstados = "SELECT id_mesa, estado_ocupacion FROM tbl_ocupacion WHERE estado_ocupacion NOT LIKE 'Registrada';";
    $stmt = $conn->prepare($sqlRecuperarEstados);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resultado)) {
        echo "No se ha encontrado ninguna mesa.";
    } else {
        $ARRAYocupaciones = array();
        foreach ($resultado as $fila) {
            $ARRAYocupaciones[$fila['id_mesa']] = $fila['estado_ocupacion'];
        }
        // Guardar el resultado en la sesión
        $_SESSION['ARRAYocupaciones'] = $ARRAYocupaciones;
    }
} catch (Exception $e) {
    echo "Error al encontrar mesas: " . $e->getMessage();
}
?>
