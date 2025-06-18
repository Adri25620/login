<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Inventario de Celulares</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormInventario">
                        <input type="hidden" id="inv_id" name="inv_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="inv_modelo" class="form-label">
                                    <i class="bi bi-device-ssd me-2"></i>Modelo
                                </label>
                                <input type="text" class="form-control" id="inv_modelo" name="inv_modelo" placeholder="Ingrese el modelo del celular" required>
                            </div>
                            <div class="col-lg-6">
                                <label for="inv_marca" class="form-label">
                                    <i class="bi bi-tag me-2"></i>Marca
                                </label>
                                <select class="form-select" id="inv_marca" name="inv_marca" required>
                                    <option value="" selected disabled>Seleccione una marca...</option>
                                    <?php foreach ($marcas as $marca): ?>
                                        <?php if ($marca->mar_situacion == 1): ?>
                                            <option value="<?= $marca->mar_id ?>"><?= $marca->mar_nombre ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="inv_precio" class="form-label">
                                    <i class="bi bi-currency-dollar me-2"></i>Precio
                                </label>
                                <input type="number" class="form-control" id="inv_precio" name="inv_precio" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="inv_stock" class="form-label">
                                    <i class="bi bi-boxes me-2"></i>Stock
                                </label>
                                <input type="number" class="form-control" id="inv_stock" name="inv_stock" min="0" placeholder="0" required>
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

<div class="row justify-content-center p-3" id="SeccionTablaInventario" style="display:none;">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <h3 class="text-center">INVENTARIO REGISTRADO</h3>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableInventario">
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/inventario/index.js') ?>"></script>