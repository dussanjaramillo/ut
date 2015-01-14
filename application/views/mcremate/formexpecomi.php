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


function redireccionar(valor){
    var url = ''; 
    if(valor == 'S'){
        url = '<?=base_url()?>index.php/verfpagosprojuridicos/formRemComiLici/<?php echo $cod_avaluo?>/<?php echo $avaluo->COD_FISCALIZACION?>'
    }else{
        url = '';
    }
    alert(url);
    location.href=url;
}

function closeDiv(id_div){
    $("#" + id_div).hide("slow");
}

function openDiv(id_div){
    $("#" + id_div).show("slow");
}

function returnList(){
    location.href = '<?=base_url()?>index.php/mcremate/listexpecomi';
}

function salvar(){

    var url = "<?php echo base_url()?>index.php/mcremate/saveexpecomi"; // El script a dónde se realizará la petición.
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
<h2>Resultado de la diligencia del remate</h2>
<form id="prinPlantilla" method="POST" name="prinPlantilla" action="<?php echo base_url()?>index.php/mcremate/generarpdf" target="_blank">
    <input type="hidden" name="html" id="htmlHidden" />
</form>

<form id="prin1Plantilla" method="POST" name="prin1Plantilla" action="<?php echo base_url()?>index.php/mcremate/imprimepdf" target="_blank">
    <input type="hidden" name="html" id="htmlHidden1" />
</form>
<div class="alert alert-success" id="save_div" style="display: none">
    <p>Se guardo correctamente la diligencia</p>
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
    ?>
    <div class="controls controls-row">
        <div class="span4">
            <?php 
                echo form_label('¿Resultado de la diligencia?', 'nombre_diligencia');  
                $data = array(
                    'name'        => 'nombre_diligencia',
                    'id'          => 'nombre_diligencia1',
                    'value'       => 'ADJUDICADO',
                    'style'       => 'margin:10px'
                    );

                echo (form_radio($data) . 'Adjudicado');
                $data = array(
                    'name'        => 'nombre_diligencia',
                    'id'          => 'nombre_diligencia2',
                    'value'       => 'DESIERTO',
                    'style'       => 'margin:10px'
                    );

                echo (form_radio($data) . 'Desierto');
            ?>
        </div>
        <div class="span4" align="left">
            <?php
            echo form_label('Documento del remate', 'documento_gestion');    

            $data = array(
                       'name'        => 'documento_gestion',
                       'id'          => 'documento_gestion',
                       'value'       => 'upload',
                       'class'       => 'span3',
                     );

            echo form_upload($data);
            ?>
        </div>
    </div> 
</div> 
    
    <hr>
    
   <div class="controls controls-row"> 
        
        <div class="span4">
            <?php 
            $url = base_url();
            $data = array(
                'name' => 'cancelar',
                'id' => 'cancelar',
                'value' => 'cancelar',
                'type' => 'cancelar',
                'content' => 'cancelar',
                'onclick' => 'location.href=\'' . $url . 'index.php/mcremate/listexpecomi\''
            );
            echo form_button($data);
            ?>
        </div>
       <div class="span4">
            <?php 
            $data = array(
                'name' => 'guardar',
                'id' => 'guardar',
                'value' => 'Guardar',
                'type' => 'button',
                'content' => 'Guardar',
                'onclick' => 'uploadAjax()'
            );

            echo form_button($data);
            ?>
        </div>
        
<hr>
   </div>

   </div>     

<div class="alert alert-error" id="error_div" style="display: none"></div>   
</div>

<?php 
echo form_close();
?>
<div class="alert alert-success" id="alert" style="display: none">
<button class="close" data-dismiss="alert" type="button">×</button>
El auto se <?php echo ( ($auto == null) ? ' almaceno' : 'actualizo' )?> con exito.
</div>
<script>
    function uploadAjax(){
        $(".ajax_load").show("slow");
        var inputFileImage = document.getElementById("documento_gestion");
        var file = inputFileImage.files[0];
        var data = new FormData();
        data.append('documento_gestion',file);
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


   