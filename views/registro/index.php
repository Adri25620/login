<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Registro de Usuarios</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormUsuarios" enctype="multipart/form-data">
                        <input type="hidden" id="us_id" name="us_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_nombres" class="form-label">Nombres Completos</label>
                                <input type="text" class="form-control" id="us_nombres" name="us_nombres" placeholder="Ingrese sus nombres completos">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_apellidos" class="form-label">Apellidos Completos</label>
                                <input type="text" class="form-control" id="us_apellidos" name="us_apellidos" placeholder="Ingrese sus apellidos completos">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="us_telefono" class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="us_telefono" name="us_telefono" placeholder="Ingrese su numero de telefono">
                            </div>

                            <div class="col-lg-4">
                                <label for="us_dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control" id="us_dpi" name="us_dpi" placeholder="Ingrese su DPI">
                            </div>

                            <div class="col-lg-4">
                                <label for="us_correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="us_correo" name="us_correo" placeholder="ej. ejemplo@ejemplo.com">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="us_contrasenia" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="us_contrasenia" name="us_contrasenia" placeholder="Ingrese su contraseña">
                            </div>
                            <div class="col-lg-6">
                                <label for="us_confirmar_contra" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="us_confirmar_contra" name="us_confirmar_contra" placeholder="Confirme su contraseña">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="us_direccion" class="form-label">Direccion</label>
                                <input type="text" class="form-control" id="us_direccion" name="us_direccion" placeholder="Ingrese su dirección">
                            </div>
                            <div class="col-lg-4">
                                <label for="us_rol" class="form-label">Seleccione el rol:</label>
                                <select name="us_rol" id="us_rol" class="form-select" required>
                                    <option value="" selected disabled>Seleccione un rol...</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <?php if ($rol->rol_situacion == 1): ?>
                                            <option value="<?= $rol->rol_id ?>"><?= $rol->rol_nombre ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="us_fotografia" class="form-label">Fotografía</label>
                                <input type="file" class="form-control form-control-lg" id="us_fotografia" name="us_fotografia" accept="image/*">
                                <div class="form-text">Formatos permitidos: JPG, PNG. Máximo 2MB</div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar"><i class="bi bi-floppy me-2"></i>
                                    Guardar
                                </button>
                            </div>

                            <div class="col-auto ">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar"><i class="bi bi-pencil me-2"></i>
                                    Modificar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar"><i class="bi bi-arrow-clockwise me-2"></i>
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">USUARIOS REGISTRADOS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableUsuarios">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= asset('build/js/registro/index.js') ?>"></script>