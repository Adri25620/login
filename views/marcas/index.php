<div class="row justify-content-center p-3">
    <div class="col-lg-8">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">GESTIÓN DE MARCAS</h3>
                    <h4 class="text-center mb-2 text-success">Registro de Marcas de Celulares</h4>
                </div>

                <div class="row justify-content-center p-4 shadow-lg">
                    <form id="FormMarcas">
                        <input type="hidden" id="mar_id" name="mar_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="mar_nombre" class="form-label">Nombre de la Marca</label>
                                <input type="text" class="form-control" id="mar_nombre" name="mar_nombre" 
                                       placeholder="Ej. Samsung, iPhone, Xiaomi" required>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="mar_descripcion" class="form-label">Descripcion</label>
                                <input type="text" class="form-control" id="mar_descripcion" name="mar_descripcion" placeholder="Descripcion detallada">
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

<div class="row justify-content-center p-3" id="SeccionTablaMarcas" style="display: none">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
            <div class="card-body p-3">
                <h3 class="text-center mb-4">
                    <i class="bi bi-list-ul me-2"></i>MARCAS REGISTRADAS
                </h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableMarcas">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/marcas/index.js') ?>"></script>