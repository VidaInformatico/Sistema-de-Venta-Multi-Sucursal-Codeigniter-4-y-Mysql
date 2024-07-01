<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<div class="d-flex justify-content-between">
    <h4 class="" id="titulo">Sucursales</h4>

    <div>
        <p>
            <a href="<?= base_url('sucursal/new'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nuevo
            </a>
        </p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm" id="dataTable" aria-describedby="titulo" style="width: 100%">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Direccion</th>
                <th style="width: 3%"></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($sucursales as $sucursal) : ?>
                <tr>
                    <td><?= esc($sucursal['folio_venta']); ?></td>
                    <td><?= esc($sucursal['nombre']); ?></td>
                    <td><?= $sucursal['telefono']; ?></td>
                    <td><?= $sucursal['correo']; ?></td>
                    <td><?= $sucursal['direccion']; ?></td>
                    <td>
                        <a class='btn btn-warning btn-sm' href='<?= base_url('sucursal/' . $sucursal['id'] . '/edit'); ?>' rel='tooltip' data-bs-placement='top' title='Modificar registro'>
                            <span class='fas fa-edit'></span>
                        </a>
                        <a class='btn btn-danger btn-sm' href='#' onclick="eliminarRegistro(<?php echo $sucursal['id']; ?>)" title='Eliminar registro'>
                            <span class='fas fa-trash'></span>
                        </a>
                    </td>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>

<form action="" method="post" id="form-elimina">
    <input type="hidden" name="_method" value="DELETE">
    <?= csrf_field(); ?>
</form>

<?php
$this->endSection();
$this->section('script');
?>

<script>
    $(document).ready(function(e) {

        $('#dataTable').DataTable({
            "language": {
                "url": "<?= base_url('js/DatatablesSpanish.json'); ?>"
            },
            "pageLength": 10,
            "order": [
                [0, "asc"]
            ]
        });
    });

    function eliminarRegistro(id) {
        Swal.fire({
            title: "Eliminar Registro?",
            text: "Esta seguro de eliminar el registro!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {
                const url = '<?= base_url('sucursal/'); ?>' + id;
                const formElimina = document.querySelector('#form-elimina')
                formElimina.action = url;
                formElimina.submit();
            }
        });

    }
</script>

<?php $this->endSection(); ?>