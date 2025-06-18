import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAsignaciones = document.getElementById('FormAsignaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('SeccionTablaAsignaciones');
const asig_usuario = document.getElementById('asig_usuario');
const asig_aplicacion = document.getElementById('asig_aplicacion');
const asig_permisos = document.getElementById('asig_permisos');
const asig_usuario_asignador = document.getElementById('asig_usuario_asignador');
const asig_motivo = document.getElementById('asig_motivo');

// Validación de campos obligatorios
const ValidarUsuario = () => {
    if (asig_usuario.value.trim() === '') {
        asig_usuario.classList.remove('is-valid');
        asig_usuario.classList.add('is-invalid');
        return false;
    } else {
        asig_usuario.classList.remove('is-invalid');
        asig_usuario.classList.add('is-valid');
        return true;
    }
}

// Función para habilitar/deshabilitar permisos según aplicación
const ValidarAplicacion = async () => {
    if (asig_aplicacion.value.trim() === '') {
        asig_aplicacion.classList.remove('is-valid');
        asig_aplicacion.classList.add('is-invalid');
        // Deshabilitar y limpiar permisos
        asig_permisos.disabled = true;
        asig_permisos.value = '';
        asig_permisos.classList.remove('is-valid', 'is-invalid');
        // Limpiar opciones de permisos
        asig_permisos.innerHTML = '<option value="" selected disabled>Primero seleccione una aplicación...</option>';
        return false;
    } else {
        asig_aplicacion.classList.remove('is-invalid');
        asig_aplicacion.classList.add('is-valid');
        // Cargar permisos de la aplicación seleccionada
        await cargarPermisosPorAplicacion(asig_aplicacion.value);
        return true;
    }
}

// Nueva función para cargar permisos por aplicación
const cargarPermisosPorAplicacion = async (aplicacionId) => {
    try {
        // Mostrar loading en el select de permisos
        asig_permisos.innerHTML = '<option value="" selected disabled>Cargando permisos...</option>';
        asig_permisos.disabled = true;

        const url = `/proyecto_uno/asignacion/obtenerPermisosPorAplicacionAPI?aplicacion_id=${aplicacionId}`;
        const config = { method: 'GET' };
        
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            // Limpiar select de permisos
            asig_permisos.innerHTML = '<option value="" selected disabled>Seleccione un permiso...</option>';
            
            // Agregar permisos de la aplicación
            if (data && data.length > 0) {
                data.forEach(permiso => {
                    const option = document.createElement('option');
                    option.value = permiso.per_id;
                    option.textContent = permiso.per_nombre_permiso;
                    asig_permisos.appendChild(option);
                });
                asig_permisos.disabled = false;
            } else {
                asig_permisos.innerHTML = '<option value="" selected disabled>No hay permisos disponibles para esta aplicación</option>';
            }
        } else {
            asig_permisos.innerHTML = '<option value="" selected disabled>Error al cargar permisos</option>';
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
        asig_permisos.innerHTML = '<option value="" selected disabled>Error al cargar permisos</option>';
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo cargar los permisos",
            showConfirmButton: true,
        });
    }
}

const ValidarPermisos = () => {
    if (asig_permisos.value.trim() === '') {
        asig_permisos.classList.remove('is-valid');
        asig_permisos.classList.add('is-invalid');
        return false;
    } else {
        asig_permisos.classList.remove('is-invalid');
        asig_permisos.classList.add('is-valid');
        return true;
    }
}

const ValidarUsuarioAsignador = () => {
    if (asig_usuario_asignador.value.trim() === '') {
        asig_usuario_asignador.classList.remove('is-valid');
        asig_usuario_asignador.classList.add('is-invalid');
        return false;
    } else {
        asig_usuario_asignador.classList.remove('is-invalid');
        asig_usuario_asignador.classList.add('is-valid');
        return true;
    }
}

const ValidarMotivo = () => {
    const motivo = asig_motivo.value.trim();
    
    if (motivo.length < 5) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Motivo muy corto",
            text: "El motivo debe tener al menos 5 caracteres",
            showConfirmButton: true,
        });
        asig_motivo.classList.remove('is-valid');
        asig_motivo.classList.add('is-invalid');
        return false;
    } else if (motivo.length > 250) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Motivo muy largo",
            text: "El motivo no puede exceder 250 caracteres",
            showConfirmButton: true,
        });
        asig_motivo.classList.remove('is-valid');
        asig_motivo.classList.add('is-invalid');
        return false;
    } else {
        asig_motivo.classList.remove('is-invalid');
        asig_motivo.classList.add('is-valid');
        return true;
    }
}

// Validación completa del formulario
const ValidarFormularioCompleto = () => {
    let esValido = true;
    
    if (!ValidarUsuario()) esValido = false;
    if (!ValidarAplicacion()) esValido = false;
    if (!ValidarPermisos()) esValido = false;
    if (!ValidarUsuarioAsignador()) esValido = false;
    if (!ValidarMotivo()) esValido = false;
    
    return esValido;
}

