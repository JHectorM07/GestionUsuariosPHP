<?php
require_once '../app/controladores/ContratosController.php';
require_once '../app/controladores/ClientesController.php';

$contratosController = new ContratosController();
$clientesController  = new ClientesController();

$contratos = $contratosController->listar();
$clientes  = $clientesController->listar();

// Mensaje flash dejado por el controlador
$mensaje = $_SESSION['mensaje_contratos'] ?? null;
unset($_SESSION['mensaje_contratos']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Contratos</h2>
    <button type="button" class="btn btn-success btn-sm" onclick="prepararModalCrearContrato()">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Contrato
    </button>
</div>

<!-- ALERTA FLASH -->
<?php if ($mensaje): ?>
    <div class="alert alert-<?= htmlspecialchars($mensaje['tipo']) ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <?= htmlspecialchars($mensaje['texto']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- TABLA DE LISTADO -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Tipo Ítem</th>
                        <th>Monto</th>
                        <th>Periodicidad</th>
                        <th>F. Inicio</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($contratos)): ?>
                        <?php foreach ($contratos as $con): ?>
                        <tr>
                            <td><?= (int) $con['contrato_id'] ?></td>
                            <td><strong><?= htmlspecialchars($con['nombre_cliente'] ?? ('Cliente #' . $con['cliente_id'])) ?></strong></td>
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    <?= htmlspecialchars($con['tipo_item'] ?? 'servicio') ?>
                                </span>
                            </td>
                            <td>$<?= number_format((float) ($con['monto_acordado'] ?? 0), 2) ?></td>
                            <td class="text-capitalize"><?= htmlspecialchars($con['periodicidad'] ?? 'mensual') ?></td>
                            <td><?= htmlspecialchars($con['fecha_inicio'] ?? '') ?></td>
                            <td>
                                <?php 
                                    $estado = $con['estado'] ?? 'inactivo';
                                    $badgeColor = ($estado === 'activo') ? 'bg-success' : (($estado === 'suspendido') ? 'bg-warning text-dark' : 'bg-secondary');
                                ?>
                                <span class="badge <?= $badgeColor ?>">
                                    <?= ucfirst(htmlspecialchars($estado)) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Crear -->
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm me-1" 
                                        title="Nuevo Contrato"
                                        onclick="prepararModalCrearContrato()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>

                                <!-- Ver -->
                                <button type="button" 
                                        class="btn btn-outline-info btn-sm me-1" 
                                        title="Ver Detalles"
                                        onclick='prepararModalVerContrato(<?= json_encode($con) ?>)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <!-- Editar -->
                                <button type="button" 
                                        class="btn btn-outline-warning btn-sm me-1" 
                                        title="Editar Contrato"
                                        onclick='prepararModalEditarContrato(<?= json_encode($con) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <!-- Eliminar -->
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        title="Eliminar Contrato"
                                        onclick="eliminarContrato(<?= (int) $con['contrato_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                No hay contratos registrados.
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm" 
                                        title="Nuevo Contrato"
                                        onclick="prepararModalCrearContrato()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL ÚNICO DE CONTRATOS (Crear, Ver y Editar) -->
