<?php
session_start();

$registro = isset($_GET['registro']) ? true : false; // Parámetro para alternar entre inicio y registro
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="contenedor-principal">
    <div class="contenedor-secundario <?php echo $registro ? 'reverse' : ''; ?>">
    
      <div class="contenedor-img <?php echo $registro ? 'start' : 'end'; ?>">
        <img
          src="login.jpeg"
          alt="Logo"
          class="imagen <?php echo $registro ? 'rotate' : ''; ?>"
          style="width: 50%;"
        />
      </div>
     
      <div class="contenedor-formulario <?php echo $registro ? 'end' : 'start'; ?>">
        <div class="formulario <?php echo $registro ? 'rounded-left' : 'rounded-right'; ?>">
          <h1 class="titulo">
            <?php echo $registro ? "Registro" : "Inicio de sesión"; ?>
          </h1>

          <?php if(isset($_GET['error'])): ?>
    <div id="alert-message" class="alert alert-error fade-in">
        <?php 
            switch($_GET['error']) {
                case 1:
                    echo "Usuario o contraseña incorrectos";
                    break;
                case 2:
                    echo "Por favor complete todos los campos";
                    break;
            }
        ?>
    </div>
    <script>
        setTimeout(function() {
            const alert = document.getElementById('alert-message');
            alert.classList.add('fade-out');
            setTimeout(function() {
                alert.style.display = 'none';
            }, 300);
        }, 3000);
    </script>
<?php endif; ?>

          <form method="POST" action="conexion.php" id="formulario">
            <?php if ($registro): ?>
              <input type="text" name="nombre" placeholder="Nombre" required />
            <?php endif; ?>
            <input type="email" name="username" placeholder="Correo electrónico" required />
            <input type="password" name="password" placeholder="Contraseña" required />
            <?php if ($registro): ?>
              <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required />
            <?php endif; ?>
            <button type="submit" class="boton boton-login">
              <?php echo $registro ? "Registrarse" : "Iniciar sesión"; ?>
            </button>
          </form>

    
          <!-- <div class="acciones">
            <p class="texto">
              <?php if ($registro): ?>
                ¿Ya tienes una cuenta?
                <a href="?">Iniciar sesión</a>
              <?php else: ?>
                ¿Aún no estás registrado?
                <a href="?registro=1">Crear una cuenta</a>
              <?php endif; ?>
            </p>
            <?php if (!$registro): ?>
              <p class="texto">
                <a href="#">Olvidé mi contraseña</a>
              </p>
            <?php endif; ?>
          </div> -->

    
          <?php if (!$registro): ?>
            <div class="separador">
              <div class="linea"></div>
              <p class="texto-separador">O</p>
              <div class="linea"></div>
            </div>
          
          
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('formulario').addEventListener('submit', function (e) {
      const password = document.querySelector('input[name="password"]');
      const confirmPassword = document.querySelector('input[name="confirm_password"]');

      if (confirmPassword && password.value !== confirmPassword.value) {
        alert('Las contraseñas no coinciden');
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
