import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormPermisos = document.getElementById('FormPermisos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('SeccionTablaPermisos');

const MostrarRegistros = () => {
    const estaOculto = SeccionTabla.style.display === 'none';
    
    if (estaOculto) {
        SeccionTabla.style.display = 'block';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        BuscarPermisos(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const GuardarPermiso = async (event) => {

    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormPermisos, ['per_id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);

    const url = '/proyecto_uno/permiso/guardarAPI';
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
            BuscarPermisos(false);

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

const BuscarPermisos = async (mostrarMensaje = false) => {

    const url = '/proyecto_uno/permiso/buscarAPI';
    const config = {
        method: 'GET'
    }

    try {

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {

            if (mostrarMensaje) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: `Se cargaron ${data.length} permiso(s) correctamente`,
                    showConfirmButton: true,
                    timer: 2000
                });
            }

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

const datatable = new DataTable('#TablePermisos', {
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
            data: 'per_id',
            width: '8%',
            className: 'text-center',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Aplicación', 
            data: 'aplicacion_nombre',
            width: '20%',
            className: 'text-center'
        },
        { 
            title: 'Nombre del Permiso', 
            data: 'per_nombre_permiso',
            width: '25%',
            className: 'text-center'
        },
        { 
            title: 'Clave', 
            data: 'per_clave_permiso',
            width: '15%',
            className: 'text-center'
        },
        { 
            title: 'Descripción', 
            data: 'per_descripcion',
            width: '20%',
            className: 'text-center',
            render: (data, type, row, meta) => {
                if (data && data.length > 50) {
                    return data.substring(0, 50) + '...';
                }
                return data || 'Sin descripción';
            }
        },
        {
            title: 'Acciones',
            data: 'per_id',
            width: '12%',
            className: 'text-center',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1 btn-sm' 
                         data-id="${data}" 
                         data-aplicacion="${row.per_aplicacion}"  
                         data-nombre="${row.per_nombre_permiso}"
                         data-clave="${row.per_clave_permiso}"
                         data-descripcion="${row.per_descripcion}">   
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1 btn-sm' 
                         data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {

    const datos = event.currentTarget.dataset

    document.getElementById('per_id').value = datos.id
    document.getElementById('per_aplicacion').value = datos.aplicacion
    document.getElementById('per_nombre_permiso').value = datos.nombre
    document.getElementById('per_clave_permiso').value = datos.clave
    document.getElementById('per_descripcion').value = datos.descripcion

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const limpiarTodo = () => {

    FormPermisos.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Limpiar las validaciones visuales
    FormPermisos.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = '';
    });
}

const ModificarPermiso = async (event) => {

    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormPermisos, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormPermisos);

    const url = '/proyecto_uno/permiso/modificarAPI';
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
            BuscarPermisos(true);

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

const EliminarPermisos = async (e) => {

    const idPermiso = e.currentTarget.dataset.id

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

        const url = `/proyecto_uno/permiso/EliminarAPI?id=${idPermiso}`;
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
                
                BuscarPermisos(true);
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


BuscarPermisos(); 
datatable.on('click', '.eliminar', EliminarPermisos);
datatable.on('click', '.modificar', llenarFormulario);
FormPermisos.addEventListener('submit', GuardarPermiso);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarPermiso);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);