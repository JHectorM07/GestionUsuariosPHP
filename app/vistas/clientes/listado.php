<?php
$clientes = $clientesController->listar();

// Mensaje que dejó el controlador (crear, actualizar o borrar)
$mensaje = $_SESSION['mensaje_clientes'] ?? null;
unset($_SESSION['mensaje_clientes']);
?>

<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-people me-2"></i>Clientes</h1>
</div>

<!-- BLOQUE DE ALERTA: Si hay un mensaje en sesión, se muestra aquí -->
<?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <?php echo htmlspecialchars($mensaje['texto']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="bg-white rounded shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabla-clientes">
            <thead class="table-dark">
                <tr>
                    <th style="width:40px"></th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No hay registros.</td></tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <tr style="cursor: pointer;">
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="seleccion" value="<?php echo (int) $c['cliente_id']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($c['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                            <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Botones CRUD -->
    <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
        <a href="/appgestionusuarios/public/index.php?controlador=clientes&accion=crear" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Crear</a>
        <a href="#" class="btn btn-outline-secondary disabled" data-base="/appgestionusuarios/public/index.php?controlador=clientes&accion=ver"><i class="bi bi-eye me-1"></i> Ver</a>
        <a href="#" class="btn btn-primary disabled" data-base="/appgestionusuarios/public/index.php?controlador=clientes&accion=editar"><i class="bi bi-pencil-square me-1"></i> Editar</a>
        <a href="#" class="btn btn-danger disabled" data-base="/appgestionusuarios/public/index.php?controlador=clientes&accion=eliminar"><i class="bi bi-trash me-1"></i> Eliminar</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('#tabla-clientes input[name="seleccion"]');
    const filas = document.querySelectorAll('#tabla-clientes tbody tr');

    function actualizarAcciones(id) {
        document.querySelectorAll('[data-base]').forEach(function (b) {
            b.href = b.dataset.base + '&id=' + id;
            b.classList.remove('disabled');
        });
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            actualizarAcciones(this.value);
        });
    });

    filas.forEach(function (tr) {
        tr.addEventListener('click', function (e) {
            const radio = this.querySelector('input[name="seleccion"]');
            if (radio && e.target.tagName !== 'INPUT') {
                radio.checked = true;
                actualizarAcciones(radio.value);
            }
        });
    });
});
</script>