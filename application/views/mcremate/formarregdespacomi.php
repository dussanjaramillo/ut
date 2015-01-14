<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
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
<script>

function verPdf(){
    var ed = tinyMCE.get('documento');
    var data_documento = ed.getContent();
    $("#htmlHidden").val(data_documento);
    
    document.prinPlantilla.submit();
}

function printPdf(){
    var ed = tinyMCE.get('documento');
    var data_documento = ed.getContent();
    $("#htmlHidden1").val(data_documento);
    
    document.prin1Plantilla.submit();
}

function habilitarDocumento(){
    $("#campo_documento").show("slow");
    $("#campo_comentarios").show("slow");
    $("#guardar").removeAttr('disabled');
    $("#generarPdf").removeAttr('disabled');
    $("#imprimir").removeAttr('disabled');
}

function crearAuto(){
    location.href = '<?=base_url()?>index.php/verfpagosprojuridicos/formDespachoComisorios/<?php echo $cod_avaluo?>/<?php echo $cod_despachocomisorio?>';
}

function closeDiv(id_div){
    $("#" + id_div).hide("slow");
}

function openDiv(id_div){
    $("#" + id_div).show("slow");
}

function returnList(){
    location.href = '<?=base_url()?>index.php/mcremate/listarregdespacomi';
}

function salvar(){
    $(".ajax_load").show("slow");
    var url = "<?php echo base_url()?>index.php/mcremate/updatearregdespacomi"; // El script a dónde se realizará la petición.
    $.ajax({
           type: "POST",
           url: url,
           data: $("#formDespacho").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               if($.trim(data) == 'ok'){
                   openDiv('save_div');
                   closeDiv('gestion');
                   setTimeout('returnList()', 5000);
                }else{
                    $("#error_div").show("slow");
                    $("#error_div").html(data);
                    setTimeout('closeDiv("error_div")', 5000);
                }
                
                closeDiv('ajax_load');
                
           }
     });
}
</script>
<div id="ajax_load" class="ajax_load" style="display: none">
<div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div class="center-form-large">
<?php echo $custom_error;?>
<h2>Gestionar Despacho Comisorio</h2>
<form id="prinPlantilla" method="POST" name="prinPlantilla" action="<?php echo base_url()?>index.php/mcremate/generarpdf" target="_blank">
    <input type="hidden" name="html" id="htmlHidden" />
</form>

<form id="prin1Plantilla" method="POST" name="prin1Plantilla" action="<?php echo base_url()?>index.php/mcremate/imprimepdf" target="_blank">
    <input type="hidden" name="html" id="htmlHidden1" />
</form>
<div class="alert alert-success" id="save_div" style="display: none">
    <p>El despacho se actualizo correctamente</p>
</div>  
<?php 
echo form_open(base_url() . 'index.php/mcremate/savecomisorio', 'method="post" id="formDespacho" onsubmit="return false" enctype="multipart/form-data"');
?>
<div align="center" id="gestion" style="background: none repeat scroll 0 0 #FFFFFF;">
    <div class="controls controls-row">
    <?php 
    require_once('datosavaluo.php');
    echo form_hidden('cod_avaluo', $cod_avaluo);
    echo form_hidden('cod_fiscalizacion', $avaluo->COD_FISCALIZACION);
    echo form_hidden('cod_despachocomisorio', $cod_despachocomisorio);
    ?>
</div> 
    
    <h3>Documento</h3>    
    <?php
    //echo form_label('Documento', 'documento');                            
    $data = array(
               'name'        => 'documento',
               'id'          => 'documento',
               'value'       => $documento,
               'class'       => 'span3',
             );

    echo form_textarea($data);
    ?>
    
    <hr>
    
    <h3>Comentarios del documento</h3>    
    <textarea id="comentarios" rows="10" style="width: 688px" name="comentarios"><?php echo $despachocomisorio->COMENTARIOS?></textarea>
        
    <hr>

    <h3>Comentarios de la aprobacion</h3>    
    <textarea id="comentarios" rows="10" style="width: 688px" disabled="disabled" name="observaciones_aprobacion"><?php echo $despachocomisorio->OBSERVACIONES_APROBACION?></textarea>
 
    <hr>
       <div class="controls controls-row"> 
   <div class="span2">
            <?php 
            $data = array(
                'name' => 'guardar',
                'id' => 'guardar',
                'value' => 'Guardar',
                'type' => 'button',
                'content' => 'Guardar',
                'onclick' => 'salvar()'
            );

            echo form_button($data);
            ?>
        </div>
        <div class="span2">
            <?php 
            $data = array(
                'name' => 'generarPdf',
                'id' => 'generarPdf',
                'value' => 'validar',
                'type' => 'button',
                'content' => 'Generar PDF',
                'onclick' => 'verPdf()'
            );
            echo form_button($data);
            ?>
        </div>
        <div class="span2">
            <?php 
            $url = base_url();
            $data = array(
                'name' => 'imprimir',
                'id' => 'imprimir',
                'value' => 'imprimir',
                'type' => 'imprimir',
                'content' => 'Imprimir',
                'onclick' => 'printPdf()'
            );
            echo form_button($data);
            ?>
        </div>      
        <div class="span2">
            <?php 
            $url = base_url();
            $data = array(
                'name' => 'cancelar',
                'id' => 'cancelar',
                'value' => 'cancelar',
                'type' => 'cancelar',
                'content' => 'cancelar',
                'onclick' => 'location.href=\'' . $url . 'index.php/mcremate/listarregdespacomi\''
            );
            echo form_button($data);
            ?>
        </div>
        
<hr>
</div

   </div>     

<div class="alert alert-error" id="error_div" style="display: none" align="left"></div>   
</div>

<?php 
echo form_close();
?>
<div class="alert alert-success" id="alert" style="display: none">
<button class="close" data-dismiss="alert" type="button">×</button>
El auto se <?php echo ( ($auto == null) ? ' almaceno' : 'actualizo' )?> con exito.
</div>

 <script>
tinymce.init({
        language : 'es',
        selector: "textarea#documento",
        theme: "modern",
        width: "700px",
        height: "600px",
        plugins: [
         "advlist autolink lists link  charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         //"insertdatetime media nonbreaking save table contextmenu directionality",
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
 
 var client = new XMLHttpRequest();

function upload() {
    var file = document.getElementById("documento_sol_firmado");
    //create new FormData instance to transfer as Form Type
    var formData = new FormData();
    // add the file intended to be upload to the created FormData instance
    formData.append("upload", file.files[0]);

    client.open("post", "index.php/mcremate/uploadFile", true);
    client.setRequestHeader("Content-Type", "multipart/form-data");
    client.send(formData);  // send formData to the server using XHR
}

// register handler to check XHR instance's status when receiving the response
client.onreadystatechange = function () {
    if (client.readyState == 4 && client.status == 200) {
        alert(client.statusText);
    }
}


function uploadAjax(){
    var inputFileImage = document.getElementById("documento_sol_firmado");
    var file = inputFileImage.files[0];
    var data = new FormData();
    data.append('documento_sol_firmado',file);
    var url = "<?php echo base_url()?>index.php/mcremate/uploadFile";
    $.ajax({
    url:url,
        type:"POST",
        contentType:false,
        data:data,
        processData:false,
        cache:false}) .done(function( msg ) {
            salvar();
        });
}
</script>
   