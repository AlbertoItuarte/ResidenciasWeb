<?php
session_start();

// Verificar si el usuario está logueado y es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'profesor') {
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

$profesor_id = $_SESSION['user_id'];

// Obtener las residencias subidas por el profesor
$query_residencias = "SELECT * FROM Residencias WHERE ID_Profe = ?";
$stmt = $conexion->prepare($query_residencias);
$stmt->bind_param("i", $profesor_id);
$stmt->execute();
$result_residencias = $stmt->get_result();

$residencias = array();
while ($row = $result_residencias->fetch_assoc()) {
    $residencias[] = $row;
}

echo json_encode($residencias);

$conexion->close();
?>