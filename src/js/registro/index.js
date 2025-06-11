import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import DataTable from "datatables.net-bs5";
import { validarFormulario } from "../funciones";
import { lenguaje } from "../lenguaje";

//document.writeln("Hola");

// Selección de elementos del DOM según tu vista
const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

// Campos del formulario
const us_telefono = document.getElementById('us_telefono');
const us_dpi = document.getElementById('us_dpi');
const us_contrasenia = document.getElementById('us_contrasenia');
const us_confirmar_contra = document.getElementById('us_confirmar_contra');

// DataTable
const datosDeTabla = new DataTable('#TableUsuarios', {
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
    },
    data: [],
    columns: [
        { title: 'ID', data: 'us_id' },
        { title: 'Nombres', data: null, render: row => `${row.us_pri_nombre} ${row.us_seg_nombre || ''}`.trim() },
        { title: 'Apellidos', data: null, render: row => `${row.us_pri_apellido} ${row.us_seg_apellido || ''}`.trim() },
        { title: 'Teléfono', data: 'us_telefono' },
        { title: 'DPI', data: 'us_dpi' },
        { title: 'Correo', data: 'us_correo' },
        {
            title: 'Opciones',
            data: 'us_id',
            render: (data, type, row) => `
                <button class='btn btn-warning modificar' data-id="${data}">Modificar</button>
                <button class='btn btn-danger eliminar' data-id="${data}">Eliminar</button>
            `
        }
    ]
});

// Validaciones simples
us_telefono.addEventListener('blur', () => {
    if (us_telefono.value.length !== 8) {
        us_telefono.classList.add('is-invalid');
        Swal.fire('Teléfono incorrecto', 'Debe tener 8 dígitos', 'warning');
    } else {
        us_telefono.classList.remove('is-invalid');
        us_telefono.classList.add('is-valid');
    }
});

us_dpi.addEventListener('blur', () => {
    if (us_dpi.value.length !== 13) {
        us_dpi.classList.add('is-invalid');
        Swal.fire('DPI incorrecto', 'Debe tener 13 dígitos', 'warning');
    } else {
        us_dpi.classList.remove('is-invalid');
        us_dpi.classList.add('is-valid');
    }
});

// Validación de contraseña segura
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
        us_contrasenia.title = "Falta: " + errores.join(", ");
        return false;
    } else {
        us_contrasenia.classList.remove('is-invalid');
        us_contrasenia.classList.add('is-valid');
        us_contrasenia.title = "Contraseña segura ✓";
        return true;
    }
};
us_contrasenia.addEventListener('input', validarContrasenaSegura);

// Validar coincidencia de contraseñas
us_confirmar_contra.addEventListener('blur', () => {
    if (us_contrasenia.value !== us_confirmar_contra.value) {
        us_confirmar_contra.classList.add('is-invalid');
        Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
    } else {
        us_confirmar_contra.classList.remove('is-invalid');
        us_confirmar_contra.classList.add('is-valid');
    }
});

// Cargar usuarios en la tabla
const cargarUsuarios = async () => {
    try {
        const resp = await fetch('/proyecto_uno/api/registro/buscar');
        const datos = await resp.json();
        if (datos.codigo === 1) {
            datosDeTabla.clear().draw();
            datosDeTabla.rows.add(datos.data).draw();
        }
    } catch (e) {
        Swal.fire('Error', 'No se pudo cargar la tabla', 'error');
    }
};

// Guardar usuario
FormUsuarios.addEventListener('submit', async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarContrasenaSegura() || us_contrasenia.value !== us_confirmar_contra.value) {
        Swal.fire('Error', 'Verifica la contraseña', 'error');
        BtnGuardar.disabled = false;
        return;
    }

    const formData = new FormData(FormUsuarios);

    try {
        const resp = await fetch('/proyecto_uno/api/registro/guardar', {
            method: 'POST',
            body: formData
        });
        const datos = await resp.json();
        if (datos.codigo === 1) {
            Swal.fire('¡Éxito!', datos.mensaje, 'success');
            FormUsuarios.reset();
            cargarUsuarios();
        } else {
            Swal.fire('Error', datos.mensaje, 'error');
        }
    } catch (e) {
        Swal.fire('Error', 'No se pudo guardar', 'error');
    }
    BtnGuardar.disabled = false;
});

// Modificar usuario (evento delegado)
datosDeTabla.on('click', '.modificar', async function (e) {
    const id = e.target.dataset.id;
    // Aquí puedes cargar los datos del usuario y llenar el formulario para editar
    // Ejemplo: fetch(`/api/registro/buscar?id=${id}`) y llenar los campos
});

// Eliminar usuario (evento delegado)
datosDeTabla.on('click', '.eliminar', async function (e) {
    const id = e.target.dataset.id;
    const confirm = await Swal.fire({
        title: '¿Eliminar usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    });
    if (confirm.isConfirmed) {
        try {
            const resp = await fetch(`/proyecto_uno/api/registro/eliminar?id=${id}`);
            const datos = await resp.json();
            if (datos.codigo === 1) {
                Swal.fire('Eliminado', datos.mensaje, 'success');
                cargarUsuarios();
            } else {
                Swal.fire('Error', datos.mensaje, 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'No se pudo eliminar', 'error');
        }
    }
});

// Limpiar formulario
BtnLimpiar.addEventListener('click', () => {
    FormUsuarios.reset();
    // Limpiar validaciones visuales
    FormUsuarios.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
});

// Inicializar tabla al cargar
document.addEventListener('DOMContentLoaded', cargarUsuarios);