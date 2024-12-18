<?php
session_start();

// Verificar si el usuario está logueado y es un profesor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'profesor') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesProfesor.css">
    <title>Dashboard Profesor</title>
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
                        <li><a href="dashboard_profesor.php">Inicio</a></li>
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
        <div class="contenido">
            <h2>Gestión de Residencias</h2>
            <p>En esta sección podrás agregar, modificar y eliminar residencias profesionales.</p>
        </div>
        <div class="residenciasAgregadas" id="residenciasContainer">
            <!-- Aquí se mostrarán las residencias -->
        </div>
        <button onclick="mostrarFormularioAgregar()">Agregar Residencia</button>
    </div>
    <div id="formularioAgregar" class="formulario" style="display: none;">
        <h3>Agregar Residencia</h3>
        <form id="formAgregarResidencia">
            <input type="text" name="nombre" placeholder="Nombre de la Residencia" required>
            <input type="text" name="documento" placeholder="Ruta del Documento PDF" required>
            <button type="submit">Agregar</button>
            <button type="button" onclick="cerrarFormularioAgregar()">Cerrar</button>
        </form>
    </div>
    <div id="modalModificar" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalModificar()">&times;</span>
            <h3>Modificar Residencia</h3>
            <form id="formModificarResidencia">
                <input type="hidden" name="id" id="residenciaId">
                <input type="text" name="nombre" id="residenciaNombre" placeholder="Nombre de la Residencia" required>
                <button type="submit">Modificar</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });

        function mostrarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'block';
        }

        function cerrarFormularioAgregar() {
            document.getElementById('formularioAgregar').style.display = 'none';
        }

        function mostrarModalModificar(id, nombre) {
            document.getElementById('residenciaId').value = id;
            document.getElementById('residenciaNombre').value = nombre;
            document.getElementById('modalModificar').style.display = 'block';
        }

        function cerrarModalModificar() {
            document.getElementById('modalModificar').style.display = 'none';
        }

        document.getElementById('formAgregarResidencia').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('agregarResidencia.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Residencia agregada exitosamente');
                    obtenerResidencias();
                    cerrarFormularioAgregar();
                } else {
                    alert('Error al agregar residencia');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('formModificarResidencia').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('modificarResidencia.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Residencia modificada exitosamente');
                    obtenerResidencias();
                    cerrarModalModificar();
                } else {
                    alert('Error al modificar residencia');
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function obtenerResidencias() {
            fetch('obtenerResidenciasProfesor.php')
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
                            <button onclick="mostrarModalModificar(${residencia.ID}, '${residencia.Nombre}')">Modificar</button>
                            <button onclick="eliminarResidencia(${residencia.ID})">Eliminar</button>
                        `;
                        container.appendChild(card);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function eliminarResidencia(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta residencia?')) {
                fetch('eliminarResidencia.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Residencia eliminada exitosamente');
                        obtenerResidencias();
                    } else {
                        alert('Error al eliminar residencia');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Llamar a la función para obtener las residencias al cargar la página
        obtenerResidencias();
    </script>
</body>
</html>