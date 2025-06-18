import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('SeccionTablaUsuarios');
const us_telefono = document.getElementById('us_telefono');
const us_dpi = document.getElementById('us_dpi');
const us_contrasenia = document.getElementById('us_contrasenia');
const us_confirmar_contra = document.getElementById('us_confirmar_contra');
const us_correo = document.getElementById('us_correo');
const us_nombres = document.getElementById('us_nombres');
const us_apellidos = document.getElementById('us_apellidos');

// Validación de teléfono
const ValidarTelefono = () => {
    const telefono = us_telefono.value.trim();
    
    if (telefono.length < 1) {
        us_telefono.classList.remove('is-valid', 'is-invalid');
        return true;
    }
    
    if (telefono.length !== 8 || !/^\d+$/.test(telefono)) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Teléfono inválido",
            text: "El teléfono debe tener exactamente 8 dígitos",
            showConfirmButton: true,
        });
        us_telefono.classList.remove('is-valid');
        us_telefono.classList.add('is-invalid');
        return false;
    } else {
        us_telefono.classList.remove('is-invalid');
        us_telefono.classList.add('is-valid');
        return true;
    }
}

// Validación de DPI
const ValidarDPI = () => {
    const dpi = us_dpi.value.trim();
    
    if (dpi.length < 1) {
        us_dpi.classList.remove('is-valid', 'is-invalid');
        return true;
    }
    
    if (dpi.length !== 13 || !/^\d+$/.test(dpi)) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "DPI inválido",
            text: "El DPI debe tener exactamente 13 dígitos",
            showConfirmButton: true,
        });
        us_dpi.classList.remove('is-valid');
        us_dpi.classList.add('is-invalid');
        return false;
    } else {
        us_dpi.classList.remove('is-invalid');
        us_dpi.classList.add('is-valid');
        return true;
    }
}

// Validación de correo electrónico
const ValidarCorreo = () => {
    const correo = us_correo.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (correo.length < 1) {
        us_correo.classList.remove('is-valid', 'is-invalid');
        return true;
    }
    
    if (!emailRegex.test(correo)) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Correo inválido",
            text: "Por favor ingrese un correo electrónico válido",
            showConfirmButton: true,
        });
        us_correo.classList.remove('is-valid');
        us_correo.classList.add('is-invalid');
        return false;
    } else {
        us_correo.classList.remove('is-invalid');
        us_correo.classList.add('is-valid');
        return true;
    }
}

// Validación de nombres y apellidos
const ValidarNombres = () => {
    const nombres = us_nombres.value.trim();
    
    if (nombres.length < 2) {
        us_nombres.classList.remove('is-valid');
        us_nombres.classList.add('is-invalid');
        return false;
    } else if (nombres.length > 100) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Nombres muy largos",
            text: "Los nombres no pueden exceder 100 caracteres",
            showConfirmButton: true,
        });
        us_nombres.classList.remove('is-valid');
        us_nombres.classList.add('is-invalid');
        return false;
    } else {
        us_nombres.classList.remove('is-invalid');
        us_nombres.classList.add('is-valid');
        return true;
    }
}

const ValidarApellidos = () => {
    const apellidos = us_apellidos.value.trim();
    
    if (apellidos.length < 2) {
        us_apellidos.classList.remove('is-valid');
        us_apellidos.classList.add('is-invalid');
        return false;
    } else if (apellidos.length > 100) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Apellidos muy largos",
            text: "Los apellidos no pueden exceder 100 caracteres",
            showConfirmButton: true,
        });
        us_apellidos.classList.remove('is-valid');
        us_apellidos.classList.add('is-invalid');
        return false;
    } else {
        us_apellidos.classList.remove('is-invalid');
        us_apellidos.classList.add('is-valid');
        return true;
    }
}

// Validación de contraseñas
const ValidarContrasenia = () => {
    const contrasenia = us_contrasenia.value;
    
    if (contrasenia.length < 1) {
        us_contrasenia.classList.remove('is-valid', 'is-invalid');
        return true;
    }
    
    if (contrasenia.length < 6) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Contraseña muy corta",
            text: "La contraseña debe tener al menos 6 caracteres",
            showConfirmButton: true,
        });
        us_contrasenia.classList.remove('is-valid');
        us_contrasenia.classList.add('is-invalid');
        return false;
    } else {
        us_contrasenia.classList.remove('is-invalid');
        us_contrasenia.classList.add('is-valid');
        ValidarConfirmacionContrasenia(); // Revalidar confirmación
        return true;
    }
}

const ValidarConfirmacionContrasenia = () => {
    const contrasenia = us_contrasenia.value;
    const confirmacion = us_confirmar_contra.value;
    
    if (confirmacion.length < 1) {
        us_confirmar_contra.classList.remove('is-valid', 'is-invalid');
        return true;
    }
    
    if (contrasenia !== confirmacion) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Las contraseñas no coinciden",
            text: "La confirmación debe ser igual a la contraseña",
            showConfirmButton: true,
        });
        us_confirmar_contra.classList.remove('is-valid');
        us_confirmar_contra.classList.add('is-invalid');
        return false;
    } else {
        us_confirmar_contra.classList.remove('is-invalid');
        us_confirmar_contra.classList.add('is-valid');
        return true;
    }
}

