<?php
require_once '../app/controladores/ProductosController.php';

$productosController = new ProductosController();
$productos = $productosController->listar();

// Mensaje flash dejado por el controlador
$mensaje = $_SESSION['mensaje_productos'] ?? null;
unset($_SESSION['mensaje_productos']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Productos</h2>
    <button type="button" class="btn btn-success btn-sm" onclick="prepararModalCrearProducto()">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
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
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Precio Unitario</th>
                        <th>Stock / Cantidad</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td><?= (int) $prod['producto_id'] ?></td>
                            <td>
                                <span class="badge bg-light text-dark border text-capitalize">
                                    Categoría #<?= (int) $prod['categoria_id'] ?>
                                </span>
                            </td>
                            <td><strong><?= htmlspecialchars($prod['producto']) ?></strong></td>
                            <td><?= htmlspecialchars($prod['descripcion'] ?? '') ?></td>
                            <td>$<?= number_format((float) ($prod['precio_unitario'] ?? 0), 2) ?></td>
                            <td>
                                <?php $cant = (int) ($prod['cantidad'] ?? 0); ?>
                                <span class="badge <?= $cant > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $cant ?> uds.
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Crear -->
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm me-1" 
                                        title="Nuevo Producto"
                                        onclick="prepararModalCrearProducto()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>

                                <!-- Ver -->
                                <button type="button" 
                                        class="btn btn-outline-info btn-sm me-1" 
                                        title="Ver Detalles"
                                        onclick='prepararModalVerProducto(<?= json_encode($prod) ?>)'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <!-- Editar -->
                                <button type="button" 
                                        class="btn btn-outline-warning btn-sm me-1" 
                                        title="Editar Producto"
                                        onclick='prepararModalEditarProducto(<?= json_encode($prod) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <!-- Eliminar -->
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        title="Eliminar Producto"
                                        onclick="eliminarProducto(<?= (int) $prod['producto_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No hay productos registrados.
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm" 
                                        title="Nuevo Producto"
                                        onclick="prepararModalCrearProducto()">
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

<!-- MODAL ÚNICO DE PRODUCTOS (Crear, Ver y Editar) -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formProducto">
                <input type="hidden" name="id" id="producto_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductoTitulo">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="txtCategoriaProductoId" class="form-label">ID Categoría</label>
                            <input type="number" min="1" name="categoria_id" id="txtCategoriaProductoId" class="form-control" required placeholder="1">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="txtProducto" class="form-label">Nombre del Producto</label>
                            <input type="text" name="producto" id="txtProducto" class="form-control" required placeholder="Ej. Router">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="txtDescripcionProducto" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="txtDescripcionProducto" class="form-control" rows="2" placeholder="Ej. Router para oficina"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="txtPrecioUnitario" class="form-label">Precio Unitario ($)</label>
                            <input type="number" step="0.01" min="0.01" name="precio_unitario" id="txtPrecioUnitario" class="form-control" required placeholder="0.00">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="txtCantidadProducto" class="form-label">Cantidad (Stock)</label>
                            <input type="number" min="0" name="cantidad" id="txtCantidadProducto" class="form-control" value="0" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProducto">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalProductoBootstrap = null;

document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('modalProducto');
    if (modalElement) {
        modalProductoBootstrap = new bootstrap.Modal(modalElement);
    }

    const formProducto = document.getElementById('formProducto');
    if (formProducto) {
        formProducto.addEventListener('submit', guardarOActualizarProducto);
    }
});

function habilitarCamposProducto(habilitar) {
    const campos = ['txtCategoriaProductoId', 'txtProducto', 'txtDescripcionProducto', 'txtPrecioUnitario', 'txtCantidadProducto'];
    campos.forEach(id => {
        document.getElementById(id).disabled = !habilitar;
    });
    document.getElementById('btnGuardarProducto').style.display = habilitar ? 'inline-block' : 'none';
}

function prepararModalCrearProducto() {
    document.getElementById('formProducto').reset();
    document.getElementById('producto_id').value = '';
    document.getElementById('modalProductoTitulo').innerText = 'Nuevo Producto';
    habilitarCamposProducto(true);
    modalProductoBootstrap.show();
}

function prepararModalEditarProducto(prod) {
    document.getElementById('producto_id').value = prod.producto_id;
    document.getElementById('txtCategoriaProductoId').value = prod.categoria_id;
    document.getElementById('txtProducto').value = prod.producto;
    document.getElementById('txtDescripcionProducto').value = prod.descripcion || '';
    document.getElementById('txtPrecioUnitario').value = prod.precio_unitario;
    document.getElementById('txtCantidadProducto').value = prod.cantidad ?? 0;

    document.getElementById('modalProductoTitulo').innerText = 'Editar Producto #' + prod.producto_id;
    habilitarCamposProducto(true);
    modalProductoBootstrap.show();
}

function prepararModalVerProducto(prod) {
    prepararModalEditarProducto(prod);
    document.getElementById('modalProductoTitulo').innerText = 'Detalles del Producto #' + prod.producto_id;
    habilitarCamposProducto(false);
}

async function guardarOActualizarProducto(e) {
    e.preventDefault();

    const id = document.getElementById('producto_id').value;
    const datos = new FormData(e.target);
    const accion = id ? 'actualizar' : 'guardar';
    const url = `index.php?controlador=productos&accion=${accion}`;

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            modalProductoBootstrap.hide();
            location.reload();
        } else {
            alert(resultado.mensaje || 'Ocurrió un error al procesar el producto.');
        }
    } catch (error) {
        console.error('Error en la petición AJAX:', error);
    }
}

async function eliminarProducto(id) {
    if (!confirm('¿Está seguro de que desea eliminar este producto?')) return;

    const datos = new FormData();
    datos.append('id', id);

    try {
        const respuesta = await fetch('index.php?controlador=productos&accion=borrar', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            location.reload();
        } else {
            alert(resultado.mensaje || 'No se pudo eliminar el producto.');
        }
    } catch (error) {
        console.error('Error en la eliminación:', error);
    }
}
</script>