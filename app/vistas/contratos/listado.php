<?php
// Obtener el listado de contratos mediante el controlador de contratos
$contratos = $contratosController->listar();

// Mensaje flash que dejó el controlador (crear, actualizar o borrar)
$mensaje = $_SESSION['mensaje_contratos'] ?? null;
unset($_SESSION['mensaje_contratos']);
?>

<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-file-earmark-text me-2"></i>Contratos</h1>
</div>

<!-- Alerta de notificación cuando el controlador envía un mensaje -->
<?php if ($mensaje): ?>
    <div class="alert alert-<?php echo htmlspecialchars($mensaje['tipo']); ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <?php echo htmlspecialchars($mensaje['texto']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="bg-white rounded shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabla-contratos">
            <thead class="table-dark">
                <tr>
                    <th style="width:40px"></th>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Contratación</th>
                    <th>Monto</th>
                    <th>Periodicidad</th>
                    <th>F. Inicio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contratos)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay contratos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contratos as $con): ?>
                        <tr style="cursor: pointer;">
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="seleccion" value="<?php echo (int) $con['contrato_id']; ?>">
                            </td>
                            <td><?php echo (int) $con['contrato_id']; ?></td>
                            <td><?php echo htmlspecialchars($con['nombre_cliente'] ?? $con['cliente_id']); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border me-1 text-capitalize"><?php echo htmlspecialchars($con['tipo_item']); ?></span>
                                <?php echo htmlspecialchars($con['item_nombre'] ?? ''); ?>
                            </td>
                            <td>$<?php echo number_format((float) $con['monto_acordado'], 2); ?></td>
                            <td class="text-capitalize"><?php echo htmlspecialchars($con['periodicidad']); ?></td>
                            <td><?php echo htmlspecialchars($con['fecha_inicio']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($con['estado'] === 'activo') ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst(htmlspecialchars($con['estado'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Botones CRUD: Ver / Editar / Eliminar se activan al seleccionar una fila -->
    <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
        <a href="/appgestionusuarios/public/index.php?controlador=contratos&accion=crear" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Crear
        </a>
        <a href="#" class="btn btn-outline-secondary disabled" data-base="/appgestionusuarios/public/index.php?controlador=contratos&accion=ver">
            <i class="bi bi-eye me-1"></i> Ver
        </a>
        <a href="#" class="btn btn-primary disabled" data-base="/appgestionusuarios/public/index.php?controlador=contratos&accion=editar">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="#" class="btn btn-danger disabled" data-base="/appgestionusuarios/public/index.php?controlador=contratos&accion=eliminar">
            <i class="bi bi-trash me-1"></i> Eliminar
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('#tabla-contratos input[name="seleccion"]');
    const filas = document.querySelectorAll('#tabla-contratos tbody tr');

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