const datatable = new DataTable('#TableAsignaciones', {
    dom: `<"row mt-3 justify-content-between" <"col" l> <"col" B> <"col-3" f>>t<"row mt-3 justify-content-between" <"col-md-3 d-flex align-items-center" i> <"col-md-8 d-flex justify-content-end" p>>`,
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: 'asig_id', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Usuario', data: 'usuario_nombre' },
        { title: 'Aplicación', data: 'aplicacion_nombre' },
        { title: 'Permiso', data: 'permiso_nombre' },
        { title: 'Motivo', data: 'asig_motivo' },
        { title: 'Fecha Asignación', data: 'asig_fecha' },
        {
            title: 'Acciones',
            data: 'asig_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-usuario="${row.asig_usuario}"  
                         data-aplicacion="${row.asig_aplicacion}"  
                         data-permisos="${row.asig_permisos}"  
                         data-asignador="${row.asig_usuario_asignador}"  
                         data-motivo="${row.asig_motivo}">   
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                     <button class='btn btn-info fin-permiso mx-1' 
                         data-id="${data}">
                        <i class="bi bi-clock-history me-1"></i>Fin Permiso
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
        BuscarAsignaciones(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const limpiarTodo = () => {
    FormAsignaciones.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    FormAsignaciones.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
    });
    // Deshabilitar permisos al limpiar y restaurar opciones originales
    asig_permisos.disabled = true;
    asig_permisos.innerHTML = '<option value="" selected disabled>Primero seleccione una aplicación...</option>';
}

const BuscarAsignaciones = async (mostrarMensaje = false) => {
    const url = '/proyecto_uno/asignacion/buscarAPI';
    const config = { method: 'GET' };
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        if (codigo == 1) {
            const asignaciones = data || [];
            datatable.clear().draw();
            datatable.rows.add(asignaciones).draw();
            if (mostrarMensaje) {
                await Swal.fire({ 
                    position: "center", 
                    icon: "success", 
                    title: "¡Asignaciones cargadas!", 
                    text: `Se cargaron ${asignaciones.length} asignación(es) correctamente`, 
                    showConfirmButton: false, 
                    timer: 1500 
                });
            }
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

const GuardarAsignacion = async (event) => {
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

    const body = new FormData(FormAsignaciones);
    const url = '/proyecto_uno/asignacion/guardarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Asignación guardada exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            await BuscarAsignaciones(false);
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

const ModificarAsignacion = async (event) => {
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

    const body = new FormData(FormAsignaciones);
    const url = '/proyecto_uno/asignacion/modificarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Asignación modificada exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            await BuscarAsignaciones(false);
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

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;
    
    document.getElementById('asig_id').value = datos.id;
    document.getElementById('asig_usuario').value = datos.usuario;
    document.getElementById('asig_aplicacion').value = datos.aplicacion;
    
    // Cargar permisos de la aplicación antes de seleccionar el permiso
    await cargarPermisosPorAplicacion(datos.aplicacion);
    
    // Ahora seleccionar el permiso específico
    document.getElementById('asig_permisos').value = datos.permisos;
    document.getElementById('asig_usuario_asignador').value = datos.asignador;
    document.getElementById('asig_motivo').value = datos.motivo;
    
    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    
    window.scrollTo({ top: 0 });
}

const EliminarAsignacion = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_uno/asignacion/eliminarAPI?id=${idAsignacion}`;
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
                BuscarAsignaciones(false);
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

const FinPermiso = async (e) => {
    const idAsignacion = e.currentTarget.dataset.id;

    const AlertaConfirmarFin = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Finalizar permiso?",
        text: 'Esta acción marcará el fin del permiso del usuario',
        showConfirmButton: true,
        confirmButtonText: 'Si, Finalizar Permiso',
        confirmButtonColor: '#17a2b8',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarFin.isConfirmed) {
        const url = `/proyecto_uno/asignacion/finPermisoAPI?id=${idAsignacion}`;
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
                BuscarAsignaciones(false);
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
BuscarAsignaciones(false);
FormAsignaciones.addEventListener('submit', GuardarAsignacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAsignacion);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);
datatable.on('click', '.modificar', llenarFormulario);
datatable.on('click', '.eliminar', EliminarAsignacion);
datatable.on('click', '.fin-permiso', FinPermiso);

// Validaciones en tiempo real
asig_usuario.addEventListener('change', ValidarUsuario);
asig_aplicacion.addEventListener('change', ValidarAplicacion);
asig_permisos.addEventListener('change', ValidarPermisos);
asig_usuario_asignador.addEventListener('change', ValidarUsuarioAsignador);
asig_motivo.addEventListener('blur', ValidarMotivo);

// Deshabilitar permisos al cargar la página
asig_permisos.disabled = true;