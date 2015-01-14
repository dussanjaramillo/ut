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
$dataPDF            = array('name'=>'pdf','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-success',);
$datanotificacion   = array('name'=>'notificacion','id'=> 'notificacion','value'=>@$plantilla,'width'=> '80%','heigth'=> '50%'); 
$data               = array('name'=>'comentarios','id'=>'comentarios','value'=>@$result->COMENTARIOS,'width'=> '100%','heigth'=>'5%','style'=> 'width:98%','required'=>'true');
$dataFisc           = array('name'=>'cod_fiscalizacion','id'=>'cod_fiscalizacion','type'=>'hidden','value'=>@$fiscalizacion);
$dataNit            = array('name'=>'cod_nit','id'=>'cod_nit','type'=>'hidden','value'=>$nit);
$dataApro           = array('name'=>'verifi_aprobado','id'=>'verifi_aprobado','type'=>'hidden','value'=>@$result->APROBADO);
$dataRevi           = array('name'=>'verifi_revisado','id'=>'verifi_revisado','type'=>'hidden','value'=>@$result->REVISADO);
$dataAuto           = array('name'=>'num_auto','id'=>'num_auto','type'=>'hidden','value'=>@$result->NUM_AUTOGENERADO);
$dataTipo           = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>@$tipo);
$dataPerfil         = array('name'=>'perfil','id'=>'perfil','type'=>'hidden','value'=>@$perfil);
$dataUser           = array('name'=>'iduser','id'=>'iduser','type'=>'hidden','value'=>@$iduser);
$dataguardar        = array('name'=>'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success',);                        
$datacancel         = array('name'=>'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/auto_liquidacion/autos_info\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-success');
$datafile           = array('name'  => 'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=>'10',
                             );
$attributes = array('class'=>'form-inline','id'=>'LiquidacionEdit','name'=>'LiquidacionEdit','method'=>'POST');
echo form_open_multipart('auto_liquidacion/tareasAuto',$attributes);
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
?>


