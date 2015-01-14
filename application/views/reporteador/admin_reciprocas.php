<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>

</div>

<table width="80%" border="0" style="margin: 0 auto;">
    <tr>
        <td>Concepto</td>
        <td>Sub Concepto</td>
        <td>Cuenta</td>
        <td>Estado</td>
    </tr>
    <tr>
        <td>
            <?php $datos = Reporteador::concepto_general() ?>
            <select id="concepto" name="concepto">
                <option value=""></option>
                <?php
                foreach ($datos as $concepto) {
                    ?>
                    <option value="<?php echo $concepto['COD_TIPOCONCEPTO'] ?>"><?php echo $concepto["NOMBRE_TIPO"] ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
        <td><select id="sub_concepto" name="sub_concepto"></select></td>
        <td>
            <?php $datos = Reporteador::cuentas_contables(); ?>
            <select id="cuenta" name="cuenta">
                <option value=""></option>
                <?php
                foreach ($datos as $concepto) {
                    ?>
                    <option value="<?php echo $concepto['COD_CUENTA'] ?>"><?php echo $concepto['COD_CUENTA'] ?> - <?php echo $concepto["NOMBRE_CUENTA"] ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
        <td>
            <input type="hidden" id="valor_estado" name="valor_estado" value="0">
            <table width="100%">
                <tr>
                    <td>
                        <input type="radio" class="valor_estado" name="corriente" value="0" checked="checked">
                    </td>
                    <td>
                        Valor Corriente
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" class="valor_estado" name="corriente" value="1">
                    </td>
                    <td>
                        Valor no Corriente
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <div id="resultado"></div>
        </td>
    </tr>
    <tr>
        <td colspan="4" align="center">
            <button id="guardar" class="btn btn-success">Guardar</button>
        </td>
    </tr>
</table>
<br><p>
<div id="tabla">
    <?php echo $tables; ?>
</div>

<script>
    $('#tablas').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "iDisplayLength": 10,
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null,
        },
    });
    jQuery(".preload, .load").hide();

    $('.valor_estado').click(function() {
        $('#valor_estado').val(this.value);
    });
    $('#concepto').change(function() {
        var concepto = this.value;
        if (concepto == '')
            return false;
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/traer_subconcepto'); ?>";
        $.post(url, {concepto: concepto})
                .done(function(msg) {
                    $('#sub_concepto').html('');
                    jQuery(".preload, .load").hide();
                    $('#sub_concepto').append(msg);
                }).fail(function(msg) {
            alert('Datos no encontrados');
            jQuery(".preload, .load").hide();
        })
    });
    $('#sub_concepto').change(function() {
        mirar_conceptos();
    });
    $('#cuenta').change(function() {
        mirar_conceptos();
    });
    function mirar_conceptos() {
        var sub_concepto = $('#sub_concepto').val();
        var cuenta = $('#cuenta').val();
        if (sub_concepto == "" || cuenta == "") {
            return false;
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/info_subconcepto'); ?>";
        $.post(url, {sub_concepto: sub_concepto, cuenta: cuenta})
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultado').html(msg);
                }).fail(function(msg) {
            alert('Datos no encontrados');
            jQuery(".preload, .load").hide();
        })
    }

    $('#guardar').click(function() {
        var concepto = $('#concepto').val();
        var sub_concepto = $('#sub_concepto').val();
        var cuenta = $('#cuenta').val();
        var hay = $('#hay').val();
        if (concepto == '' || sub_concepto == '' || cuenta == '') {
            alert('Todos los Campos son Obligatorios');
            return false;
        }
        if (hay == 0)
            guardar();
        else {
            var r = confirm('La Cuenta ya Tiene Asociada un Concepto Desea Reemplazarlo');
            if (r == true)
                guardar();
        }

    });
    function guardar() {
        var sub_concepto = $('#sub_concepto').val();
        var cuenta = $('#cuenta').val();
        var valor_estado = $('#valor_estado').val();
        var url = "<?php echo base_url('index.php/reporteador/guardar_concepto_contable'); ?>";
        jQuery(".preload, .load").show();
        $.post(url, {sub_concepto: sub_concepto, cuenta: cuenta, valor_estado: valor_estado})
                .done(function(msg) {
                    $('#resultado').html('');
                    $('#concepto').val('');
                    $('#cuenta').val('');
                    $('#sub_concepto').html('');
                    $('#tabla').html(msg);
                    window.location.reload();
                }).fail(function(msg) {
            alert('Datos no encontrados');
            jQuery(".preload, .load").hide();
        })
    }

</script>