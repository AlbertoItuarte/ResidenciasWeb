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
    <title>Residencias</title>
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
                        <li><a href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </nav>
            </div>
        </header>
    </div>

    <div style="display: flex; flex-direction:column; justify-content: center; align-items: center; padding-top: 4rem;">
       
        <h1 style="text-align:center; padding-top:2rem;">Residencias</h1>
        <div class="residenciasAgregadas" id="residenciasContainer">
            <!-- Aquí se mostrarán las residencias -->
        </div>
    </div>
    <script>
        document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });

        // Función para obtener las residencias
        function obtenerResidencias() {
            fetch('obtenerResidencias.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('residenciasContainer');
                    container.innerHTML = '';
                    data.forEach(residencia => {
                        const card = document.createElement('div');
                        card.className = 'cards';
                        card.innerHTML = `
                            <h3>${residencia.Nombre}</h3>
                            <button><a href="${residencia.Documento}" target="_blank">Abrir PDF</a></button>
                            <button onclick="agregarFavorito(${residencia.ID})">Agregar a Favoritos</button>
                        `;
                        container.appendChild(card);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Función para agregar a favoritos
        function agregarFavorito(idResidencia) {
            fetch('agregarFavorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `residencia_id=${idResidencia}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Residencia agregada a favoritos');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Llamar a la función para obtener las residencias al cargar la página
        obtenerResidencias();
    </script>
</body>
</html>
