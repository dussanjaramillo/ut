
<?php
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php     
if (@$result->COD_ESTADO == 2) {
    $checked1 = TRUE;
    $checked2 = FALSE;
}
if (@$result->COD_ESTADO == 3) {
    $checked1 = FALSE;
    $checked2 = TRUE;
}
if (@$result->COD_ESTADO != 3 && @$result->COD_ESTADO != 2){
    $checked2 = FALSE;
    $checked1 = FALSE;
}
$attributes         = array('id'=>'citacionFrm','name'=>'citacionFrm','class'=>'form-inline','method'=>'POST','enctype' => 'multipart/form-data', );
$dataComent         = array('name'=>'comentarios','id'=>'comentarios','value'=>@$result->OBSERVACIONES,'width'=> '100%','heigth'=>'5%','style'=> 'width:98%','required'=>'true');
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$dataPDF            = array('name'=>'button','id'=>'pdf','value'=>'Generar PDF','type'=>'button','content'=>'<i class="fa fa-file"></i> Generar PDF','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value'=>'Cancelar','type'=>'button','onclick'=>'window.location=\''.base_url().'index.php/auto_liquidacion/autos_info\';','content'=>'<i class="fa fa-undo"></i> Cancelar','class'=>'btn btn-success');
$datafile           = array('name'=>'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=> '10');
$datanotificacion   = array('name'=>'notificacion','id'=>'notificacion','value'=>@$plantilla,'width'=>'80%','heigth'=>'50%');
$datarevisado       = array('name'=>'revisado','id'=>'revisado','checked'=>FALSE,'style'=>'margin:10px');                 

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
            <td colspan="2">
            <?php echo form_textarea($datanotificacion);
            ?>
            </td>
        </tr>
        <tr id="geNotifi" style="display:none">
            <td colspan='2'>
                <?php echo form_label('Generar Notificación', 'notificacion')."<br>"; ?>
                <a href='http://www.sena.edu.co/transparencia/gestion-juridica/paginas/cobros-coactivos.aspx' class='btn-danger' target=_blank>
                    Crear Notificación Página Web
                </a>
            </td>
        </tr>
        <tr>
            <td id="selectAsigna" style="display:none">
                <table>
                    <tr>
                        <td>
                            <?php
                            echo form_label('Asignar a&nbsp;<span><font color="red"><b>*</b></font></span>', 'asignando');?> 
                            <select name="asignado" id="asignado" required>
                                <option value=''>--Seleccione--</option>
                                    <?php foreach($asignado as $row){
                                    echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?>
                            </select>            
                        </td>
                        <td id="revised" class="span3" style="display:none">
                            <?php
                             echo form_label('Revisado', 'revisado')."<br>";                            
                             echo form_radio($datarevisado,"1",$checked1)."&nbsp;&nbsp;GENERADO<br>";
                             echo form_radio($datarevisado,"2",$checked2)."&nbsp;&nbsp;DEVOLVER<br><br><br>";
                            ?>    
                        </td>
                    </tr>
                </table>                               
            </td>            
        </tr>                
         <tr id="cargColilla" style="display:none">
            <td align="left">
                <p>
                <?php
                    echo form_label('Cargar Pantallazo', 'doccolilla');
                    echo "<br>".form_upload($datafile);
                ?>
                </p>
            </td>             
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'observacion');        
                echo "&nbsp;&nbsp;".form_textarea($dataComent);
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
            echo form_button($dataguardar)."&nbsp;&nbsp;";            
            echo form_button($dataPDF)."&nbsp;&nbsp;";            
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
     
      window.history.forward(-1);
      $('#filecolilla').change(function(){ 
        var extensiones_permitidas = new Array(".jpg",".png",".gif"); 
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
                      $('#geNotifi').show();
                  }
              break;
              case '2':
                $("#asignado").val(user);
                $('#cargColilla').show();    
                $('#geNotifi').show();
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
        window.history.forward(-1);
        $('#error').html("");
        tinyMCE.triggerSave();
        var clave = $('#clave').val();
        $('#codigo').val(clave);
        var estado = $('#id_estado').val();
        var observ = $('#observacion').val();
        var doccolilla = $("#filecolilla").val();
        var asigna = $("#asignado").val();
        var revisado = $("input[name='revisado']:checked").val(); 
        var perfil = $("#perfil").val();
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/auto_liquidacion/modificarNotific')?>';
        var valida = true; 
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
                if (doccolilla == '') {
                      $('#error').show();
                      $('#error').html("<b>Por Favor adjuntar el Pantallazo</b>");
                      $("#filecolilla").focus();
                      valida = false;
                      break;
                }
                if (valida == true){
                    $('#id_estado').val('6');
                }
                break;
        }        

        if (valida == true) {
            if (estado == '2'){
                    alert ('Por favor, no olvide pegar la Notificación a la Cartelera');
            }
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
        $('#citacionFrm').attr("action", "citacion");
    });    
    
    
  </script>