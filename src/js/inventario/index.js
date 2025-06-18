import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormInventario = document.getElementById('FormInventario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('SeccionTablaInventario');
const inv_modelo = document.getElementById('inv_modelo');
const inv_marca = document.getElementById('inv_marca');
const inv_precio = document.getElementById('inv_precio');
const inv_stock = document.getElementById('inv_stock');
const inv_estado = document.getElementById('inv_estado');

// Validaciones
const ValidarModelo = () => {
    const modelo = inv_modelo.value.trim();
    
    if (modelo.length < 3) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Modelo muy corto",
            text: "El modelo debe tener al menos 3 caracteres",
            showConfirmButton: true,
        });
        inv_modelo.classList.remove('is-valid');
        inv_modelo.classList.add('is-invalid');
        return false;
    } else if (modelo.length > 100) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Modelo muy largo",
            text: "El modelo no puede exceder 100 caracteres",
            showConfirmButton: true,
        });
        inv_modelo.classList.remove('is-valid');
        inv_modelo.classList.add('is-invalid');
        return false;
    } else {
        inv_modelo.classList.remove('is-invalid');
        inv_modelo.classList.add('is-valid');
        return true;
    }
}

const ValidarMarca = () => {
    if (inv_marca.value.trim() === '') {
        inv_marca.classList.remove('is-valid');
        inv_marca.classList.add('is-invalid');
        return false;
    } else {
        inv_marca.classList.remove('is-invalid');
        inv_marca.classList.add('is-valid');
        return true;
    }
}

const ValidarPrecio = () => {
    const precio = parseFloat(inv_precio.value);
    
    if (isNaN(precio) || precio < 0) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Precio inválido",
            text: "El precio debe ser un número mayor o igual a 0",
            showConfirmButton: true,
        });
        inv_precio.classList.remove('is-valid');
        inv_precio.classList.add('is-invalid');
        return false;
    } else {
        inv_precio.classList.remove('is-invalid');
        inv_precio.classList.add('is-valid');
        return true;
    }
}

const ValidarStock = () => {
    const stock = parseInt(inv_stock.value);
    
    if (isNaN(stock) || stock < 0) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Stock inválido",
            text: "El stock debe ser un número mayor o igual a 0",
            showConfirmButton: true,
        });
        inv_stock.classList.remove('is-valid');
        inv_stock.classList.add('is-invalid');
        return false;
    } else {
        inv_stock.classList.remove('is-invalid');
        inv_stock.classList.add('is-valid');
        actualizarEstado();
        return true;
    }
}

// Función para actualizar el estado automáticamente
const actualizarEstado = () => {
    const stock = parseInt(inv_stock.value);
    
    if (!isNaN(stock)) {
        if (stock > 0) {
            inv_estado.value = 'disponible';
            inv_estado.style.color = '#198754';
        } else {
            inv_estado.value = 'no disponible';
            inv_estado.style.color = '#dc3545';
        }
    } else {
        inv_estado.value = '';
        inv_estado.style.color = '#6c757d';
    }
}

// Validación completa del formulario
const ValidarFormularioCompleto = () => {
    let esValido = true;
    
    if (!ValidarModelo()) esValido = false;
    if (!ValidarMarca()) esValido = false;
    if (!ValidarPrecio()) esValido = false;
    if (!ValidarStock()) esValido = false;
    
    return esValido;
}

