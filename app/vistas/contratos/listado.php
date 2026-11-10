<?php
// Obtener el listado de contratos mediante el controlador
$contratos = $contratosController->listar();

// Mensaje flash que dejó el controlador (crear, actualizar o borrar)
$mensaje = $_SESSION['mensaje_contratos'] ?? null;
unset($_SESSION['mensaje_contratos']);
?>

<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-file-earmark-text me-2"></i>Contratos</h1>
</div>

<!-- BLOQUE DE ALERTA -->
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
                    <th>Tipo Ítem</th>
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
                            <td><?php echo htmlspecialchars($con['nombre_cliente'] ?? ('Cliente #' . $con['cliente_id'])); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border me-1 text-capitalize">
                                    <?php echo htmlspecialchars($con['tipo_item'] ?? 'servicio'); ?>
                                </span>
                            </td>
                            <td>$<?php echo number_format((float) ($con['monto_acordado'] ?? 0), 2); ?></td>
                            <td class="text-capitalize"><?php echo htmlspecialchars($con['periodicidad'] ?? 'mensual'); ?></td>
                            <td><?php echo htmlspecialchars($con['fecha_inicio'] ?? ''); ?></td>
                            <td>
                                <?php 
                                    $estado = $con['estado'] ?? 'inactivo';
                                    $badgeColor = ($estado === 'activo') ? 'success' : (($estado === 'suspendido') ? 'warning text-dark' : 'secondary');
                                ?>
                                <span class="badge bg-<?php echo $badgeColor; ?>">
                                    <?php echo ucfirst(htmlspecialchars($estado)); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- BARRA DE ACCIONES CON BOTONES Y POST -->
    <form id="form-acciones-contratos" method="POST" action="/appgestionusuarios/public/index.php" class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
        <input type="hidden" name="controlador" value="contratos">
        <input type="hidden" name="accion" id="input-accion-contrato" value="">
        <input type="hidden" name="id" id="input-id-contrato" value="">

        <!-- Botón Crear -->
        <button type="submit" onclick="document.getElementById('input-accion-contrato').value='crear'" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Crear
        </button>

        <!-- Botones dependientes de selección -->
        <button type="submit" id="btn-ver-contrato" onclick="document.getElementById('input-accion-contrato').value='ver'" class="btn btn-outline-secondary" disabled>
            <i class="bi bi-eye me-1"></i> Ver
        </button>
        <button type="submit" id="btn-editar-contrato" onclick="document.getElementById('input-accion-contrato').value='editar'" class="btn btn-primary" disabled>
            <i class="bi bi-pencil-square me-1"></i> Editar
        </button>
        <button type="submit" id="btn-eliminar-contrato" onclick="document.getElementById('input-accion-contrato').value='eliminar'" class="btn btn-danger" disabled>
            <i class="bi bi-trash me-1"></i> Eliminar
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('#tabla-contratos input[name="seleccion"]');
    const filas = document.querySelectorAll('#tabla-contratos tbody tr');
    const inputId = document.getElementById('input-id-contrato');
    const botones = [
        document.getElementById('btn-ver-contrato'),
        document.getElementById('btn-editar-contrato'),
        document.getElementById('btn-eliminar-contrato')
    ];

    function activarSeleccion(id) {
        inputId.value = id;
        botones.forEach(b => b.removeAttribute('disabled'));
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            activarSeleccion(this.value);
        });
    });

    filas.forEach(function (tr) {
        tr.addEventListener('click', function (e) {
            const radio = this.querySelector('input[name="seleccion"]');
            if (radio && e.target.tagName !== 'INPUT') {
                radio.checked = true;
                activarSeleccion(radio.value);
            }
        });
    });
});
</script>