// Validación completa del formulario
const ValidarFormularioCompleto = () => {
    let esValido = true;
    
    // Validar campos obligatorios
    if (!ValidarNombres()) esValido = false;
    if (!ValidarApellidos()) esValido = false;
    if (!ValidarCorreo()) esValido = false;
    if (!ValidarTelefono()) esValido = false;
    if (!ValidarDPI()) esValido = false;
    
    // Solo validar contraseñas si están visibles (modo crear)
    if (!document.getElementById('grupo_password').classList.contains('d-none')) {
        if (!ValidarContrasenia()) esValido = false;
        if (!ValidarConfirmacionContrasenia()) esValido = false;
    }
    
    // Sin validación de rol
    
    return esValido;
}

const datatable = new DataTable('#TableUsuarios', {
    dom: `<"row mt-3 justify-content-between" <"col" l> <"col" B> <"col-3" f>>t<"row mt-3 justify-content-between" <"col-md-3 d-flex align-items-center" i> <"col-md-8 d-flex justify-content-end" p>>`,
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: 'us_id', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Nombres', data: 'us_nombres' },
        { title: 'Apellidos', data: 'us_apellidos' },
        { title: 'Teléfono', data: 'us_telefono' },
        { title: 'DPI', data: 'us_dpi' },
        { title: 'Correo', data: 'us_correo' },
        {
            title: 'Foto',
            data: 'foto_url',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                if (data && data.trim() !== '') {
                    return `<img src="${data}" alt="Foto" style="height: 50px; width: auto; border-radius: 5px;">`;
                } else {
                    return `<i class="bi bi-person-fill text-muted" style="font-size: 30px;"></i>`;
                }
            }
        },
        {
            title: 'Acciones',
            data: 'us_id',
            searchable: false,
            orderable: false,
            render: (data, type, row) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombres="${row.us_nombres}"  
                         data-apellidos="${row.us_apellidos}"  
                         data-telefono="${row.us_telefono}"  
                         data-dpi="${row.us_dpi}"  
                         data-correo="${row.us_correo}"
                         data-direccion="${row.us_direccion}">   
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
        BuscarUsuarios(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const limpiarTodo = () => {
    FormUsuarios.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    FormUsuarios.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = '';
    });
    document.getElementById('grupo_password').classList.remove('d-none');
    document.getElementById('grupo_password_confirm').classList.remove('d-none');
    document.getElementById('grupo_foto').classList.remove('d-none');
}

const BuscarUsuarios = async (mostrarMensaje = false) => {
    const url = '/proyecto_uno/registro/buscarAPI';
    console.log('URL completa:', window.location.origin + url);
    const config = { method: 'GET' };
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        if (codigo == 1) {
            // Asegurar que data sea un array
            const usuarios = data || [];
            datatable.clear().draw();
            datatable.rows.add(usuarios).draw();
            if (mostrarMensaje) {
                await Swal.fire({ 
                    position: "center", 
                    icon: "success", 
                    title: "¡Usuarios cargados!", 
                    text: `Se cargaron ${usuarios.length} usuario(s) correctamente`, 
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

const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    // Validar formulario completo
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

    const body = new FormData(FormUsuarios);
    const url = '/proyecto_uno/registro/guardarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Usuario guardado exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            await BuscarUsuarios(false);
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

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    // Validar formulario (sin contraseñas en modo edición)
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

    const body = new FormData(FormUsuarios);
    const url = '/proyecto_uno/registro/modificarAPI';
    const config = { method: 'POST', body };
    
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        
        if (codigo == 1) {
            await Swal.fire({ 
                position: "center", 
                icon: "success", 
                title: "¡Usuario modificado exitosamente!", 
                text: mensaje, 
                showConfirmButton: false, 
                timer: 2000 
            });
            limpiarTodo();
            await BuscarUsuarios(false);
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
    
    document.getElementById('us_id').value = datos.id;
    document.getElementById('us_nombres').value = datos.nombres;
    document.getElementById('us_apellidos').value = datos.apellidos;
    document.getElementById('us_telefono').value = datos.telefono;
    document.getElementById('us_dpi').value = datos.dpi;
    document.getElementById('us_correo').value = datos.correo;
    document.getElementById('us_direccion').value = datos.direccion;
    
    // Ocultar campos de contraseña y foto en modo edición
    document.getElementById('grupo_password').classList.add('d-none');
    document.getElementById('grupo_password_confirm').classList.add('d-none');
    document.getElementById('grupo_foto').classList.add('d-none');
    
    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
    
    window.scrollTo({ top: 0 });
}

const EliminarUsuarios = async (e) => {
    const idUsuario = e.currentTarget.dataset.id;

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
        const url = `/proyecto_uno/registro/EliminarAPI?id=${idUsuario}`;
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
                BuscarUsuarios(false);
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
BuscarUsuarios(false);
FormUsuarios.addEventListener('submit', GuardarUsuario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);
datatable.on('click', '.modificar', llenarFormulario);
datatable.on('click', '.eliminar', EliminarUsuarios);

// Validaciones en tiempo real
us_telefono.addEventListener('blur', ValidarTelefono);
us_dpi.addEventListener('blur', ValidarDPI);
us_correo.addEventListener('blur', ValidarCorreo);
us_nombres.addEventListener('blur', ValidarNombres);
us_apellidos.addEventListener('blur', ValidarApellidos);
us_contrasenia.addEventListener('blur', ValidarContrasenia);
us_confirmar_contra.addEventListener('blur', ValidarConfirmacionContrasenia);