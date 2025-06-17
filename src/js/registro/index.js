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
        { title: 'Rol', data: 'rol_nombre' },
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
            render: (data) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' data-id="${data}"><i class='bi bi-pencil-square me-1'></i> Modificar</button>
                     <button class='btn btn-danger eliminar mx-1' data-id="${data}"><i class="bi bi-trash3 me-1"></i>Eliminar</button>
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
    const config = { method: 'GET' };
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
            if (mostrarMensaje) {
                await Swal.fire({ position: "center", icon: "success", title: "¡Usuarios cargados!", text: `Se cargaron ${data.length} usuario(s) correctamente`, showConfirmButton: false, timer: 1500 });
            }
        }
    } catch (error) {
        console.log(error);
    }
}

const GuardarUsuario = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;
    const body = new FormData(FormUsuarios);
    const url = '/proyecto_uno/registro/guardarAPI';
    const config = { method: 'POST', body };
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        if (codigo == 1) {
            await Swal.fire({ position: "center", icon: "success", title: "¡Usuario guardado exitosamente!", text: mensaje, showConfirmButton: false, timer: 2000 });
            limpiarTodo();
            await BuscarUsuarios(false);
        } else {
            await Swal.fire({ position: "center", icon: "error", title: "Error al guardar", text: mensaje, showConfirmButton: true });
        }
    } catch (error) {
        console.log(error);
    }
    BtnGuardar.disabled = false;
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;
    const body = new FormData(FormUsuarios);
    const url = '/proyecto_uno/registro/modificarAPI';
    const config = { method: 'POST', body };
    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;
        if (codigo == 1) {
            await Swal.fire({ position: "center", icon: "success", title: "¡Usuario modificado exitosamente!", text: mensaje, showConfirmButton: false, timer: 2000 });
            limpiarTodo();
            await BuscarUsuarios(false);
        } else {
            await Swal.fire({ position: "center", icon: "error", title: "Error al modificar", text: mensaje, showConfirmButton: true });
        }
    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
}

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;
    const usuarioId = datos.id;
    const url = `/proyecto_uno/registro/buscarAPI?id=${usuarioId}`;
    const config = { method: 'GET' };
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
            document.getElementById('grupo_password').classList.add('d-none');
            document.getElementById('grupo_password_confirm').classList.add('d-none');
            document.getElementById('grupo_foto').classList.add('d-none');
            BtnGuardar.classList.add('d-none');
            BtnModificar.classList.remove('d-none');
        }
    } catch (error) {
        console.log(error);
    }
}

BuscarUsuarios(false);
FormUsuarios.addEventListener('submit', GuardarUsuario);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);
datatable.on('click', '.modificar', llenarFormulario);
