<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Nuevo sucursal</h4>

<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form class="row g-3" method="post" action="<?= base_url('sucursal'); ?>" autocomplete="off">

    <?= csrf_field(); ?>

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-9 mb-3">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre'); ?>" placeholder="Nombre" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="correo" class="form-label"><span class="text-danger">*</span> Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" value="<?= set_value('correo'); ?>" placeholder="Correo" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="telefono" class="form-label"><span class="text-danger">*</span> Telefono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= set_value('telefono'); ?>" placeholder="Telefono">
    </div>

    <div class="col-md-4 mb-3">
        <label for="folio_venta" class="form-label"><span class="text-danger">*</span> Folio</label>
        <input type="text" class="form-control" id="folio_venta" name="folio_venta" value="<?= set_value('folio_venta'); ?>" placeholder="Folio">
    </div>

    <div class="col-md-6 mb-3">
        <label for="direccion"><span class="text-danger">*</span> Dirección</label>
        <textarea id="direccion" class="form-control" name="direccion" rows="2" placeholder="Dirección"></textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label for="mensaje"><span class="text-danger">*</span> Mensaje</label>
        <textarea id="mensaje" class="form-control" name="mensaje" rows="2" placeholder="Mensaje"></textarea>
    </div>

    <div class="col-md-12 text-right">
        <a href="<?= base_url('sucursal'); ?>" class="btn btn-secondary">Regresar</a>
        <button class="btn btn-success" type="submit">Guardar</button>
    </div>
</form>

<?php
$this->endSection();