<table cellspacing="0" cellspading="0" border="0" align="center">
    <tr>
        <td>
        <?php $this->load->view('auto_liquidacion/cabecera',$this->data); ?>
        </td>
    </tr>
    <tr>
        <p><td><?= form_textarea($datanotificacion); ?></td></p>
    </tr><tr><td><br></td></tr>
    <tr>
        <p>
        <td>                
            <?php
            echo form_label('Asignar a&nbsp;<span><font color="red"><b>*</b></font></span>', 'asignando');?> 
            <select name="asignado" id="asignado" required>
                <option value=''>--Seleccione--</option>
                    <?php foreach($asignado as $row){
                    echo '<option value="'.$row->IDUSUARIO."-".$row->IDCARGO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?>
            </select>            
        </td>
        </p>
   </tr><tr><td><br></td></tr>
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
            <td align="left">
                <div class="span3">
                    <?php
                    @$nrProc = $result->COD_FISCALIZACION;
                    $results=array();
                    $cRuta  = $rutaArchivo;                   
                    @$handler = opendir($cRuta);
                    if ($handler){
                        while ($file = readdir($handler)) {
                            if ($file != '.' && $file != '..')
                                $results[] = $file;
                        }
                        closedir($handler);
                    }
                    if(count($results)>0){?>
                        <div align="left">
                            <?php
                            $cCadena = "";
                            echo "Lista de Adjuntos<br>";
                            for($x=0; $x<1; $x++) {
                                  $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>Liquidación de Proceso ".$nrProc; // $results[$x]
                                  $cCadena .= "</a><br>";
                                  echo $cCadena;
                            }
                            echo "<br>";?>                        
                        </div>
                    <?php } ?>
                    <input type="hidden" id="documento" name="documento" value="<?php echo count($results); ?>">
                </div>
            </td>
        </tr>
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
     
     $(document).ready(function() {
        window.history.forward(-1);
        tinyMCE.triggerSave();
        var perfil = $('#perfil').val();
        var estado = $('#verifi_aprobado').val();
        var revisado = $('#verifi_revisado').val();  
        var user = $("#iduser").val();        
        var documento = $('#documento').val();
        
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
        
        if (estado == '2'){
            $('input:radio').attr('disabled', 'disabled');
            $('#guardar').prop("disabled",true);
            $('#error').show();
            $('#error').html("<b>Resolución Aprobada y Firmada</b>");
        }else if (perfil == 43 && estado != '1'){
            $('#divdevol').hide();
        }
                                     
        /////// ----- Validacion de Perfiles ----- \\\\\\\\
        if (perfil == "41"){
           $('#aproved').hide();
        }

        if (perfil == "43" && estado != '1'){
             $('#aproved').hide();
             $('#revised').hide();             
        }else if (perfil == "43" && estado == '1' && documento != '1'){
            $("#asignado").val(user+'-8');
            $("#asignado").attr('disabled', true)
            $('#REVISADO').hide();
            $('#RECHAZADO').hide();
            $('#APROBADO').show();
            $('#cargColilla').show();
            $('#revised').hide();
            $('#error').html("<b>Por favor adjuntar documento Firmado</b>");  
        }
        
        if (revisado == '2' && perfil == '43' ){              
                $('#revised').show();
                $('#error').show();
                $('#error').html("<b>Por Favor Validar el Auto</b>");
        }
        if (revisado == '1' && perfil == '41' ){              
                $('#revised').show(); 
                $('#error').show();
                $('#error').html("<b>Por Favor Validar el Auto</b>");
        }
        if (estado == '3' && perfil == '41'){             
                jQuery("input[name=aprobado]").attr("disabled",true)
                $('#aproved').show();
                $('#error').show();
                $('#error').html("<b>Por Favor Validar el Auto</b>");
        }
        
        if (perfil == '42'){
            jQuery("input[name=revisado]").attr("disabled",true)
        }
        //alert (perfil+'***'+estado+'***'+revisado);
     })
     
     $('#guardar').click(function() {
        $('#error').html("");
        var coment = $('#comentarios').val();
        var asigna = $('#asignado').val();
        var clave = $('#clave').val();
        var iduser = $('#iduser').val();
        var perfil = $('#perfil').val();
        var filecolilla = $('#filecolilla').val();
        var aprobado = $("input[name='aprobado']:checked").val(); 
        var revisado = $("input[name='revisado']:checked").val(); 
        var valida = true;
        var res = asigna.split("-");
        var url = '<?php echo base_url('index.php/auto_liquidacion/tareasAuto')?>';

        //alert (perfil+"***"+res[1]+"***"+aprobado+"***"+revisado);
        $('#codigo').val(clave);
        if (coment == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        }else if (asigna == "") {
            $('#error').show();
            $('#error').html("<b>Seleccione Asignar a</b>");
            valida = false;
        }else if (perfil == 41 && revisado == undefined){
            $('#error').show();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        }else if (perfil == 41 && revisado == '2' && res[1] != 8){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Abogado</b>");
            valida = false;
        }else if (perfil == 41 && revisado == '1' && res[1] != 9){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Coordinador</b>");
            valida = false;
        }else if (perfil == 42 && res[1] != 7 && aprobado == '3'){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Secretario</b>");
            valida = false;
        }else if (perfil == 42 && res[1] != 8 && aprobado == '1'){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Abogado</b>");
            valida = false;
        }else if (perfil == 43 && revisado == '2'){
            $('#error').show();
            $('#error').html("<b>Por favor validar Revisión</b>");
            valida = false;
        }else if (perfil == 43 && revisado == '1' && res[1] != 7){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Secretario</b>");
            valida = false;
        }else if (perfil == 43 && (res[1] != 8 || res[1] == 8) && (aprobado == '' || aprobado == '1' || aprobado == '3')){
            $('#error').show();
            $('#error').html("<b>Por favor validar Aprobación</b>");
            valida = false;
        }else if (perfil == 43 && res[1] != 8 && aprobado == '2'){
            $('#error').show();
            $('#error').html("<b>Asignar a usuario Abogado</b>");
            valida = false;
        }else if (asigna == iduser && perfil != 42){
            $('#error').show();
            $('#error').html("<b>Por favor asignar a otro Usuario</b>");
            valida = false;
        }else if (perfil == 42 && aprobado == undefined){
            $('#error').show();
            $('#error').html("<b>Por favor validar Aprobación</b>");
            valida = false;
        }else if (perfil == '43' && aprobado == 2 && filecolilla == ''){
            $('#error').show();
            $('#error').html("<b>Por favor adjuntar documento</b>");
            $('#filecolilla').focus();
            valida = false;
        }
                
        if (valida == true) {            
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#asignado").removeAttr('disabled');
            $("#LiquidacionEdit").attr("action", url);
            $('#LiquidacionEdit').removeAttr('target');
            $('#LiquidacionEdit').submit()
        }
    });
     
     $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#liquidacion').val(notify);
        $("#LiquidacionEdit").attr("action", "<?= base_url('index.php/auto_liquidacion/pdf')?>");
        $('#LiquidacionEdit').attr('target', '_blank');
        $('#LiquidacionEdit').submit();
    });
</script>     