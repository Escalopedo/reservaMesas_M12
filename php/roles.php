<?php

function obtenerRolUsuario($username, $conn) {
    // Preparar la consulta para obtener el rol del usuario
    $query = "SELECT r.nombre_rol 
              FROM tbl_usuarios u
              INNER JOIN tbl_roles r ON u.id_rol = r.id_rol
              WHERE u.username = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_rol'];
    } else {
        return null;
    }
}
?>
