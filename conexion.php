<?php
$database = "parqueadero"; // Nombre de la base de datos
$user = 'root'; // Nombre de usuario
$password = ''; // Contraseña

try {
    // Conexión a la base de datos con PDO
    $conection = new PDO('mysql:host=localhost;dbname=' . $database . ';charset=utf8', $user, $password);
    // Establecer el modo de error de PDO a excepción
    $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si hay algún error en la conexión se muestra un mensaje con el error
    echo "¡Error!: " . $e->getMessage() . "<br/>";
}
?>
