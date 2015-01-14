<?php
if (@$proceso['RADICADO_ONBASE'] == NULL):
    $valueRadicado = '';
    $valuefecha = '';
    else:
        $valueRadicado = $proceso['RADICADO_ONBASE'];
        $valuefecha = $proceso['FECHA_ONBASE'];
endif;
$datanotificacion   = array('name'=>'notificacion','id'=>'notificacion','value'=>@$plantilla,'width'=>'80%','heigth'=>'50%');
$comentarios        = array('name'=>'comentarios','id'=>'comentarios','value'=>set_value('comentarios'),'width'=>'100%','heigth'=> '5%','style'=>'width:98%');
$datafile           = array('name'=>'file','id'=>'file','value'=>'','maxlength'=>'10','type'=>'file');
$dataguardar        = array('name'=>'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success');
$dataadicionales    = array('name'=>'adicionales','id'=>'adicionales','style'=>'margin:10px');
$dataonbase         = array('name'=>'onbase','id'=> 'onbase','value'=>@$valueRadicado,'maxlength'=> '10');
$datadevolucion     = array('name'=>'devolucion','id'=>'devolucion','style'=> 'margin:10px');
$dataFecha          = array('name'=>'fechanotificacion','id'=>'fechanotificacion','value'=>@$valuefecha,'maxlength'=>'12','readonly'=>'readonly','size'=> '15');
$datacancel         = array('name'=>'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
$dataPDF            = array('name'=>'button','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-info');
$attributes         = array('id'=>'frmtp', 'name' => 'frmtp', 'method' => 'POST');

echo form_open_multipart("acuerdodepagojuridico/guardarnotificaciones", $attributes);
?>
<input id="informacion" name='informacion' type='hidden'>
<input id="cod_coactivo" name='cod_coactivo' type='hidden' value="<?= $cod_coactivo ?>">
<input id="estado" name='estado' type='hidden' value="<?= $estado ?>">
<input id="nit" name='nit' type='hidden' value="<?= $nit ?>">
<input id="acuerdo" name='acuerdo' type='hidden' value="<?= $acuerdo ?>">
<center>
<table>
    <tr>
        <td colspan='2'>
            <table width="100%" border="0" align="center">
                <tr>
                    <td width="23%" rowspan="2"><img src="<?= base_url('img/Logotipo_SENA.png') ?>" width="100" height="100" /></td>
                    <td width="44%" height="94"><div align="center"><h4><b><?= $titulo ?></b></h4></div></td>
                </tr>
                <tr>
                  <td height="50" colspan="2"><div align="center">
                    <h2><input readonly value="<?= $proceso['TITULO_ENCABEZADO'] ?>" name="Titulo_Encabezado" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="50" /></h2>
                  </div></td>
                </tr>
            </table>   
        </td>
    </tr>
    <tr id="notificar">
        <td colspan='2'>
            <?= form_textarea($datanotificacion) ?>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <input type="button" id="ver_comentarios" value='Ver Comentarios' class='btn btn-info'>            
            <br>
        </td>
        <td id="paginaWeb" style="display:none">
            <?php echo form_label('Generar Notificación: ', 'notificacion'); ?>
                <a href='http://www.sena.edu.co/transparencia/gestion-juridica/paginas/cobros-coactivos.aspx' class='btn-danger' target=_blank>
                    Crear Notificación Página Web
                </a>
        </td>
    </tr>    
    <tr>        
        <td colspan='2'>                            
            <div class="Comentarios" style="width: 84%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <?php
            echo form_label('<b><h2>Historial de Comentarios</h2></b><span class="required"></span>', 'lb_comentarios');
            echo "<br>";
            for ($i = 0; $i < sizeof($comentario); $i++) {
                echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
                echo '<b>Fecha del Comentario: </b>' . $comentario[$i]["CREACION_FECHA"] . '<br>';
                echo '<b>Hecho Por: </b>' . $comentario[$i]["NOMBRES"] . " " . $comentario[$i]["APELLIDOS"] . '<br>';
                echo $comentario[$i]["COMENTARIO"];
                echo "<br><br>";
                echo '</div>';
                echo "<br>";
            }
            ?>

        </div>
        </td>        
    </tr>
    <tr><td><br></td></tr>
    <tr id="radicOnbase" style="display:none">
            <td>
                <?php 
                    echo form_label('Numero de radicado Onbase', 'onbase');               
                    echo form_input($dataonbase);
                ?>
            </td>
            <td>
                <?php
                   echo form_label('Fecha notificaci&oacute;n', 'fechanotificacion');               
                   echo form_input($dataFecha)."&nbsp;";               
                ?>
            </div>
            </td>
    </tr>
    <tr id='seleccion'>
        <td colspan='2'>
            <div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                <?php
                    echo form_label('<b>Revisión</b>&nbsp;&nbsp;<span class="required"></span>', 'revisado');
                    echo "Aprobado".form_radio($dataadicionales,"1")."&nbsp;&nbsp;";
                    echo "No Aprobado".form_radio($dataadicionales,"0")."&nbsp;";
                ?>
            </div>
        </td>
    </tr>     
     <tr>
        <td id="Devol" style="display:none">
            <?php
             echo form_label('Devolucion', 'devolucion')."<br>";
             echo "&nbsp;".form_radio($datadevolucion,"1")."&nbsp;&nbsp;SI<br>";
             echo "&nbsp;".form_radio($datadevolucion,"0")."&nbsp;&nbsp;NO";                
             ?>
        </td> 
        <td id="motDevol" style="display:none">
            <?php echo form_label('Motivo devoluci&oacute;n&nbsp;&nbsp;', 'motivo'); ?>
            <select name="motivo" id="motivo">
            <option value=''>--Seleccione--</option>
                <?php foreach($motivos as $row){
                    echo '<option value="'.$row->COD_MOTIVO_DEVOLUCION.'">'.$row->MOTIVO_DEVOLUCION.'</option>';}?>
            </select>
        </td>
     </tr>  
     <tr id="cargColilla" style="display:none">            
            <td align="left" colspan='2'>
                <?php echo form_label('Cargar Documento', 'cargar')."<br>";?>
                <?= form_upload($datafile);?>
                
            </td>
     </tr>
    <tr><td><br></td></tr>
    <tr>
        <td colspan='2'>
            <?php 
            echo form_label('<b>Comentarios</b>&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            echo form_textarea($comentarios) 
            ?>
        </td>
    </tr>
    <tr>
        <td align='center' colspan='2'>
            <?php
            echo form_button($dataguardar)."&nbsp;";
            echo form_button($dataPDF)."&nbsp;";
            echo form_button($datacancel); 
            ?>
        </td>
    </tr>
    <tr>
        <td colspan='2' align='center'>
            <br><br>
            <div style="display:none" id="error" class="alert alert-danger"></div>
        </td>
    </tr>    
</table>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" >
        <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>
</div>
</center>
<?php echo form_close(); ?>
<script type="text/javascript">
    var estado = <?= $estado ?>;
    if (estado == 1395){
        var readonly = 1;
    }else {
        var readonly = 0;
    }
    tinymce.init({
        language : "es",
        selector: "textarea#notificacion",
        theme: "modern",
        readonly: readonly,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace visualblocks wordcount visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
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
     
    $(document).ready(function(){
        $('#fechanotificacion').datepicker({
              dateFormat: "dd/mm/yy",
              maxDate: "0"
          });
        var estado = <?= $estado ?>;
        
        switch(estado){ 
            case 1516:
                 $("#seleccion").hide();
                $("#cargColilla").hide();
            break;
            case 1396:
                $("#seleccion").hide();
                $("#cargColilla").hide();
            break;
            case 1403:
                $("#seleccion").hide();
                $("#cargColilla").hide();
            break;
            case 1499:
                $("#seleccion").hide();
                $("#cargColilla").hide();
            break;
            case 1395:
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").show();                
            break;
            case 1402:
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").show();                
            break;
            case 1498:
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").show();                
            break;
            case 1397:
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#radicOnbase").show();
                $("#Devol").show();                
                $("#onbase").attr('readonly',true); 
                $("#fechanotificacion").attr('readonly',true); 
             $('#file').change(function(){ 
                var extensiones_permitidas = new Array(".pdf"); 
                var archivo = $('#file').val();
                var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                var permitida = false;

                for (var i = 0; i < extensiones_permitidas.length; i++) {
                    if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                    }
                } 
                    if (!permitida) {
                        $('#file').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                        $("#file").focus();
                    }else{
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                        return 1;
                    } 
              });
            break;
            case 1500:
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#radicOnbase").show();
                $("#Devol").show();                
                $("#onbase").attr('readonly',true); 
                $("#fechanotificacion").attr('readonly',true); 
             $('#file').change(function(){ 
                var extensiones_permitidas = new Array(".pdf"); 
                var archivo = $('#file').val();
                var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                var permitida = false;

                for (var i = 0; i < extensiones_permitidas.length; i++) {
                    if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                    }
                } 
                    if (!permitida) {
                        $('#file').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                        $("#file").focus();
                    }else{
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                        return 1;
                    } 
              });
            break;
            case 1404:
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#radicOnbase").show();
                $("#Devol").show();                
                $("#onbase").attr('readonly',true); 
                $("#fechanotificacion").attr('readonly',true); 
             $('#file').change(function(){ 
                var extensiones_permitidas = new Array(".pdf"); 
                var archivo = $('#file').val();
                var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                var permitida = false;

                for (var i = 0; i < extensiones_permitidas.length; i++) {
                    if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                    }
                } 
                    if (!permitida) {
                        $('#file').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                        $("#file").focus();
                    }else{
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                        return 1;
                    } 
              });
            break;
            case 1409:
                $("#seleccion").hide();
                $("#cargColilla").show();
                $("#notificar").hide();
                $('#paginaWeb').show();
                $('#pdf').attr('disabled',true);
                $('#file').change(function(){ 
                var extensiones_permitidas = new Array(".jpg",".png",".gif"); 
                var archivo = $('#file').val();
                var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                var permitida = false;

                for (var i = 0; i < extensiones_permitidas.length; i++) {
                    if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                    }
                } 
                    if (!permitida) {
                        $('#file').val('');
                        $('#error').show();
                        $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                        $("#file").focus();
                    }else{
                        $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                        return 1;
                    } 
              });
            break;
            case 1408:
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#notificar").hide();
                $('#paginaWeb').show();
                $('#pdfButon').hide();
            break;
        case 1279:
           
                $("#seleccion").hide();
                $("#cargColilla").hide();
                $("#radicOnbase").hide();   
            break;
        }
        
    })
     
    $(".Comentarios").hide();
    $('#ver_comentarios').click(function() {
        $(".Comentarios").show();
    });
    
    $('input[name="devolucion"]').change(function() {
        if ($('input[name="devolucion"]:checked').val() == '0') {
            $('#motDevol').hide('slow');
            $('#motivo').val('');
        }else{($('input[name="devolucion"]:checked').val() == '1') 
            $('#motDevol').show();                    
        } 
    });
    
    $('#comentarios').keyup(function() {
            var max_chars = 200;
            var chars = $(this).val().length;
            var diff = max_chars - chars;
            if (diff <= 0){
                $('#error').show();
                $('#error').html('Excedio el limite de carácteres en el campo comentarios');
                $('#guardar').attr('disabled',true);   
            }else{
                $('#guardar').attr('disabled',false); 
                $('#error').hide();
            }
    }); 
    
    function char_especiales(string){
        var comentarios = '';
        comentarios=string.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
        $('#comentarios').val(comentarios);
    }
    
    $('#guardar').click(function(){
        var estado = <?= $estado ?>;
        var comentarios = $('#comentarios').val();
        var file = $('#file').val();
        var adicionales = $("input[name='adicionales']:checked").val(); 
        var onbase = $('#onbase').val();
        var motivo = $('#motivo').val();
        var fechanotificacion = $('#fechanotificacion').val();             
        var devolucion = $('input[name="devolucion"]:checked').val();
        var valida = true;
        char_especiales(comentarios);
             
        switch (estado){            
            case 1279://Genera la notificación.
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }                
            break;
            case 1499:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }else if (adicionales == undefined){
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#adicionales').focus();
                    valida = false;
                }                   
            break;
            case 1391:
                if (file == ''){
                    $('#error').show();
                    $('#error').html('Por favor adjuntar algun archivo');
                    $('#adicionales').focus();
                    valida = false;
                }else if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }
            break;
            case 1500:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }else if(devolucion == undefined){
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#devolucion').focus();
                    valida = false;
                }else if(file == ''){
                    $('#error').show();
                    $('#error').html('Por favor adjuntar archivo PDF');
                    $('#file').focus();
                    valida = false;
                }else if (devolucion == 1 && motivo == ''){                   
                        $('#error').show();
                        $('#error').html('Por favor Seleccionar Motivo de Devolucón');
                        $('#motivo').focus();
                        valida = false;
                }
            break;
            case 1396:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                } 
            break; 
            case 1395:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }else if (onbase == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Número Onbase');
                    $('#onbase').focus();
                    valida = false;
                } else if (fechanotificacion == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Fecha de Envío');
                    $('#fechanotificacion').focus();
                    valida = false;
                }  
            break;
            case 1498:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }else if (onbase == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Número Onbase');
                    $('#onbase').focus();
                    valida = false;
                } else if (fechanotificacion == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Fecha de Envío');
                    $('#fechanotificacion').focus();
                    valida = false;
                }  
            break;
            case 1397:
                if (comentarios == ''){
                    $('#error').show();
                    $('#error').html('Por favor Ingresar Comentarios');
                    $('#comentarios').focus();
                    valida = false;
                }else if(devolucion == undefined){
                    $('#error').show();
                    $('#error').html('Por favor Seleccionar la opción correspondiente');
                    $('#devolucion').focus();
                    valida = false;
                }else if(file == ''){
                    $('#error').show();
                    $('#error').html('Por favor adjuntar archivo PDF');
                    $('#file').focus();
                    valida = false;
                }else if (devolucion == 1 && motivo == ''){                   
                        $('#error').show();
                        $('#error').html('Por favor Seleccionar Motivo de Devolucón');
                        $('#motivo').focus();
                        valida = false;
                }
                break;
                case 1409:
                        if (file == ''){
                            $('#error').show();
                            $('#error').html('Por favor adjuntar algun archivo');
                            $('#adicionales').focus();
                            valida = false;
                        }else if (comentarios == ''){
                            $('#error').show();
                            $('#error').html('Por favor Ingresar Comentarios');
                            $('#comentarios').focus();
                            valida = false;
                        }
                    break;
                case 1404:
                        if (file == ''){
                            $('#error').show();
                            $('#error').html('Por favor adjuntar algun archivo');
                            $('#adicionales').focus();
                            valida = false;
                        }else if (comentarios == ''){
                            $('#error').show();
                            $('#error').html('Por favor Ingresar Comentarios');
                            $('#comentarios').focus();
                            valida = false;
                        }
                    break;
        }

        if (valida == true){
            $('#frmtp').submit();            
            $(".ajax_load").show("slow");
            $('#guardar').attr('disabled',true);         
        }
    })
     
     $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#informacion').val(notify);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').attr("action", "pdf");
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "guardarResolucion");
    });    
</script>