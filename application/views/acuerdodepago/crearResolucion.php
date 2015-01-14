<?php
$datanotificacion   = array('name'=>'notificacion','id'=>'notificacion','value'=>$plantilla,'width'=>'80%','heigth'=>'50%');
$comentarios        = array('name'=>'comentarios','id'=>'comentarios','value'=>set_value('comentarios'),'width'=>'100%','heigth'=> '5%','style'=>'width:98%','required'=>true);
$dataonbase         = array('name'=>'onbase','id'=> 'onbase','value'=>NULL,'maxlength'=> '10');
$dataFecha          = array('name'=>'fechanotificacion','id'=>'fechanotificacion','value'=>NULL,'maxlength'=>'12','readonly'=>'readonly','size'=> '15');
$dataguardar        = array('name' => 'guardar','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success');
$datacancel         = array('name' => 'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/acuerdodepago\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
$dataPDF            = array('name' => 'button','id' => 'pdf','value' => 'Generar PDF','type' => 'button','content' => '<i class="fa fa-file"></i> Generar PDF','class' => 'btn btn-info');
$attributes         = array('id' => 'frmtp', 'name' => 'frmtp', 'method' => 'POST');

echo form_open_multipart("acuerdodepago/guardarResolucion", $attributes);
?>
<input id="informacion" name='informacion' type='hidden'>
<input id="cod_fiscalizacion" name='cod_fiscalizacion' type='hidden' value="<?= $fiscalizacion ?>">
<input id="estado" name='estado' type='hidden' value="<?= $estado ?>">
<input id="nombre_documento" name='nombre_documento' type='hidden' value="<?= $nombre_plantilla ?>">
<input id="nit" name='nit' type='hidden' value="<?= $nit ?>">
<input id="acuerdo" name='acuerdo' type='hidden' value="<?= $acuerdo ?>">
<center>
<table>
    <tr>
        <td>
            <table width="100%" border="0" align="center">
                <tr>
                    <td width="23%" rowspan="2"><img src="<?= base_url('img/Logotipo_SENA.png') ?>" width="100" height="100" /></td>
                    <td width="44%" height="94"><div align="center"><h4><b><?= $titulo ?></b></h4></div></td>
                </tr>
                <tr>
                  <td height="50" colspan="2"><div align="center">
                    <h2><input name="Titulo_Encabezado" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="50" /></h2>
                  </div></td>
                </tr>
            </table>   
        </td>
    </tr>
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
    <tr id="notificar">
        <td>
            <?= form_textarea($datanotificacion) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
            echo form_label('<b>Comentarios</b>&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'comentarios');
            echo form_textarea($comentarios) 
            ?>
        </td>
    </tr>
    <tr>
        <td align='center'>
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
    tinymce.init({
        language : "es",
        selector: "textarea#notificacion",
        theme: "modern",
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
        var estado = <?= $estado ?>;
        switch(estado){
            case 1406:
                $('#notificar').hide();
                $('#pdf').attr('disabled',true);
                break;
        }          
      })
     
      $('#guardar').click(function(){
            tinyMCE.triggerSave();
            var onbase = $('#onbase').val();
            var fechanotificacion = $('#fechanotificacion').val();    
            var comentarios = $('#comentarios').val();
            var notificacion = $('#notificacion').val();
            var Titulo_Encabezado = $('#Titulo_Encabezado').val();
            var valida = true;
            if (comentarios == ''){
                $('#error').show();
                $('#error').html('Por favor Ingresar Comentarios');
                $('#comentarios').focus();
                valida = false;
            }

            if (valida == true){
                //$('#frmtp').submit();
                $(".ajax_load").show("slow");
                var url = "<?= base_url('index.php/acuerdodepago/guardarResolucion')?>";
                $.post(url,{acuerdo:<?= $acuerdo ?>,estado:<?= $estado ?>,cod_fiscalizacion:'<?= $fiscalizacion ?>',nit:<?= $nit ?>,onbase:onbase,fechanotificacion:fechanotificacion,notificacion:notificacion,Titulo_Encabezado:Titulo_Encabezado,comentarios:comentarios})
                .done(function(data){
                    $('.conn').html(data);
                    location.reload();
                }).fail(function (){
                    //alert('Error en la consulta')
                    $(".ajax_load").hide("slow");
                })                  
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