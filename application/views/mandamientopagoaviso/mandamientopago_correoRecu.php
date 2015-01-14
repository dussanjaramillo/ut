<?php 
/**
 * mandamientopago_correo -- Vista para adicionar notificacion por correo.
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

        $attributes = array('id' => 'correoFrm', 'name' => 'correoFrm',
            'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data', 
            'onSubmit' => 'return comprobarextension()');
        echo form_open_multipart(current_url(),$attributes); ?>
        <?php echo $custom_error; ?>
        <center>
        <h2>Crear Notificaci&oacute;n por Correo Resolucion Recurso</h2>
        </center>
        <div class="center-form-large-20">
        <br>
        <br>
        <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
        </div>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="mandamiento" id="mandamiento">
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
            <td colspan="2">
            <p>
            <?php
                echo form_label('Comentarios&nbsp;&nbsp;', 'observacion');
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
            <td>
            <p>
            <?php
                $selecte = array();
                echo form_label('Estado&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'estado_id');
                $selecte[''] = "-- Seleccione --";
                $selecte['1'] = "ELABORADO";
                $selecte['2'] = "RADICADO";
                $selecte['3'] = "ENTREGADO";
                $selecte['4'] = "COMUNICACION DEVUELTA";
                echo "<br>".form_dropdown('estado_id', $selecte, '','id="estado_id" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('estado_id','<div>','</div>');
            ?>    
            </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Numero de radicado Onbase', 'onbase');
                $dataonbase = array(
                           'name'        => 'onbase',
                           'id'          => 'onbase',
                           'value'       => '',
                           'maxlength'   => '10',
                         );
                echo "<br>".form_input($dataonbase);
                echo form_error('onbase','<div>','</div>');
            ?>
            </p>
            </td>
            <td>
            <div class="span3">    
            <?php
                echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion');
                $dataFecha = array(
                          'name'        => 'fechanotificacion',
                          'id'          => 'fechanotificacion',
                          'value'       => date('d/m/Y'),
                          'maxlength'   => '12',
                          'readonly'    => 'readonly',
                          'size'        => '15'
                        );

               echo form_input($dataFecha)."&nbsp;";
               echo form_error('fechanotificacion','<div>','</div>');

               echo form_hidden('fechanotificaciond',date("d/m/Y"));
            ?>
            </div>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Fecha Onbase', 'fechaonbase');
                $dataFechaOn = array(
                          'name'        => 'fechaonbase',
                          'id'          => 'fechaonbase',
                          'value'       => '',
                          'maxlength'   => '12',
                          'readonly'    => 'readonly',
                          'size'        => '15'
                        );

               echo "<br>".form_input($dataFechaOn)."&nbsp;";
               echo form_error('fechaonbase','<div>','</div>');

               echo form_hidden('fechaonbased',date("d/m/Y"));
            ?>
            </p>    
            </td>
            <td>
            <div class="span3">
            <?php
                echo form_label('N&uacute;mero de colilla correo', 'colilla');
                $datacolilla = array(
                           'name'        => 'colilla',
                           'id'          => 'colilla',
                           'value'       => '',
                           'maxlength'   => '10',
                         );
                echo "<br>".form_input($datacolilla);
                echo form_error('colilla','<div>','</div>');
            ?>
            </div>
            </td>
        </tr>
        <tr>
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar colilla', 'filecolilla');
                    $datafile = array(
                               'name'        => 'userfile',
                               'id'          => 'filecolilla',
                               'value'       => '',
                               'maxlength'   => '10',
                             );
                    echo "<br>".form_upload($datafile);
                    echo form_error('filecolilla','<div>','</div>');
                ?>
                </p>
            </td>
            <td align="left">
                <p>
                <div class="span3">
                    <?php
                    $results=array();
                    $cRuta  = "./application/uploads/";
                    $cRuta .= "mandamientos/correo/";
                    //echo $cRuta."<br>";
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
                        for($x=0; $x<count($results); $x++) {
                              $cCadena  = "&nbsp;";  //<img src='../../img/trash.png' style='cursor:pointer' title='Eliminar'";
                              //$cCadena .= "onclick=javascript:f_DeleteAdj('".$cRuta."{$results[$x]}')>&nbsp;";
                              $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Colilla"; // $results[$x]
                              $cCadena .= "</a><br>";
                              echo $cCadena;
                        }
                        echo "<br>";?>
                        </div>
                    <?php } ?>
                </div>
                </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo');
                $selecte[''] = "-- Seleccione --";
                foreach($motivos as $row) {
                    $selecte[$row->COD_MOTIVO_DEVOLUCION] = $row->MOTIVO_DEVOLUCION;
                }
                echo "<br>".form_dropdown('motivo', $selecte, '','id="motivo" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('motivo','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                <?php
                    echo form_label('C&oacute;digo comunicaci&oacute;n', 'comunicacion');
                    $datacomunica = array(
                               'name'        => 'comunicacion',
                               'id'          => 'comunicacion',
                               'value'       => set_value('comunicacion'),
                               'maxlength'   => '50'
                             );
                    echo "<br>".form_input($datacomunica);
                    echo form_error('comunicacion','<div>','</div>');
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
                   'class' => 'btn btn-info'
                   );
            echo form_button($dataPDF)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/generate\';',
                   'content' => '<i class="fa fa-trash-o"></i> Cancelar',
                   'class' => 'btn btn-warning'
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
        var estado = $('#estado_id').val();
        var onbase = $('#onbase').val();
        var fechaonb = $('#fechaonbase').val();
        var fechanot = $('#fechanotificacion').val();
        var motivo = $('#motivo').val();
        var colilla = $('#colilla').val();
//      var observ = $('#observacion').val();
        var comunicacion = $('#comunicacion').val();
        var valida = true;
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese Notificaci&oacute;n</b></font>");
            valida = false;
        }
        else if (estado == "") {
            $('#error').html("<font color='red'><b>Seleccione un Estado</b></font>");
            valida = false;
        }

        else if (fechaonb != "" && fechanot != "" && valida == true) {
            anoonb = parseInt(fechaonb.split("/")[2]);
            mesonb = parseInt(fechaonb.split("/")[1]) -1;
            diaonb = parseInt(fechaonb.split("/")[0])
            anonot = parseInt(fechanot.split("/")[2]);
            mesnot = parseInt(fechanot.split("/")[1]) -1;
            dianot = parseInt(fechanot.split("/")[0])
            fechaonbase = new Date(anoonb, mesonb, diaonb);
            fechanotify = new Date(anonot, mesnot, dianot);
            if (fechanotify < fechaonbase) {
                $('#error').html("<font color='red'><b>La fecha de Notificaci&oacute;n no puede ser menor que la fecha de Onbase</b></font>");
                valida =  false;
            }
        }

        else if (estado == '2' && onbase == '') {
            $('#error').html("<font color='red'><b>Ingrese N&uacute;mero de radicado Onbase</b></font>");
            valida = false;
        }
        
        else if (estado == '4' && motivo == ''){
          $('#error').html("<font color='red'><b>Ingrese Motivo Devolucio&oacute;n</b></font>");
          valida = false;  
        }

        else if (estado == '3' && fechanot == ''){
          $('#error').html("<font color='red'><b>Ingrese Fecha de Notificacio&oacute;n</b></font>");
          valida = false;  
        }
        
        else if (estado == '3' && fechanot == ''){
          $('#error').html("<font color='red'><b>Ingrese Fecha de Notificacio&oacute;n</b></font>");
          valida = false;  
        }
        
         else if (estado == '3' && colilla == ''){
          $('#error').html("<font color='red'><b>Ingrese N&uacute;mero de Colilla</b></font>");
          colilla
          valida = false;  
        }
        
        else if (comunicacion == '') {
            $('#error').html("<font color='red'><b>Ingrese Codigo de Comunicaci&oacute;n</b></font>");
            valida = false;
        }
        
        else if (valida == true) {            
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#correoFrm').submit();
            $(".ajax_load").hide("slow");
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#correoFrm').attr("action", "pdf");
        $('#correoFrm').submit();
        $('#correoFrm').attr("action", "correo");
    });
    
    function comprobarextension() {
        if ($("#filecolilla").val() != "") {
            $('#error').html();
            var archivo = $("#filecolilla").val();
            //alert (archivo);
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensiÃ³n estÃ¡ entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                  permitida = true;
                  break;
                }
            }
            if (!permitida) {
              jQuery("#filecolilla").val("");
              mierror = "Comprueba la extension de los archivos a subir.<br>Solo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
              $('#error').html("<font color='red'><b>"+mierror+"</b></font>");
              $('#guardar').prop("disabled",false);
              return false;
            }
            return true;
        }
    }
    
    function f_DeleteAdj(xRuta) {
        if (confirm('¿Esta seguro de Eliminar el archivo?')) {
            document.forms['correoFrm']['vRuta'].value=xRuta;
            document.forms['correoFrm']['vUbicacion'].value="correo";
            document.forms['correoFrm'].action="delFile";
            document.forms['correoFrm'].submit();
            document.forms['correoFrm'].action="correo";
        }
    }
  </script>