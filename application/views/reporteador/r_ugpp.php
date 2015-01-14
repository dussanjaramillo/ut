<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<h1><?php echo $titulo; ?></h1>
<p><br>
<form id="form1" action="<?php echo base_url('index.php/reporteador/reporte_ugpp') ?>" method="POST" target="_blank" onsubmit="return enviar()">
    <table  border="0" style="margin: 0 auto;">
        <tr>
            <td colspan="2">Fecha piso cálculo Presunta
                <?php
                $data = array(
                    'name' => 'fecha',
                    'id' => 'fecha',
                    'style' => 'width: 100px;',
                    'required' => 'true',
                    'readonly' => 'true',
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio_class" value="1" checked="checked"></td>
            <td>Actualización Información de Contacto y Ubicación Aportantes</td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio_class" value="2"></td>
            <td>Reporte Desagregado</td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio_class" value="3"></td>
            <td>Reporte Consolidado</td>
        </tr>
        <tr>
        <tr>
        <input type="hidden" id="accion" name="accion" value="0">
        <input type="hidden" id="name_reporte" name="name_reporte" value="REPORTE_UGPP">
        <td colspan="2" align="center">
            <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             

            <!--<button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;-->

            <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
            <button id="txt" class="btn btn-info fa fa-table" > TXT</button>
            
        </td>
        </tr>
        </tr>
    </table>
</form>
<div id="resultados"></div>

<script>
    $('#excel').click(function () {
        $('#accion').val("1");
    });
    $('#pdf').click(function () {
        $('#accion').val("2");
    });
    $('#txt').click(function () {
        $('#accion').val("3");
    });
    $('#cunsultar').click(function () {
        $('#accion').val("0");
    });
    function enviar() {
        var accion = $('#accion').val();
        if (accion == 1 || accion == 2 || accion == 3 || accion == 4)
            return true;
        else
            return false;
    }
    function ajaxValidationCallback(status, form, json, options) {

    }
    $('.primer').click(function () {
        $("#table thead *").remove();
        $("#table tbody *").remove();
        $('#accion').val("3");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/reporte_ugpp'); ?>";
        $.post(url, $('#form1').serialize())
                .done(function (msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultados').html(msg);
                }).fail(function (msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
    });
    jQuery(".preload, .load").hide();

    $(document).ready(function () {
        $("#fecha").datepicker({
            showOn: 'button',
            buttonText: 'Fecha piso',
            buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
            buttonImageOnly: true,
            numberOfMonths: 1,
            maxDate: "-1",
            minDate: "-5y",
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
        });
    });
</script>