<?php
session_start();

// Verificar si el usuario está logueado y es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header("Location: index.php");
    exit();
}

$server = "localhost";
$user = "root";
$password = "root";
$database = "residenciasdb";

$conexion = new mysqli($server, $user, $password, $database);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$alumno_id = $_SESSION['user_id'];
$residencia_id = $_POST['residencia_id'];

// Verificar si la residencia ya está en favoritos
$query_verificar = "SELECT * FROM Favoritos WHERE ID_Residencia = ? AND ID_Alumno = ?";
$stmt = $conexion->prepare($query_verificar);
$stmt->bind_param("ii", $residencia_id, $alumno_id);
$stmt->execute();
$result_verificar = $stmt->get_result();

if ($result_verificar->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'La residencia ya está en favoritos']);
} else {
    // Agregar la residencia a favoritos
    $query_agregar = "INSERT INTO Favoritos (ID_Residencia, ID_Alumno) VALUES (?, ?)";
    $stmt = $conexion->prepare($query_agregar);
    $stmt->bind_param("ii", $residencia_id, $alumno_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Residencia agregada a favoritos']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar residencia a favoritos']);
    }
}

$conexion->close();
?>