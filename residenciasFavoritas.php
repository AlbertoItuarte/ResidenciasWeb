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

// Obtener las residencias favoritas del alumno
$query_favoritas = "
    SELECT Residencias.* 
    FROM Favoritos 
    JOIN Residencias ON Favoritos.ID_Residencia = Residencias.ID 
    WHERE Favoritos.ID_Alumno = ?";
$stmt = $conexion->prepare($query_favoritas);
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$result_favoritas = $stmt->get_result();

$favoritas = array();
while ($row = $result_favoritas->fetch_assoc()) {
    $favoritas[] = $row;
}

echo json_encode($favoritas);

$conexion->close();
?>