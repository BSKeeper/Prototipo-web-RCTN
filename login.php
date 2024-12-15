<?php
// Conexión a la base de datos
$host = "localhost";
$username = "root";
$password = "";
$dbname = "RCTN";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]));
}

// Verificar datos enviados por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : null;

    if (!$correo || !$contraseña) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(["success" => false, "message" => "Correo y contraseña son requeridos."]);
        exit();
    }

    // Preparar y ejecutar la consulta
    $sql = "SELECT * FROM usuarios WHERE Correo = ?"; // Asegúrate de que 'Correo' esté en mayúscula
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Error al preparar la consulta."]));
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contraseña, $usuario['Contraseña'])) { // Verifica que el campo sea 'Contraseña' en mayúscula
            session_start();
            $_SESSION['usuario'] = $usuario;
            echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso."]);
        } else {
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
    }

    $stmt->close();
}

$conn->close();

?>