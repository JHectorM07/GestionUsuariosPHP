<?php
// Instanciamos el controlador y obtenemos la lista de clientes para la tabla
require_once '../app/controladores/ClientesController.php';
$clientesController = new ClientesController();
$clientes = $clientesController->listar(); 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Clientes</h2>
</div>

<!-- TABLA DE LISTADO -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)): ?>
                        <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= $c['cliente_id'] ?></td>
                            <td><strong><?= htmlspecialchars($c['cliente']) ?></strong></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td>
                                <span class="badge <?= $c['estado'] === 'activo' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= ucfirst($c['estado']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Icono Crear Nuevo Cliente -->
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm me-1" 
                                        title="Nuevo Cliente"
                                        onclick="prepararModalCrear()">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                
                                <!-- Icono Editar -->
                                <button type="button" 
                                        class="btn btn-outline-warning btn-sm me-1" 
                                        title="Editar Cliente"
                                        onclick='prepararModalEditar(<?= json_encode($c) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <!-- Icono Eliminar -->
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        title="Eliminar Cliente"
                                        onclick="eliminarCliente(<?= $c['cliente_id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                No hay clientes registrados.
                            </td>
                            <td class="text-center">
                                <!-- Botón de crear disponible incluso cuando la tabla está vacía -->
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm" 
                                        title="Nuevo Cliente"
                                        onclick="prepararModalCrear()">
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

<!-- MODAL ÚNICO DE CLIENTES (Crear y Editar) -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formCliente">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitulo">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- ID Oculto: Si está vacío es CREAR, si tiene valor es ACTUALIZAR -->
                    <input type="hidden" name="id" id="cliente_id" value="">

                    <div class="mb-3">
                        <label for="txtCliente" class="form-label">Nombre del Cliente</label>
                        <input type="text" name="cliente" id="txtCliente" class="form-control" required placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="mb-3">
                        <label for="txtEmail" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" id="txtEmail" class="form-control" required placeholder="correo@ejemplo.com">
                    </div>

                    <div class="mb-3">
                        <label for="txtTelefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="txtTelefono" class="form-control" placeholder="Ej. 555-1234">
                    </div>

                    <div class="mb-3">
                        <label for="txtEstado" class="form-label">Estado</label>
                        <select name="estado" id="txtEstado" class="form-select">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>