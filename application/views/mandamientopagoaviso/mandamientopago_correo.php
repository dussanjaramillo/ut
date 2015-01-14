
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
        $attributes = array('id' => 'citacionFrm', 'name' => 'citacionFrm',
            'class' => 'form-inline', 
            'method' => 'POST', 
            'enctype' => 'multipart/form-data', 
            );
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
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo?>">
            
            </td>
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
                echo form_label('Concepto', 'concepto');
                echo "</td><td>";
                $dataconcepto = array(
                           'name'        => 'concepto',
                           'id'          => 'concepto',
                           'value'       => 'FIC',
                           'maxlength'   => '30',
                           'size'        => '120',
                           'disabled'    => 'disabled'
                         );
                echo form_input($dataconcepto);              
                echo form_error('concepto','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
            <td>
            <p>
            <?php
                echo form_label('Instancia', 'instancia');
                echo "</td><td>";
                $datainsta = array(
                           'name'        => 'instancia',
                           'id'          => 'instancia',
                           'value'       => 'Notificacion por aviso',
                           'maxlength'   => '30',
                           'size'        => '120',
                           'disabled'    => 'disabled'
                         );
                echo form_input($datainsta);
                echo form_error('instancia','<div>','</div>');                                       
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
                           'value'       => $gestion->NOMBRE_GESTION,
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
            <?php
                echo form_label('Direcci&oacute;n', 'direccion');
                echo "</td><td>";
                $datatelefono = array(
                           'name'        => 'direccion',
                           'id'          => 'direccion',
                           'value'       => $informacion[0]['DIRECCION'],
                           'maxlength'   => '30',
                           'disabled'    => 'disabled'
                         );
                echo form_input($datatelefono);
                echo form_error('telefono','<div>','</div>');
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
            <td>
                <p>
                <?php
                $select = array();
                echo form_label('<span class="required"><font color="red"><b>* </b></font></span> Asignar a', 'asignado');
                $select[''] = "-- Seleccione --";
                foreach($asignado as $row) {
                    $select[$row->IDUSUARIO."-".$row->IDCARGO] = $row->NOMBRES." ".$row->APELLIDOS;
                }
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo form_dropdown('asignado', $select,'','id="asignado" class="chosen" placeholder="-- Seleccione --" ');
                ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('<span class="required"><font color="red"><b>* </b></font></span>Observaci&oacute;n','observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),                            
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
            $dataenviar = array(
                   'name' => 'tmp',
                   'id' => 'tmp',
                   'value' => 'Enviar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-file-text-o fa-lg"></i> Enviar',
                   'class' => 'btn btn-info'
                   );
            echo form_button($dataenviar)."&nbsp;&nbsp;";
            $dataPDF = array(
                   'name' => 'button',
                   'id' => 'pdf',
                   'value' => 'Generar PDF',
                   'type' => 'button',
                   'content' => '<i class="fa fa-file"></i> Generar PDF',
                   'class' => 'btn btn-warning'
                   );
            echo form_button($dataPDF)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/nits\';',
                   'content' => '<i class="fa fa-undo"></i> Cancelar',
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
        $('#fechanotificacion').datepicker({dateFormat: "dd/mm/y"});
        $('#fechaonbase').datepicker({dateFormat: "dd/mm/y"});
      });
      
      $('#guardar').click(function() {
        $('#error').html("");
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        var asigna = $('#asignado').val();
        var observa = $('#observacion').val();
        var valida = true;
        var res = asigna.split("-");   
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese Notificaci&oacute;n</b></font>");
            $("#notificacion").focus();
            valida = false;
        }else if (asigna == "") {
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false;
        }else if (observa == "") {
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
        }else if (res[1] != 7 ){
            $('#error').html("<font color='red'><b>Por favor asignar a Usuario Secretario</b></font>");
            valida = false;
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#citacionFrm').submit();
              //alert ("ok");
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        $(".ajax_load").show("slow");
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#citacionFrm').attr("action", "pdf");
        $('#citacionFrm').submit();
        $('#citacionFrm').attr("action", "citacion");
    });
    
//     $('.documento').click(function() {
//        tinyMCE.triggerSave();
//        var valor = $('#inactivo').val();
//        $('#mandamiento').val(valor);
//        $('#citacionFrm').attr("action", "pdf");
//        $('#citacionFrm').submit();
//        $('#citacionFrm').attr("action", "citacion");
//    });
    
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