<div class="modal fade" id="modalContrato" tabindex="-1" aria-labelledby="modalContratoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formContrato">
                <input type="hidden" name="id" id="contrato_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalContratoTitulo">Nuevo Contrato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="selCliente" class="form-label">Cliente</label>
                        <select name="cliente_id" id="selCliente" class="form-select" required>
                            <option value="">Seleccione un cliente...</option>
                            <?php foreach ($clientes as $cli): ?>
                                <option value="<?= (int) $cli['cliente_id'] ?>">
                                    <?= htmlspecialchars($cli['cliente']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="selTipoItem" class="form-label">Tipo Ítem</label>
                            <select name="tipo_item" id="selTipoItem" class="form-select">
                                <option value="servicio">Servicio</option>
                                <option value="producto">Producto</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="txtMonto" class="form-label">Monto Acordado ($)</label>
                            <input type="number" step="0.01" min="0.01" name="monto_acordado" id="txtMonto" class="form-control" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="selPeriodicidad" class="form-label">Periodicidad</label>
                            <select name="periodicidad" id="selPeriodicidad" class="form-select">
                                <option value="mensual">Mensual</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                                <option value="unico">Pago Único</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="txtDiaCorte" class="form-label">Día de Corte</label>
                            <input type="number" min="1" max="31" name="dia_corte" id="txtDiaCorte" class="form-control" value="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="txtFechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="txtFechaInicio" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="selEstadoContrato" class="form-label">Estado</label>
                            <select name="estado" id="selEstadoContrato" class="form-select">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarContrato">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalContratoBootstrap = null;

document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('modalContrato');
    if (modalElement) {
        modalContratoBootstrap = new bootstrap.Modal(modalElement);
    }

    const formContrato = document.getElementById('formContrato');
    if (formContrato) {
        formContrato.addEventListener('submit', guardarOActualizarContrato);
    }
});

function habilitarCamposContrato(habilitar) {
    const campos = ['selCliente', 'selTipoItem', 'txtMonto', 'selPeriodicidad', 'txtDiaCorte', 'txtFechaInicio', 'selEstadoContrato'];
    campos.forEach(id => {
        document.getElementById(id).disabled = !habilitar;
    });
    document.getElementById('btnGuardarContrato').style.display = habilitar ? 'inline-block' : 'none';
}

function prepararModalCrearContrato() {
    document.getElementById('formContrato').reset();
    document.getElementById('contrato_id').value = '';
    document.getElementById('modalContratoTitulo').innerText = 'Nuevo Contrato';
    habilitarCamposContrato(true);
    modalContratoBootstrap.show();
}

function prepararModalEditarContrato(contrato) {
    document.getElementById('contrato_id').value = contrato.contrato_id;
    document.getElementById('selCliente').value = contrato.cliente_id;
    document.getElementById('selTipoItem').value = contrato.tipo_item || 'servicio';
    document.getElementById('txtMonto').value = contrato.monto_acordado;
    document.getElementById('selPeriodicidad').value = contrato.periodicidad || 'mensual';
    document.getElementById('txtDiaCorte').value = contrato.dia_corte || 1;
    document.getElementById('txtFechaInicio').value = contrato.fecha_inicio;
    document.getElementById('selEstadoContrato').value = contrato.estado || 'activo';

    document.getElementById('modalContratoTitulo').innerText = 'Editar Contrato #' + contrato.contrato_id;
    habilitarCamposContrato(true);
    modalContratoBootstrap.show();
}

function prepararModalVerContrato(contrato) {
    prepararModalEditarContrato(contrato);
    document.getElementById('modalContratoTitulo').innerText = 'Detalles del Contrato #' + contrato.contrato_id;
    habilitarCamposContrato(false);
}

async function guardarOActualizarContrato(e) {
    e.preventDefault();

    const id = document.getElementById('contrato_id').value;
    const datos = new FormData(e.target);
    const accion = id ? 'actualizar' : 'guardar';
    const url = `index.php?controlador=contratos&accion=${accion}`;

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            modalContratoBootstrap.hide();
            location.reload();
        } else {
            alert(resultado.mensaje || 'Ocurrió un error al procesar el contrato.');
        }
    } catch (error) {
        console.error('Error en la petición AJAX:', error);
    }
}

async function eliminarContrato(id) {
    if (!confirm('¿Está seguro de que desea eliminar este contrato?')) return;

    const datos = new FormData();
    datos.append('id', id);

    try {
        const respuesta = await fetch('index.php?controlador=contratos&accion=borrar', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            location.reload();
        } else {
            alert(resultado.mensaje || 'No se pudo eliminar el contrato.');
        }
    } catch (error) {
        console.error('Error en la eliminación:', error);
    }
}
</script>