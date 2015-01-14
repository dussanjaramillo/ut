<?php
$dataguardar = array('name' => 'button', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success');
$datacancel = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/devolucion/devoluciones_generadas\';', 'content' => '<i class="fa fa-minus-circle"></i> Cancelar', 'class' => 'btn btn-warning');
$datacamara = array('name' => 'camara', 'id' => 'camara', 'checked' => TRUE, 'style' => 'margin:10px');
$datanit = array('name' => 'nit', 'id' => 'nit', 'checked' => TRUE, 'style' => 'margin:10px');
$datasoporte = array('name' => 'soporte', 'id' => 'soporte', 'checked' => TRUE, 'style' => 'margin:10px');
$datarespuesta = array('name' => 'respuesta', 'id' => 'respuesta', 'checked' => TRUE, 'style' => 'margin:10px');
$databanco = array('name' => 'banco', 'id' => 'banco', 'checked' => TRUE, 'style' => 'margin:10px');
$verificacion = array('name' => 'verificacion', 'id' => 'verificacion', 'type' => 'hidden','checked' => TRUE);
$devolucion = array('name' => 'devolucion', 'id' => 'devolucion', 'type' => 'hidden', 'value' => $devolucion);
$tipo = array('name' => 'tipo', 'id' => 'tipo', 'type' => 'hidden', 'value' => $tipo);
$attributes = array('name' => 'devolucionFrm', 'id' => 'devolucionFrm', 'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open_multipart(current_url(), $attributes);
echo form_input($verificacion);
echo form_input($devolucion);
echo form_input($tipo);
?>
<?php
switch ($tipo_devolucion) {
    case 'N':
        ?>
        <table align='center'>
            <tr>
                <td align='center' colspan='2'><h2>Documentos</h2></td>
            </tr>
            <tr>
                <td><b>Documento de Identificación</b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($datacamara, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datacamara, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Comprobante de Pago:</b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($datanit, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datanit, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Certificacion Bancaria: </b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($databanco, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($databanco, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Documentos Soporte: </b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($datasoporte, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datasoporte, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan='2' align='center'>
                    <div style="display:none" id="error" class="alert alert-danger"></div>
                </td>
            </tr>

        </table>
        <br><br>
        <table  width="500" border="0" align="center">
            <tr>
                <td colspan="4" align='center'>
                    <div style="display:none" id="error" class="alert alert-danger"></div>
                </td>
            </tr>
            <tr>
                <td align='center' colspan="4"><?=
                    form_button($dataguardar) . "&nbsp;&nbsp;";
                    echo form_button($datacancel);
                    ?></td>
            </tr>
        </table>
        <?php
        break;

    default:
        ?>
        <table align='center'>
            <tr>
                <td align='center' colspan='2'><h2>Documentos</h2></td>
            </tr>
            <tr>
                <td><b>Camara y Comercio</b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($datacamara, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datacamara, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>RUT</b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($datanit, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($datanit, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td><b>Soporte de Banco</b></td>
                <td>
                    <?=
                    "&nbsp;" . form_radio($databanco, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                    echo "&nbsp;" . form_radio($databanco, "0") . "&nbsp;&nbsp;NO";
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan='2' align='center'>
                    <div style="display:none" id="error" class="alert alert-danger"></div>
                </td>
            </tr><tr><td><br></td></tr>
            <tr>
                <td align='center' colspan='2'>
                    <?=
                    form_button($dataguardar) . "&nbsp;&nbsp;";
                    echo form_button($datacancel);
                    ?>
                </td>
            </tr>
        </table>
        <?php
        break;
}
?>


<?php echo form_close(); ?>
<script>
    $('#guardar').click(function() {
        var camara = $('input[name="camara"]:checked').val();
        var nit = $('input[name="nit"]:checked').val();
        var banco = $('input[name="banco"]:checked').val();
        var respuesta = $('input[name="respuesta"]:checked').val();
        var soporte = $('input[name="soporte"]:checked').val();
        var url = '<?php echo base_url('index.php/devolucion/guardar_verificacion') ?>';
        var valida = true;

        if (camara == undefined) {
            $('#error').show();
            $('#camara').focus();
            $('#error').html("<font color='red'><b>Seleccione la opción respecto a camara</b></font>");
            valida = false;
        } else if (nit == undefined) {
            $('#error').show();
            $('#nit').focus();
            $('#error').html("<font color='red'><b>Seleccione la opción respecto a rut</b></font>");
            valida = false;
        } else if (banco == undefined) {
            $('#error').show();
            $('#banco').focus();
            $('#error').html("<font color='red'><b>Seleccione la opción respecto al banco</b></font>");
            valida = false;
        }
        if (respuesta != undefined) {
            if (soporte == undefined) {
                $('#error').show();
                $('#soporte').focus();
                $('#error').html("<font color='red'><b>Seleccione la opción respecto al soporte de documentos</b></font>");
                valida = false;
            }
        }
        if (valida == true) {
            if (camara == 1 && nit == 1 && banco == 1) {
                var verificacion = 'S';
            } else {
                var verificacion = 'N';
            }
            $("#verificacion").val(verificacion);
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled", true);
            $("#devolucionFrm").attr("action", url);
            $('#devolucionFrm').removeAttr('target');
            $('#devolucionFrm').submit();
        }
    });
</script>    
