<?php
function getConnection() {
    $env_path = __DIR__ . '/../../.env';
    $env_vars = [];
    if(file_exists($env_path)) {
        $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line) {
            if(strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            $env_vars[trim($name)] = trim($value);
        }
    }

    $host = $env_vars['DB_HOST'] ?? 'localhost';
    $db   = $env_vars['DB_NAME'] ?? 'task_manager';
    $user = $env_vars['DB_USER'] ?? 'task_admin';
    $pass = $env_vars['DB_PASS'] ?? 'Admin123!';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die(json_encode(["error" => "Error de conexion: " . $e->getMessage()]));
    }
}
?>