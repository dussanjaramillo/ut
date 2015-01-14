<?php 
/**
 * mandamientopago_editRes -- Vista para editar las resoluciones.
 *
 * @author		Human Team Technology QA
 * @author		Sergio Amaya. - saas02@gmail.com
 * @version		1.3
 * @since		Enero de 2014
 */
?>
<script type="text/javascript">
    tinymce.init({
        language : "es",
        selector: "textarea#resolucion",
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
$data = $result;
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php     
        $attributes = array('class' => 'form-inline',
            'id' => 'ResolucionEdit',
            'name' => 'ResolucionEdit',
            'method' => 'POST');
        echo form_open(current_url(),$attributes);
        echo form_open_multipart(base_url(),$attributes);
        echo $custom_error;
        echo form_hidden('id', $data->COD_MANDAMIENTO_ADELANTE); ?>
        <center>
            <h2>Editar Resoluci&oacute;n ordenando seguir adelante</h2>
        </center>
        <div class="center-form-large-20">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
            <input type="hidden" name="gestion_cobro" id="gestion_cobro" value="<?php echo @$post['gestion_cobro'];?>">            
            <input type="hidden" name="verifi_aprobado" id="verifi_aprobado" value="<?php echo $data->APROBADO;?>"> 
        <br>
        <br>
       <table cellspacing="0" cellspading="0" border="0" align="center">
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
                           'value'       => 'Mandamiento de Pago',
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
                &nbsp;
            </p>
            </td>
        </tr>
        </table>
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" id="clave" name="clave" value="<?= $data->COD_MANDAMIENTOPAGO?>">
            <input type="hidden" id="codigo" name="codigo" value="">
            <?php
                //echo form_label('Mandamiento<span class="required">*</span>', 'mandamiento');
                //var_dump($plantilla);
                $datamandamiento = array(
                           'name'        => 'resolucion',
                           'id'          => 'resolucion',
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
            <td>
            <p>
            <?php
            echo form_label('Comentarios&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            $datacoment = array(
                        'name'        => 'comentarios',
                        'id'          => 'comentarios',
                        'value'       => $data->COMENTARIO,
                        'rows'        => '7',
                        'cols'        => '80',
                        'style'       => 'width:82%'
                      );

            echo "&nbsp;&nbsp;".form_textarea($datacoment);
            echo form_error('comentarios','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>
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
        <tr>
            <td align="left">
                <p>
                <div class="span3">
                    <?php
                    $nrActa = $data->COD_MANDAMIENTOPAGO;
                    $results=array();
                    $cRuta  = "./application/uploads/".$nrActa;
                    $cRuta .= "/mandamientos/";                    
                    $cRuta."<br>";                    
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
                              $cCadena  = "&nbsp;"; //<img src='../../img/trash.png' style='cursor:pointer' title='Eliminar'";
                              //$cCadena .= "onclick=javascript:f_DeleteAdj('".$cRuta."{$results[$x]}')>&nbsp;";
                              $cCadena .= ($x+1).". <a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Mandamiento N° ".$nrActa; // $results[$x]
                              $cCadena .= "</a><br>";
                              echo $cCadena;
                        }
                        echo "<br>";?>                        
                    </div>
                    <?php } ?>
                    <input type="hidden" id="documento" name="documento" value="<?php echo count($results); ?>">
                </div>
                </p>
            </td>
        </tr>
        <tr>
            <td align="center">
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
                              echo '<div id="APROBADO">';
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
                                     <input type='hidden' value='".$empresa['perfiles']."' name='perfil' id='perfil'>
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
            <td align="center">
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
        var documento = $('#documento').val();
        var perfil = $('#perfil').val();
        var estado = $('#verifi_aprobado').val();
        var revisado = $('#verifi_revisado').val();
                                
            if (estado == 'APROBADO'){
                $('input:radio').attr('disabled', 'disabled');
                $('#guardar').prop("disabled",true);
                $('#error').html("<font color='red'><b>Mandamiento Aprobado y Firmado</b></font>");
            }else if (perfil == 43){
                $('#divdevol').hide();
            }
                                   
        if (documento != '1'){
            $('#APROBADO').hide('slow');
        }

  
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
         if (band43 == 1 && band42 != 1 && revisado){
             $('#aproved').hide();
             $('#revised').hide();
         }                  
        
        if (band41 == 1 && band42 != 1 && band43 !=1){
            $('#aproved').hide();
        }
        
        if (revisado == 'DEVOLVER' && perfil == '43' ){              
                $('#revised').show(); 
                $('#error').html("<font color='red'><b>Por Favor Validar el Mandamiento</b></font>");
        }
        if (revisado == 'REVISADO' && perfil == '41' ){              
                $('#revised').show(); 
                $('#error').html("<font color='red'><b>Por Favor Validar el Mandamiento</b></font>");
        }
        if (estado == 'RECHAZADO' && perfil == '41'){             
                jQuery("input[name=aprobado]").attr("disabled",true)
                $('#aproved').show();
                $('#error').html("<font color='red'><b>Por Favor Validar el Mandamiento</b></font>");
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
        var aprobado = $("input[name='aprobado']:checked").val(); 
        var revisado = $("input[name='revisado']:checked").val(); 
        var valida = true;
        var res = asigna.split("-");

        $('#codigo').val(clave);
        if (coment == "") {
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
        }else if (asigna == "") {
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == undefined){
            $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == 'DEVOLVER' && res[1] != 8){
            $('#error').html("<font color='red'><b>Asignar a usuario Abogado</b></font>");
            valida = false;
        }else if (perfil == 41 && revisado == 'REVISADO' && res[1] != 9){
            $('#error').html("<font color='red'><b>Asignar a usuario Coordinador</b></font>");
            valida = false;
        }else if (perfil == 42 && res[1] != 7 && aprobado == 'RECHAZADO'){
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
        }else if (perfil == 42 && res[1] != 9 && aprobado == 'REVISADO'){
            $('#error').html("<font color='red'><b>Por favor asignar a usuario Coordinador</b></font>");
            valida = false;
        }else if (perfil == 42 && res[1] != 7 && aprobado == 'APROBADO'){
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
        }else if (asigna == iduser && perfil != 42){
            $('#error').html("<font color='red'><b>Por favor asignar a otro Usuario</b></font>");
            valida = false;
        }else if (perfil == 42 && aprobado == undefined){
            $('#error').html("<font color='red'><b>Por favor validar Aprobación</b></font>");
            valida = false;
        }else if (perfil == 43 && revisado == 'DEVOLVER'){
            $('#error').html("<font color='red'><b>Por favor validar Revisión</b></font>");
            valida = false;
        }else if (perfil == 43 && revisado == 'REVISADO' && res[1] != 7){
            $('#error').html("<font color='red'><b>Asignar a usuario Secretario</b></font>");
            valida = false;
        }
                
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#ResolucionEdit').submit()
            $(".ajax_load").hide("slow");
//            alert ('ok');
        }
    });
    
    $('#pdf').click(function() {
        $('#ResolucionEdit').attr("action", "pdf");
        $('#ResolucionEdit').submit();
        $('#ResolucionEdit').attr("action", "edit");
    });
  </script>