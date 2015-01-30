
<?php
header('Content-Type: text/html; charset=UTF-8'); 
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php          
$attributes = array('id' => 'citacionFrm', 'name' => 'citacionFrm','class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data', );
        echo form_open_multipart(current_url(),$attributes); ?>
        <?php echo $custom_error; 
        echo "<center>".$titulo."</center>";  
       @$rutaInac=base_url().$inactivo->DOC_FIRMADO.$inactivo->NOMBRE_DOC_CARGADO;
        ?>        
        <div class="center-form-large-20">
        <br>
        <br>
            <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
	    <td>
	    <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="mandamiento" id="mandamiento">
            <input type="hidden" name="temporal" id="temporal">
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">            
            </td>
	</tr>
	<tr>
            <?php
            $this->load->view('mandamientopago/cabecera',$this->data); 
            ?>
        </tr>
        </table>        
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
            <?php 
                if (count($inactivo)>0){
                    echo "<tr>
                            <td align='right'>
                                <a href='$rutaInac' class='btn-success' target=_blank>
                                    Citaci&oacute;n Anterior
                                </a>
                            </td>
                        </tr>";
                }                
            ?>       
        <tr>
            <td colspan="2">
            <p>
            <?php
            $cRuta = $rutaTemporal;                         
                    if (is_dir($cRuta)) {
                        $handle = opendir($cRuta);
                        while ($file = readdir($handle)) {
                         if (is_file($cRuta.$file)) {
                             $plantilla = read_template($cRuta.$file);
                             $datanotificacion = array(
                                'name'        => 'notificacion',
                                'id'          => 'notificacion',
                                'value'       => $plantilla,
                                'width'        => '80%',
                                'heigth'       => '50%'
                            ); 
                          }
                        }                         
                    }else {
                              $datanotificacion = array(
                                'name'        => 'notificacion',
                                'id'          => 'notificacion',
                                'value'       => set_value('notificacion'),
                                'width'        => '80%',
                                'heigth'       => '50%'
                             );
                            
                          }                
                echo form_textarea($datanotificacion); 

            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('<span class="required"><font color="red"><b>* </b></font></span>Observaci&oacute;n','observacion');
                echo "<br>";
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),                            
                            'style'       => 'width:98%'
                          );

                echo "&nbsp;&nbsp;".form_textarea($data);
                echo form_error('observacion','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <p>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </p>
            <p>
            <?php 
            $dataguardar = array('name' => 'button','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar','class' => 'btn btn-success');
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $dataenviar = array('name' => 'tmp','id' => 'tmp','value' => 'Enviar','type' => 'button','content' => '<i class="fa fa-file-text-o fa-lg"></i> Guardar','class' => 'btn btn-success');
            echo form_button($dataenviar)."&nbsp;&nbsp;";
            $dataPDF = array('name' => 'button','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-info');
            echo form_button($dataPDF)."&nbsp;&nbsp;";
            $datacancel = array('name' => 'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/nits\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>
        </table>
        <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
    <input type="hidden" name="titulo_doc" id="titulo_doc" >
        <?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>

  <script type="text/javascript">
  $(document).ready(function() { 
        window.history.forward(-1);
        tinyMCE.triggerSave();
        $('#fechanotificacion').datepicker({dateFormat: "dd/mm/y"});
        $('#fechaonbase').datepicker({dateFormat: "dd/mm/y"});
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
  });
  
$('#observacion').keyup(function() {
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
    var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g,' ');
    $('#observacion').val(comentarios);
}
      
      $('#guardar').click(function() {
        $('#error').html("");
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        var observa = $('#observacion').val();
        var valida = true;
        char_especiales(observa); 
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'citacion'){
            url = url+'/citacion';
        }else if (tipo == 'correo'){
            url = url+'/correo';
        }else if (tipo == 'citacionex'){
            url = url+'/citacionex';
        }else if (tipo == 'correoexc'){
            url = url+'/correoExc';
        }else if (tipo == 'citacionrec'){
            url = url+'/citacionrec';
        }else if (tipo == 'resosa'){
            url = url+'/citacionResosa';
        }else if (tipo == 'edicto'){
            url = url+'/addEdicto';
        }else if (tipo == 'acta'){
            url = url+'/acta';
        }else if (tipo == 'actaexc'){
            url = url+'/actaexc';
        }else if (tipo == 'actaRec'){
            url = url+'/actaRec';
        }else if (tipo == 'correoresosa'){
            url = url+'/correoResosa';
        }else if (tipo == 'actaresosa'){
            url = url+'/actaResosa';
        }
        
        
        
        
        if (notify == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Notificaci&oacute;n</b>");
            $("#notificacion").focus();
            valida = false;
        }else if (observa == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#citacionFrm").attr("action", url);
            $('#citacionFrm').removeAttr('target');
            $('#citacionFrm').submit();
              //alert ("ok");
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $("#citacionFrm").attr("action", "<?= base_url('index.php/mandamientopago/pdf')?>");
        $('#citacionFrm').attr('target', '_blank');
        $('#citacionFrm').submit();
    });
    
    
    $('#tmp').click(function() {
        tinyMCE.triggerSave();
        var temp = $('#notificacion').val();
        var tipo = $('#tipo').val();
        $(".ajax_load").show("slow");
        $('#temporal').val(temp);
        $('#tipo').val(tipo);
        $('#citacionFrm').attr("action", "temp");
        $('#citacionFrm').submit();
    });

</script>