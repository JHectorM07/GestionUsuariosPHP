<aside class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="dashboard">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-house-door me-2"></i> Inicio
                </button>
            </form>
        </li>
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="clientes">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'clientes' ? 'active' : ''; ?>">
                    <i class="bi bi-people me-2"></i> Clientes
                </button>
            </form>
        </li>
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="contratos">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'contratos' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-text me-2"></i> Contratos
                </button>
            </form>
        </li>
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="servicios">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'servicios' ? 'active' : ''; ?>">
                    <i class="bi bi-gear-wide-connected me-2"></i> Servicios
                </button>
            </form>
        </li>
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="productos">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'productos' ? 'active' : ''; ?>">
                    <i class="bi bi-box-seam me-2"></i> Productos
                </button>
            </form>
        </li>
        <li>
            <form method="POST" action="/appgestionusuarios/public/index.php">
                <input type="hidden" name="controlador" value="cobros">
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent <?php echo $controlador === 'cobros' ? 'active' : ''; ?>">
                    <i class="bi bi-cash-stack me-2"></i> Cobros
                </button>
            </form>
        </li>
    </ul>

    <hr class="text-secondary">

    <form method="POST" action="/appgestionusuarios/public/index.php">
        <input type="hidden" name="controlador" value="login">
        <input type="hidden" name="accion" value="salir">
        <button type="submit" class="nav-link text-danger fw-semibold w-100 text-start border-0 bg-transparent">
            <i class="bi bi-box-arrow-right me-2"></i> Salir
        </button>
    </form>
</aside>