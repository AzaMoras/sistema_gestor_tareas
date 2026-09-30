<?php
session_start();
require_once '../conections/conection.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $nombre = $_POST['usuario'] ?? '';
    $pass = $_POST['contrasena'] ?? '';
    
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT id, Contrasena FROM Usuarios WHERE Nombre = ?");
    $stmt->execute([$nombre]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $pass === $user['Contrasena']) {
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Datos incorrectos o usuario no existe."]);
    }
} elseif ($action === 'register') {
    $nombre = $_POST['usuario'] ?? '';
    $pass = $_POST['contrasena'] ?? '';
    
    $pdo = getConnection();
    $stmt = $pdo->prepare("INSERT INTO Usuarios (Nombre, Contrasena) VALUES (?, ?)");
    try {
        $stmt->execute([$nombre, $pass]);
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error al registrar (quizá el usuario ya existe)."]);
    }
} elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(["success" => true]);
}
?>