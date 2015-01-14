<?php
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');
$dataadicionales    = array('name'=>'adicionales','adicionales','checked'=>FALSE,'style'=>'margin:10px');
$verificacion       = array('name'=>'verificacion','id'=>'verificacion','type'=>'hidden');
$tipo1              = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>$tipo);
$fiscalizacion1     = array('name'=>'fiscalizacion','id'=>'fiscalizacion','type'=>'hidden','value'=>$fiscalizacion);
$nit1               = array('name'=>'nit','id'=>'nit','type'=>'hidden','value'=>$nit);
$acuerdo1           = array('name'=>'acuerdo','id'=>'acuerdo','type'=>'hidden','value'=>$acuerdo);
$attributes         = array('name'=>'devolucionFrm','id'=>'devolucionFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');


echo form_open_multipart(current_url(),$attributes);                
echo form_input($acuerdo1);
echo form_input($tipo1);
echo form_input($fiscalizacion1);
echo form_input($nit1);
echo form_input($verificacion);

?>
<table align='center'>
    <tr>
        <td align='center' colspan='2'><h2>Verificacion de Garantias</h2></td>
    </tr>
    <tr>
        <td id='info' colspan='2' align='center' >
            <b>Desea Modificar Garantias?</b>
        </td>
    </tr>        
    <tr>
        <td colspan='2' align='center'>
            <?=
               "&nbsp;".form_radio($dataadicionales,"1")."&nbsp;&nbsp;SI&nbsp;&nbsp;";
               echo "&nbsp;".form_radio($dataadicionales,"0")."&nbsp;&nbsp;NO";
            ?>
        </td>
    </tr>   
    <tr>
        <td colspan='2' id="observaciones">
            
        </td>
    </tr>
    <tr id="garantias" align='center' style="display:none">
        <td colspan='2'>            
            <input type="button" id="<?php echo $fiscalizacion ?>,<?php echo $nit ?>,<?php echo $acuerdo ?>" value="Garantias" class="btn btn-success" onclick='enviarGarantia(this.id)'>
        </td>        
    </tr>
    <tr>
        <td colspan='2' align='center'>
            <div style="display:none" id="error" class="alert alert-danger"></div>
        </td>
    </tr><tr><td><br></td></tr>
    <tr>
        <td id='botones' align='center' colspan='2' style="display:none">
            <?=
            form_button($dataguardar)."&nbsp;&nbsp;";
            echo form_button($datacancel);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <input type="hidden" id="nit1" name="nitg" >
            <input type="hidden" id="tipo" name="tipo" value="A">
            <input type="hidden" id="resolucion" name="resolucion">
            <input type="hidden" id="fiscalizacion" name="fiscalizacion">
            <input type="hidden" id="liquidacion" name="liquidacion">
            <input type="hidden" id="acuerdo" name="acuerdo">
        </td>
    </tr>
</table>

<?php echo form_close(); ?>
<script>            
    $('input[name="adicionales"]').change(function() {
        var check = $('input[name="adicionales"]:checked').val();
        if (check == 1){
            $('#garantias').show();
            $('#botones').hide();
        }else {
            $('#garantias').hide();
            $('#botones').show();
        }         
     });
    
    function enviarGarantia(id){
       $(".ajax_load").show("slow");
       var datos = id.split(',');
       var fiscalizacion    = datos[0];
       var nit              = datos[1];
       var acuerdo          = datos[2];       
       var url = "<?= base_url('index.php/acuerdodepago/modificaciongarantias') ?>";
       $.ajax({
               type: "POST",
               url: url,
               data: {nit:nit,acuerdo:acuerdo,fiscalizacion:fiscalizacion},
               success: 
                   function(data){ 
                   $(".ajax_load").hide();                   
               }
           });                
    }
    
    $('#guardar').click(function() {       
        var adicionales  = $('input[name="adicionales"]:checked').val();        
        var url = '<?php echo base_url('index.php/acuerdodepago/guardar_verificacion')?>';
        var valida  = true;        
        if (adicionales == undefined){
            $('#error').show();            
            $('#error').html("<font color='red'><b>Seleccione alguna opción por favor</b></font>");
            $('#adicionales').focus();
            valida = false;
        }
        
        if (valida == true) { 
             if (adicionales == 1) {
                var verificacion = 'S';
            } else {
                var verificacion = 'N';
            }
            $("#verificacion").val(verificacion);
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#devolucionFrm").attr("action", url);
            $('#devolucionFrm').removeAttr('target');
            $('#devolucionFrm').submit();
        }
    });   
</script>    

