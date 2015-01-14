<?php
$tipo_solicitud = array('name' => 'tipo_solicitud', 'id' => 'tipo_solicitud', 'type' => 'hidden');
?>
<form id="frmTmp" method="POST">
    <div id='dialog' style="display: none;">
        <center>
            <p><b>Gestionar Avoca Conocimiento</b></p>
        </center>  
    </div>
    <input name="cod_coactivo" type="hidden" value="<?php echo $cod_coactivo ?>" readonly=""/>
</form>    
<script>
    $("#dialog").dialog({width: 700, height: 150, show: "slide", hide: "scale", resizable: "false", position: "center", modal: "true"});
    $("#dialog").dialog({
        buttons: [{
                id: "si",
                text: "Aprobación Avoca Conocimiento al Expediente",
                class: "btn btn-success",
                click: function() {
                    var url = "<?= base_url() ?>index.php/recepciontitulos/Aprobar_AutoAvocaConocimiento";
                    $(".ajax_load").show("slow");
                    var cod_coactivo = "<?php echo $cod_coactivo; ?>";
                    $.post(url, {cod_coactivo: cod_coactivo});
                    $("#frmTmp").attr("action", url);
                    $('#frmTmp').submit();
                }
            },
            {
                id: "no",
                text: "Gestionar Rechazo",
                class: "btn btn-success",
                click: function() {
                    var url = "<?= base_url() ?>index.php/recepciontitulos/Crear_DocRechazo";
                    $(".ajax_load").show("slow");
                    var cod_coactivo = "<?php echo $cod_coactivo; ?>";
                    alert(cod_coactivo);
                    $.post(url, {cod_coactivo: cod_coactivo});
                    $("#frmTmp").attr("action", url);
                    $('#frmTmp').submit();
                }
            }]
    });
</script>
