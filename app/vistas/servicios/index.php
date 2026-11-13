<?php
require_once '../app/controladores/ServiciosController.php';

$serviciosController = new ServiciosController();
$servicios = $serviciosController->listar();

// Mensaje flash dejado por el controlador
$mensaje = $_SESSION['mensaje_servicios'] ?? null;
unset($_SESSION['mensaje_servicios']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Servicios</h2>
    <button type="button" class="btn btn-success btn-sm" onclick="prepararModalCrearServicio()">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Servicio
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
                        <th>Categoría</th>
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th>Precio Base</th>
                        <th>Periodicidad</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($servicios)): ?>
                        <?php foreach ($servicios as $srv): ?>
                        <tr>
                            <td><?= (int) $srv['servicio_id'] ?></td>
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    Categoría #<?= (int) $srv['categoria_id'] ?>
                                </span>
                            </td>
                            <td><strong><?= htmlspecialchars($srv['servicio']) ?></strong></td>
                            <td><?= htmlspecialchars($srv['descripcion'] ?? '') ?></td>
                            <td>$<?= number_format((float) ($srv['precio_base'] ?? 0), 2) ?></td>
                            <td class="text-capitalize">
                                <?php $per = !empty($srv['periodicidad']) ? $srv['periodicidad'] : 'unico'; ?>
                                <?= $per === 'unico' ? 'Pago Único' : htmlspecialchars($per) ?>
                            </td>
                            <td class="text-center">
                                <!-- Crear -->
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm me-1" 
                                        title="Nuevo Servicio"
                                        onclick="prepararModalCrearServicio()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>

                                <!-- Ver -->
                                <button type="button" 
                                        class="btn btn-outline-info btn-sm me-1" 
                                        title="Ver Detalles"
                                        onclick='prepararModalVerServicio(<?= json_encode($srv) ?>)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <!-- Editar -->
                                <button type="button" 
                                        class="btn btn-outline-warning btn-sm me-1" 
                                        title="Editar Servicio"
                                        onclick='prepararModalEditarServicio(<?= json_encode($srv) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <!-- Eliminar -->
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        title="Eliminar Servicio"
                                        onclick="eliminarServicio(<?= (int) $srv['servicio_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No hay servicios registrados.
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm" 
                                        title="Nuevo Servicio"
                                        onclick="prepararModalCrearServicio()">
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

<!-- MODAL ÚNICO DE SERVICIOS (Crear, Ver y Editar) -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formServicio">
                <input type="hidden" name="id" id="servicio_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalServicioTitulo">Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="txtCategoriaId" class="form-label">ID Categoría</label>
                            <input type="number" min="1" name="categoria_id" id="txtCategoriaId" class="form-control" required placeholder="1">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="txtServicio" class="form-label">Nombre del Servicio</label>
                            <input type="text" name="servicio" id="txtServicio" class="form-control" required placeholder="Ej. Hosting Web">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="txtDescripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="txtDescripcion" class="form-control" rows="2" placeholder="Detalles del servicio..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="txtPrecioBase" class="form-label">Precio Base ($)</label>
                            <input type="number" step="0.01" min="0.01" name="precio_base" id="txtPrecioBase" class="form-control" required placeholder="0.00">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="selPeriodicidadServicio" class="form-label">Periodicidad</label>
                            <select name="periodicidad" id="selPeriodicidadServicio" class="form-select">
                                <option value="mensual">Mensual</option>
                                <option value="anual">Anual</option>
                                <option value="unico">Pago Único</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarServicio">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalServicioBootstrap = null;

document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('modalServicio');
    if (modalElement) {
        modalServicioBootstrap = new bootstrap.Modal(modalElement);
    }

    const formServicio = document.getElementById('formServicio');
    if (formServicio) {
        formServicio.addEventListener('submit', guardarOActualizarServicio);
    }
});

function habilitarCamposServicio(habilitar) {
    const campos = ['txtCategoriaId', 'txtServicio', 'txtDescripcion', 'txtPrecioBase', 'selPeriodicidadServicio'];
    campos.forEach(id => {
        document.getElementById(id).disabled = !habilitar;
    });
    document.getElementById('btnGuardarServicio').style.display = habilitar ? 'inline-block' : 'none';
}

function prepararModalCrearServicio() {
    document.getElementById('formServicio').reset();
    document.getElementById('servicio_id').value = '';
    document.getElementById('modalServicioTitulo').innerText = 'Nuevo Servicio';
    habilitarCamposServicio(true);
    modalServicioBootstrap.show();
}

function prepararModalEditarServicio(servicio) {
    document.getElementById('servicio_id').value = servicio.servicio_id;
    document.getElementById('txtCategoriaId').value = servicio.categoria_id;
    document.getElementById('txtServicio').value = servicio.servicio;
    document.getElementById('txtDescripcion').value = servicio.descripcion || '';
    document.getElementById('txtPrecioBase').value = servicio.precio_base;
    document.getElementById('selPeriodicidadServicio').value = servicio.periodicidad || 'unico';

    document.getElementById('modalServicioTitulo').innerText = 'Editar Servicio #' + servicio.servicio_id;
    habilitarCamposServicio(true);
    modalServicioBootstrap.show();
}

function prepararModalVerServicio(servicio) {
    prepararModalEditarServicio(servicio);
    document.getElementById('modalServicioTitulo').innerText = 'Detalles del Servicio #' + servicio.servicio_id;
    habilitarCamposServicio(false);
}

async function guardarOActualizarServicio(e) {
    e.preventDefault();

    const id = document.getElementById('servicio_id').value;
    const datos = new FormData(e.target);
    const accion = id ? 'actualizar' : 'guardar';
    const url = `index.php?controlador=servicios&accion=${accion}`;

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            modalServicioBootstrap.hide();
            location.reload();
        } else {
            alert(resultado.mensaje || 'Ocurrió un error al procesar el servicio.');
        }
    } catch (error) {
        console.error('Error en la petición AJAX:', error);
    }
}

async function eliminarServicio(id) {
    if (!confirm('¿Está seguro de que desea eliminar este servicio?')) return;

    const datos = new FormData();
    datos.append('id', id);

    try {
        const respuesta = await fetch('index.php?controlador=servicios&accion=borrar', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            location.reload();
        } else {
            alert(resultado.mensaje || 'No se pudo eliminar el servicio.');
        }
    } catch (error) {
        console.error('Error en la eliminación:', error);
    }
}
</script>