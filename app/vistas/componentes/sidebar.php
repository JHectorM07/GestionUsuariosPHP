<aside class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=dashboard" class="nav-link <?php echo $controlador === 'dashboard' ? 'active' : ''; ?>">
                <i class="bi bi-house-door me-2"></i> Inicio
            </a>
        </li>
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=clientes" class="nav-link <?php echo $controlador === 'clientes' ? 'active' : ''; ?>">
                <i class="bi bi-people me-2"></i> Clientes
            </a>
        </li>
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=contratos" class="nav-link <?php echo $controlador === 'contratos' ? 'active' : ''; ?>">
                <i class="bi bi-file-earmark-text me-2"></i> Contratos
            </a>
        </li>
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=servicios" class="nav-link <?php echo $controlador === 'servicios' ? 'active' : ''; ?>">
                <i class="bi bi-gear-wide-connected me-2"></i> Servicios
            </a>
        </li>
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=productos" class="nav-link <?php echo $controlador === 'productos' ? 'active' : ''; ?>">
                <i class="bi bi-box-seam me-2"></i> Productos
            </a>
        </li>
        <li>
            <a href="/appgestionusuarios/public/index.php?controlador=cobros" class="nav-link <?php echo $controlador === 'cobros' ? 'active' : ''; ?>">
                <i class="bi bi-cash-stack me-2"></i> Cobros
            </a>
        </li>
    </ul>

    <hr class="text-secondary">

    <a href="/appgestionusuarios/public/index.php?controlador=login&accion=salir" class="nav-link text-danger fw-semibold">
        <i class="bi bi-box-arrow-right me-2"></i> Salir
    </a>
</aside>
