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
        $attributes = array('id' => 'levantaFrm', 'name' => 'levantaFrm',
            'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data', 
            );
        echo form_open_multipart(current_url(),$attributes); 
        echo form_open_multipart(base_url(),$attributes);
        ?>
        
        <center>
        <h2>Levantamiento de medidas cautelares</h2>
        </center>
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="mandamiento" id="mandamiento">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">            
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
            <td colspan="2">
            <p>
            <?php
                //echo form_label('Notificacion<span class="required">*</span>', 'notificacion');
                $datanotificacion = array(
                           'name'        => 'notificacion',
                           'id'          => 'notificacion',
                           'value'       => set_value('notificacion'),
                           'width'        => '80%',
                           'heigth'       => '50%'
                         );
                echo form_textarea($datanotificacion);
                echo form_error('notificacion','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('Comentarios&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),
                            'rows'        => '8',
                            'cols'        => '80',
                            'style'       => 'width:82%'
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
                <div id="error"></div>
            </p>
            <p>
            <?php 
            $dataguardar = array(
                   'name' => 'button',
                   'id' => 'guardar',
                   'value' => 'Guardar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $dataPDF = array(
                   'name' => 'button',
                   'id' => 'pdf',
                   'value' => 'Generar PDF',
                   'type' => 'button',
                   'content' => '<i class="fa fa-file"></i> Generar PDF',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataPDF)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/nits\';',
                   'content' => '<i class="fa fa-trash-o"></i> Cancelar',
                   'class' => 'btn btn-success'
                   );
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>
        </table>
        <?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>

  <script type="text/javascript"> 
  $(document).ready(function() {
          tinyMCE.triggerSave();
          $('#fechanotificacion').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
          });
          $('#fechaonbase').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
          });
  });
  
      $('#guardar').click(function() {
        tinyMCE.triggerSave();
        $('#error').html("");        
        var notify = $('#notificacion').val();
        var observacion = $('#observacion').val();
        var valida = true;
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese Notificaci&oacute;n</b></font>");
            valida = false;
        }else if (observacion == ""){
            $('#error').html("<font color='red'><b>Ingrese Observaci&oacute;n</b></font>");
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#levantaFrm').submit();
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#levantaFrm').attr("action", "pdf");
        $('#levantaFrm').submit();
        $('#levantaFrm').attr("action", "citacion");
    });

  </script>