<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Modificar cliente</h4>

<!-- Mensajes de validación -->
<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form class="row g-3" method="post" action="<?= base_url('clientes/' . $cliente['id']); ?>" autocomplete="off">
    <?= csrf_field(); ?>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-3 mb-3">
        <label for="identidad" class="form-label"><span class="text-danger">*</span> Identidad</label>
        <input type="text" class="form-control" id="identidad" name="identidad" value="<?= set_value('identidad', $cliente['identidad']); ?>" placeholder="N° identidad" required autofocus>
    </div>

    <div class="col-md-9 mb-3">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre', $cliente['nombre']); ?>" placeholder="Nombre" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="apellido" class="form-label"><span class="text-danger">*</span> Apellido</label>
        <input type="text" class="form-control" id="apellido" name="apellido" value="<?= set_value('apellido', $cliente['apellido']); ?>" placeholder="Apellido" required>
    </div>

    <div class="col-md-4 mb-3">
        <label for="telefono" class="form-label"><span class="text-danger">*</span> Telefono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= set_value('telefono', $cliente['telefono']); ?>" placeholder="Telefono">
    </div>

    <div class="col-md-4 mb-3">
        <label for="direccion"><span class="text-danger">*</span> Dirección</label>
        <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Dirección"><?= set_value('direccion', $cliente['direccion']); ?></textarea>
    </div>

    <div class="col-md-12 text-right">
        <a href="<?= base_url('clientes'); ?>" class="btn btn-secondary">Regresar</a>
        <button class="btn btn-success" type="submit">Guardar</button>
    </div>
</form>

<?php
$this->endSection();