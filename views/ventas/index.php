<div class="row justify-content-center p-3">
    <div class="col-lg-10">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <h3 class="text-center mb-2">BIENVENIDO</h3>
                    <h4 class="text-center mb-2 text-primary">Gestión de Ventas</h4>
                </div>

                <div class="row justify-content-center p-5 shadow-lg">

                    <form id="FormVentas">
                        <input type="hidden" id="ven_id" name="ven_id">

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-6">
                                <label for="ven_cliente" class="form-label">
                                    <i class="bi bi-person me-2"></i>Cliente
                                </label>
                                <select class="form-select" id="ven_cliente" name="ven_cliente" required>
                                    <option value="" selected disabled>Seleccione un cliente...</option>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <?php if ($cliente->cli_situacion == 1): ?>
                                            <option value="<?= $cliente->cli_id ?>"><?= $cliente->cli_nombres . ' ' . $cliente->cli_apellidos ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="ven_usuario" class="form-label">
                                    <i class="bi bi-person-gear me-2"></i>Usuario Vendedor
                                </label>
                                <select class="form-select" id="ven_usuario" name="ven_usuario" required>
                                    <option value="" selected disabled>Seleccione un usuario...</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <?php if ($usuario->us_situacion == 1): ?>
                                            <option value="<?= $usuario->us_id ?>"><?= $usuario->us_nombres . ' ' . $usuario->us_apellidos ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-4">
                                <label for="ven_fecha_venta" class="form-label">
                                    <i class="bi bi-calendar-event me-2"></i>Fecha de Venta
                                </label>
                                <input type="datetime-local" class="form-control" id="ven_fecha_venta" name="ven_fecha_venta" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="ven_total" class="form-label">
                                    <i class="bi bi-currency-dollar me-2"></i>Total de Venta
                                </label>
                                <input type="number" class="form-control" id="ven_total" name="ven_total"
                                    placeholder="0.00" step="0.01" min="0" required>
                                <div class="form-text">
                                    Ingrese el monto total de la venta
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-lg-12">
                                <label for="ven_observaciones" class="form-label">
                                    <i class="bi bi-chat-text me-2"></i>Observaciones
                                </label>
                                <textarea class="form-control" id="ven_observaciones" name="ven_observaciones" 
                                    rows="3" placeholder="Ingrese observaciones adicionales sobre la venta"></textarea>
                                <div class="form-text">
                                    Descripción detallada de la venta o comentarios adicionales
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5">
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

<div class="row justify-content-center p-3" id="SeccionTablaVentas" style="display:none;">
    <div class="col-lg-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body p-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <h3 class="text-center text-primary">
                            <i class="bi bi-receipt me-2"></i>VENTAS REGISTRADAS
                        </h3>
                    </div>
                </div>

                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TableVentas">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/ventas/index.js') ?>"></script>