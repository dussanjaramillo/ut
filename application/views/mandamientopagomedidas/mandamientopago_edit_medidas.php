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
        <?php     
        $data = $result;        
        $attributes = array('id' => 'citacionFrm', 'name' => 'citacionFrm',
            'class' => 'form-inline', 
            'method' => 'POST', 
            'enctype' => 'multipart/form-data', 
            );
        echo form_open_multipart(current_url(),$attributes);       
        echo "<center> $titulo </center>";
        ?>

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
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];;?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
            <input type="hidden" name="id_estado" id="id_estado" value="<?php echo  $data->COD_ESTADO; ?>">
            <input type="hidden" name="medida" id="medida" value="<?php echo @$resultMedida->COD_MEDIDACAUTELAR;?>">  
            <input type="hidden" id="clave" name="clave" value="<?php echo @$data->COD_MANDAMIENTOPAGO; ?>">
            <input type="hidden" id="notifica" name="notifica" value="<?php echo @$data->COD_AVISONOTIFICACION; ?>">
            <input type="hidden" id="codigo" name="codigo" value="">            
            <input type='hidden' value='<?php echo $permiso[0]['IDGRUPO'] ?>' name='perfil' id='perfil'>
            <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
        </tr>
        <tr>
            <?php $this->load->view('mandamientopago/cabecera',$this->data); ?>
        </tr>
        </table>
        <table cellspacing="0" cellspading="0" border="0" align="center">
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
                    $select[$row->IDUSUARIO."-".$row->IDCARGO] = $row->NOMBRES." ".$row->APELLIDOS;
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
                <div id="error" style="display:none" class="alert alert-danger"></div>
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
      var respuesta = <?= $cod_respuesta ?>;
      //alert (respuesta+'--'+$("#perfil").val());
      window.history.forward(-1);
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

          $('#fechanotificacion').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
          });
          $('#fechaonbase').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
          });
          var estado = $('#id_estado').val();
          var perfil = $("#perfil").val();
          var user = $("#iduser").val();
          switch (respuesta){ 
            case 252:
                if (perfil == 41){
                    $('#selectAsigna').show();
                    $('#revised').show();
                }                  
            break;
            case 1058:
                if (perfil == 41){
                    $('#selectAsigna').show();
                    $('#revised').show();
                }                  
            break;
            case 254:
                if (perfil == 41){
                    $('#selectAsigna').show();
                    $('#revised').show();
                }             
            break; 
            case 253:
            if (perfil == 42){
                $('#selectAsigna').show();
                $('#revised').show();
            }                  
            break;
            case 1337:
                if (perfil == 43){
                    $('#cargDocumento').show();
                }                
                break;
           case 1338:
                if (perfil == 43){
                    $('#selectAsigna').show();                    
                }                
                break;
          }             
