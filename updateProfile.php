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
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$correo = isset($_POST['correo']) ? $_POST['correo'] : null;
$contraseñaActual = isset($_POST['contraseñaActual']) ? $_POST['contraseñaActual'] : null;
$nuevaContraseña = isset($_POST['nuevaContraseña']) ? $_POST['nuevaContraseña'] : null;

// Verificar si el usuario proporcionó la contraseña actual y nueva
if ($contraseñaActual && $nuevaContraseña) {
    // Consultar la contraseña actual almacenada
    $sql = "SELECT Contraseña FROM Usuarios WHERE UsuarioID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioID);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($contraseñaActual, $usuario['Contraseña'])) {
        // Si la contraseña actual es correcta, hashear la nueva contraseña
        $nuevaContraseñaHasheada = password_hash($nuevaContraseña, PASSWORD_DEFAULT);
    } else {
        echo json_encode(["success" => false, "message" => "La contraseña actual es incorrecta."]);
        exit();
    }
}

$updateFields = [];
$params = [];

if ($nombre) {
    $updateFields[] = "Nombre = ?";
    $params[] = $nombre;
}
if ($correo) {
    $updateFields[] = "Correo = ?";
    $params[] = $correo;
}
if ($nuevaContraseña) {
    $updateFields[] = "Contraseña = ?";
    $params[] = $nuevaContraseñaHasheada;
}

// Si no hay campos para actualizar, retornar error
if (empty($updateFields)) {
    echo json_encode(["success" => false, "message" => "No se ha proporcionado ningún dato para actualizar."]);
    exit();
}

// Agregar el ID de usuario al final de los parámetros
$params[] = $usuarioID;

$sql = "UPDATE Usuarios SET " . implode(", ", $updateFields) . " WHERE UsuarioID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta."]);
    exit();
}

// Vincular los parámetros
$stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Perfil actualizado correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el perfil."]);
}

$stmt->close();
$conn->close();
?>