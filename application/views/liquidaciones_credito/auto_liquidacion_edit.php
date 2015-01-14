<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php
if (@$result->REVISADO == "1") {
    $checked1 = TRUE;
}else {
    $checked1 = FALSE;
}
if (@$result->REVISADO == "2") {
   $checked2 = TRUE;
}else {
    $checked2 = FALSE;
}
    @$result->APROBADO == "1";                              
    if (@$result->APROBADO == "1") {
        $checked3 = TRUE;
    }else {
        $checked3 = FALSE;
    }
    if (@$result->APROBADO == "2") {
        $checked4 = TRUE;
    }else {
        $checked4 = FALSE;
    }
    if (@$result->APROBADO == "3") {
        $checked5 = TRUE;
    }else {
        $checked5 = FALSE;
    }
                            
$dataaprobado       = array('name'=>'aprobado','id'=> 'aprobado','style'=> 'margin:10px','required'=>'true');
$datarevisado       = array('name'=>'revisado','id'=> 'revisado','style'=> 'margin:10px','required'=>'true');
$dataLiquid         = array('name'=>'liquidacion','id'=>'liquidacion','type'=>'hidden');
$dataPDF            = array('name'=>'pdf','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-info',);
$datanotificacion   = array('name'=>'notificacion','id'=> 'notificacion','value'=>@$plantilla,'width'=> '80%','heigth'=> '50%'); 
$data               = array('name'=>'comentarios','id'=>'comentarios','value'=>@$result->COMENTARIOS,'width'=> '100%','heigth'=>'5%','style'=> 'width:98%','required'=>'true');
$dataFisc           = array('name'=>'cod_coactivo','id'=>'cod_coactivo','type'=>'hidden','value'=>@$cod_coactivo);
$dataNit            = array('name'=>'cod_nit','id'=>'cod_nit','type'=>'hidden','value'=>$nit);
$dataApro           = array('name'=>'verifi_aprobado','id'=>'verifi_aprobado','type'=>'hidden','value'=>@$result->APROBADO);
$dataRevi           = array('name'=>'verifi_revisado','id'=>'verifi_revisado','type'=>'hidden','value'=>@$result->REVISADO);
$dataAuto           = array('name'=>'num_auto','id'=>'num_auto','type'=>'hidden','value'=>@$result->NUM_AUTOGENERADO);
$dataTipo           = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>@$tipo);
$dataPerfil         = array('name'=>'perfil','id'=>'perfil','type'=>'hidden','value'=>@$perfil);
$dataUser           = array('name'=>'iduser','id'=>'iduser','type'=>'hidden','value'=>@$iduser);
$dataguardar        = array('name'=>'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success',);                        
$datacancel         = array('name'=>'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
$datafile           = array('name'  => 'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=>'10',);
$dataGestion        = array('name'=>'gestion','id'=>'gestion','type'=>'hidden','value'=>$gestion);  

$attributes = array('class'=>'form-inline','id'=>'LiquidacionEdit','name'=>'LiquidacionEdit','method'=>'POST');
echo form_open_multipart('liquidaciones_credito/tareasAuto',$attributes);
echo @$custom_error;
echo form_input($dataLiquid);
echo form_input($dataFisc);
echo form_input($dataNit);
echo form_input($dataTipo);
echo form_input($dataApro);
echo form_input($dataRevi);
echo form_input($dataAuto);
echo form_input($dataPerfil);
echo form_input($dataUser);
echo form_input($dataGestion);

?>


<table cellspacing="0" cellspading="0" border="0" align="center">
    <tr>
        <td>
        <?php $this->load->view('liquidaciones_credito/cabecera',$this->data); ?>
        </td>
    </tr>
    <tr>
        <p><td><?= form_textarea($datanotificacion); ?></td></p>
    </tr><tr><td><br></td></tr>
    <tr><td><br></td></tr>
   <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar Documento', 'filecolilla');                    
                    echo "<br>".form_upload($datafile);
                ?>
                </p>
            </td>
    </tr>
        <p>
        <tr>            
            <td><?= 'Comentarios&nbsp;<span class="required"><font color="red"><b>*</b></font></span>'.form_textarea($data);?></td>                                
        </tr><tr><td><br></td></tr>     
        </p>            
        <tr>
            <td align="center">
                <table border ="0">
                    <tr>
                        <td>
                         <p>
                            <div id="revised" class="span3">
                            <?php
                             echo form_label('Revisado', 'revisado')."<br>";
                             echo form_radio($datarevisado,"1",$checked1)."&nbsp;&nbsp;APROBADO<br>";
                             echo "<div id='divdevol'>";
                             echo form_radio($datarevisado,'2',$checked2).'&nbsp;&nbsp;DEVOLVER<br><br><br>';
                             echo "</div>";
                             
                            ?>    
                            </div>
                         </p>
                        </td>
                        <td>
                            <p>
                             <div id="aproved" class="span3">
                             <?php 
                              echo form_label('Aprobado', 'aprobado')."<br>";  
                              echo '<div id="REVISADO">';
                              echo form_radio($dataaprobado,"1",$checked3)."&nbsp;&nbsp;REVISADO<br>";
                              echo '</div>';
                              echo '<div id="APROBADO" style="display:none">';
                              echo form_radio($dataaprobado,"2",$checked4)."&nbsp;&nbsp;APROBADO Y FIRMADO<br>";
                              echo '</div>';
                              echo '<div id="RECHAZADO">';
                              echo form_radio($dataaprobado,"3",$checked5)."&nbsp;&nbsp;DEVOLVER";
                              echo '</div>';
                              echo form_error('aprobado','<div>','</div>'); 
                             ?>
                             </div>
                             </p>             
                        </td>
                    </tr>
                </table>
            </td>
        </tr>        
        <tr align ='center'>
            <td>
                <?php
                echo form_button($dataguardar)."&nbsp;&nbsp;";
                echo form_button($dataPDF)."&nbsp;&nbsp;";
                echo form_button($datacancel);
                ?>
            </td>
        </tr>
        <tr><td><br><td><br></td></tr>
        <tr>
            <td align='center'>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr><tr><td><br><td><br></td></tr>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>
</table>
<?php echo form_close(); ?>
<script type="text/javascript">   
    tinymce.init({
        language : "es",
        selector: "textarea#notificacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks wordcount visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            //"emoticons template paste textcolor moxiemanager"
           ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
     });
     
$('#comentarios').keyup(function() {
    var max_chars = 200;
    var chars = $(this).val().length;
    var diff = max_chars - chars;
    if (diff <= 0){
        $('#error').show();
        $('#error').html('Excedio el limite de carácteres en el campo comentarios');
        $('#guardar').attr('disabled',true);   
    }else{
        $('#guardar').attr('disabled',false); 
        $('#error').hide();
    }
}); 
    
function char_especiales(string){
    var comentarios=string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
    $('#comentarios').val(comentarios);
} 
     
     $(document).ready(function() {
        window.history.forward(-1);
        tinyMCE.triggerSave();
        var perfil = $('#perfil').val();
        var estado = $('#verifi_aprobado').val();
        var revisado = $('#verifi_revisado').val();  
        var user = $("#iduser").val();        
        var documento = $('#documento').val();    
        var gestion = <?php echo $gestion ?>;
        
        //alert (gestion);
        switch (gestion){
            case 1473:
                $('#aproved').hide();
                $('#revisado').attr('checked',false);
                jQuery("input[name=revisado]").attr("checked",false)
                break;
            case 1475:
                $('#aproved').hide();
                $('#revised').hide();
                break;
            case 1474:
                $('#revised').hide();
                jQuery("input[name=aprobado]").attr("checked",false)
                break;
            case 1476:
                $('#aproved').hide();
                $('#revised').hide();
                $('#cargColilla').show();
                break;
        }
     })   
     
     $('#guardar').click(function() {
        $('#error').html("");
        var coment = $('#comentarios').val();
        var clave = $('#clave').val();
        var perfil = $('#perfil').val();
        var filecolilla = $('#filecolilla').val();
        var aprobado = $("input[name='aprobado']:checked").val(); 
        var revisado = $("input[name='revisado']:checked").val();
        var gestion = <?php echo $gestion ?>;
        char_especiales(coment);
        var valida = true;
        var url = '<?php echo base_url('index.php/liquidaciones_credito/tareasAuto')?>';
        $('#codigo').val(clave);
        //alert (perfil+"***"+aprobado+"***"+revisado);
        
        if (coment == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        }
        switch (gestion){
            case 1473:
                if (revisado == undefined){
                    $('#error').show();
                    $('#error').html("<b>Por favor validar Revisión</b>");
                    valida = false;
                }
                break;
            case 1474:
                if (aprobado == undefined){
                    $('#error').show();
                    $('#error').html("<b>Por favor validar Revisión</b>");
                    valida = false;
                }
                break;
            case 1476:
                if (filecolilla == ''){
                    $('#error').show();
                    $('#error').html("<b>Por favor adjuntar documento</b>");
                    $('#filecolilla').focus();
                    valida = false;
                }
                break;
        }
                
        if (valida == true) {            
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#asignado").removeAttr('disabled');
            $("#LiquidacionEdit").attr("action", url);
            $('#LiquidacionEdit').removeAttr('target');
            $('#LiquidacionEdit').submit()
            //alert ('ok');
        }
    });
     
     $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#liquidacion').val(notify);
        $("#LiquidacionEdit").attr("action", "<?= base_url('index.php/liquidaciones_credito/pdf')?>");
        $('#LiquidacionEdit').attr('target', '_blank');
        $('#LiquidacionEdit').submit();
    });
    
     $('#filecolilla').change(function(){ 
        var extensiones_permitidas = new Array(".pdf"); 
        var archivo = $('#filecolilla').val();
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        var permitida = false;
        
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
            permitida = true;
            break;
            }
        } 
            if (!permitida) {
                $('#filecolilla').val('');
                $('#error').show();
                $('#error').html("<b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b>");
                $("#filecolilla").focus();
            }else{
                $('#error').show();
                $('#error').html("<b>Archivo Subido Correctamente</b>");
                return 1;
            } 
      });
</script>     