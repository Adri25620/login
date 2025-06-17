<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #17a2b8;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">GESTION DE CLIENTES</h3>
                    <h4 class="text-center mb-2 text-info">Registro de Clientes de la Tienda</h4>
                </div>

                <div class="row justify-content-center p-4 shadow-lg">
                    <form id="FormClientes">
                        <input type="hidden" id="cli_id" name="cli_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="cli_nombres" class="form-label">Nombres Completos</label>
                                <input type="text" class="form-control" id="cli_nombres" name="cli_nombres"
                                    placeholder="Ej. Juan" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="cli_apellidos" class="form-label">Apellidos Completos</label>
                                <input type="text" class="form-control" id="cli_apellidos" name="cli_apellidos"
                                    placeholder="Ej. Garcia" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="cli_nit" class="form-label">NIT</label>
                                <input type="text" class="form-control" id="cli_nit" name="cli_nit"
                                    placeholder="Ej. 12345678-9">
                            </div>
                            <div class="col-lg-4">
                                <label for="cli_telefono" class="form-label">Telefono</label>
                                <input type="number" class="form-control" id="cli_telefono" name="cli_telefono"
                                    placeholder="Ej. 12345678">
                            </div>
                            <div class="col-lg-4">
                                <label for="cli_correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="cli_correo" name="cli_correo"
                                    placeholder="ej. cliente@correo.com">
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="cli_direccion" class="form-label">Direccion Completa</label>
                                <input type="text" class="form-control" id="cli_direccion" name="cli_direccion"
                                    placeholder="Direccion completa del cliente">
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

<div class="row justify-content-center p-3"  id="SeccionTablaClientes" style="display: none">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #17a2b8;">
            <div class="card-body p-3">
                <h3 class="text-center mb-4">
                    <i class="bi bi-people me-2"></i>CLIENTES REGISTRADOS
                </h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableClientes">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/clientes/index.js') ?>"></script>