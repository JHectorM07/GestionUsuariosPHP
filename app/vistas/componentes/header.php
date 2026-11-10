<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MiEmpresa TI</title>

    <link rel="stylesheet" href="/appgestionusuarios/public/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/appgestionusuarios/public/assets/css/custom.css">
</head>
<body class="bg-light panel-body">

    <!-- HEADER SUPERIOR -->
    <header class="navbar navbar-dark bg-dark sticky-top shadow-sm px-3" style="height: 60px;">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="/appgestionusuarios/public/index.php?controlador=dashboard">
            <i class="bi bi-cpu-fill text-primary fs-4"></i>
            <span>MiEmpresa TI</span>
        </a>

        <div class="dropdown text-end">
            <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-5 me-1"></i>
                <span><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/appgestionusuarios/public/index.php?controlador=login&accion=salir"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
            </ul>
        </div>
    </header>
