<?php

$this->extend('plantilla/layout');
$this->section('contentido');

?>

<div class="d-flex justify-content-between">

    <h4 class="" id="titulo">Ventas</h4>

    <div>
        <p>
            <a href="<?php echo site_url('ventas/bajas'); ?>" class="btn btn-danger btn-sm">Anuladas</a>
        </p>
    </div>

</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm" id="dataTable" aria-describedby="titulo" style="width: 100%">
        <thead>
            <tr>
                <th>Serie</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th width="3%"></th>
                <th width="3%"></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($ventas as $venta) : ?>
                <tr>
                    <td><?= $venta['folio']; ?></td>
                    <td><?= $venta['total']; ?></td>
                    <td><?= $venta['fecha']; ?></td>
                    <td><?= esc($venta['usuario']); ?></td>
                    <td>
                        <a href='<?= base_url('ventas/imprimirTicket/' . $venta['id']); ?>' class='btn btn-primary btn-sm' rel='tooltip' data-bs-placement='top' title='Ver ticket'>
                            <span class='fas fa-print'></span>
                        </a>
                    </td>

                    <td>
                        <a class='btn btn-danger btn-sm' href='#' onclick="eliminarRegistro(<?php echo $venta['id']; ?>)" title='Eliminar registro'>
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
                [0, "desc"]
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
                const url = '<?= base_url('ventas/'); ?>' + id;
                const formElimina = document.querySelector('#form-elimina')
                formElimina.action = url;
                formElimina.submit();
            }
        });

    }
</script>

<?php $this->endSection(); ?>