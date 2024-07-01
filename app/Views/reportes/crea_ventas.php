<?php

$this->extend('plantilla/layout');

$this->section('contentido');
?>

<h4 class="mt-3">Reporte de ventas</h4>

<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
    </div>
<?php endif ?>

<form id="form_venta" method="post" action="<?php echo base_url('reportes/ventas'); ?>" autocomplete="off">
    <?= csrf_field(); ?>

    <div class="col-12 mt-3">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-12 col-sm-4">
                <label><span class="text-danger">*</span> Fecha de inicio:</label>
                <input type='date' id="fecha_inicio" name="fecha_inicio" class="form-control" required1>
            </div>

            <div class="col-12 col-sm-4">
                <label><span class="text-danger">*</span> Fecha de fin:</label>
                <input type='date' id="fecha_fin" name="fecha_fin" class="form-control" required1>
            </div>


            <div class="col-12 col-sm-4">
                <label><span class="text-danger">*</span> Estado:</label>
                <select name="estado" id="estado" class="form-control" required1>
                    <option value="1">Activas</option>
                    <option value="0">Canceladas</option>
                </select>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">Generar</button>
    </div>
</form>

<?php $this->endSection(); ?>