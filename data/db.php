<?php
$host = 'localhost'; // Cambia si es necesario
$db = 'juego_preguntas_respuestas';
$user = 'root'; // Cambia por tu usuario
$pass = '1234'; // Cambia por tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}
?>
