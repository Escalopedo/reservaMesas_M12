<?php
// Función para redirigir con errores
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

// Función que recupera la información del usuario (camarero)
function get_info_waiter_bbdd($conn, $id_usuario) {
    try {
        // Preparar la consulta con un marcador de posición
        $query = "SELECT * FROM tbl_usuarios WHERE id_usuario = :id_usuario";

        // Preparar el statement
        $stmt = $conn->prepare($query);

        // Enlazar los parámetros
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró el usuario
        if ($row) {
            return [
                'username' => $row['username'],
                'name' => $row['nombre_usuario'],  // Nuevo campo
                'surname' => $row['apellidos_usuario']  // Nuevo campo
            ];
        } else {
            return null; // O manejar el caso en que no se encuentre el usuario
        }
    } catch (PDOException $e) {
        // Manejar el error según sea necesario
        throw new Exception("Error al recuperar la información del usuario: " . $e->getMessage());
    }
}
?>
