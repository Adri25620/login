// Importar las librerías necesarias para el funcionamiento
import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

// Aquí se capturan los elementos del formulario y botones del HTML
const FormUsuarios = document.getElementById('FormUsuarios');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

// Aquí se capturan los campos específicos que necesitan validación
const us_telefono = document.getElementById('us_telefono');
const us_dpi = document.getElementById('us_dpi');
const us_contrasenia = document.getElementById('us_contrasenia');
const us_confirmar_contra = document.getElementById('us_confirmar_contra');

// Aquí se valida el teléfono - debe tener exactamente 8 dígitos
const validarTelefono = () => {
    // Aquí se obtiene el valor del teléfono y se quitan espacios
    const CantidadDigitos = us_telefono.value.trim();

    // Aquí se pregunta si el campo está vacío
    if (CantidadDigitos.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_telefono.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío (no obligatorio mostrar error)
    } else {
        // Aquí se pregunta si NO tiene exactamente 8 dígitos
        if (CantidadDigitos.length !== 8) {
            // Aquí se pone la clase roja (error)
            us_telefono.classList.add('is-invalid');
            us_telefono.classList.remove('is-valid');
            // Aquí se muestra el mensaje de error al usuario
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Teléfono incorrecto",
                text: "Debe tener exactamente 8 dígitos",
                showConfirmButton: true,
            });
            return false; // Aquí se dice que la validación falló
        } else {
            // Aquí se pone la clase verde (correcto) porque tiene 8 dígitos
            us_telefono.classList.remove('is-invalid');
            us_telefono.classList.add('is-valid');
            return true; // Aquí se dice que la validación pasó
        }
    }
}

// Aquí se valida el DPI - debe tener exactamente 13 dígitos
const validarDPI = () => {
    // Aquí se obtiene el valor del DPI y se quitan espacios
    const digitosDPI = us_dpi.value.trim();

    // Aquí se pregunta si el campo está vacío
    if (digitosDPI.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_dpi.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    } else {
        // Aquí se pregunta si NO tiene exactamente 13 dígitos
        if (digitosDPI.length !== 13) {
            // Aquí se pone la clase roja (error)
            us_dpi.classList.add('is-invalid');
            us_dpi.classList.remove('is-valid');
            // Aquí se muestra el mensaje de error al usuario
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "DPI incorrecto",
                text: "Debe tener exactamente 13 dígitos",
                showConfirmButton: true,
            });
            return false; // Aquí se dice que la validación falló
        } else {
            // Aquí se pone la clase verde (correcto) porque tiene 13 dígitos
            us_dpi.classList.remove('is-invalid');
            us_dpi.classList.add('is-valid');
            return true; // Aquí se dice que la validación pasó
        }
    }
}

// Aquí se valida que la contraseña sea segura
const validarContrasenaSegura = () => {
    // Aquí se obtiene el valor de la contraseña
    const password = us_contrasenia.value;
    // Aquí se crea una lista para guardar qué le falta a la contraseña
    let errores = [];
    
    // Aquí se pregunta si el campo está vacío
    if (password.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_contrasenia.classList.remove('is-valid', 'is-invalid');
        us_contrasenia.title = '';
        return true; // Aquí se dice que está bien si está vacío
    }
    
    // Aquí se revisan todos los requisitos de la contraseña
    if (password.length < 10) errores.push("Mínimo 10 caracteres"); // Aquí se pregunta si es muy corta
    if (!/[A-Z]/.test(password)) errores.push("Al menos una mayúscula"); // Aquí se pregunta si tiene mayúscula
    if (!/[a-z]/.test(password)) errores.push("Al menos una minúscula"); // Aquí se pregunta si tiene minúscula
    if (!/[0-9]/.test(password)) errores.push("Al menos un número"); // Aquí se pregunta si tiene número
    if (!/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/.test(password)) errores.push("Al menos un carácter especial"); // Aquí se pregunta si tiene símbolo
    
    // Aquí se pregunta si encontró errores
    if (errores.length > 0) {
        // Aquí se pone la clase roja (error)
        us_contrasenia.classList.add('is-invalid');
        us_contrasenia.classList.remove('is-valid');
        // Aquí se muestra en el tooltip qué le falta
        us_contrasenia.title = "Falta: " + errores.join(", ");
        return false; // Aquí se dice que la validación falló
    } else {
        // Aquí se pone la clase verde (correcto)
        us_contrasenia.classList.remove('is-invalid');
        us_contrasenia.classList.add('is-valid');
        // Aquí se muestra que la contraseña está bien
        us_contrasenia.title = "Contraseña segura ✓";
        return true; // Aquí se dice que la validación pasó
    }
}

