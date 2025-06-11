import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

// Campos del formulario
const us_telefono = document.getElementById('us_telefono');
const us_dpi = document.getElementById('us_dpi');
const us_contrasenia = document.getElementById('us_contrasenia');
const us_confirmar_contra = document.getElementById('us_confirmar_contra');

const validarTelefono = () => {
    if (us_telefono.value.length !== 8) {
        us_telefono.classList.add('is-invalid');
        us_telefono.classList.remove('is-valid');
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Teléfono incorrecto",
            text: "Debe tener exactamente 8 dígitos",
            showConfirmButton: true,
        });
        return false;
    } else {
        us_telefono.classList.remove('is-invalid');
        us_telefono.classList.add('is-valid');
        return true;
    }
}

const validarDPI = () => {
    if (us_dpi.value.length !== 13) {
        us_dpi.classList.add('is-invalid');
        us_dpi.classList.remove('is-valid');
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "DPI incorrecto",
            text: "Debe tener exactamente 13 dígitos",
            showConfirmButton: true,
        });
        return false;
    } else {
        us_dpi.classList.remove('is-invalid');
        us_dpi.classList.add('is-valid');
        return true;
    }
}

const validarContrasenaSegura = () => {
    const password = us_contrasenia.value;
    let errores = [];
    
    if (password.length < 10) errores.push("Mínimo 10 caracteres");
    if (!/[A-Z]/.test(password)) errores.push("Al menos una mayúscula");
    if (!/[a-z]/.test(password)) errores.push("Al menos una minúscula");
    if (!/[0-9]/.test(password)) errores.push("Al menos un número");
    if (!/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/.test(password)) errores.push("Al menos un carácter especial");
    
    if (errores.length > 0) {
        us_contrasenia.classList.add('is-invalid');
        us_contrasenia.classList.remove('is-valid');
        us_contrasenia.title = "Falta: " + errores.join(", ");
        return false;
    } else {
        us_contrasenia.classList.remove('is-invalid');
        us_contrasenia.classList.add('is-valid');
        us_contrasenia.title = "Contraseña segura ✓";
        return true;
    }
}

const validarConfirmarContrasena = () => {
    if (us_contrasenia.value !== us_confirmar_contra.value) {
        us_confirmar_contra.classList.add('is-invalid');
        us_confirmar_contra.classList.remove('is-valid');
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Las contraseñas no coinciden",
            showConfirmButton: true,
        });
        return false;
    } else {
        us_confirmar_contra.classList.remove('is-invalid');
        us_confirmar_contra.classList.add('is-valid');
        return true;
    }
}

const limpiarTodo = () => {
    FormUsuarios.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Limpiar validaciones visuales
    FormUsuarios.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = ''; // Limpiar tooltips
    });
}

const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    // Validar todos los campos
    const telefonoValido = validarTelefono();
    const dpiValido = validarDPI();
    const contrasenaValida = validarContrasenaSegura();
    const confirmarContrasenaValida = validarConfirmarContrasena();

    if (!telefonoValido || !dpiValido || !contrasenaValida || !confirmarContrasenaValida) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Verifique todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormUsuarios);

    const url = '/proyecto_uno/registro/guardar';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            // Limpiar formulario y validaciones
            FormUsuarios.reset();
            FormUsuarios.querySelectorAll('.form-control, .form-select').forEach(element => {
                element.classList.remove('is-valid', 'is-invalid');
                element.title = '';
            });
            BtnGuardar.classList.remove('d-none');
            BtnModificar.classList.add('d-none');
            
            BuscarUsuarios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarUsuarios = async () => {
    const url = '/proyecto_uno/registro/buscar';
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TableUsuarios', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'us_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Nombres', 
            data: 'us_nombres'
        },
        { 
            title: 'Apellidos', 
            data: 'us_apellidos'
        },
        { title: 'Teléfono', data: 'us_telefono' },
        { title: 'DPI', data: 'us_dpi' },
        { title: 'Correo', data: 'us_correo' },
        { title: 'Rol', data: 'rol_nombre' },
        {
            title: 'Acciones',
            data: 'us_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}">   
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

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;
    const usuarioId = datos.id;

    const url = `/proyecto_uno/registro/buscar?id=${usuarioId}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const resultado = await respuesta.json();
        const { codigo, mensaje, data } = resultado;

        if (codigo == 1 && data.length > 0) {
            const usuario = data[0];
            
            document.getElementById('us_id').value = usuarioId;
            document.getElementById('us_nombres').value = usuario.us_nombres;
            document.getElementById('us_apellidos').value = usuario.us_apellidos;
            document.getElementById('us_telefono').value = usuario.us_telefono;
            document.getElementById('us_direccion').value = usuario.us_direccion;
            document.getElementById('us_dpi').value = usuario.us_dpi;
            document.getElementById('us_correo').value = usuario.us_correo;
            document.getElementById('us_rol').value = usuario.us_rol;
            
            // No cargar contraseñas por seguridad
            document.getElementById('us_contrasenia').value = '';
            document.getElementById('us_confirmar_contra').value = '';
            
            BtnGuardar.classList.add('d-none');
            BtnModificar.classList.remove('d-none');
            
            window.scrollTo({
                top: 0
            });

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
        console.log('Error completo:', error);
        
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo cargar el usuario",
            showConfirmButton: true,
        });
    }
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    const body = new FormData(FormUsuarios);

    const url = '/proyecto_uno/registro/modificar';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarUsuarios();

        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
    BtnModificar.disabled = false;
}

const EliminarUsuarios = async (e) => {
    const idUsuario = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto_uno/registro/eliminar?id=${idUsuario}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarUsuarios();
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
            console.log(error)
        }
    }
}

// Configurar eventos
BuscarUsuarios();
FormUsuarios.addEventListener('submit', GuardarUsuario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
us_telefono.addEventListener('blur', validarTelefono);
us_dpi.addEventListener('blur', validarDPI);
us_contrasenia.addEventListener('input', validarContrasenaSegura);
us_confirmar_contra.addEventListener('blur', validarConfirmarContrasena);
datatable.on('click', '.modificar', llenarFormulario);
datatable.on('click', '.eliminar', EliminarUsuarios);