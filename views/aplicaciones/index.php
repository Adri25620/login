<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Registro de Aplicaciones</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormAplicaciones">
                        <input type="hidden" id="ap_id" name="ap_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="ap_nombre_lg" class="form-label">Nombre Largo</label>
                                <input type="text" class="form-control" id="ap_nombre_lg" name="ap_nombre_lg" placeholder="Ingrese el nombre largo de la aplicación">
                            </div>
                            <div class="col-lg-6">
                                <label for="ap_nombre_md" class="form-label">Nombre Medio</label>
                                <input type="text" class="form-control" id="ap_nombre_md" name="ap_nombre_md" placeholder="Ingrese el nombre medio de la aplicación">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="ap_nombre_ct" class="form-label">Nombre Corto</label>
                                <input type="text" class="form-control" id="ap_nombre_ct" name="ap_nombre_ct" placeholder="Ingrese el nombre corto de la aplicación">
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

<div class="row justify-content-center p-3" id="SeccionTablaAplicaciones" style="display:none;">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">APLICACIONES REGISTRADAS</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableAplicaciones">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/aplicaciones/index.js') ?>"></script>