// Aquí se valida que las contraseñas coincidan
const validarConfirmarContrasena = () => {
    // Aquí se pregunta si el campo de confirmación está vacío
    if (us_confirmar_contra.value.length < 1) {
        // Aquí se quitan las clases de validación si está vacío
        us_confirmar_contra.classList.remove('is-valid', 'is-invalid');
        return true; // Aquí se dice que está bien si está vacío
    }

    // Aquí se pregunta si las dos contraseñas NO son iguales
    if (us_contrasenia.value !== us_confirmar_contra.value) {
        // Aquí se pone la clase roja (error)
        us_confirmar_contra.classList.add('is-invalid');
        us_confirmar_contra.classList.remove('is-valid');
        // Aquí se muestra el mensaje de error al usuario
        Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Las contraseñas no coinciden",
            showConfirmButton: true,
        });
        return false; // Aquí se dice que la validación falló
    } else {
        // Aquí se pone la clase verde (correcto) porque coinciden
        us_confirmar_contra.classList.remove('is-invalid');
        us_confirmar_contra.classList.add('is-valid');
        return true; // Aquí se dice que la validación pasó
    }
}

// Aquí se limpia todo el formulario y se regresa al estado inicial
const limpiarTodo = () => {
    // Aquí se limpian todos los campos del formulario
    FormUsuarios.reset();
    // Aquí se muestra el botón de guardar
    BtnGuardar.classList.remove('d-none');
    // Aquí se oculta el botón de modificar
    BtnModificar.classList.add('d-none');
    
    // Aquí se quitan todas las clases de validación de todos los campos
    FormUsuarios.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.classList.remove('is-valid', 'is-invalid');
        element.title = ''; // Aquí se limpian los tooltips
    });
}

// Aquí se guarda un nuevo usuario
const GuardarUsuario = async (event) => {
    // Aquí se evita que el formulario se envíe de forma normal
    event.preventDefault();
    // Aquí se desactiva el botón para evitar clicks múltiples
    BtnGuardar.disabled = true;

    // Aquí se validan todos los campos antes de enviar
    const telefonoValido = validarTelefono();
    const dpiValido = validarDPI();
    const contrasenaValida = validarContrasenaSegura();
    const confirmarContrasenaValida = validarConfirmarContrasena();

    // Aquí se pregunta si alguna validación falló
    if (!telefonoValido || !dpiValido || !contrasenaValida || !confirmarContrasenaValida) {
        // Aquí se muestra mensaje de que el formulario está incompleto
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Verifique todos los campos",
            showConfirmButton: true,
        });
        // Aquí se reactiva el botón
        BtnGuardar.disabled = false;
        return; // Aquí se sale de la función sin enviar nada
    }

    // Aquí se preparan los datos del formulario para enviar
    const body = new FormData(FormUsuarios);

    // Aquí se define la URL donde se enviarán los datos
    const url = '/proyecto_uno/registro/guardarAPI';
    // Aquí se configura el método POST
    const config = {
        method: 'POST',
        body
    }

    try {
        // Aquí se envían los datos al servidor
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        // Aquí se extraen el código y mensaje de la respuesta
        const { codigo, mensaje } = datos

        // Aquí se pregunta si el guardado fue exitoso
        if (codigo == 1) {
            // Aquí se muestra mensaje de éxito
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });

            // Aquí se limpia el formulario
            limpiarTodo();
            
            // Aquí se recargan los usuarios para mostrar el nuevo
            await BuscarUsuarios();

        } else {
            // Aquí se muestra mensaje de error si algo salió mal
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log(error)
        // Aquí se muestra mensaje de error de conexión
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo completar la operación",
            showConfirmButton: true,
        });
    }
    // Aquí se reactiva el botón al final
    BtnGuardar.disabled = false;
}

// Aquí se buscan y cargan todos los usuarios
const BuscarUsuarios = async () => {
    // Aquí se define la URL para buscar usuarios
    const url = '/proyecto_uno/registro/buscarAPI';
    // Aquí se configura el método GET
    const config = {
        method: 'GET'
    }

    try {
        // Aquí se solicitan los datos al servidor
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        // Aquí se extraen el código, mensaje y datos de la respuesta
        const { codigo, mensaje, data } = datos

        // Aquí se pregunta si se obtuvieron datos correctamente
        if (codigo == 1) {
            // Aquí se muestra mensaje de éxito con la cantidad de usuarios
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Usuarios cargados!",
                text: `Se cargaron ${data.length} usuario(s) correctamente`,
                showConfirmButton: true,
                timer: 2000 // Aquí se cierra automáticamente en 2 segundos
            });

            // Aquí se limpian los datos anteriores de la tabla
            datatable.clear().draw();
            // Aquí se agregan los nuevos datos a la tabla
            datatable.rows.add(data).draw();
        } else {
            // Aquí se muestra mensaje si no hay datos
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Sin datos",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log('Error en BuscarUsuarios:', error)
        // Aquí se muestra mensaje de error de conexión
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar los usuarios",
            showConfirmButton: true,
        });
    }
}

