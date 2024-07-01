<?php

$idVentaTmp = uniqid();

$this->extend('plantilla/layout');
$this->section('style');
?>

<style>
    .label-total {
        font-weight: bold;
        font-size: 30px;
        text-align: center;
    }

    #total {
        font-weight: bold;
        font-size: 30px;
        text-align: center;
        border: #E2EBED;
        background: #ffffff;
    }
</style>

<?php
$this->endSection();
$this->section('contentido');
?>

<div class="row mt-4">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <form id="form_venta" name="form_venta" method="post" action="<?php echo base_url('ventas'); ?>" autocomplete="off">
            <?= csrf_field(); ?>
            <input type="hidden" id="id_venta" name="id_venta" value="<?php echo $idVentaTmp; ?>">

            <div class="form-group">
                <div class="row">
                    <div class="col-md-8 border p-5">
                        <div class="input-group mb-3">
                            <input class="form-control" id="codigo" name="codigo" type="text" placeholder="Escribe el código y presiona enter" autofocus>
                        </div>
                        <label for="codigo" id="resultado_error" style="color:red"></label>
                        <br>
                        <div class="table-responsive">
                            <table id="tablaProductos" class="table table-hover table-striped table-sm table-responsive tablaProductos align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>C&oacute;digo</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Cant</th>
                                        <th>Total</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="no-productos">
                                        <td colspan="7" class="text-center">NO HAY PRODUCTOS</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-md-4 border p-5">
                        <input type="hidden" id="id_cliente" name="id_cliente">
                        <div class="form-group mb-2">
                            <input class="form-control" id="cliente" name="cliente" type="text" placeholder="Buscar Cliente" aria-label="cliente" aria-describedby="basic-addon2">
                        </div>
                        <label for="cliente" id="resultado_error1" style="color:red"></label>
                        <br>
                        <div class="form-group mb-2">
                            <span class="input-group-text" id="basic-addon4">Nombre</span>
                            <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Nombre" aria-label="nombre" aria-describedby="basic-addon4" disabled>
                        </div>
                        <div class="form-group mb-2">
                            <span class="input-group-text" id="basic-addon3">Telefono</span>
                            <input class="form-control" id="telefono" name="telefono" type="text" placeholder="Telefono" aria-label="telefono" aria-describedby="basic-addon3" disabled>
                        </div>

                        <div class="text-center mb-2">
                            <label class='label-total'> Total <?php echo env('SIMMONEY'); ?></label>
                            <input type="text" name="total" id="total" size="7" readonly="true" value="0.00">
                        </div>

                        <div class="d-grid">
                            <button id="completa_venta" type="button" class="btn btn-success btn-block">Completar venta</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<?php
$this->endSection();
$this->section('script');
?>

<script type="text/javascript">
    const idVenta = '<?php echo $idVentaTmp; ?>'
    const baseUrl = '<?php echo base_url(); ?>'

    $(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        $("#codigo").autocomplete({
            source: baseUrl + '/productos/autocompleteData',
            minLength: 3,
            focus: function() {
                return false;
            },
            select: function(event, ui) {
                event.preventDefault();
                $("#codigo").val(ui.item.value);
                setTimeout(
                    function() {
                        e = jQuery.Event("keypress");
                        e.which = 13;
                        enviaProducto(e, ui.item.value, 1);
                    }, 500);
            }
        });

        $("#cliente").autocomplete({
            source: baseUrl + '/clientes/autocompleteData',
            minLength: 3,
            focus: function() {
                return true;
            },
            select: function(event, ui) {
                $("#id_cliente").val(ui.item.id);
                $("#nombre").val(ui.item.label);
                $("#telefono").val(ui.item.telefono);

            }
        });

        $("#codigo").on("keyup", function(event) {
            enviaProducto(event, this.value, 1);
        });

        $("#completa_venta").click(function() {
            var nFilas = $("#tablaProductos tbody tr:not(.no-productos)").length;

            if (nFilas < 1) {
                Swal.fire({
                    title: 'Aviso',
                    text: 'Por favor, agregue al menos un producto antes de completar la venta.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                });
                return; // Agregamos esta línea para detener la ejecución si no hay productos
            }

            $("#form_venta").submit();
        });

    });

    function enviaProducto(e, codigo, cantidad) {
        let enterKey = 13;

        if (e.which === enterKey && codigo && cantidad > 0) {
            agregarProducto(codigo, cantidad);
        }
    }

    function agregarProducto(codigo, cantidad) {
        $.ajax({
            method: "POST",
            url: baseUrl + '/nuevaventa/inserta',
            data: {
                '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>',
                codigo: codigo,
                cantidad: cantidad,
                id_venta: idVenta
            },
            success: function(response) {
                if (response && response != "") {
                    $('#codigo').autocomplete('close');
                    $("#codigo").val('');

                    var resultado = JSON.parse(response);

                    $("#resultado_error").html(resultado.error);
                    $('#tablaProductos tbody').empty();
                    $("#tablaProductos tbody").append(resultado.datos);
                    $("#total").val(resultado.total);
                }
            }
        });
        $("#codigo").focus();
    }

    function eliminaProducto(idProducto, idVenta) {
        $.ajax({
            method: "POST",
            url: baseUrl + '/nuevaventa/elimina',
            data: {
                '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>',
                id_producto: idProducto,
                id_venta: idVenta,
            },
            success: function(response) {
                if (response && response != "") {
                    $('#codigo').val('');

                    var resultado = JSON.parse(response);

                    $("#resultado_error").html('');
                    $('#tablaProductos tbody').empty();
                    $("#tablaProductos tbody").append(resultado.datos);
                    $("#total").val(resultado.total);
                }
            }
        });
        $("#codigo").focus();
    }
</script>



<?php $this->endSection(); ?>