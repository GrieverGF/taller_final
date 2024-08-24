<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'db';  
$dbname = 'lavanderia_flamingo';
$user = 'root';
$password = 'example_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Error en la conexión a la base de datos: " . $e->getMessage());
    die("Error en la conexión a la base de datos.");
}
?>
