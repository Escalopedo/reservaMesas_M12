<?php
function redirect_with_errors($url, $errors) {
    if (!empty($errors) && !empty($url)) {
        foreach ($errors as $valor) {
            $erroresPrepared['error'] = $valor;
        }
        $errorParams = http_build_query($erroresPrepared);
        header("Location: {$url}?{$errorParams}");
        exit();
    }
}

// Función que recupera la información del camarero
function get_info_waiter_bbdd($conn, $id_camarero) {
    try {
        // Preparar la consulta con un marcador de posición
        $query = "SELECT * FROM tbl_camarero WHERE id_camarero = :id_camarero";

        // Preparar el statement
        $stmt = $conn->prepare($query);

        // Enlazar los parámetros
        $stmt->bindParam(':id_camarero', $id_camarero, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el camarero
        if ($row) {
            return [
                'username' => $row['username'],
                'name' => $row['nombre_camarero'],
                'surname' => $row['apellidos_camarero']
            ];
        } else {
            return null; // O manejar el caso en que no se encuentre el camarero
        }
    } catch (PDOException $e) {
        // Manejar el error según sea necesario
        throw new Exception("Error al recuperar la información del camarero: " . $e->getMessage());
    }
}
?>
