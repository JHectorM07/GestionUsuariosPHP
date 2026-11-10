<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema TI</title>
    <!-- Bootstrap local -->
    <link rel="stylesheet" href="/appgestionusuarios/public/assets/bootstrap/css/bootstrap.min.css">
    <!-- Iconos de Bootstrap (Opcionales via CDN o locales) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="/appgestionusuarios/public/assets/css/custom.css">
</head>
<body class="bg-login d-flex flex-column justify-content-between min-vh-100">

    <!-- Header con espacio para el Logo (Superior Izquierda) -->
    <header class="p-3">
        <div class="container-fluid">
            <!-- Reemplaza el src con la ruta de tu logo -->
            <img src="/AppG/public/assets/img/logo.png" alt="Logo Empresa" height="40" class="img-fluid" onerror="this.style.display='none'">
            <span class="text-white fw-bold ms-2 h5">Gestion de usuarios</span>
        </div>
    </header>

    <!-- Contenido Central: Formulario de Login -->
    <main class="d-flex align-items-center justify-content-center flex-grow-1">
        <div class="login-card p-4">
            
            <!-- Icono Central de Usuario -->
            <div class="text-center mb-4">
                <i class="bi bi-person-circle text-white" style="font-size: 5rem; color: aliceblue;"></i>
            </div>

            <form action="/appgestionusuarios/public/index.php?controlador=login&accion=ingresar" method="POST">
                
                <!-- Input Usuario -->
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario" required autocomplete="off">
                </div>

                <!-- Input Contraseña con Ojito -->
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-light border" type="button" id="btnTogglePassword">
                            <i class="bi bi-eye" id="iconEye"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón de Inicio -->
                <div class="text-center">
                    <button type="submit" class="btn btn-light px-4 py-2 fw-semibold rounded-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
                    </button>
                </div>

            </form>
        </div>
    </main>

    <?php if(isset($_SESSION['error_login'])): ?>
                <div class="text-center" style="color: white;">
                        <?php 
                            echo $_SESSION['error_login'];
                            unset($_SESSION['error_login']);
                        ?>
                </div>
            <?php endif; ?>

    <footer class="text-center text-white-50 py-3 small">
        &copy; <?php echo date('Y'); ?> Sistema de Gestión TI
    </footer>

    <!-- JS de Bootstrap -->
    <script src="../../../public/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html> 