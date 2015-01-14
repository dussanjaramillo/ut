
<?php
if( ! defined('BASEPATH') ) exit('No direct script access allowed');
    if (@$result->COD_ESTADO == 2) {
    $checked1 = TRUE;
    $checked2 = FALSE;
    }elseif (@$result->COD_ESTADO == 3) {
        $checked1 = FALSE;
        $checked2 = TRUE;
    }elseif (@$result->COD_ESTADO != 3 && @$result->COD_ESTADO != 2){
        $checked2 = FALSE;
        $checked1 = FALSE;
    }
if (@$result->NUM_RADICADO_ONBASE == '0'){
    $onbase = set_value('onbase');
}else {
    $onbase = @$result->NUM_RADICADO_ONBASE;
}    
$attributes         = array('id'=>'citacionFrm','name'=>'citacionFrm','class'=>'form-inline','method'=>'POST','enctype' => 'multipart/form-data', );
$dataComent         = array('name'=>'comentarios','id'=>'comentarios','value'=>@$result->OBSERVACIONES,'width'=> '100%','heigth'=>'5%','style'=> 'width:98%','required'=>'true');
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacomunica       = array('name'=>'comunicacion','id'=>'comunicacion','value'=>set_value('comunicacion'),'maxlength'=>'10');
$dataPDF            = array('name'=>'button','id'=>'pdf','value'=>'Generar PDF','type'=>'button','content'=>'<i class="fa fa-file"></i> Generar PDF','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value'=>'Cancelar','type'=>'button','onclick'=>'window.location=\''.base_url().'index.php/auto_liquidacion/autos_info\';','content'=>'<i class="fa fa-undo"></i> Cancelar','class'=>'btn btn-success');
$datadoc            = array('name'=>'doccolilla','id'=>'doccolilla','value'=>'','maxlength'=>'10');
$datafile           = array('name'=>'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=> '10');
$datanotificacion   = array('name'=>'notificacion','id'=>'notificacion','value'=>@$plantilla,'width'=>'80%','heigth'=>'50%');
$dataFechaOn        = array('name'=>'fechaonbase','id'=>'fechaonbase','value'=>set_value('fechaonbase'),'maxlength'=>'12','readonly'=>'readonly','size'=>'15');
$dataFecha          = array('name'=>'fechanotificacion','id'=>'fechanotificacion','value'=>@$result->FECHA_NOTIFICACION,'maxlength'=>'12','readonly'=>'readonly','size'=>'15');
$datarevisado       = array('name'=>'revisado','id'=>'revisado','checked'=>FALSE,'style'=>'margin:10px');  
$dataonbase         = array('name'=>'onbase','id'=>'onbase','value'=>$onbase,'maxlength'=>'10','type'=>'number');
$datadevolucion     = array('name'=>'devolucion','id'=>'devolucion','checked'=> FALSE,'style'=>'margin:10px');
$datacolilla        = array('name'=>'colilla','id'=>'colilla','value'=>'','maxlength'=>'10');
                

$dataFisc       = array('name'=>'fiscalizacion','id'=>'fiscalizacion','type'=>'hidden','value'=>@$fiscalizacion);
$dataNit        = array('name'=>'cod_nit','id'=>'cod_nit','type'=>'hidden','value'=>$nitempresa);
$dataTipo       = array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>@$tipo);
$dataAuto       = array('name'=>'num_auto','id'=>'num_auto','type'=>'hidden','value'=>@$result->NUM_AUTOGENERADO);
$dataNotif      = array('name'=>'notifica','id'=>'notifica','type'=>'hidden','value'=>@$result->COD_AVISONOTIFICACION);
$dataPerfil     = array('name'=>'perfil','id'=>'perfil','type'=>'hidden','value'=>@$perfil);
$dataUser       = array('name'=>'iduser','id'=>'iduser','type'=>'hidden','value'=>@$iduser);
$dataEstado     = array('name'=>'id_estado','id'=>'id_estado','type'=>'hidden','value'=>@$result->COD_ESTADO);
$dataTemp       = array('name'=>'temporal','id'=>'temporal','type'=>'hidden');
$dataLiquid     = array('name'=>'liquidacion','id'=>'liquidacion','type'=>'hidden');
echo form_open_multipart(current_url(),$attributes); 
@$rutaInac=base_url().'uploads/liquidacion/'.$fiscalizacion.'/pdf/'.@$tipo.'/'.$inactivo->NOMBRE_DOC_CARGADO;
echo @$custom_error; 
echo form_input($dataFisc);
echo form_input($dataNit);
echo form_input($dataTipo);
echo form_input($dataTemp);
echo form_input($dataLiquid);
echo form_input($dataAuto);
echo form_input($dataPerfil);
echo form_input($dataUser);
echo form_input($dataEstado);
echo form_input($dataNotif);
?>
        <div class="center-form-large-20">
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <?php $this->load->view('auto_liquidacion/cabecera',$this->data); ?>
            </td>
        </tr>
        <tr>
            <td>
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
            </td>
        </tr>                
        <tr>
            <td colspan="2">
            <?php
                echo form_textarea($datanotificacion);
                echo form_error('notificacion','<div>','</div>');
            ?>
            </td>
        </tr>
        <tr id="selectAsigna" style="display:none">
            <td>
                <table>
                    <tr>
                        <td>
                            <?php
                            echo form_label('Asignar a&nbsp;<span class="required"><font color="red"><b>* </b></font></span>', 'asignado');?>
                                <select name="asignado" id="asignado" required>
                                <option value=''>--Seleccione--</option>
                                <?php foreach($asignado as $row){
                                echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?>
                            </select>  
                        </td>
                        <td id="revised" style="display:none">
                            <?php
                                echo form_label('Revisado&nbsp;<span class="required"><font color="red"><b>* </b></font></span>', 'revisado')."<br>";                       
                                echo form_radio($datarevisado,"1",$checked1)."&nbsp;&nbsp;APROBADO<br>";
                                echo form_radio($datarevisado,"2",$checked2)."&nbsp;&nbsp;DEVOLVER<br><br><br>";
                           ?>    
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr id="radicOnbase" style="display:none">
            <td>
                <table>
                    <tr>
                        <td width="400">
                            <?php            
                                echo form_label('Numero de radicado Onbase', 'onbase');    
                                echo "<br>".form_input($dataonbase);
                                echo form_error('onbase','<div>','</div>');
                            ?>
                        </td>
                        <td width="400">    
                            <?php
                                echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion')."<br>";
                                echo form_input($dataFecha)."&nbsp;";
                                echo form_hidden('fechanotificaciond',date("d/m/Y"));
                             ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>     
        <tr id="numColilla" style="display:none">
            <td>
                <table>
                    <tr>
                        <td width="400">
                            <?php
                                echo form_label('Fecha Onbase', 'fechaonbase');                
                                echo "<br>".form_input($dataFechaOn)."&nbsp;";
                                echo form_hidden('fechaonbased',date("d/m/Y"));
                            ?>    
                        </td>
                        <td width="400">    
                            <?php
                                echo form_label('N&uacute;mero de colilla correo', 'colilla');
                                echo "<br>".form_input($datacolilla);
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>             
        <tr>
            <td>
            <table>
                <tr>
                    <td id="Devol" style="display:none" width="400">
                        <?php
                            echo form_label('Devolucion', 'devolucion')."<br>";                   
                            echo "&nbsp;".form_radio($datadevolucion,"1")."&nbsp;&nbsp;SI<br>";
                            echo "&nbsp;".form_radio($datadevolucion,"0")."&nbsp;&nbsp;NO";                
                        ?>
                    </td>
                    <td id="motDevol" style="display:none" width="400">
                    <?php
                        echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo')."<br>";?>
                        <select name="motivo" id="motivo" required>
                            <option value=''>--Seleccione--</option>
                            <?php foreach($motivos as $row){
                            echo '<option value="'.$row->COD_MOTIVO_DEVOLUCION.'">'.$row->MOTIVO_DEVOLUCION.'</option>';}?>
                        </select>  
                    </td>
                </tr>            
            </table> 
            </td>        
        </tr>
        <tr id="cargColilla" style="display:none">
            <td align="left" colspan='2'>
                <?php
                    echo form_label('Cargar colilla', 'filecolillab')."<br>";                    
                    echo form_upload($datafile);
                ?>
            </td>
        </tr>
         <tr id="cargDocumento" style="display:none">
            <td align="left" colspan='2'>
                <?php
                    echo form_label('Cargar Documento', 'doccolilla');                    
                    echo "<br>".form_upload($datadoc);
                ?>
            </td>            
        </tr>
        <tr id="idComu" style="display:none">
            <td colspan="2">
                    <?php
                    echo form_label('C&oacute;digo comunicaci&oacute;n', 'comunicacion');                    
                    echo "<br>".form_input($datacomunica);
                    ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <?php
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'observacion');
                echo "&nbsp;&nbsp;".form_textarea($dataComent);
            ?>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <?php 
            echo form_button($dataguardar)."&nbsp;&nbsp;";            
            echo form_button($dataPDF)."&nbsp;&nbsp;";            
            echo form_button($datacancel);
            ?>
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
      var fechanot =  $('#fechanotificacion').val();                    
      var anonot = parseInt(fechanot.split("/")[2]);
      var mesnot = parseInt(fechanot.split("/")[1]) -1;
      var dianot = parseInt(fechanot.split("/")[0]);      
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
                $('#error').html("<b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b>");
                $("#filecolilla").focus();
            }else{
                $('#error').show();
                $('#error').html("<b>Archivo Subido Correctamente</b>");
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
                $('#error').html("<b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b>");
                $("#doccolilla").focus();
            }else{
                $('#error').show();
                $('#error').html("<b>Archivo Subido Correctamente</b>");
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
        var observ = $('#comentarios').val();
        var fechaonb = $('#fechaonbase').val();
        var devolucion = $('input[name="devolucion"]:checked').val();
        var motivo = $('#motivo').val();
        var doccolilla = $("#doccolilla").val();
        var asigna = $("#asignado").val();
        var revisado = $("input[name='revisado']:checked").val(); 
        var perfil = $("#perfil").val();
        var valida = true; 
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/auto_liquidacion/modificarNotific')?>';
        
        ////alert (tipo+'*******'+url+'*******'+estado)
        //validaciones
        
        if (observ == "") {
            $('#error').show();
            $('#error').html("<b>Ingrese Comentarios</b>");
            valida = false;
        }
        switch (estado){
            default :
                if (asigna == "") {
                    $('#error').show();
                    $('#error').html("<b>Seleccione Asignar a</b>");
                    valida = false;            
                }
                if (perfil == 41 && revisado == undefined && valida == true){
                    $('#error').show();
                    $('#error').html("<b>Por favor validar Revisión</b>");
                    valida = false;
                    break;
                }else {
                    if (perfil == 41 && revisado == '1' && valida == true){
                        $('#id_estado').val('2');
                    }else if (perfil == 41 && revisado == '2' && valida == true){
                        $('#id_estado').val('3');
                    }else if (perfil == 43 && revisado == undefined && valida == true){
                        $('#id_estado').val('4');
                    }
                }
            break;
            case '2':
               if (onbase == ''){
                    $('#error').show();
                    $('#error').html("<b>Ingrese N&uacute;mero Onbase</b>");
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
                    $('#error').html("<b>Ingrese Comentarios</b>");
                    valida = false;
                    break;
                }else if (doccolilla == '') {
                      $('#error').show();
                      $('#error').html("<b>Por Favor adjuntar el Documento</b>");
                      $("#doccolilla").focus();
                      valida = false;
                      break;
                }else if (fechaonb == '') {
                      $('#error').show();
                      $('#error').html("<b>Ingrese Fecha onbase</b>");
                      $("#fechaonbase").focus();
                      valida = false;
                      break;
                }else if (devolucion == undefined){
                      $('#error').show();
                      $('#error').html("<b>Seleccione el campo Devolución</b>");
                      $("#devolucion").focus();
                      valida = false;                      
                      break;
                }else if(devolucion == 1 && motivo == ''){
                      $('#error').show();
                      $('#error').html("<b>Seleccione motivo de Devolución</b>");
                      $("#motivo").focus();
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
            $("#citacionFrm").attr("action", url);
            $('#citacionFrm').removeAttr('target');
            $('#citacionFrm').submit();
        }
    });   
});
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#liquidacion').val(notify);
        $("#citacionFrm").attr("action", "<?= base_url('index.php/auto_liquidacion/pdf')?>");
        $('#citacionFrm').attr('target', '_blank');
        $('#citacionFrm').submit();
    });    
    
    
  </script>