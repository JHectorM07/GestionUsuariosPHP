<?php
// Obtener la lista de clientes desde el controlador
$clientes = $clientesController->listar();

// Mensaje flash que dejó el controlador (crear, actualizar o borrar)
$mensaje = $_SESSION['mensaje_clientes'] ?? null;
unset($_SESSION['mensaje_clientes']);
?>

<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-people me-2"></i>Clientes</h1>
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
        <table class="table table-hover align-middle mb-0" id="tabla-clientes">
            <thead class="table-dark">
                <tr>
                    <th style="width:40px"></th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay clientes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <tr style="cursor: pointer;">
                            <td class="text-center">
                                <input class="form-check-input" type="radio" name="seleccion" value="<?php echo (int) $c['cliente_id']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($c['cliente'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($c['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($c['telefono'] ?? ''); ?></td>
                            <td>
                                <?php 
                                    $estado = $c['estado'] ?? 'inactivo';
                                    $badgeColor = ($estado === 'activo') ? 'success' : 'secondary';
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
    <form id="form-acciones-clientes" method="POST" action="/appgestionusuarios/public/index.php" class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
        <input type="hidden" name="controlador" value="clientes">
        <input type="hidden" name="accion" id="input-accion-cliente" value="">
        <input type="hidden" name="id" id="input-id-cliente" value="">

        <!-- Botón Crear (Envía directamente la acción 'crear') -->
        <button type="submit" onclick="document.getElementById('input-accion-cliente').value='crear'" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Crear
        </button>

        <!-- Botones dependientes de selección -->
        <button type="submit" id="btn-ver-cliente" onclick="document.getElementById('input-accion-cliente').value='ver'" class="btn btn-outline-secondary" disabled>
            <i class="bi bi-eye me-1"></i> Ver
        </button>
        <button type="submit" id="btn-editar-cliente" onclick="document.getElementById('input-accion-cliente').value='editar'" class="btn btn-primary" disabled>
            <i class="bi bi-pencil-square me-1"></i> Editar
        </button>
        <button type="submit" id="btn-eliminar-cliente" onclick="document.getElementById('input-accion-cliente').value='eliminar'" class="btn btn-danger" disabled>
            <i class="bi bi-trash me-1"></i> Eliminar
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('#tabla-clientes input[name="seleccion"]');
    const filas = document.querySelectorAll('#tabla-clientes tbody tr');
    const inputId = document.getElementById('input-id-cliente');
    const botones = [
        document.getElementById('btn-ver-cliente'),
        document.getElementById('btn-editar-cliente'),
        document.getElementById('btn-eliminar-cliente')
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