const datatable = new DataTable('#TableInventario', {
    dom: `<"row mt-3 justify-content-between" <"col" l> <"col" B> <"col-3" f>>t<"row mt-3 justify-content-between" <"col-md-3 d-flex align-items-center" i> <"col-md-8 d-flex justify-content-end" p>>`,
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: 'inv_id', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Modelo', data: 'inv_modelo' },
        { title: 'Marca', data: 'marca_nombre' },
        { 
            title: 'Precio', 
            data: 'inv_precio',
            render: (data) => `Q ${parseFloat(data).toFixed(2)}`
        },
        { 
            title: 'Stock', 
            data: 'inv_stock'
        },
        { 
            title: 'Estado', 
            data: 'inv_estado',
            render: (data, type, row) => {
                // Determinar estado basado en el stock actual
                const stock = parseInt(row.inv_stock);
                return stock > 0 ? 'Disponible' : 'No Disponible';
            }
        },
        {
            title: 'Acciones',
            data: 'inv_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-modelo="${row.inv_modelo}"  
                         data-marca="${row.inv_marca}"  
                         data-precio="${row.inv_precio}"  
                         data-stock="${row.inv_stock}">   
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const MostrarRegistros = () => {
    const estaOculto = SeccionTabla.style.display === 'none';
    if (estaOculto) {
        SeccionTabla.style.display = 'block';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        BuscarInventario(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const limpiarTodo = () => {
    FormInventario.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    FormInventario.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
    });
    inv_estado.value = '';
    inv_estado.style.color = '#6c757d';
}

const BuscarInventario = async (mostrarMensaje = false) => {
    const url = '/proyecto_uno/inventario/buscarAPI';
    console.log('URL completa:', window.location.origin + url);
    const config = { method: 'GET' };
    
    try {
        console.log('Realizando petición a:', url);
        const respuesta = await fetch(url, config);
        console.log('Respuesta HTTP status:', respuesta.status);
        
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }
        
        const datos = await respuesta.json();
        console.log('Datos recibidos completos:', datos);
        
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const inventario = data || [];
            console.log('Inventario a cargar:', inventario);
            console.log('Cantidad de registros:', inventario.length);
            
            datatable.clear().draw();
            
            if (inventario.length > 0) {
                datatable.rows.add(inventario).draw();
            }
            
            if (mostrarMensaje) {
                await Swal.fire({ 
                    position: "center", 
                    icon: "success", 
                    title: "¡Inventario cargado!", 
                    text: `Se cargaron ${inventario.length} producto(s) correctamente`, 
                    showConfirmButton: false, 
                    timer: 1500 
                });
            }
        } else {
            console.error('Error del servidor:', mensaje);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.error('Error completo:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor: " + error.message,
            showConfirmButton: true,
        });
    }
}

const GuardarInventario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!ValidarFormularioCompleto()) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe corregir todos los errores antes de continuar",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormInventario);
    const url = '/proyecto_uno/inventario/guardarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Producto guardado exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            if (SeccionTabla.style.display !== 'none') {
                await BuscarInventario(false);
            }
        } else {
            await Swal.fire({ 
                position: "center", 
                icon: "error", 
                title: "Error al guardar", 
                text: mensaje, 
                showConfirmButton: true 
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    BtnGuardar.disabled = false;
}

const ModificarInventario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!ValidarFormularioCompleto()) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe corregir todos los errores antes de continuar",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormInventario);
    const url = '/proyecto_uno/inventario/modificarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Producto modificado exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            if (SeccionTabla.style.display !== 'none') {
                await BuscarInventario(false);
            }
        } else {
            await Swal.fire({ 
                position: "center", 
                icon: "error", 
                title: "Error al modificar", 
                text: mensaje, 
                showConfirmButton: true 
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor",
            showConfirmButton: true,
        });
    }
    BtnModificar.disabled = false;
}

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;
    
    document.getElementById('inv_id').value = datos.id;
    document.getElementById('inv_modelo').value = datos.modelo;
    document.getElementById('inv_marca').value = datos.marca;
    document.getElementById('inv_precio').value = datos.precio;
    document.getElementById('inv_stock').value = datos.stock;
    
    actualizarEstado();
    
    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    
    window.scrollTo({ top: 0 });
}

const EliminarInventario = async (e) => {
    const idInventario = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este producto',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_uno/inventario/eliminarAPI?id=${idInventario}`;
        const config = { method: 'GET' };

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                await BuscarInventario(false);
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.log(error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
}

const GestionarStock = async (e) => {
    const datos = e.currentTarget.dataset;
    
    const { value: nuevaCantidad } = await Swal.fire({
        title: 'Gestionar Stock',
        html: `
            <p><strong>Producto:</strong> ${datos.modelo}</p>
            <p><strong>Stock actual:</strong> <span class="badge bg-primary">${datos.stock}</span></p>
            <label for="nueva-cantidad" class="form-label">Nueva cantidad:</label>
        `,
        input: 'number',
        inputValue: datos.stock,
        inputAttributes: {
            min: 0,
            step: 1,
            id: 'nueva-cantidad',
            class: 'form-control'
        },
        showCancelButton: true,
        confirmButtonText: 'Actualizar Stock',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value || value < 0) {
                return 'Debe ingresar una cantidad válida (mayor o igual a 0)';
            }
        }
    });

    if (nuevaCantidad !== undefined) {
        const body = new FormData();
        body.append('inv_id', datos.id);
        body.append('nueva_cantidad', nuevaCantidad);

        const url = '/proyecto_uno/inventario/actualizarStockAPI';
        const config = { method: 'POST', body };

        try {
            const respuesta = await fetch(url, config);
            const datosRespuesta = await respuesta.json();
            const { codigo, mensaje } = datosRespuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Stock actualizado",
                    text: mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                await BuscarInventario(false);
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.log(error);
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo conectar con el servidor",
                showConfirmButton: true,
            });
        }
    }
}

// Event Listeners
FormInventario.addEventListener('submit', GuardarInventario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarInventario);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);
datatable.on('click', '.modificar', llenarFormulario);
datatable.on('click', '.eliminar', EliminarInventario);

// Validaciones en tiempo real
inv_modelo.addEventListener('blur', ValidarModelo);
inv_marca.addEventListener('change', ValidarMarca);
inv_precio.addEventListener('blur', ValidarPrecio);
inv_stock.addEventListener('input', ValidarStock);
inv_stock.addEventListener('change', actualizarEstado);

// Cargar datos iniciales al final
console.log('Iniciando carga de inventario...');
BuscarInventario(false);