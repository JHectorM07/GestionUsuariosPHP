<?php
require_once '../app/controladores/ContratosController.php';
require_once '../app/controladores/ClientesController.php';
require_once '../app/controladores/ServiciosController.php';
require_once '../app/controladores/ProductosController.php';

$contratosController = new ContratosController();
$clientesController  = new ClientesController();
$serviciosController = new ServiciosController();
$productosController = new ProductosController();

$contratos = $contratosController->listar();
$clientes  = $clientesController->listar();
$servicios = $serviciosController->listar();
$productos = $productosController->listar();

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
                        <th>Ítem Contratado</th>
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
                                <?php 
                                    $tipo = $con['tipo_item'] ?? 'servicio';
                                    $nombreItem = ($tipo === 'servicio') 
                                        ? ($con['nombre_servicio'] ?? 'Sin especificar') 
                                        : ($con['nombre_producto'] ?? 'Sin especificar');
                                ?>
                                <span class="badge bg-light text-dark border text-capitalize me-1">
                                    <?= htmlspecialchars($tipo) ?>
                                </span>
                                <small class="text-muted"><?= htmlspecialchars($nombreItem) ?></small>
                            </td>
                            <td>$<?= number_format((float) ($con['monto_acordado'] ?? 0), 2) ?></td>
                            <td class="text-capitalize">
                                <?= ($con['periodicidad'] === 'unico') ? 'Pago Único' : htmlspecialchars($con['periodicidad'] ?? 'mensual') ?>
                            </td>
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
                                <button type="button" class="btn btn-outline-success btn-sm me-1" title="Nuevo Contrato" onclick="prepararModalCrearContrato()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm me-1" title="Ver Detalles" onclick='prepararModalVerContrato(<?= json_encode($con) ?>)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm me-1" title="Editar Contrato" onclick='prepararModalEditarContrato(<?= json_encode($con) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar Contrato" onclick="eliminarContrato(<?= (int) $con['contrato_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No hay contratos registrados.</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-success btn-sm" title="Nuevo Contrato" onclick="prepararModalCrearContrato()">
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

<!-- MODAL ÚNICO DE CONTRATOS -->
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
                        <div class="col-md-5 mb-3">
                            <label for="selTipoItem" class="form-label">Tipo Ítem</label>
                            <select name="tipo_item" id="selTipoItem" class="form-select" onchange="alternarSelectorItem()">
                                <option value="servicio">Servicio</option>
                                <option value="producto">Producto</option>
                            </select>
                        </div>

                        <!-- Selector de Servicios -->
                        <div class="col-md-7 mb-3" id="grupoSelectServicio">
                            <label for="selServicio" class="form-label">Servicio</label>
                            <select name="servicio_id" id="selServicio" class="form-select" onchange="autocompletarDatosServicio()">
                                <option value="">Seleccione un servicio...</option>
                                <?php foreach ($servicios as $srv): ?>
                                    <option value="<?= (int) $srv['servicio_id'] ?>" 
                                            data-precio="<?= htmlspecialchars($srv['precio_base']) ?>"
                                            data-periodicidad="<?= htmlspecialchars($srv['periodicidad'] ?? 'mensual') ?>">
                                        <?= htmlspecialchars($srv['servicio']) ?> ($<?= number_format((float)$srv['precio_base'], 2) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Selector de Productos -->
                        <div class="col-md-7 mb-3 d-none" id="grupoSelectProducto">
                            <label for="selProducto" class="form-label">Producto</label>
                            <select name="producto_id" id="selProducto" class="form-select" onchange="autocompletarDatosProducto()">
                                <option value="">Seleccione un producto...</option>
                                <?php foreach ($productos as $prod): ?>
                                    <option value="<?= (int) $prod['producto_id'] ?>" 
                                            data-precio="<?= htmlspecialchars($prod['precio_unitario']) ?>">
                                        <?= htmlspecialchars($prod['producto']) ?> ($<?= number_format((float)$prod['precio_unitario'], 2) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="txtMonto" class="form-label">Monto Acordado ($)</label>
                            <input type="number" step="0.01" min="0.01" name="monto_acordado" id="txtMonto" class="form-control" required placeholder="0.00">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="selPeriodicidad" class="form-label">Periodicidad</label>
                                <select name="periodicidad" id="selPeriodicidad" class="form-select" onchange="recalcularMontoPorPeriodicidad()">
                                    <option value="mensual">Mensual</option>
                                    <option value="anual">Anual</option>
                                    <option value="unico">Pago Único</option>
                                </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="txtDiaCorte" class="form-label">Día Corte</label>
                            <input type="number" min="1" max="31" name="dia_corte" id="txtDiaCorte" class="form-control" value="1" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="txtFechaInicio" class="form-label">F. Inicio</label>
                            <input type="date" name="fecha_inicio" id="txtFechaInicio" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
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

function alternarSelectorItem() {
    const tipo = document.getElementById('selTipoItem').value;
    const grupoSrv = document.getElementById('grupoSelectServicio');
    const grupoProd = document.getElementById('grupoSelectProducto');
    const selPer = document.getElementById('selPeriodicidad');

    const optMensual = selPer.querySelector('option[value="mensual"]');
    const optAnual   = selPer.querySelector('option[value="anual"]');
    const optUnico   = selPer.querySelector('option[value="unico"]');

    if (tipo === 'servicio') {
        grupoSrv.classList.remove('d-none');
        grupoProd.classList.add('d-none');
        document.getElementById('selProducto').value = '';

        // Servicios: habilitar Mensual y Anual, ocultar Pago Único
        if (optMensual) optMensual.hidden = false;
        if (optAnual)   optAnual.hidden = false;
        if (optUnico)   optUnico.hidden = true;

        if (selPer.value === 'unico') {
            selPer.value = 'mensual';
        }
    } else {
        grupoProd.classList.remove('d-none');
        grupoSrv.classList.add('d-none');
        document.getElementById('selServicio').value = '';

        // Productos: fijar Pago Único y ocultar Mensual/Anual
        if (optMensual) optMensual.hidden = true;
        if (optAnual)   optAnual.hidden = true;
        if (optUnico)   optUnico.hidden = false;

        selPer.value = 'unico';
    }
}

function recalcularMontoPorPeriodicidad() {
    const tipo = document.getElementById('selTipoItem').value;
    if (tipo !== 'servicio') return;

    const selectSrv = document.getElementById('selServicio');
    const opcion = selectSrv.options[selectSrv.selectedIndex];
    if (!opcion || !opcion.value) return;

    const precioBase = parseFloat(opcion.dataset.precio) || 0;
    const perBase = opcion.dataset.periodicidad || 'mensual'; // Cómo viene del catálogo
    const perElegida = document.getElementById('selPeriodicidad').value; // Cómo lo quiere pagar el cliente

    let montoCalculado = precioBase;

    if (perBase === 'mensual' && perElegida === 'anual') {
        montoCalculado = precioBase * 12;
    } else if (perBase === 'anual' && perElegida === 'mensual') {
        montoCalculado = precioBase / 12;
    }

    document.getElementById('txtMonto').value = montoCalculado.toFixed(2);
}

function autocompletarDatosServicio() {
    const select = document.getElementById('selServicio');
    const opcion = select.options[select.selectedIndex];
    if (opcion && opcion.value) {
        const per = opcion.dataset.periodicidad;
        document.getElementById('selPeriodicidad').value = (per === 'anual') ? 'anual' : 'mensual';
        recalcularMontoPorPeriodicidad();
    }
}

function autocompletarDatosProducto() {
    const select = document.getElementById('selProducto');
    const opcion = select.options[select.selectedIndex];
    if (opcion && opcion.value) {
        document.getElementById('txtMonto').value = opcion.dataset.precio || '';
        document.getElementById('selPeriodicidad').value = 'unico';
    }
}
function habilitarCamposContrato(habilitar) {
    const campos = ['selCliente', 'selTipoItem', 'selServicio', 'selProducto', 'txtMonto', 'selPeriodicidad', 'txtDiaCorte', 'txtFechaInicio', 'selEstadoContrato'];
    campos.forEach(id => {
        document.getElementById(id).disabled = !habilitar;
    });
    document.getElementById('btnGuardarContrato').style.display = habilitar ? 'inline-block' : 'none';
}

function prepararModalCrearContrato() {
    document.getElementById('formContrato').reset();
    document.getElementById('contrato_id').value = '';
    document.getElementById('modalContratoTitulo').innerText = 'Nuevo Contrato';
    alternarSelectorItem();
    habilitarCamposContrato(true);
    modalContratoBootstrap.show();
}

function prepararModalEditarContrato(contrato) {
    document.getElementById('contrato_id').value = contrato.contrato_id;
    document.getElementById('selCliente').value = contrato.cliente_id;
    document.getElementById('selTipoItem').value = contrato.tipo_item || 'servicio';
    alternarSelectorItem();

    if (contrato.tipo_item === 'producto') {
        document.getElementById('selProducto').value = contrato.producto_id || '';
    } else {
        document.getElementById('selServicio').value = contrato.servicio_id || '';
    }

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