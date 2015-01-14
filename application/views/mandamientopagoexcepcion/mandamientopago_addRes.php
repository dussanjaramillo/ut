<?php 
/**
 * resolucionpago_addRes -- Vista para adicionar las resoluciones de excepciones.
 *
 * @author		Human Team Technology QA
 * @author		Sergio Amaya S. - saas02@gmail.com
 * @version		1.3
 * @since		Febrero de 2014
 */
?>
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }
    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>
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
        $attributes = array('class' => 'form-inline',
            'id' => 'ResolucionAdd',
            'name' => 'ResolucionAdd',
            'method' => 'POST');
        echo form_open(current_url(),$attributes); 
        echo form_open_multipart(base_url(),$attributes);
        ?>
        
        <center>
            <h2>Crear Resoluci&oacute;n ordenando seguir adelante</h2>
        </center>
        <div class="center-form-large-20">
        <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
        <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
        <input type="hidden" name="cod_fiscalizacion" id="cod_fiscalizacion" value="<?php echo $fiscalizacion ?>">
        <input type="hidden" name="mandamiento" id="mandamiento">
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
                echo form_label('Dirección', 'direccion');
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
            echo form_label('Comentarios&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            $data = array(
                        'name'        => 'comentarios',
                        'id'          => 'comentarios',
                        'value'       => set_value('comentarios'),
                        'width'       => '100%',
                        'heigth'      => '5%',
                        'style'       => 'width:82%'
                        //'class'       => 'input-mini'
                      );

            echo "&nbsp;&nbsp;".form_textarea($data);
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
                echo form_dropdown('asignado', $select,'','id="asignado" class="chosen" placeholder="-- Seleccione --" ');
                ?>
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
                             echo "<br>";
                             echo form_label('Revisado', 'revisado')."<br>";
                                $datarevisado = array(
                                    'name'        => 'revisado',
                                    'id'          => 'revisado',
                                    'checked'     => FALSE,
                                    'style'       => 'margin:10px'
                                    );                             
                             echo form_radio($datarevisado,"APROBADO")."&nbsp;&nbsp;PRE-APROBADO<br>";
                             echo form_radio($datarevisado,"DEVOLVER")."&nbsp;&nbsp;DEVOLVER<br><br><br>";

                             echo form_error('revisado','<div>','</div>');
                            ?>    
                            </div>
                         </p>
                        </td>
                        <td>
                            <p>
                             <div id="aproved" class="span3">
                             <?php
                              echo form_label('Aprobado', 'aprobado')."<br>";  
                                 $dataaprobado = array(
                                     'name'        => 'aprobado',
                                     'id'          => 'aprobado',
                                     'checked'     => FALSE,
                                     'style'       => 'margin:10px'
                                     );
                              echo form_radio($dataaprobado,"REVISADO")."&nbsp;&nbsp;REVISADO<br>";
                              echo "<div id='aprobado' style='display:none'>";
                              echo form_radio($dataaprobado,"APROBADO")."&nbsp;&nbsp;APROBADO Y FIRMADO<br>";
                              echo "</div>";
                              echo form_radio($dataaprobado,"RECHAZADO")."&nbsp;&nbsp;DEVOLVER";
                              

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
  <script type="text/javascript">
$(document).ready(function() {
    var perfil = $('#perfil').val();
  
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
         if (band43 == 1 && band42 != 1){
             $('#aproved').hide();
             $('#revised').hide();
         }
        
        if (band41 == 1 && band42 != 1 && band43 !=1){
            $('#guardar').attr('disabled','disabled');
            $('#aproved').hide();
            $('#revised').hide();
        }
        
        
        
    $('#guardar').click(function() { 
        tinyMCE.triggerSave();
        $('#error').html("");
        var coment = $('#comentarios').val();
        var asigna = $('#asignado').val();
        var notify = $('#notificacion').val();
        var valida = true;
        var res = asigna.split("-");        
        
        
        if (notify == "") {
            $('#error').html("<font color='red'><b>Ingrese Notificaci&oacute;n</b></font>");
            $("#notificacion").focus();
            valida = false;
        }
        if (coment == "") {
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            $('#comentarios').focus();
            valida = false;
        }
        if (asigna == "") {
            $('#error').html("<font color='red'><b>Seleccione Asignar a</b></font>");
            $('#asignado').focus();
            valida = false;
        }
       
        if (res[1] != 7 ){
            $('#error').html("<font color='red'><b>Por favor asignar a otro Usuario</b></font>");
            valida = false;
        }
        
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#ResolucionAdd').submit();
            $(".ajax_load").hide("slow");
            //alert ('ok');
        }
    });
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#ResolucionAdd').attr("action", "pdf");
        $('#ResolucionAdd').submit();
        $('#ResolucionAdd').attr("action", "add");
    });
 });
  </script>
   