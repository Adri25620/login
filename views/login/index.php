
<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);">
                <div class="card-body p-5">
                    <div class="row mb-4">
                        <div class="text-center">
                            <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
                            <h4 class="text-center mb-2">INICIAR SESIÓN</h4>
                            <p class="text-muted">Ingresa tus credenciales para acceder</p>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <form id="FormLogin">
                            <div class="row mb-3 justify-content-center">
                                <label for="correo" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="us_correo" name="us_correo" placeholder="ej. ejemplo@correo.com">
                                </div>
                            </div>

                            <div class="row mb-3 justify-content-center">
                                <label for="us_contrasenia" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña:
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="us_contrasenia" name="us_contrasenia" placeholder="Ingresa tu contraseña">
                                </div>
                            </div>

                            <div class="row justify-content-center mt-4">
                                <div class="col-auto">
                                    <button class="btn btn-primary" type="submit" id="BtnLogin">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
