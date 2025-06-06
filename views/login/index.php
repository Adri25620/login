<div class="container">
        <div class="row justify-content-center p-3">
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
                                    <label for="usuario" class="form-label">
                                        <i class="fas fa-user me-2"></i>Usuario:
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="usuario" name="usuario" 
                                               placeholder="Ingrese su usuario..." required>
                                    </div>
                                </div>

                                <div class="row mb-4 justify-content-center">
                                    <label for="contrasena" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Contraseña:
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="contrasena" name="contrasena" 
                                               placeholder="Ingrese su contraseña..." required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="recordar" name="recordar">
                                            <label class="form-check-label" for="recordar">
                                                Recordar mis datos
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-sign-in-alt me-2"></i>INICIAR SESIÓN
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a href="#" class="text-decoration-none">
                                            <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= asset('build/js/login/index.js') ?>"></script>