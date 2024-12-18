<?php
session_start();

// Verificar si el usuario está logueado y es un alumno
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'alumno') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesAlumno.css">
    <title>Dashboard Alumno</title>
</head>
<body>
    <div>
        <header class="header">
            <img src="log.png" alt="logo" width="50" height="50">
            <h1>Residencias Profesionales</h1>
            <div class="menu">
                <button id="menuButton">Menú</button>
                <nav id="menu" class="hidden">
                    <ul>
                        <li><a href="dashboard_alumno.php">Inicio</a></li>
                        <li><a href="residencias_alumno.php">Mis residencias</a></li>
                        <li><a href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </nav>
            </div>
        </header>
    </div>
    <div class="bienvenida">
        <div>
            <h2 style="font-size: 2rem">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
        </div>
    </div>
    <div style="display: flex; flex-direction:column; justify-content: center; align-items: center; padding-top: 4rem;">
        <div id="contenido" class="contenido" style="display: none;">
            <h2>Información de residencias</h2>
            <p>En esta sección podrás ver la información de tus residencias profesionales agregadas.</p>
        </div>
        <div class="residenciasAgregadas" id="residenciasFavoritasContainer">
            <!-- Aquí se mostrarán las residencias favoritas -->
        </div>
        <button><a href="residencias.php">Ver residencias</a></button>
    </div>
    <script>
        document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });

        // Función para obtener las residencias favoritas
        function obtenerResidenciasFavoritas() {
            fetch('residenciasFavoritas.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('residenciasFavoritasContainer');
                    container.innerHTML = '';
                    if (data.length === 0) {
                        document.getElementById('contenido').style.display = 'block';
                    } else {
                        data.forEach(residencia => {
                            const card = document.createElement('div');
                            card.className = 'cards';
                            card.innerHTML = `
                                <h3>${residencia.Nombre}</h3>
                                <button><a href="${residencia.Documento}" target="_blank">Abrir PDF</a></button>
                            `;
                            container.appendChild(card);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Llamar a la función para obtener las residencias favoritas al cargar la página
        obtenerResidenciasFavoritas();
    </script>
</body>
</html>