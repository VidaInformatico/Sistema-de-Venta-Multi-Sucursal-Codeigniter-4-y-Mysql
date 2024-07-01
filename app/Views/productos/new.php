<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Nuevo producto</h4>

<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form action="<?= base_url('reportes/procesarImportacion') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="form-group">
        <input type="file" name="archivo_excel" class="form-control mb-2" required />
        <button type="submit" class="btn btn-primary">Importar Productos</button>
        <a href="<?= base_url('assets/productos.xlsx'); ?>" download="formato-productos.xlsx" class="btn btn-success">Descargar Formato</a>
    </div>
</form>

<form class="row g-3" method="post" action="<?= base_url('productos'); ?>" autocomplete="off">

    <?= csrf_field(); ?>

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-3 mb-3">
        <label for="codigo" class="form-label"><span class="text-danger">*</span> Código de barras</label>
        <input type="text" class="form-control" id="codigo" name="codigo" value="<?= set_value('codigo'); ?>" placeholder="Código" required autofocus>
    </div>

    <div class="col-md-9 mb-3">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre'); ?>" placeholder="Nombre" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="precio" class="form-label"><span class="text-danger">*</span> Precio</label>
        <input type="text" class="form-control" id="precio" name="precio" value="<?= set_value('precio'); ?>" placeholder="Precio" onkeypress="return validateDecimal(this.value);" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="inventariable"><span class="text-danger">*</span> Es inventariable</label>
        <select class="form-control" name="inventariable" id="inventariable" required>
            <option value="1">Si</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label for="existencia" class="form-label">Stock Actual</label>
        <input type="text" class="form-control" id="existencia" name="existencia" placeholder="Stock Actual" value="<?= set_value('existencia'); ?>">
    </div>

    <div class="col-md-12 text-right">
        <a href="<?= base_url('productos'); ?>" class="btn btn-secondary">Regresar</a>
        <button class="btn btn-success" type="submit">Guardar</button>
    </div>
</form>

<?php
$this->endSection();
$this->section('script');
?>

<script>
    document.addEventListener("keypress", function(e) {
        let code = e.keyCode || e.which;
        if (code === 13) {
            e.preventDefault();
            return false;
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        let inventariableSelect = document.getElementById("inventariable");
        let existenciaInput = document.getElementById("existencia");

        inventariableSelect.addEventListener("change", function() {
            let option = inventariableSelect.options[inventariableSelect.selectedIndex].value;

            if (option == 1) {
                existenciaInput.readOnly = false;
            } else {
                existenciaInput.readOnly = true;
            }

            existenciaInput.value = 0;
        });
    });

    function validateDecimal(valor) {
        let re = /^\d*\.?\d*$/;
        return re.test(valor);
    }
</script>


<?php $this->endSection(); ?>