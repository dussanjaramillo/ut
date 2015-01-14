<?php 
$datanotificacion = array('name'=>'notificacion','id'=>'notificacion','value'=>set_value('notificacion'),'width'=> '80%','heigth'=> '50%'); 
$data           =   array('name'=>'comentarios','id'=>'comentarios','value'=> set_value('comentarios'),'width'=> '100%','heigth'=>'5%','style'=> 'width:98%', 'onKeyUp' => 'return longitudMax(this,500)','required'=>'true');
$dataDevol      =   array('name'=>'devolucion','id'=>'devolucion','type'=>'hidden','value'=>$devolucion);
$dataRespu      =   array('name'=>'respuesta','id'=>'respuesta','type'=>'hidden','value'=>$respuesta);
$dataNit        =   array('name'=>'nit','id'=>'nit','type'=>'hidden','value'=>$nit);
$dataTipo       =   array('name'=>'tipo','id'=>'tipo','type'=>'hidden','value'=>$tipo);
$dataLiquid     =   array('name'=>'informacion','id'=>'informacion','type'=>'hidden');
$dataguardar    =   array('name'=>'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success',);                        
$dataPDF        =   array('name'=>'pdf','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file-pdf-o"></i> Generar PDF','class' => 'btn btn-info',);
$datacancel     =   array('name'=>'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/devolucion/devoluciones_generadas\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');

$attributes = array('class'=>'form-inline','id'=>'DocumentoAdd','name'=>'DocumentoAdd','method'=>'POST');
echo form_open_multipart(current_url(),$attributes); 
echo @$custom_error;
echo form_input($dataDevol);
echo form_input($dataNit);
echo form_input($dataTipo);
echo form_input($dataLiquid);
echo form_input($dataRespu);
?>
   
    <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td align='center'><?= $titulo ?></td>
        </tr>
        <tr>
        <td><?= form_textarea($datanotificacion); ?></td>
        </tr><tr><td><br></td></tr>
        <tr id='respuesta_Asignado' style="display:none">            
            <td>                
                <?php
                echo form_label('Asignar a&nbsp;<span><font color="red"><b>*</b></font></span>', 'asignando');?> 
                <select name="asignado" id="asignado" required>
                    <option value=''>--Seleccione--</option>
                        <?php foreach($asignado as $row){
                        echo '<option value="'.$row->IDUSUARIO.'">'.$row->NOMBRES.' '.$row->APELLIDOS.'</option>';}?>
                </select>            
            </td>            
        </tr><tr><td><br></td></tr>
        <tr>
            <p>
                <td><?= 'Comentarios&nbsp;&nbsp;&nbsp;&nbsp;(Max. 500 Caracteres)&nbsp;<span class="required"><font color="red"><b>*</b></font></span> '.form_textarea($data);?></td>
            </p>            
        </tr>
        <tr><td><br></td></tr> 
        
        <tr>
            <td>
                <br />
                <div class="Comentarios"   style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
                    <?php
                    echo form_label('<b><h3>Historial de Comentarios</h3></b><span class="required"></span>', 'lb_comentarios');
                    echo "<br>";
                    for ($i = 0; $i < sizeof($comentario); $i++) {
                        echo '<div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
                        echo '<b>Fecha del Comentario: </b>' . $comentario[$i]["FECHA_CREACION"] . '<br>';
                        echo '<b>Hecho Por: </b>' . $comentario[$i]["NOMBRES"] . " " . $comentario[$i]["APELLIDOS"] . '<br>';
                        echo $comentario[$i]["COMENTARIO"];
                        echo "<br><br>";
                        echo '</div>';
                        echo "<br>";
                    }
                    ?>
                </div>
                <br />
            </td>
        </tr>
        
        
        <tr>
            <td align='center'>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </td>
        </tr>
        <tr align ='center'>
            <td>
                <?php
                echo form_button($dataguardar)."&nbsp;&nbsp;";
                echo form_button($dataPDF)."&nbsp;&nbsp;";
                echo form_button($datacancel);
                ?>
            </td>
        </tr>

    </table>
        <div id="ajax_load" class="ajax_load" style="display: none">
            <div class="preload" id="preload" >
                <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
            </div>
        </div>
<?php echo form_close(); ?>
<script>
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

$(document).ready(function() {    
    var respuesta = $('#respuesta').val();
    window.history.forward(-1);
    tinyMCE.triggerSave();
    if (respuesta == '1184'){
        $('#respuesta_Asignado').show();
    }
});

$('#guardar').click(function() {
    tinyMCE.triggerSave();
    var asignado        = $('#asignado').val();
    var notificacion    = $('#notificacion').val();
    var comentarios     = $('#comentarios').val();
    var respuesta       = $('#respuesta').val();
    var url = '<?php echo base_url('index.php/devolucion/guardar_documentos')?>';
    var valida          = true;
    
    if (notificacion == ''){
        $('#error').show();
        $('#notificacion').focus();
        $('#error').html("<font color='red'><b>Por Favor Ingresar Información</b></font>");
        valida = false;
    }else if (asignado == '' && respuesta == 1184){
        $('#error').show();
        $('#asignado').focus();
        $('#error').html("<font color='red'><b>Por Favor Asignar usuario</b></font>");
        valida = false;
    }else if (comentarios == ''){
        $('#error').show();
        $('#comentarios').focus();
        $('#error').html("<font color='red'><b>Por Favor Ingresar Comentarios</b></font>");
        valida = false;
    }
    
    if (valida == true){
        $(".ajax_load").show("slow");
        $('#guardar').prop("disabled",true);
        $("#DocumentoAdd").attr("action", url);
        $('#DocumentoAdd').removeAttr('target');
        $('#DocumentoAdd').submit();
    }
    
});

$('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#informacion').val(notify);
        $("#DocumentoAdd").attr("action", "<?= base_url('index.php/devolucion/pdf')?>");
        $('#DocumentoAdd').attr('target', '_blank');
        $('#DocumentoAdd').submit();
});
</script>    