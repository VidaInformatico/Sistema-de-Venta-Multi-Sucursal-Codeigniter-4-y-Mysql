<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<h4 class="mt-3">Modificar usuario</h4>

<!-- Mensajes de validación -->
<!-- Mensajes de validación -->
<?php if (session()->getFlashdata('errors') !== null) : ?>
    <div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
        <?= session()->getFlashdata('errors'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<form class="row g-3" method="post" action="<?= base_url('usuarios/' . $usuario['id']); ?>" autocomplete="off">
    <?= csrf_field(); ?>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

    <div class="col-12">
        <p class="fst-italic">
            Campos marcados con asterisco (<span class="text-danger">*</span>) son obligatorios.
        </p>
    </div>

    <div class="col-md-3 mb-3">
        <label for="usuario" class="form-label"><span class="text-danger">*</span> Usuario</label>
        <input type="text" class="form-control" id="usuario" name="usuario" value="<?= set_value('usuario', $usuario['usuario']); ?>" placeholder="Usuario" required autofocus>
    </div>

    <div class="col-md-9 mb-3">
        <label for="nombre" class="form-label"><span class="text-danger">*</span> Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= set_value('nombre', $usuario['nombre']); ?>" placeholder="Nombre" required>
    </div>

    <div class="form-group col-md-4 mb-3">
        <label for="id_sucursal">Sucursal</label>
        <select id="id_sucursal" class="form-control" name="id_sucursal">
            <option value="">Seleccionar</option>
            <?php foreach ($sucursales as $sucursal) { ?>
                <option value="<?php echo $sucursal['id']; ?>" <?= ($sucursal['id'] == $usuario['id_sucursal']) ? 'selected' : ''; ?>><?php echo $sucursal['nombre']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-3 mb-3">
        <label>SuperAdmin</label>
        <div class="checkbox-wrapper-8">
            <input class="tgl tgl-skewed" id="cb3-8" <?php echo ($usuario['id_rol'] == null) ? 'checked' : ''; ?> value="1" name="superadmin" type="checkbox" />
            <label class="tgl-btn" data-tg-off="OFF" data-tg-on="ON" for="cb3-8"></label>
        </div>
    </div>

    <div class="form-group col-md-4 mb-3 <?php echo ($usuario['id_rol'] == null) ? 'd-none' : ''; ?>" id="rolsuper">
        <label for="id_rol">Rol</label>
        <select id="id_rol" class="form-control" name="id_rol">
            <option value="">Seleccionar</option>
            <?php foreach ($roles as $rol) { ?>
                <option value="<?php echo $rol['id']; ?>" <?= ($rol['id'] == $usuario['id_rol']) ? 'selected' : ''; ?>><?php echo $rol['nombre']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="col-md-12 text-right">
        <a href="<?= base_url('usuarios'); ?>" class="btn btn-secondary">Regresar</a>
        <button class="btn btn-success" type="submit">Guardar</button>
    </div>
</form>

<?php
$this->endSection();
$this->section('script');
?>

<script>
    const superadmin = document.querySelector('#cb3-8')
    const rolsuper = document.querySelector('#rolsuper')
    superadmin.addEventListener('change', function(e) {
        if (!e.target.checked) {            
            rolsuper.classList.remove('d-none');
        } else {
            id_rol.value = 1;
            rolsuper.classList.add('d-none');
        }
    })
</script>


<?php $this->endSection(); ?>