//              default :
//                  if (perfil == 43){
//                     $('#selectAsigna').show();
//                  }else if (perfil == 41){
//                      $('#selectAsigna').show();
//                      $('#revised').show();
//                  }
//              break;
//              case '2':
//                $("#asignado").val(user);
//                $('#selectAsigna').show();
//                $('#revised').show();
//               break;
//              case '3':
//               $('#selectAsigna').show();
//               break;
//              case '4':
//                $('#radicOnbase').show();
//                $('#numColilla').show();
//                $('#cargColilla').show();              
//                $('#cargDocumento').show();
//                $('#idComu').show();
//                $('#Devol').show();
//                break;
//                case '7':
//                    $('#selectAsigna').show();
//                    $('#revised').show();
//                break;
//          }
          
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
        var observ = $('#observacion').val();       
        var filecolilla = $("#doccolilla").val();
        var asigna = $("#asignado").val();
        var revisado = $("input[name='revisado']:checked").val(); 
        var perfil = $("#perfil").val();
        var res = asigna.split("-");
        var valida = true; 
        //validaciones
        //alert ("perfil="+perfil+"****estado="+estado+"****revisado="+revisado+"***"+asigna);
        if (asigna == "" && respuesta != '1337') {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false; 
            return;
        }else if (observ == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
            return;
        }else if (perfil == 41 && revisado == 'DEVOLVER' && res[1] != '8'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
            return;
        }else if (perfil == 41 && revisado == 'APROBADO' && res[1] != '9'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Coordinador</b></font>");
            valida = false;
            return;
        }else if (perfil == 42 && revisado == 'DEVOLVER' && res[1] != '7'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
            return;
        }else if (perfil == 42 && revisado == 'APROBADO' && res[1] != '8'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
            return;
        }
        
        switch (respuesta){
            case 252:
                if (perfil == 41){
                    if (revisado == undefined && valida == true){
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
                        valida = false;
                    }else if (perfil == 41 && revisado == 'APROBADO' && valida == true){
                        $('#id_estado').val('2');
                    }else if (perfil == 41 && revisado == 'DEVOLVER' && valida == true){
                        $('#id_estado').val('7');
                    }
                }
                break;
            case 1058:
                if (perfil == 41){
                    if (revisado == undefined && valida == true){
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
                        valida = false;
                    }else if (perfil == 41 && revisado == 'APROBADO' && valida == true){
                        $('#id_estado').val('2');
                    }else if (perfil == 41 && revisado == 'DEVOLVER' && valida == true){
                        $('#id_estado').val('3');
                    }
                }
            break;
            case 254:
                if (perfil == 41 && asigna == undefined){
                   $('#error').show();
                   $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
                   valida = false;
                }else {
                    if (revisado == 'APROBADO'){
                        $('#id_estado').val('2');
                    }else{
                        $('#id_estado').val('7');
                    }
                }                                
            break;
            case 253:
                if (perfil == 42){
                    if (revisado == undefined && valida == true){
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
                        valida = false;
                    }else if (perfil == 42 && revisado == 'APROBADO' && valida == true){
                        $('#id_estado').val('5');
                    }else if (perfil == 42 && revisado == 'DEVOLVER' && valida == true){
                        $('#id_estado').val('3');
                    }
                }
            break;
            case 1337:
                if ($('#doccolilla').val() == ''){
                    $('#error').show();
                    $('#error').html("<font color='red'><b>Por favor adjuntar Documento</b></font>");
                    valida = false;
                }else {
                    $('#id_estado').val('6');
                }
                break;
            case 1338:
                if (perfil == 43){
                    if (asigna == undefined){
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
                        valida = false;
                    }else{
                        $('#id_estado').val('4');
                    }
                }
                break;
        }
        
//        switch (estado){ 
//            default :
//                if (perfil == 41 && revisado == undefined && valida == true){
//                    $('#error').show();
//                    $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
//                    valida = false;
//                    break;
//                }else {
//                    if (perfil == 41 && revisado == 'APROBADO' && valida == true){
//                        $('#id_estado').val('2');
//                    }else if (perfil == 41 && revisado == 'DEVOLVER' && valida == true){
//                        $('#id_estado').val('3');
//                    }
//                }
//            break;
//            case '2':                 
//                if (perfil == 42 && revisado == 'APROBADO' && valida == true){
//                    $('#id_estado').val('4');
//                }else if (perfil == 42 && revisado == 'DEVOLVER' && valida == true){
//                    $('#id_estado').val('7');
//                }
//            break;
//           case '3':               
//                if (valida == true){
//                    $('#id_estado').val('1');
//                }
//           break;           
//           case '7':                 
//                if (perfil == 41 && revisado == 'APROBADO' && valida == true){
//                    $('#id_estado').val('2');
//                }else if (perfil == 41 && revisado == 'DEVOLVER' && valida == true){
//                    $('#id_estado').val('3');
//                }
//                break;
//           case '4':
//               if (filecolilla == '' && valida == true) {
//                      $('#error').show();
//                      $('#error').html("<font color='red'><b>Por Favor adjuntar el Documento</b></font>");
//                      $("#doccolilla").focus();
//                      valida = false;
//                      break;                
//                 }
//                    if (valida == true ){
//                       $('#id_estado').val('6');
//                    }
//        }        

        if (valida == true) { 
            var url = '<?php echo base_url('index.php/mandamientopago/editLevanta')?>';
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $("#citacionFrm").attr("action", url);
            $('#citacionFrm').removeAttr('target');
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
        $('#citacionFrm').attr("target","_blank");
        $('#citacionFrm').submit();
        $('#citacionFrm').attr("action", "citacion");        
    });                
</script>
