<?php
session_start();

$server = "localhost";
$user = "root";
$password = "root";
$database = "residenciasdb";

$conexion = new mysqli($server, $user, $password, $database);

if($conexion->connect_error){
    die("Error en la conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conexion, $_POST['username']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);

    // Verificar en tabla Profesores
    $query_profesor = "SELECT * FROM Profesores WHERE Correo = '$username' AND Contraseña = '$password'";
    $result_profesor = $conexion->query($query_profesor);

    // Verificar en tabla Alumno
    $query_alumno = "SELECT * FROM Alumno WHERE Correo = '$username' AND Contraseña = '$password'";
    $result_alumno = $conexion->query($query_alumno);

    if ($result_profesor->num_rows == 1) {
        $row = $result_profesor->fetch_assoc();
        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['user_type'] = 'profesor';
        $_SESSION['nombre'] = $row['Nombre'];
        header("Location: dashboard_profesor.php");
        exit();
    } 
    elseif ($result_alumno->num_rows == 1) {
        $row = $result_alumno->fetch_assoc();
        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['user_type'] = 'alumno';
        $_SESSION['nombre'] = $row['Nombre'];
        header("Location: dashboard_alumno.php");
        exit();
    } 
    else {
        header("Location: index.php?error=1");
        exit();
    }
}

$conexion->close();
?>