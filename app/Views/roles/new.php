<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Nuevo rol</h4>

<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form class="row g-3" method="post" action="<?= base_url('roles'); ?>" autocomplete="off">

    <?= csrf_field(); ?>

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-4">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre'); ?>" placeholder="Nombre" required>
    </div>

    <div class="form-group col-md-8">
        <label for="permisos">Permisos <span class="text-danger">*</span></label>
        <?php foreach ($permisos as $permiso) { ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permisos[]" value="<?php echo $permiso['permiso']; ?>" id="permiso_<?php echo $permiso['id']; ?>">
                <label class="form-check-label" for="permiso_<?php echo $permiso['id']; ?>">
                    <?php echo ucfirst($permiso['permiso']); ?>
                </label>
            </div>
        <?php } ?>
    </div>


    <div class="col-md-12 text-right">
        <a href="<?= base_url('roles'); ?>" class="btn btn-secondary">Regresar</a>
        <button class="btn btn-success" type="submit">Guardar</button>
    </div>
</form>

<?php
$this->endSection(); ?>