// Aquí se configura la tabla de usuarios con DataTables
const datatable = new DataTable('#TableUsuarios', {
    // Aquí se define el diseño de la tabla (buscador, paginación, etc.)
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
    // Aquí se define el idioma de la tabla
    language: lenguaje,
    // Aquí se inicia la tabla sin datos
    data: [],
    // Aquí se definen las columnas de la tabla
    columns: [
        {
            // Aquí se crea la columna de número correlativo
            title: 'No.',
            data: 'us_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1 // Aquí se numera automáticamente
        },
        { 
            // Aquí se muestra la columna de nombres
            title: 'Nombres', 
            data: 'us_nombres'
        },
        { 
            // Aquí se muestra la columna de apellidos
            title: 'Apellidos', 
            data: 'us_apellidos'
        },
        { 
            // Aquí se muestra la columna de teléfono
            title: 'Teléfono', 
            data: 'us_telefono' 
        },
        { 
            // Aquí se muestra la columna de DPI
            title: 'DPI', 
            data: 'us_dpi' 
        },
        { 
            // Aquí se muestra la columna de correo
            title: 'Correo', 
            data: 'us_correo' 
        },
        { 
            // Aquí se muestra la columna de rol
            title: 'Rol', 
            data: 'rol_nombre' 
        },
        { 
            // Aquí se muestra la columna de foto
            title: 'Foto', 
            data: 'foto_url',
            searchable: false, // Aquí se dice que no se puede buscar por foto
            orderable: false, // Aquí se dice que no se puede ordenar por foto
            render: (data, type, row) => {
                // Aquí se pregunta si tiene foto
                if (data && data.trim() !== '') {
                    // Aquí se muestra la imagen
                    return `<img src="${data}" alt="Foto de usuario" style="height: 50px; width: auto;">`;
                } else {
                    // Aquí se muestra un ícono si no tiene foto
                    return `<i class="bi bi-person-fill text-muted" style="font-size: 30px;"></i>`;
                }
            }
        },
        {
            // Aquí se crean los botones de acciones
            title: 'Acciones',
            data: 'us_id',
            searchable: false, // Aquí se dice que no se puede buscar por acciones
            orderable: false, // Aquí se dice que no se puede ordenar por acciones
            render: (data, type, row, meta) => {
                // Aquí se crean los botones de modificar y eliminar
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

// Aquí se llena el formulario con los datos del usuario a modificar
const llenarFormulario = async (event) => {
    // Aquí se obtiene el ID del usuario desde el botón que se presionó
    const datos = event.currentTarget.dataset;
    const usuarioId = datos.id;

    // Aquí se define la URL para buscar el usuario específico
    const url = `/proyecto_uno/registro/buscarAPI?id=${usuarioId}`;
    // Aquí se configura el método GET
    const config = {
        method: 'GET'
    }

    try {
        // Aquí se solicitan los datos del usuario específico
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const resultado = await respuesta.json();
        // Aquí se extraen el código, mensaje y datos de la respuesta
        const { codigo, mensaje, data } = resultado;

        // Aquí se pregunta si se encontró el usuario
        if (codigo == 1 && data.length > 0) {
            // Aquí se obtiene el primer (y único) usuario de la respuesta
            const usuario = data[0];
            
            // Aquí se llenan todos los campos del formulario con los datos del usuario
            document.getElementById('us_id').value = usuarioId;
            document.getElementById('us_nombres').value = usuario.us_nombres;
            document.getElementById('us_apellidos').value = usuario.us_apellidos;
            document.getElementById('us_telefono').value = usuario.us_telefono;
            document.getElementById('us_direccion').value = usuario.us_direccion;
            document.getElementById('us_dpi').value = usuario.us_dpi;
            document.getElementById('us_correo').value = usuario.us_correo;
            document.getElementById('us_rol').value = usuario.us_rol;
            
            // Aquí se dejan vacías las contraseñas por seguridad
            document.getElementById('us_contrasenia').value = '';
            document.getElementById('us_confirmar_contra').value = '';
            
            // Aquí se oculta el botón de guardar
            BtnGuardar.classList.add('d-none');
            // Aquí se muestra el botón de modificar
            BtnModificar.classList.remove('d-none');
            
            // Aquí se lleva al usuario al inicio de la página
            window.scrollTo({
                top: 0
            });

        } else {
            // Aquí se muestra mensaje de error si no se encontró el usuario
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log('Error completo:', error);
        
        // Aquí se muestra mensaje de error de conexión
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo cargar el usuario",
            showConfirmButton: true,
        });
    }
}

// Aquí se modifica un usuario existente
const ModificarUsuario = async (event) => {
    // Aquí se evita que el formulario se envíe de forma normal
    event.preventDefault();
    // Aquí se desactiva el botón para evitar clicks múltiples
    BtnModificar.disabled = true;

    // Aquí se preparan los datos del formulario para enviar
    const body = new FormData(FormUsuarios);

    // Aquí se define la URL donde se enviarán los datos de modificación
    const url = '/proyecto_uno/registro/modificarAPI';
    // Aquí se configura el método POST
    const config = {
        method: 'POST',
        body
    }

    try {
        // Aquí se envían los datos al servidor
        const respuesta = await fetch(url, config);
        // Aquí se convierte la respuesta a JSON
        const datos = await respuesta.json();
        // Aquí se extraen el código y mensaje de la respuesta
        const { codigo, mensaje } = datos

        // Aquí se pregunta si la modificación fue exitosa
        if (codigo == 1) {
            // Aquí se muestra mensaje de éxito
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Éxito!",
                text: mensaje,
                showConfirmButton: true,
            });

            // Aquí se limpia el formulario
            limpiarTodo();
            
            // Aquí se recargan los usuarios para mostrar los cambios
            await BuscarUsuarios();

        } else {
            // Aquí se muestra mensaje de error si algo salió mal
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        // Aquí se captura cualquier error de conexión
        console.log(error)
        // Aquí se muestra mensaje de error de conexión
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo completar la modificación",
            showConfirmButton: true,
        });
    }
    // Aquí se reactiva el botón al final
    BtnModificar.disabled = false;
}

// Aquí se elimina un usuario
const EliminarUsuarios = async (e) => {
    // Aquí se obtiene el ID del usuario desde el botón que se presionó
    const idUsuario = e.currentTarget.dataset.id

    // Aquí se pregunta al usuario si está seguro de eliminar
    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Desea ejecutar esta acción?",
        text: 'Está completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    // Aquí se pregunta si el usuario confirmó la eliminación
    if (AlertaConfirmarEliminar.isConfirmed) {
        // Aquí se define la URL para eliminar el usuario
        const url = `/proyecto_uno/registro/eliminarAPI?id=${idUsuario}`;
        // Aquí se configura el método GET
        const config = {
            method: 'GET'
        }

        try {
            // Aquí se envía la petición de eliminación
            const consulta = await fetch(url, config);
            // Aquí se convierte la respuesta a JSON
            const respuesta = await consulta.json();
            // Aquí se extraen el código y mensaje de la respuesta
            const { codigo, mensaje } = respuesta;

            // Aquí se pregunta si la eliminación fue exitosa
            if (codigo == 1) {
                // Aquí se muestra mensaje de éxito
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "¡Éxito!",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                // Aquí se recargan los usuarios para quitar el eliminado
                await BuscarUsuarios();
            } else {
                // Aquí se muestra mensaje de error si algo salió mal
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            // Aquí se captura cualquier error de conexión
            console.log(error)
            // Aquí se muestra mensaje de error de conexión
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error de conexión",
                text: "No se pudo completar la eliminación",
                showConfirmButton: true,
            });
        }
    }
}

// Aquí se configuran todos los eventos (qué pasa cuando se hace click o se cambia algo)
BuscarUsuarios(); // Aquí se cargan los usuarios al inicio
FormUsuarios.addEventListener('submit', GuardarUsuario); // Aquí se dice qué hacer cuando se envía el formulario
BtnLimpiar.addEventListener('click', limpiarTodo); // Aquí se dice qué hacer cuando se hace click en limpiar
BtnModificar.addEventListener('click', ModificarUsuario); // Aquí se dice qué hacer cuando se hace click en modificar
us_telefono.addEventListener('change', validarTelefono); // Aquí se valida el teléfono cuando cambia
us_dpi.addEventListener('change', validarDPI); // Aquí se valida el DPI cuando cambia
us_contrasenia.addEventListener('input', validarContrasenaSegura); // Aquí se valida la contraseña mientras se escribe
us_confirmar_contra.addEventListener('change', validarConfirmarContrasena); // Aquí se valida la confirmación cuando cambia
datatable.on('click', '.modificar', llenarFormulario); // Aquí se dice qué hacer cuando se hace click en modificar de la tabla
datatable.on('click', '.eliminar', EliminarUsuarios); // Aquí se dice qué hacer cuando se hace click en eliminar de la tabla