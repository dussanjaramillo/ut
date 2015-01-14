<?php
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/devolucion/devoluciones_generadas\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');
$dataadicionales    = array('name'=>'adicionales','id'=>'adicionales','checked'=>FALSE,'style'=>'margin:10px');
$verificacion       = array('name'=>'verificacion','id'=>'verificacion','type'=>'hidden');
$tipo               = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>$tipo);
$devolucion         = array('name'=>'devolucion','id'=>'devolucion','type'=>'hidden','value'=>$devolucion);
$attributes         = array('name'=>'devolucionFrm','id'=>'devolucionFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');
echo form_open_multipart(current_url(),$attributes);                
echo form_input($verificacion);
echo form_input($devolucion);
echo form_input($tipo);
?>
<table align='center'>
    <tr>
        <td align='center' colspan='2'><h2>Documentos</h2></td>
    </tr>
    <tr>
        <td><b>¿Documentos Adicionales Solicitados?</b></td>
        <td>
            <?=
            "&nbsp;" . form_radio($dataadicionales, "1") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
            echo "&nbsp;" . form_radio($dataadicionales, "0") . "&nbsp;&nbsp;NO";
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
<div id="resultado"></div>
<?php echo form_close(); ?>
<script>
    var devolucion = "<?php echo $devolucion['value']; ?>";
    $('#guardar').click(function() {
        $(".ajax_load").show("slow");
        var adicionales = $('input[name="adicionales"]:checked').val();
        var url = '<?php echo base_url('index.php/tiempos/bloqueo') ?>';
        var valida = true;
        var fecha = '<?php echo $fecha["FECHA_CREACION"]; ?>';
        var tipo = '<?php echo $tipo['value']; ?>';


        if (adicionales == undefined) {
            $('#error').show();
            $('#camara').focus();
            $('#error').html("<font color='red'><b>Seleccione si hubo documentos adicionales</b></font>");
            valida = false;
        }

        if (valida == true) {
            if (adicionales == 1) {
                var verificacion = 'S';
                var gestion = 459;
            } else {
                var verificacion = 'N';
                var gestion = 508;
            }
            $("#resultado").load(url, {
                    codfiscalizacion: 0, gestion: gestion, fecha: fecha, mostrar: 'SI', si: 'guardar_verificacion', no: 'devoluciones_generadas', parametros: 'verificacion:' + verificacion + ';' + 'devolucion:' + devolucion+ ';' + 'tipo:' + tipo + '', BD: ''}, function() {
                            jQuery(".preload, .load").hide();
                            $(".ajax_load").show("hide");
                });

            }
        });
</script>    

