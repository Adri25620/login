<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-9">
            <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-shield fa-2x text-primary mb-2"></i>
                        <h4 class="mb-1">INICIAR SESIÓN</h4>
                        <p class="text-muted small">Ingresa tus credenciales para acceder</p>
                    </div>

                    <form id="FormLogin">
                        <div class="mb-3">
                            <label for="correo" class="form-label small">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control" id="us_correo" name="us_correo" placeholder="ej. ejemplo@correo.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="us_contrasenia" class="form-label small">
                                <i class="fas fa-lock me-2"></i>Contraseña:
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="us_contrasenia" name="us_contrasenia" placeholder="Ingresa tu contraseña">
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button class="btn btn-primary" type="submit" id="BtnLogin">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/login/index.js')?>"> </script>