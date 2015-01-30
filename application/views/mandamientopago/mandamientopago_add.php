
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
</script>
<?php    
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php     
        $attributes = array('class' => 'form-inline',
            'id' => 'MandamientoAdd',
            'name' => 'MandamientoAdd',
            'method' => 'POST');
        echo form_open(current_url(),$attributes);
        echo form_open_multipart(base_url(),$attributes);           
        echo "<center>".$titulo."</center>";        
        ?> 
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">       
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
            <input type="hidden" name="temporal" id="temporal">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo @$idmandamiento ?>">
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
            <input type="hidden" name="mandamiento" id="mandamiento">
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
        <tr>
            <td>
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
                                'value'       => @$filas2,
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
            <td>
            <p>
            <?php
            echo form_label('Comentarios&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            echo "<br>";
            $data = array(
                        'name'        => 'comentarios',
                        'id'          => 'comentarios',
                        'value'       => set_value('comentarios'),
                        'width'       => '100%',
                        'heigth'      => '5%',
                        'style'       => 'width:98%'
                      );

            echo "&nbsp;&nbsp;".form_textarea($data);
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td align="center">
            <p>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </p>
            <p>
            <?php 
            $dataguardar = array(
                   'name' => 'guardar',
                   'id' => 'guardar',
                   'value' => 'Guardar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar',
                   'class' => 'btn btn-success',
                   );
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $dataenviar = array('name' => 'tmp','id' => 'tmp','value' => 'Enviar','type' => 'button','content' => '<i class="fa fa-file-text-o fa-lg"></i>Guardar','class' => 'btn btn-success');
            echo form_button($dataenviar)."&nbsp;&nbsp;";
            $dataPDF = array('name' => 'pdf','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file-pdf-o"></i> Generar PDF','class' => 'btn btn-info',);
            echo form_button($dataPDF)."&nbsp;&nbsp;";
            $datacancel = array('name' => 'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>        
        </table>
        
        <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
        <input type="hidden" name="titulo_doc" id="titulo_doc" >
        <?php echo form_close(); ?>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>
  <script type="text/javascript">
$(document).ready(function() {    
    window.history.forward(-1);
    tinyMCE.triggerSave();
    var perfil = $('#perfil').val();    
        /////// ----- Validacion de Perfiles ----- \\\\\\\\
        if (perfil == "42"){
            var band42 = 1;
        }
        if (perfil == "41"){
            var band41 = 1;
        }
        if (perfil == "43"){
            var band43 = 1;
        }
         if (band43 == 1 && band42 != 1){
             $('#aproved').hide();
             $('#revised').hide();
         }
        
        if (band41 == 1 && band42 != 1 && band43 !=1){
            $('#guardar').attr('disabled','disabled');
            $('#aproved').hide();
            $('#revised').hide();
        }
        
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
    var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g,' ');
    $('#comentarios').val(comentarios);
}    
        
    $('#guardar').click(function() {   
        tinyMCE.triggerSave();
        var coment = $('#comentarios').val();
        var notify = $('#notificacion').val();
        var tipo = $('#tipo').val();
        var valida = true;
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'mandamiento'){
            url = url+'/add';
        }else if (tipo == 'adelante'){
            url = url+'/addRes';
        }else if (tipo == 'excepcion'){
            url = url+'/addExc';
        }else if (tipo == 'levanta'){
            url = url+'/levanta';
        }else if (tipo == 'recurso'){
            url = url+'/addRec';
        }   
        char_especiales(coment);
        if (notify == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Notificación</b>");
            valida = false;
        }else if (coment == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        }if (valida == true) {            
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#MandamientoAdd").attr("action", url);
            $('#MandamientoAdd').removeAttr('target');
            $('#MandamientoAdd').submit();            
            //alert ('ok');
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $("#MandamientoAdd").attr("action", "<?= base_url('index.php/mandamientopago/pdf')?>");
        $('#MandamientoAdd').attr('target', '_blank');
        $('#MandamientoAdd').submit();
    });
    
     $('#tmp').click(function() {
        tinyMCE.triggerSave();
        var temp = $('#notificacion').val();
        var tipo = $('#tipo').val();
        $('#temporal').val(temp);
        var cod_coactivo = $('#cod_coactivo').val();
        var url  = '<?= base_url('index.php/mandamientopago/temp') ?>';  
        $(".ajax_load").show("slow");
         $.post(url,{temporal:temp,tipo:tipo,cod_coactivo:cod_coactivo})
                    .done(function(data){
                        $('#MandamientoAdd').attr("action", "nits");
                        $('#MandamientoAdd').removeAttr('target');
                        $('#MandamientoAdd').submit();
                        //$(".ajax_load").hide("slow");
                    }).fail(function (){
                        $(".ajax_load").hide("slow");
                    })

    });
     
  </script>
   