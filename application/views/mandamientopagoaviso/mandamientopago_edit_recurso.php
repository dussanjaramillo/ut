<script type="text/javascript">
    tinymce.init({
        language : "es",
        selector: "textarea#mandamiento",
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
$data = array();
$data = $result;    
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php     
        $attributes = array('class' => 'form-inline',
            'id' => 'MandamientoEdit',
            'name' => 'MandamientoEdit',
            'method' => 'POST');
        
        echo form_open_multipart('mandamientopago/editExc',$attributes);
        echo form_hidden('id', $data->COD_MANDAMIENTOPAGO);
        
        echo "<center>".$titulo."</center>";               
        echo $custom_error;                 
        ?>

        <div class="center-form-large-20">
        <br>
        <br>
        <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
        <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
        <input type="hidden" name="verifi_aprobado" id="verifi_aprobado" value="<?php echo $data->APROBADO;?>"> 
        <input type="hidden" name="verifi_revisado" id="verifi_revisado" value="<?php echo $data->REVISADO;?>">
        <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
        <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>
        
        <table cellspacing="0" cellspading="0" border="0" align="center">
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
            <input type="hidden" id="clave" name="clave" value="<?= $data->COD_MANDAMIENTOPAGO?>">
            <input type="hidden" id="codigo" name="codigo" value="">
            <?php
                $datamandamiento = array(
                           'name'        => 'mandamiento',
                           'id'          => 'mandamiento',
                           'value'       => @$plantilla,
                           'width'       => '80%',
                           'heigth'      => '50%'
                         );
                echo form_textarea($datamandamiento);
                echo form_error('mandamiento','<div>','</div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p>
            <?php
            $select = array();
            echo form_label('Asignar a&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'asignado');
            $select[''] = "-- Seleccione --";
            foreach($asignado as $row) {
                $select[$row->IDUSUARIO."-".$row->IDCARGO] = $row->NOMBRES." ".$row->APELLIDOS;
            }
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            echo form_dropdown('asignado', $select, $data->ASIGNADO_A,'id="asignado" class="chosen" placeholder="-- Seleccione --" ');

            echo form_error('asignado','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
                <tr id="numResolu" style="display:none">            
                <td>
                    <p>
                    <?php
                        echo form_label('Fecha Resolución&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'fecharesolu');
                        $dataFechaRe = array(
                                  'name'        => 'fecharesolu',
                                  'id'          => 'fecharesolu',
                                  'value'       => '',
                                  'maxlength'   => '12',
                                  'readonly'    => 'readonly',
                                  'size'        => '15'
                                );

                       echo "<br>".form_input($dataFechaRe)."&nbsp;";
                       echo form_error('fecharesolu','<div>','</div>');

                       echo form_hidden('fechaonbased',date("d/m/Y"));
                    ?>
                    </p>    
                </td>
                <td>
                    <?php
                        echo form_label('N&uacute;mero de Resolución&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'numreso');
                        $datacolilla = array(
                                   'name'        => 'numreso',
                                   'id'          => 'numreso',
                                   'value'       => '',
                                   'maxlength'   => '10',
                                 );
                        echo "<br>".form_input($datacolilla);
                    ?>
                </td>
        </tr> 
        <tr id="cargColilla" style="display:none">
            <td align="left" colspan='2'>
                <p>
                <?php
                    echo form_label('Cargar Documento&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'filecolilla');
                    $datafile = array(
                               'name'        => 'filecolilla',
                               'id'          => 'filecolilla',
                               'value'       => '',
                               'maxlength'   => '10', 
                             );
                    echo "<br>".form_upload($datafile);
                ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <?php            
            echo form_label('Comentarios&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            $datacoment = array(
                        'name'        => 'comentarios',
                        'id'          => 'comentarios',
                        'value'       => $data->COMENTARIOS,
                        'rows'        => '7',
                        'cols'        => '80',
                        'style'       => 'width:82%'
                      );

            echo "&nbsp;&nbsp;".form_textarea($datacoment);
            echo form_error('comentarios','<div><b><font color="red">','</font></b></div>');
            ?>
            </td>
        </tr>
        <tr>
            <td align="left">
                <p>
                <div class="span3">
                    <?php
                    $nrProc = $data->COD_FISCALIZACION;
                    $nrActa = $data->COD_MANDAMIENTOPAGO;                    
                    $results=array();
                    $cRuta  = $rutaArchivo;                   
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
                              $cCadena  = "&nbsp;"; 
                              $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Mandamiento N° Proceso ".$nrProc; // $results[$x]
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
            <td align="center" colspan="2">
                <table border ="0">
                    <tr>
                        <td>
                         <p>
                            <div id="revised" class="span3">
                            <?php
                             echo form_label('Revisado', 'revisado')."<br>";
                             if ($data->REVISADO == "REVISADO") {
                             $checked1 = TRUE;
                             }else {
                              $checked1 = FALSE;
                             }
                             if ($data->REVISADO == "DEVOLVER") {
                                $checked2 = TRUE;
                                }else {
                                 $checked2 = FALSE;
                                }
                                $datarevisado = array(
                                    'name'        => 'revisado',
                                    'id'          => 'revisado',
                                    'style'       => 'margin:10px'
                                    );
                             echo form_radio($datarevisado,"REVISADO",$checked1)."&nbsp;&nbsp;APROBADO<br>";
                             echo "<div id='divdevol'>";
                             echo form_radio($datarevisado,'DEVOLVER',$checked2).'&nbsp;&nbsp;DEVOLVER<br><br><br>';
                             echo "</div>";
                             
                            ?>    
                            </div>
                         </p>
                        </td>
                        <td>
                            <p>
                             <div id="aproved" class="span3">
                             <?php                             
                              $data->APROBADO == "REVISADO";
                              echo form_label('Aprobado', 'aprobado')."<br>";
                                if ($data->APROBADO == "REVISADO") {
                                $checked1 = TRUE;
                                }else {
                                 $checked1 = FALSE;
                                }
                                if ($data->APROBADO == "APROBADO") {
                                $checked2 = TRUE;
                                }else {
                                 $checked2 = FALSE;
                                }
                                if ($data->APROBADO == "RECHAZADO") {
                                $checked3 = TRUE;
                                }else {
                                 $checked3 = FALSE;
                                }
                                
                                 $dataaprobado = array(
                                     'name'        => 'aprobado',
                                     'id'          => 'aprobado',
                                     'style'       => 'margin:10px'
                                     );
                              echo '<div id="REVISADO">';
                              echo form_radio($dataaprobado,"REVISADO",$checked1)."&nbsp;&nbsp;REVISADO<br>";
                              echo '</div>';
                              echo '<div id="APROBADO" style="display:none">';
                              echo form_radio($dataaprobado,"APROBADO",$checked2)."&nbsp;&nbsp;APROBADO Y FIRMADO<br>";
                              echo '</div>';
                              echo '<div id="RECHAZADO">';
                              echo form_radio($dataaprobado,"RECHAZADO",$checked3)."&nbsp;&nbsp;DEVOLVER";
                              echo '</div>';

                              echo form_error('aprobado','<div>','</div>');
                              $permisoUser = 0;
                              foreach ($permiso as $user) {
                                 $var = $user['IDGRUPO'];
                                 $permisoUser = $var.'-'.$permisoUser;
                             }
                                     $permisoUser = substr($permisoUser,0,-2);
                                     echo "<tr><td>
                                     <input type='hidden' value='".$permiso[0]['IDGRUPO']."' name='perfil' id='perfil'>
                                     </td></tr>
                                     <td>
                                     <input type='hidden' value='".$user['IDUSUARIO']."' name='iduser' id='iduser'>
                                     </td>";
                             ?>
                             </div>
                             </p>             
                        </td>
                    </tr>
                </table>
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
        window.history.forward(-1);
        tinyMCE.triggerSave();
          $('#fecharesolu').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
              //minDate: new Date('20'+anonot, mesnot, dianot)
          });
        var perfil = $('#perfil').val();
        var estado = $('#verifi_aprobado').val();
        var revisado = $('#verifi_revisado').val(); 
        var user = $("#iduser").val();
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
                $('#error').show();
                $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                return 1;
            } 
      });
            if (estado == 'APROBADO'){
                $('input:radio').attr('disabled', 'disabled');
                $('#guardar').prop("disabled",true);
                $('#error').show();
                $('#error').html("<font color='red'><b>Mandamiento Aprobado y Firmado</b></font>");
            }else if (perfil == 43 && estado != 'REVISADO'){
                $('#divdevol').hide();
            }
                                         
        /////// ----- Validacion de Perfiles ----- \\\\\\\\
        if (perfil == "41"){
           $('#aproved').hide();
        }

        if (perfil == "43" && estado != 'REVISADO'){
             $('#aproved').hide();
             $('#revised').hide();             
        }else if (perfil == "43" && estado == 'REVISADO'){
            $("#asignado").val(user+'-8');
            $("#asignado").attr('disabled', true)
            $('#cargColilla').show();
            $('#numResolu').show();
            $('#REVISADO').hide();
            $('#RECHAZADO').hide();
            $('#APROBADO').show();
            $('#revised').hide();
        }
        
        if (revisado == 'DEVOLVER' && perfil == '43' ){              
                $('#revised').show(); 
                $('#error').show();
                $('#error').html("<b>Por Favor Validar la Resolución</b>");
        }
        if (revisado == 'REVISADO' && perfil == '41' ){              
                $('#revised').show(); 
                $('#error').show();
                $('#error').html("<b>Por Favor Validar la Resolución</b>");
        }
        if (estado == 'RECHAZADO' && perfil == '41'){             
                jQuery("input[name=aprobado]").attr("disabled",true)
                $('#aproved').show();
                $('#error').show();
                $('#error').html("<b>Por Favor Validar la Resolución</b>");
        }
        
        if (perfil == '42'){
            jQuery("input[name=revisado]").attr("disabled",true)
        }
   });
      
  $('#guardar').click(function() {
        $('#error').html("");
        var coment = $('#comentarios').val();
        var asigna = $('#asignado').val();
        var clave = $('#clave').val();
        var iduser = $('#iduser').val();
        var perfil = $('#perfil').val();
        var filecolilla = $('#filecolilla').val();
        var numreso = $('#numreso').val();
        var fechareso = $('#fecharesolu').val();
        var aprobado = $("input[name='aprobado']:checked").val(); 
        var revisado = $("input[name='revisado']:checked").val(); 
        var valida = true;
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'excepcion'){
            url = url+'/editExc';
        }else if (tipo == 'recurso'){
            url = url+'/editRec';
        }
        var res = asigna.split("-");
        //alert (perfil+"+++"+res[1]+"***"+aprobado+"***"+revisado);
        $('#codigo').val(clave);
        if (coment == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
        }else if (asigna == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == undefined){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == 'DEVOLVER' && res[1] != 8){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == 'REVISADO' && res[1] != 9){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Coordinador</b></font>");
            valida = false;
        }else if (perfil == 42 && res[1] != 7 && aprobado == 'RECHAZADO'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
        }else if (perfil == 42 && res[1] != 8 && aprobado == 'REVISADO'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
        }else if (perfil == 43 && revisado == 'DEVOLVER'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
            valida = false;
        }else if (perfil == 43 && revisado == 'REVISADO' && res[1] != 7){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
        }else if (perfil == 43 && (res[1] != 8 || res[1] == 8) && (aprobado == '' || aprobado == 'REVISADO' || aprobado == 'RECHAZADO')){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor validar Aprobación</b></font>");
            valida = false;
        }else if (perfil == 43 && res[1] != 8 && aprobado == 'APROBADO'){
            $('#error').show();
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
        }else if (asigna == iduser && perfil != 42){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor asignar a otro Usuario</b></font>");
            valida = false;
        }else if (perfil == 42 && aprobado == undefined){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor validar Aprobación</b></font>");
            valida = false;
        }else if (perfil == 43 && aprobado == 'APROBADO' && filecolilla == ''){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor adjuntar Documento</b></font>");
            valida = false;
        }else if (perfil == 43 && aprobado == 'APROBADO' && fechareso == ''){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor adjuntar Fecha de Resolución</b></font>");
            valida = false;
        }else if (perfil == 43 && aprobado == 'APROBADO' && numreso == ''){
            $('#error').show();
            $('#error').html("<font color='red'><b>Por favor adjuntar Número de Resolución</b></font>");
            valida = false;
        }
                
        if (valida == true) {
            $(".ajax_load").show("slow");
            $("#asignado").removeAttr('disabled');
            $("#MandamientoEdit").attr("action", url);
            $('#MandamientoEdit').removeAttr('target');
            $('#guardar').prop("disabled",true);
            $('#MandamientoEdit').submit()
           //alert('ok');
        }
    });
    
    $('#pdf').click(function() {
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#MandamientoEdit').attr("action", "<?= base_url('index.php/mandamientopago/pdf')?>");
        $('#MandamientoEdit').attr('target', '_blank');
        $('#MandamientoEdit').submit();
    });
  </script>
   