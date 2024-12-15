<?php
session_start();

// Verificar si hay sesión activa
if (!isset($_SESSION['usuario'])) {
    echo json_encode(["success" => false, "message" => "No se ha iniciado sesión."]);
    exit();
}

// Obtener correo del usuario desde la sesión
$correo = $_SESSION['usuario']['Correo'];

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RCTN";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar los datos del usuario
$sql = "SELECT UsuarioID, Nombre, Correo FROM Usuarios WHERE Correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $usuario]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
}

$stmt->close();
$conn->close();
?>