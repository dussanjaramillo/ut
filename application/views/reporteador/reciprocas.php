<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>

</div>

<form id="form1" action="<?php echo base_url('index.php/reporteador/reporte') ?>" method="POST" target="_blank" onsubmit="return enviar()">
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <table width="80%" border="0" style="margin: 0 auto;">
        <tr>
            <td>Año</td>
            <td>
                <select id="ano_salario" name="ano_salario" style="width: 70px">
                    <?php
                    $anos = date('Y');
                    for ($i = 0; $i < 6; $i++) {
                        ?>
                        <option value="<?php echo $anos - $i ?>"><?php echo $anos - $i ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Trimestre</td>
            <td>
                <select id="trimestre" name="trimestre" style="width: 100px">
                    <option value="">Todos</option>
                    <option value="03">Primero</option>
                    <option value="06">Segundo</option>
                    <option value="09">Tercero</option>
                    <option value="12">Cuarto</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>concepto</td>
            <td>
                <?php $datos = Reporteador::concepto_general() ?>
                <select id="concepto" name="concepto">
                    <option value="">Todos</option>
                    <?php
                    foreach ($datos as $concepto) {
                        ?>
                        <option value="<?php echo $concepto['COD_TIPOCONCEPTO'] ?>"><?php echo $concepto["NOMBRE_TIPO"] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>sub Concepto</td>
            <td><select id="sub_concepto" name="sub_concepto">
                <option value="">Todos</option>
                </select></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="hidden" id="accion" name="accion" value="0">
                <input type="hidden" id="reporte" name="reporte" value="74">
                <input type="hidden" id="name_reporte" name="name_reporte" value="">
                <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             

                <button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

                <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
            </td>
        </tr>
    </table>
</form>
<div id="resultados"></div>

<script>
    jQuery(".preload, .load").hide();
    function ajaxValidationCallback(status, form, json, options) {

    }
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });
    function enviar() {
        var accion = $('#accion').val();
        if (accion == 1 || accion == 2)
            return true;
        else
            return false;
    }
    $('.primer').click(function() {

        $("#table thead *").remove();
        $("#table tbody *").remove();
        $('#accion').val("3");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/reporte'); ?>";
        $.post(url, $('#form1').serialize())
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultados').html(msg);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
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
</script>