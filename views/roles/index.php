<div class="row justify-content-center p-3">
    <div class="col-lg-8">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">GESTIÓN DE ROLES</h3>
                    <h4 class="text-center mb-2 text-primary">Administrar Roles del Sistema</h4>
                </div>

                <div class="row justify-content-center p-4 shadow-lg">
                    <form id="FormRoles">
                        <input type="hidden" id="rol_id" name="rol_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="rol_nombre" class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>Nombre del Rol
                                </label>
                                <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" 
                                       placeholder="Ej. Administrador del Sistema" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="rol_nombre_ct" class="form-label">
                                    <i class="bi bi-tag me-2"></i>Nombre Corto
                                </label>
                                <input type="text" class="form-control" id="rol_nombre_ct" name="rol_nombre_ct" 
                                       placeholder="Ej. ADMIN" required>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-auto">
                                <button class="btn btn-success" type="submit" id="BtnGuardar">
                                    <i class="bi bi-floppy me-2"></i>Guardar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-warning d-none" type="button" id="BtnModificar">
                                    <i class="bi bi-pencil me-2"></i>Modificar
                                </button>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-secondary" type="reset" id="BtnLimpiar">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
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

<div class="row justify-content-center p-3" id="SeccionTablaRoles" style="display: none">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center mb-4">
                    <i class="bi bi-list-ul me-2"></i>ROLES REGISTRADOS
                </h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableRoles">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/roles/index.js') ?>"></script>