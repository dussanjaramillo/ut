
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
        $data = array();
        $data = $result;
        $attributes = array('id' => 'citacionFrm', 'name' => 'citacionFrm',
            'class' => 'form-inline', 
            'method' => 'POST', 
            'enctype' => 'multipart/form-data', 
            );
        echo form_open_multipart(current_url(),$attributes); 
        echo "<center>".$titulo."</center>";
        @$rutaInac=base_url().$inactivo->DOC_FIRMADO.$inactivo->NOMBRE_DOC_CARGADO;
        ?>
        <?php echo $custom_error; ?>        
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
            <input type="hidden" name="id_estado" id="id_estado" value="<?php echo  $data->COD_ESTADO; ?>">
            <input type="hidden" name="mandamiento" id="mandamiento" value="">
            <input type='hidden' value='<?php echo $permiso[0]['IDGRUPO'] ?>' name='perfil' id='perfil'>
            <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
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
            <input type="hidden" id="clave" name="clave" value="<?php echo @$data->COD_MANDAMIENTOPAGO; ?>">
            <input type="hidden" id="notifica" name="notifica" value="<?php echo @$data->COD_AVISONOTIFICACION; ?>">
            <input type="hidden" id="codigo" name="codigo" value="">
        <?php 
            if (count($inactivo)>0){
                echo "<tr>
                        <td align='right' colspan='2'>
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
        <tr id="selectAsigna" style="display:none">
            <td>
                <p>
                <?php
                $select = array();
                echo form_label('<span class="required"><font color="red"><b>* </b></font></span> Asignar a', 'asignado');
                $select[''] = "-- Seleccione --";
                foreach($asignado as $row) {
                    $select[$row->IDUSUARIO] = $row->NOMBRES." ".$row->APELLIDOS;
                }
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo form_dropdown('asignado', $select,'','id="asignado" class="chosen" placeholder="-- Seleccione --" ');
                ?>
                </p>
            </td>
            <td>
                <p>
                   <div id="revised" class="span3" style="display:none">
                   <?php
                        if ($data->COD_ESTADO == 2) {
                            $checked1 = TRUE;
                            $checked2 = FALSE;
                        }
                        if ($data->COD_ESTADO == 3) {
                            $checked1 = FALSE;
                            $checked2 = TRUE;
                        }
                        if ($data->COD_ESTADO != 3 && $data->COD_ESTADO != 2){
                            $checked2 = FALSE;
                            $checked1 = FALSE;
                        }
                    echo "<br>";
                    echo form_label('Revisado', 'revisado')."<br>";
                       $datarevisado = array(
                           'name'        => 'revisado',
                           'id'          => 'revisado',
                           'checked'     => FALSE,
                           'style'       => 'margin:10px'
                           );                             
                    echo form_radio($datarevisado,"APROBADO",$checked1)."&nbsp;&nbsp;APROBADO<br>";
                    echo form_radio($datarevisado,"DEVOLVER",$checked2)."&nbsp;&nbsp;DEVOLVER<br><br><br>";

                    echo form_error('revisado','<div>','</div>');
                   ?>    
                   </div>
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
            ?>
            </div>
            </td>
        </tr>
        <tr>
            <td id="Devol" style="display:none">
                <?php
                    echo form_label('Devolucion', 'devolucion')."<br>";
                    $datadevolucion = array(
                        'name'        => 'devolucion',
                        'id'          => 'devolucion',
                        'checked'     => FALSE,
                        'style'       => 'margin:10px'
                        );
                    echo "&nbsp;".form_radio($datadevolucion,"1")."&nbsp;&nbsp;SI<br>";
                    echo "&nbsp;".form_radio($datadevolucion,"0")."&nbsp;&nbsp;NO";                
                ?>
            </td>
            <td id="motDevol" style="display:none">
                <div class="span3">
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
                </div>
            </td>
        </tr>       
        <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar colilla', 'filecolilla');
                    $datafile = array(
                               'name'        => 'filecolilla',
                               'id'          => 'filecolilla',
                               'value'       => '',//$data->DOC_COLILLA,
                               'maxlength'   => '10',
                             );
                    echo "<br>".form_upload($datafile);
                ?>
                </p>
            </td>
            <td align="left">
                <p>
                <div class="span3">
                    <?php                   
                    $results=array();
                    $cRuta  = $rutaColilla;
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
                        //echo "Lista de Adjuntos<br>";                                                
                        for($x=0; $x<count($results); $x++) {
                            if ($results[$x] == @$data->NOMBRE_COL_CARGADO.".pdf"){
                              $cCadena  = "&nbsp;";
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
         <tr id="cargDocumento" style="display:none">
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar Documento', 'doccolilla');
                    $datadoc = array(
                               'name'        => 'doccolilla',
                               'id'          => 'doccolilla',
                               'value'       => '',//$data->DOC_COLILLA,
                               'maxlength'   => '10',
                             );
                    echo "<br>".form_upload($datadoc);
                ?>
                </p>
            </td>
            <td align="left">
                <p>
                <div class="span3">
                    <?php                   
                    $results=array();
                    $cRuta  = $rutaDocumento;
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
                        //echo "Lista de Adjuntos<br>";                                                
                        for($x=0; $x<count($results); $x++) {
                            if ($results[$x] == $data->NOMBRE_DOC_CARGADO.".pdf"){
                              $cCadena  = "&nbsp;";
                              $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Documento"; // $results[$x]
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
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => $data->OBSERVACIONES,
                            'width'       => '100%',
                            'heigth'      => '5%',
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
                <div style="display:none" id="error" class="alert alert-danger"></div>
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
                   'onclick' => 'window.location=\''.base_url().'index.php/mandamientopago/nits\';',
                   'content' => '<i class="fa fa-undo"></i> Cancelar',
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
      var fechanot =  $('#fechanotificacion').val();                    
      var anonot = parseInt(fechanot.split("/")[2]);
      var mesnot = parseInt(fechanot.split("/")[1]) -1;
      var dianot = parseInt(fechanot.split("/")[0]); 
      window.history.forward(-1);
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
                $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                $("#filecolilla").focus();
            }else{
                $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                return 1;
            } 
      });
      
        $('#doccolilla').change(function(){ 
        var extensiones_permitidas = new Array(".pdf"); 
        var archivo = $('#doccolilla').val();
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        var permitida = false;
        
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
            permitida = true;
            break;
            }
        } 
            if (!permitida) {
                $('#doccolilla').val('');
                $('#error').show();
                $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                $("#doccolilla").focus();
            }else{
                $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                return 1;
            } 
      });
          $('#fechaonbase').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0",
              minDate: new Date('20'+anonot, mesnot, dianot)
          });
          var estado = $('#id_estado').val();
          var perfil = $("#perfil").val();
          var user = $("#iduser").val();
          switch (estado){              
              default :
                  if (perfil == 43){
                     $('#selectAsigna').show();
                  }else if (perfil == 41){
                      $('#selectAsigna').show();
                      $('#revised').show();
                  }
              break;
              case '2':
                $("#asignado").val(user);
                $('#radicOnbase').show();
               break;
               case '5':
                $('#radicOnbase').show();
                $('#numColilla').show();
                $('#cargColilla').show();              
                $('#cargDocumento').show();
                $('#idComu').show();
                $('#Devol').show();
                break;
          }
          
            $('input[name="devolucion"]').change(function() {
                if ($('input[name="devolucion"]:checked').val() == '0') {
                    $('#motDevol').hide('slow');
                    $('#motivo').val('');
                }else{($('input[name="devolucion"]:checked').val() == '1') 
                    $('#motDevol').show();                    
                } 
            });
             
      $('#guardar').click(function() {
        $('#error').html("");
        tinyMCE.triggerSave();
        var clave = $('#clave').val();
        $('#codigo').val(clave);
        var estado = $('#id_estado').val();
        var onbase = $('#onbase').val();
        var observ = $('#observacion').val();
        var fechaonb = $('#fechaonbase').val();
        var devolucion = $('input[name="devolucion"]:checked').val();
        var motivo = $('#motivo').val();
        var doccolilla = $("#doccolilla").val();
        var asigna = $("#asignado").val();
        var revisado = $("input[name='revisado']:checked").val(); 
        var perfil = $("#perfil").val();
        var valida = true; 
        //validaciones
        
        if (asigna == "" && perfil != 43 && estado != 2 ) {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false;            
        }
        switch (estado){
            default :
                if (perfil == 41 && revisado == undefined){
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
                    valida = false;
                    break;
                }else {
                    if (perfil == 41 && revisado == 'APROBADO'){
                        $('#id_estado').val('2');
                    }else if (perfil == 41 && revisado == 'DEVOLVER'){
                        $('#id_estado').val('3');
                    }else if (perfil == 43 && revisado == undefined){
                        $('#id_estado').val('4');
                    }
                }
            break;
            case '2':                 
                if (observ == "") {
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
                    valida = false;
                    break;
                }else if (onbase == ''){
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Ingrese N&uacute;mero Onbase</b></font>");
                    $("#onbase").focus();
                    valida = false;
                    break;
                }
                if (valida == true){
                   $('#id_estado').val('5');
                }
                break;
           case '5':
               if (observ == "") {
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
                    valida = false;
                    break;
                }else if (doccolilla == '') {
                      $('#error').show();
                      $('#error').html("<font color='red'><b>Por Favor adjuntar el Documento</b></font>");
                      $("#doccolilla").focus();
                      valida = false;
                      break;
                }else if (fechaonb == '') {
                      $('#error').show();
                      $('#error').html("<font color='red'><b>Ingrese Fecha onbase</b></font>");
                      $("#fechaonbase").focus();
                      valida = false;
                      break;
                }else if (devolucion == undefined){
                      $('#error').show();
                      $('#error').html("<font color='red'><b>Seleccione el campo Devolución</b></font>");
                      $("#devolucion").focus();
                      valida = false;
                      break;
                }
                    if (valida == true && motivo != ''){
                        $('#id_estado').val('7');
                    }else{
                        $('#id_estado').val('6');
                    }
        }        

        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#citacionFrm').submit();
              //alert ('ok');
        }
    });   
});
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $(".ajax_load").show("slow");
        $('#citacionFrm').attr("action", "pdf");
        $('#citacionFrm').submit();
        $('#citacionFrm').attr("action", "citacion");
    });    
    
  </script>