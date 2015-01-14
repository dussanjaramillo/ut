<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<p>
<center><h1>Reporte Historico Pagos por Cartera</h1></center>
<form id="form1" action="<?php echo base_url('index.php/reporteador/reporte') ?>" method="POST"  onsubmit="return enviar()">
    <!--target="_blank"-->
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <div>
        <table width="100%">
            <tr>
                <td>
                    Numero de identificaci&oacute;n
                </td>
                <td>
                    <input type="text" id="empleado" name="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>">
                    <img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /> <button id="buscar" class="btn btn-success">Buscar</button>
                </td>
            </tr>
            <tr>
                <td>
                    Tipo de Cartera
                </td>
                <td >
                    <?php $datos = Reporteador::burcar_no_misional2(isset($_POST['empleado']) ? $_POST['empleado'] : '') ?>
                    <select id="concepto2" name="concepto2">
                        <option value=""></option>
                        <?php
                        foreach ($datos as $concepto) {
                            if (isset($_POST['concepto2'])) {
                                if ($_POST['concepto2'] == $concepto['COD_TIPOCARTERA']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else {
                                $selec = "";
                            }
                            ?>
                            <option <?php echo $selec; ?>  value="<?php echo $concepto['COD_TIPOCARTERA'] ?>"><?php echo $concepto["TIPO_CARTERA"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Identificacion de la Deuda
                </td>
                <td>
                    <?php $datos = Reporteador::burcar_no_misional_deuda2(isset($_POST['concepto2']) ? $_POST['concepto2'] : '', isset($_POST['empleado']) ? $_POST['empleado'] : '') ?>
                    <select name="id_deuda"  id="id_deuda">
                        <option value=""></option>
                        <?php
                        foreach ($datos as $concepto) {
                            if (isset($_POST['id_deuda'])) {
                                if ($_POST['id_deuda'] == $concepto['COD_CARTERA_NOMISIONAL']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else {
                                $selec = "";
                            }
                            ?>
                            <option <?php echo $selec; ?>  value="<?php echo $concepto['COD_CARTERA_NOMISIONAL'] ?>"><?php echo $concepto["COD_CARTERA_NOMISIONAL"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
            <input type="hidden" name="reporte" id="reporte" value="80">
            <input type="hidden" id="accion" name="accion" value="0">
            <input type="hidden" id="name_reporte" name="name_reporte" value="">
            <td colspan="2" align="center">
                <button class="primer2 fa fa-bar-chart-o btn btn-success" align="center" > Consultar</button>&nbsp;&nbsp;

                <button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

                <button id="excel" class="btn btn-info fa fa-table" > Excel</button>&nbsp;&nbsp;
                <button id="limpiar" class="btn btn fa fa-eraser " onclick="formReset();" > Limpiar</button>
            </td>
            </tr>
        </table>
    </div>
</form>
<div id="resultado"></div>
<script>

    $('#empleado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $("#empleado").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteempleado") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini9").show();
        },
        response: function(event, ui) {
            $("#preloadmini9").hide();
        }
    });
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#limpiar').click(function() {
        $('#accion').val("6");
    });
    function enviar() {
        var empleado = $('#empleado').val();
        var concepto2 = $('#concepto2').val();
        var id_deuda = $('#id_deuda').val();
        if (id_deuda == '' || concepto2 == '' || empleado == '') {
            alert('Datos Incompletos');
            return false;
        }
        return true;
    }

    $("#buscar").click(function() {
        var idusuario = $('#empleado').val();
        if (idusuario == "") {
            alert('Campo Identificación Vacío');
            $('#concepto2').html('');
            $('#id_deuda').html('');
            return false;
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url("index.php/reporteador/burcar_no_misional") ?>";
        $.post(url, {idusuario: idusuario})
                .done(function(msg) {
                    $('#concepto2').html(msg);
                    $('#id_deuda').html('');
                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Datos no Encontrados');
            jQuery(".preload, .load").hide();
        })
        return false;
    });
    function formReset()
    {
        var vista = $('#vista').val();
        var pagina="<?php echo base_url('index.php/reporteador/'); ?>" + "/" + vista;
        alert(pagina)
        location.href = pagina;
        return false;
    }
    $("#concepto2").change(function() {
        var concepto2 = $("#concepto2").val();
        if (concepto2 == "") {
            $('#id_deuda').html('');
            return false;
        }
        jQuery(".preload, .load").show();
        var idusuario = $('#empleado').val();
        var url = "<?php echo base_url("index.php/reporteador/burcar_no_misional_deuda") ?>";
        $.post(url, {idusuario: idusuario, concepto2: concepto2})
                .done(function(msg) {
                    $('#id_deuda').html(msg);
                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Datos no Encontrados');
            jQuery(".preload, .load").hide();

        })
    });
    $("#preloadmini9").hide();
    jQuery(".preload, .load").hide();

    function ajaxValidationCallback(status, form, json, options){
}
    
</script>
