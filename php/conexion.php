<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurante_bbdd";

try {
    // Crear la conexión usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
    
    // Configurar el modo de errores de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mensaje opcional si la conexión es exitosa
    // echo "Conexión exitosa";

} catch (PDOException $e) {
    // Muestra un mensaje en caso de error
    die("Error: " . $e->getMessage());
}
?>
