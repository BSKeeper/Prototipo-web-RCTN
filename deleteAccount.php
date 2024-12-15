<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RCTN";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]));
}

// Verificar si el usuario está autenticado (sesión iniciada)
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(["success" => false, "message" => "No se ha iniciado sesión."]);
    exit();
}

$usuarioID = $_SESSION['usuario']['UsuarioID'];

// Eliminar usuario de la base de datos
$sql = "DELETE FROM Usuarios WHERE UsuarioID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioID);

if ($stmt->execute()) {
    // Cerrar sesión del usuario después de eliminar su cuenta
    session_unset();
    session_destroy();

    echo json_encode(["success" => true, "message" => "Cuenta eliminada correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar la cuenta."]);
}

$stmt->close();
$conn->close();
?>