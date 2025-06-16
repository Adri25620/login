<div class="row justify-content-center p-3">
    <div class="col-lg-8">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 1px solid #28a745;">
            <div class="card-body p-5">
                <div class="text-center">
                    <i class="bi bi-person-circle text-success" style="font-size: 5rem;"></i>
                    <h1 class="text-success mt-4 mb-3">¡BIENVENIDO!</h1>
                    
                    <div class="bg-light p-4 rounded-3 shadow-sm">
                        <h3 class="text-primary mb-1" id="nombreCompleto">
                            <?= $_SESSION['us_nombres'] ?? 'Usuario' ?> <?= $_SESSION['us_apellidos'] ?? '' ?>
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Has iniciado sesión exitosamente
                        </p>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted">
                            <i class="bi bi-clock me-2"></i>
                            Fecha y hora de acceso: <?= date('d/m/Y - H:i:s') ?>
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="/proyecto_uno/registro" class="btn btn-primary me-3">
                            <i class="bi bi-people me-2"></i>Gestión de Usuarios
                        </a>
                        <button class="btn btn-outline-danger" id="btnCerrarSesion">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/bienvenida/index.js')?>"></script>