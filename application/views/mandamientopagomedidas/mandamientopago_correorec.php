<?php 
/**
 * mandamientopago_correo -- Vista para adicionar notificacion por correo.
 *
 * @author		Human Team Technology QA
 * @author		sergio amaya s. - saas02@gmail.com
 * @version		1.1
 * @since		Febrero de 2014
 */
?>
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
        $attributes = array('id' => 'actaFrm', 'name' => 'actaFrm',
            'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data', 
            );
        echo form_open_multipart(current_url(),$attributes); 
        echo form_open_multipart(base_url(),$attributes);
        ?>
        <?php echo $custom_error; ?>
        <center>
        <h2>Crear Notificaci&oacute;n de Resoluci&oacute;n de Recurso por Correo</h2>
        </center>
            <div id="ajax_load" class="ajax_load" style="display: none">
                <div class="preload" id="preload" >
                    <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
                </div>
            </div>
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
            <td>               
       </tr>
       	<tr>
            <td>
                <?php
                 echo form_label('NIT','nit');
                 ?>
            </td>
            <td>                
            <p>
            <?php
                $dataNIT = array(
                           'name'        => 'nit',
                           'id'          => 'nit',
                           'value'       => $informacion[0]['CODEMPRESA'],
                           'maxlength'   => '30',
                           'disabled'    => 'disabled'
                         );
                echo form_input($dataNIT);
                echo form_error('nit','<div>','</div>');
            ?>
            </p>
            </td>
            <td>
            <p>
            <?php
                echo form_label('Razon Social', 'razon');
                echo "</td><td>";
                $datarazon = array(
                           'name'        => 'razon',
                           'id'          => 'razon',
                           'value'       => $informacion[0]['NOMBRE_EMPRESA'],
                           'maxlength'   => '30',
                           'size'        => '120',
                           'disabled'    => 'disabled'
                         );
                echo form_input($datarazon);
                echo form_error('razon','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>            
            <p>
            <?php            
                $select = array();
                echo form_label('Concepto', 'concepto');
                echo "</td><td>";   
                $select['2'] = "-- Seleccione --";
                foreach($concepto as $row) {
                    $select[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
                }
                echo form_dropdown('concepto', $select,'','id="concepto" class="chosen" placeholder="-- Seleccione --" ');

                echo form_error('concepto','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
            <td>
            <p>
            <?php
                $select = array();
                echo form_label('Instancia;', 'instancia');
                echo "</td><td>";
                $select['0'] = "-- Seleccione --";
                $select['0'] = "Carlos";
                echo form_dropdown('instancia', $select,'','id="instancia" class="chosen" placeholder="-- Seleccione --" ');

                echo form_error('instancia','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Representante', 'representante');
                echo "</td><td>";
                $datarepresentante = array(
                           'name'        => 'representante',
                           'id'          => 'representante',
                           'value'       => $informacion[0]['REPRESENTANTE_LEGAL'],
                           'maxlength'   => '30',
                           'size'        => '120',
                           'disabled'    => 'disabled'
                         );
                echo form_input($datarepresentante);
                echo form_error('representante','<div>','</div>');
            ?>
            </p>
            </td>
            <td>
            <p>
            <?php
                echo form_label('Tel&eacute;fono', 'telefono');
                echo "</td><td>";
                $datatelefono = array(
                           'name'        => 'telefono',
                           'id'          => 'telefono',
                           'value'       => $informacion[0]['TELEFONO_FIJO'],
                           'maxlength'   => '30',
                           'disabled'    => 'disabled'
                         );
                echo form_input($datatelefono);
                echo form_error('telefono','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                $select = array();
                echo form_label('Estado', 'estado');
                echo "</td><td>";
                $dataestado = array(
                           'name'        => 'estado',
                           'id'          => 'estado',
                           'value'       => $informacion[0]['ACTIVO'],
                           'maxlength'   => '30',
                           'disabled'    => 'disabled'
                         );
                echo form_input($dataestado);
                echo form_error('estado','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
            <td>
            <p>
                &nbsp;
            </p>
            </td>
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
                           'style'       => 'width:83%'
                         );
                echo form_textarea($datanotificacion);
            ?>
            </p>
            </td>
        </tr>      
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;', 'observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),
                            'rows'        => '8',
                            'cols'        => '80',
                            'style'       => 'width:83%'
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
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/generate\';',
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
          $('#fechanotificacion').datepicker({dateFormat: "dd/mm/y"});
          $('#fechaonbase').datepicker({dateFormat: "dd/mm/y"});
      });
      
      $('#guardar').click(function() {
        $('#error').html("");
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        var valida = true;
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese Notificaci&oacute;n</b></font>");
            $("#notificacion").focus();
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#actaFrm').submit();
              //alert ("ok");
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#actaFrm').attr("action", "pdf");
        $('#actaFrm').submit();
        $('#actaFrm').attr("action", "citacion");
    });

</script>
  