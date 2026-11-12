// Instancia global del modal
let modalClienteBootstrap = null;

document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('modalCliente');
    if (modalElement) {
        modalClienteBootstrap = new bootstrap.Modal(modalElement);
    }

    // Escuchar el evento submit del formulario dentro del modal
    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', guardarOActualizarCliente);
    }
});

// Abrimos el modal limpio para CREAR
function prepararModalCrear() {
    document.getElementById('formCliente').reset();
    document.getElementById('cliente_id').value = ''; 
    document.getElementById('modalTitulo').innerText = 'Nuevo Cliente';
    modalClienteBootstrap.show();
}

// Abrimos el modal lleno con los datos para EDITAR
function prepararModalEditar(datos) {
    document.getElementById('cliente_id').value = datos.cliente_id;
    document.getElementById('txtCliente').value = datos.cliente;
    document.getElementById('txtEmail').value   = datos.email;
    document.getElementById('txtTelefono').value = datos.telefono;
    document.getElementById('txtEstado').value  = datos.estado;
    document.getElementById('modalTitulo').innerText = 'Editar Cliente';
    modalClienteBootstrap.show();
}

// Enviamos datos por AJAX (Guardar y Actualizar)
async function guardarOActualizarCliente(e) {
    e.preventDefault();

    const id = document.getElementById('cliente_id').value;
    const datos = new FormData(e.target);

    // Determina dinámicamente si es crear o actualizar
    const accion = id ? 'actualizar' : 'guardar';
    const url = `index.php?controlador=clientes&accion=${accion}`;

    try {
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            modalClienteBootstrap.hide();
            location.reload(); 
        } else {
            alert(resultado.mensaje || 'Ocurrió un error al procesar la solicitud.');
        }
    } catch (error) {
        console.error('Error en la petición AJAX:', error);
    }
}

// Eliminar registro por AJAX
async function eliminarCliente(id) {
    if (!confirm('¿Está seguro de que desea eliminar este cliente?')) return;

    const datos = new FormData();
    datos.append('id', id);

    try {
        const respuesta = await fetch('index.php?controlador=clientes&accion=borrar', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (resultado.exito) {
            location.reload();
        } else {
            alert(resultado.mensaje || 'No se pudo eliminar el cliente.');
        }
    } catch (error) {
        console.error('Error en la eliminación:', error);
    }
}