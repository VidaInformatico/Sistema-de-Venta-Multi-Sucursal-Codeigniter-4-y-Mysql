<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Modificar rol</h4>

<!-- Mensajes de validación -->
<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form class="row g-3" method="post" action="<?= base_url('roles/' . $rol['id']); ?>" autocomplete="off">
    <?= csrf_field(); ?>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="<?= $rol['id'] ?>">

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-4">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre', $rol['nombre']); ?>" placeholder="Nombre" required>
    </div>

    <div class="form-group col-md-8">
        <label for="permisos">Permisos <span class="text-danger">*</span></label>
        <?php
        $lista = json_decode($rol['permisos'], true);
        $permisos = $permisos;
        for ($i = 0; $i < count($permisos); $i++) {
        ?>
            <div class="form-check">
                <?php
                $permiso = $permisos[$i];
                $checked = (verificar($permiso, $activos)) ? 'checked' : '';
                ?>
                <input class="form-check-input" type="checkbox" name="permisos[]" <?php echo $checked; ?> value="<?php echo $permiso; ?>" id="permiso_<?php echo $i; ?>">
                <label class="form-check-label" for="permiso_<?php echo $i; ?>">
                    <?php echo ucfirst($permiso); ?>
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