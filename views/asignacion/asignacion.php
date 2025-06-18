<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Asignación de Permisos</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormAsignaciones">
                        <input type="hidden" id="asig_id" name="asig_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="asig_usuario" class="form-label">
                                    <i class="bi bi-person me-2"></i>Usuario
                                </label>
                                <select class="form-select" id="asig_usuario" name="asig_usuario" required>
                                    <option value="" selected disabled>Seleccione un usuario...</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php if ($usuario->us_situacion == 1): ?>
                                            <option value="<?= $usuario->us_id ?>"><?= $usuario->us_nombres . ' ' . $usuario->us_apellidos ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="asig_aplicacion" class="form-label">
                                    <i class="bi bi-app-indicator me-2"></i>Aplicación
                                </label>
                                <select class="form-select" id="asig_aplicacion" name="asig_aplicacion" required>
                                    <option value="" selected disabled>Seleccione una aplicación...</option>
                                    <?php foreach ($aplicaciones as $aplicacion): ?>
                                        <?php if ($aplicacion->ap_situacion == 1): ?>
                                            <option value="<?= $aplicacion->ap_id ?>"><?= $aplicacion->ap_nombre_lg ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="asig_permisos" class="form-label">
                                    <i class="bi bi-shield-check me-2"></i>Permiso
                                </label>
                                <select class="form-select" id="asig_permisos" name="asig_permisos" required disabled>
                                    <option value="" selected disabled>Seleccione un permiso...</option>
                                    <?php foreach ($permisos as $permiso): ?>
                                        <?php if ($permiso->per_situacion == 1): ?>
                                            <option value="<?= $permiso->per_id ?>"><?= $permiso->per_nombre_permiso ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="asig_usuario_asignador" class="form-label">
                                    <i class="bi bi-person-gear me-2"></i>Usuario Asignador
                                </label>
                                <select class="form-select" id="asig_usuario_asignador" name="asig_usuario_asignador" required>
                                    <option value="" selected disabled>Seleccione usuario asignador...</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php if ($usuario->us_situacion == 1): ?>
                                            <option value="<?= $usuario->us_id ?>"><?= $usuario->us_nombres . ' ' . $usuario->us_apellidos ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="asig_motivo" class="form-label">
                                    <i class="bi bi-chat-text me-2"></i>Motivo
                                </label>
                                <input type="text" class="form-control" id="asig_motivo" name="asig_motivo" placeholder="Ingrese el motivo de la asignación" required>
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
                            <div class="col-auto">
                                <button class="btn btn-info" type="button" id="BtnMostrarRegistros">
                                    <i class="bi bi-eye me-2"></i>Mostrar Registros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center p-3" id="SeccionTablaAsignaciones" style="display:none;">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">ASIGNACIONES REGISTRADAS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAsignaciones">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/asignacion/index.js') ?>"></script>