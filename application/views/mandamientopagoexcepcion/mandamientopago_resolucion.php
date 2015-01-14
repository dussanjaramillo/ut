<?php 
/**
 * mandamientopago_resolucion -- Vista para Generar reolución.
 *
 * @author		Human Team Technology QA
 * @author		Nicolas Gonzalez R. - nigondo@gmail.com
 * @version		1.3
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
        $attributes = array('id' => 'resolucionFrm', 'name' => 'resolucionFrm',
            'class' => 'form-inline', 'method' => 'POST');
        echo form_open(current_url(),$attributes); ?>
        <?php echo $custom_error; ?>
        <center>
            <h2>Generar resoluci&oacute;n</h2>
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
            <?php
                echo form_label('NIT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'nit');
                $dataNIT = array(
                           'name'        => 'nit',
                           'id'          => 'nit',
                           'value'       => set_value('nit'),
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
                echo form_label('Razon Social&nbsp;&nbsp;', 'razon');
                $datarazon = array(
                           'name'        => 'razon',
                           'id'          => 'razon',
                           'value'       => set_value('razon'),
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
                $select[''] = "-- Seleccione --";
                foreach($concepto as $row) {
                    $select[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
                }
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo form_dropdown('concepto', $select,'','id="concepto" class="chosen" placeholder="-- Seleccione --" ');

                echo form_error('concepto','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
            <td>
            <p>
            <?php
                $select = array();
                echo form_label('Instancia', 'instancia');
                $select[''] = "-- Seleccione --";
                $select['0'] = "Carlos";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
                echo form_label('Representante&nbsp;&nbsp;', 'representante');
                $datarepresentante = array(
                           'name'        => 'representante',
                           'id'          => 'representante',
                           'value'       => set_value('representante'),
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
                echo form_label('Tel&eacute;fono&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'telefono');
                $datatelefono = array(
                           'name'        => 'telefono',
                           'id'          => 'telefono',
                           'value'       => set_value('telefono'),
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
                $select[''] = "-- Seleccione --";
                foreach($estados as $row) {
                    $select[$row->IDESTADO] = $row->NOMBREESTADO;
                }
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo form_dropdown('estado', $select,'','id="estado" class="chosen" placeholder="-- Seleccione --" ');

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
            <td>
            <p>
            <?php
                echo @form_hidden('aviso', $_POST['aviso']);
                echo form_error('telefono','<div>','</div>');
                echo form_label('Tipo excepci&oacute;n&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'excepcion');
                $selecte[''] = "-- Seleccione --";
                $selecte['1'] = "PROCEDE";
                $selecte['2'] = "PROCEDE PARCIALMENTE";
                $selecte['3'] = "NO PROCEDE";
                echo "<br>".form_dropdown('excepcion', $selecte, '','id="excepcion" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('excepcion','<div>','</div>');
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
                            'value'       => '',
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
            $dataCitacion = array(
                   'name' => 'button',
                   'id' => 'citacion',
                   'value' => 'Generar Citaci&oacute;n',
                   'type' => 'button',
                   'content' => '<i class="fa fa-calendar"></i> Generar Citaci&oacute;n',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataCitacion)."&nbsp;&nbsp;";
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
      
      $('#guardar').click(function() {
        $('#error').html();
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        var estado = $('#estado_id').val();
        var excepcion = $('#excepcion').val();
        var valida = true;
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese la Resoluci&oacute;n</b></font>");
            valida = false;
        }
        else if (excepcion == "") {
            $('#error').html("<font color='red'><b>Seleccione un Tipo de excepci&oacute;n</b></font>");
            valida = false;
        }
        else if (estado == "") {
            $('#error').html("<font color='red'><b>Seleccione un Estado</b></font>");
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#resolucionFrm').submit();
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#resolucionFrm').attr("action", "pdf");
        $('#resolucionFrm').submit();
        $('#resolucionFrm').attr("action", "pagina");
    });
    
    $('#citacion').click(function() {
        window.location='<?= base_url()?>index.php/mandamientopago/citacionex';
    });
    
  </script>