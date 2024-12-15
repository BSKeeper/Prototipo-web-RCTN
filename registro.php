<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RCTN";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Hashear la contraseña
$tipoUsuario = $_POST['tipoUsuario'];

// Insertar los datos en la tabla Usuarios de forma segura
$sql = "INSERT INTO Usuarios (Nombre, Correo, Contraseña, TipoUsuario) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $contraseña, $tipoUsuario);

if ($stmt->execute()) {
    echo "Nuevo usuario registrado";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>