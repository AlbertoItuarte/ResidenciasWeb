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

// Obtener el ID del profesor del alumno
$query_profesor = "SELECT ID_Profe FROM Alumno WHERE ID = ?";
$stmt = $conexion->prepare($query_profesor);
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$result_profesor = $stmt->get_result();
$profesor = $result_profesor->fetch_assoc();
$profesor_id = $profesor['ID_Profe'];

// Obtener las residencias subidas por el profesor del alumno
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
