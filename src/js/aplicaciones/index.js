import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAplicaciones = document.getElementById('FormAplicaciones');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnMostrarRegistros = document.getElementById('BtnMostrarRegistros');
const SeccionTabla = document.getElementById('SeccionTablaAplicaciones');

const MostrarRegistros = () => {
    const estaOculto = SeccionTabla.style.display === 'none';
    
    if (estaOculto) {
        SeccionTabla.style.display = 'block';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Registros';
        BtnMostrarRegistros.classList.remove('btn-info');
        BtnMostrarRegistros.classList.add('btn-warning');
        BuscarAplicaciones(true);
    } else {
        SeccionTabla.style.display = 'none';
        BtnMostrarRegistros.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Registros';
        BtnMostrarRegistros.classList.remove('btn-warning');
        BtnMostrarRegistros.classList.add('btn-info');
    }
}

const GuardarAplicacion = async (event) => {

    event.preventDefault();
    BtnGuardar.disabled = true;

    // Validar campos manualmente
    const nombreLargo = document.getElementById('ap_nombre_lg').value.trim();
    const nombreMedio = document.getElementById('ap_nombre_md').value.trim();
    const nombreCorto = document.getElementById('ap_nombre_ct').value.trim();

    if (!nombreLargo || !nombreMedio || !nombreCorto) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (nombreLargo.length > 100) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de validación",
            text: "El nombre largo no puede exceder 100 caracteres",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (nombreMedio.length > 50) {
        Swal.fire({
            position: "center",
            icon: "error", 
            title: "Error de validación",
            text: "El nombre medio no puede exceder 50 caracteres",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (nombreCorto.length > 20) {
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de validación", 
            text: "El nombre corto no puede exceder 20 caracteres",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormAplicaciones);

    const url = '/proyecto_uno/aplicacion/guardarAPI';
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
            BuscarAplicaciones(false);

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

const BuscarAplicaciones = async (mostrarMensaje = false) => {

    const url = '/proyecto_uno/aplicacion/buscarAPI';
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
                    text: `Se cargaron ${data.length} aplicación(es) correctamente`,
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

const datatable = new DataTable('#TableAplicaciones', {
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
            data: 'ap_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Nombre Largo', data: 'ap_nombre_lg' },
        { title: 'Nombre Medio', data: 'ap_nombre_md' },
        { title: 'Nombre Corto', data: 'ap_nombre_ct' },
        { 
            title: 'Fecha Creación', 
            data: 'ap_fecha_creacion',
            render: (data) => {
                if (data) {
                    const fecha = new Date(data);
                    return fecha.toLocaleDateString('es-GT', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
                return '';
            }
        },
        {
            title: 'Acciones',
            data: 'ap_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombre_lg="${row.ap_nombre_lg}"  
                         data-nombre_md="${row.ap_nombre_md}"  
                         data-nombre_ct="${row.ap_nombre_ct}">   
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

const llenarFormulario = (event) => {

    const datos = event.currentTarget.dataset

    document.getElementById('ap_id').value = datos.id
    document.getElementById('ap_nombre_lg').value = datos.nombre_lg
    document.getElementById('ap_nombre_md').value = datos.nombre_md
    document.getElementById('ap_nombre_ct').value = datos.nombre_ct

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0
    });
}

const limpiarTodo = () => {

    FormAplicaciones.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    // Limpiar las validaciones visuales
    FormAplicaciones.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = '';
    });
}

const ModificarAplicacion = async (event) => {

    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormAplicaciones, [''])) {
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

    const body = new FormData(FormAplicaciones);

    const url = '/proyecto_uno/aplicacion/modificarAPI';
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
            BuscarAplicaciones(true);

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

const EliminarAplicaciones = async (e) => {

    const idAplicacion = e.currentTarget.dataset.id

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

        const url = `/proyecto_uno/aplicacion/eliminarAPI?id=${idAplicacion}`;
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
                
                BuscarAplicaciones(true);
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


BuscarAplicaciones(); 
datatable.on('click', '.eliminar', EliminarAplicaciones);
datatable.on('click', '.modificar', llenarFormulario);
FormAplicaciones.addEventListener('submit', GuardarAplicacion);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarAplicacion);
BtnMostrarRegistros.addEventListener('click', MostrarRegistros);