<?php 
/**
 * mandamientopago_edit -- Vista para editar correo.
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
        $data = $result;        
        $attributes = array('id' => 'correoFrm', 'name' => 'correoFrm',
            'class' => 'form-inline', 
            'method' => 'POST', 
            'enctype' => 'multipart/form-data', 
            'onSubmit' => 'return comprobarextension()'
            );
        echo form_open_multipart(current_url(),$attributes);       
        echo form_open_multipart(base_url(),$attributes);
        ?>
        <?php echo $custom_error; ?>
        <center>
        <h2>Crear Notificaci&oacute;n de Resoluci&oacute;n de Recurso por Correo</h2>
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
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
            <input type="hidden" name="id_estado" id="id_estado" value="<?php echo  $data->COD_ESTADO; ?>">
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
            <input type="hidden" id="clave" name="clave" value="<?php echo @$data->COD_MANDAMIENTOPAGO; ?>">
            <input type="hidden" id="notifica" name="notifica" value="<?php echo @$data->COD_AVISONOTIFICACION; ?>">
            <input type="hidden" id="codigo" name="codigo" value="">
        <tr>
            <td colspan="2">
            <p>
            <?php
                //echo form_label('Notificacion<span class="required">*</span>', 'notificacion');
                $datanotificacion = array(
                           'name'        => 'notificacion',
                           'id'          => 'notificacion',
                           'value'       => @$plantilla,
                           'width'        => '80%',
                           'heigth'       => '50%'
                         );
                echo form_textarea($datanotificacion);
                echo form_error('notificacion','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr id="radicOnbase" style="display:none">
            <td>
            <p>
            <?php
            if ($data->NUM_RADICADO_ONBASE == '0'){
                $onbase = set_value('onbase');
            }else {
                $onbase = $data->NUM_RADICADO_ONBASE;
            }
                echo form_label('Numero de radicado Onbase', 'onbase');
                $dataonbase = array(
                           'name'        => 'onbase',
                           'id'          => 'onbase',
                           'value'       => $onbase,
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
                          'value'       => $data->FECHA_NOTIFICACION,
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
        <tr id="numColilla" style="display:none">
            <td>
            <p>
            <?php
                echo form_label('Fecha Onbase', 'fechaonbase');
                $dataFechaOn = array(
                          'name'        => 'fechaonbase',
                          'id'          => 'fechaonbase',
                          'value'       => $data->FECHA_ONBASE,
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
                           'value'       => '',//$data->DOC_COLILLA,
                           'maxlength'   => '10',
                         );
                echo "<br>".form_input($datacolilla);
                echo form_error('colilla','<div>','</div>');
            ?>
            </div>
            </td>
        </tr>
        <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar colilla', 'filecolilla');
                    $datafile = array(
                               'name'        => 'userfile',
                               'id'          => 'filecolilla',
                               'value'       => '',//$data->DOC_COLILLA,
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
                    $cRuta .= "mandamientos/corrreoRec/";
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
                            if ($results[$x] == $data->NOMBRE_DOC_CARGADO.".pdf"){
                              $cCadena  = "&nbsp;"; //<img src='../../img/trash.png' style='cursor:pointer' title='Eliminar'";
                              //$cCadena .= "onclick=javascript:f_DeleteAdj('".$cRuta."{$results[$x]}')>&nbsp;";
                              $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Colilla"; // $results[$x]
                              $cCadena .= "</a><br>";
                              echo $cCadena;
                            }
                        }
                        echo "<br>";?>
                        </div>
                    <?php } ?>
                </div>
                </p>
            </td>
        </tr>
        <tr id="motDevol" style="display:none">
            <td>
            <p>
            <?php
                echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo');
                $selecte[''] = "-- Seleccione --";
                foreach($motivos as $row) {
                    $selecte[$row->COD_MOTIVO_DEVOLUCION] = $row->MOTIVO_DEVOLUCION;
                }
                
                echo "<br>".form_dropdown('motivo', $selecte, $data->COD_MOTIVODEVOLUCION,'id="motivo" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('motivo','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr id="idComu" style="display:none">
            <td colspan="2">
                <p>
                    <?php
                    echo form_label('C&oacute;digo comunicaci&oacute;n', 'comunicacion');
                    $datacomunica = array(
                               'name'        => 'comunicacion',
                               'id'          => 'comunicacion',
                               'value'       => $data->COD_COMUNICACION,
                               'maxlength'   => '50'
                             );
                    echo "<br>".form_input($datacomunica);
                    echo form_error('comunicacion','<div>','</div>');
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
                            'value'       => $data->OBSERVACIONES,
                            'width'       => '100%',
                            'heigth'      => '5%',
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
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopagoaviso/add\';',
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
          var estado = $('#id_estado').val();

          if (estado == 1){
              $('#radicOnbase').show();
          }
          if (estado == 2){
              $('#radicOnbase').show();
              $('#numColilla').show();
              $('#cargColilla').show();
              $('#motDevol').show();
              $('#idComu').show();
          }
              
      $('#guardar').click(function() {
        $('#error').html("");
        tinyMCE.triggerSave();
        var clave = $('#clave').val();
        $('#codigo').val(clave);
        var motivo = $('#motivo').val();
        var onbase = $('#onbase').val();
        var observ = $('#observacion').val();
        var fechaonb = $('#fechaonbase').val();
        var fechanot = $('#fechanotificacion').val();
        var colilla = $('#colilla').val();
        var valida = true;

        switch (estado){
            case "1":
                if (onbase == ''){
                    $('#error').html("<font color='red'><b>Ingrese N&uacute;mero Onbase</b></font>");
                    $("#onbase").focus();
                    valida = false;
                }else{
                  $('#id_estado').val('2');  
                }
                break;                
            case "2":
                if (motivo != ''){
                    $('#id_estado').val('4');
                }else{
                    $('#id_estado').val('3');
                }
                    if (colilla == '') {
                      $('#error').html("<font color='red'><b>Ingrese N&uacute;mero de la colilla</b></font>");
                      $("#colilla").focus();
                      valida = false;
                    }

                    if (fechaonb != "" && fechanot != "" && valida == true) {
                    anoonb = parseInt(fechaonb.split("/")[2]);
                    mesonb = parseInt(fechaonb.split("/")[1]) -1;
                    diaonb = parseInt(fechaonb.split("/")[0])
                    anonot = parseInt(fechanot.split("/")[2]);
                    mesnot = parseInt(fechanot.split("/")[1]) -1;
                    dianot = parseInt(fechanot.split("/")[0])
                    fechaonbase = new Date(anoonb, mesonb, diaonb);
                    fechanotify = new Date(anonot, mesnot, dianot);
                    //alert("Not: "+fechanotify);
                    //alert("Onbase: "+fechaonbase);
                        if (fechanotify < fechaonbase) {
                            $('#error').html("<font color='red'><b>La fecha de Notificaci&oacute;n no puede ser menor que la fecha de Onbase</b></font>");
                            valida =  false;
                        }
                    }
                break;
        }

        if (motivo == "14" && observ == "" && valida == true) {
            $('#error').html("<font color='red'><b>Ingrese Observaciones sobre el motivo de la devoluci&oacute;n</b></font>");
            $("#observacion").focus();
            valida = false;
        }

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#correoFrm').submit();
            //alert ("ok");
        }
    });   
});
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#correoFrm').attr("action", "pdf");
        $('#correoFrm').submit();
        $('#correoFrm').attr("action", "citacion");
    });    
    

   function comprobarextension() {

        if ($("#filecolilla").val() != "") {
            $('#error').html();
            var archivo = $("#filecolilla").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extension de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extension esta entre las permitidas
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