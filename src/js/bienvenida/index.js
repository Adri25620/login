import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function() {
    const btnCerrarSesion = document.getElementById('btnCerrarSesion');
    
    if (btnCerrarSesion) {
        btnCerrarSesion.addEventListener('click', function() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: "¿Estás seguro que deseas salir del sistema?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // CON BASE URL
                    window.location.href = '/proyecto_uno/logout';
                }
            });
        });
    }
});