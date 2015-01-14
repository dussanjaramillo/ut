<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
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

<form id="form1" action="<?= base_url('index.php/mcremate/Gestion_DespachoComisorio') ?>" method="post" >
    <input type="hidden" id="cod_avaluo" name="cod_avaluo" value="<?php echo $cod_avaluo; ?>">  
    <input type="hidden" id="cod_avaluo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">   
    <input type="hidden" id="respuesta" name="respuesta">  
</form>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div class="informacion" id="informacion">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div align="center" style="background: white;width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <h2>Emitir Despacho Comisorio</h2>
    <div align="center" id="gestion" style="background: none repeat scroll 0 0 #FFFFFF;">
        <div class="controls controls-row">
            <div class="controls controls-row">
                <div class="span10">
                    <?php
                    echo form_label('¿Se recibió el documento de despacho comisorio?', 'recibio_documento');
                    $data = array(
                        'name' => 'recibio_documento',
                        'id' => 'recibio_documento1',
                        'value' => 'si',
                        'style' => 'margin:10px',
                        'onclick' => 'redireccionar(\'S\')'
                    );

                    echo (form_radio($data) . 'Si');
                    $data = array(
                        'name' => 'recibio_documento',
                        'value' => 'no',
                        'style' => 'margin:10px',
                        'onclick' => 'redireccionar(\'N\')'
                    );
                    $data_info = array(
                        'name' => 'button',
                        'id' => 'ver',
                        'value' => 'seleccionar',
                        'content' => '<i class="fa fa-eye"></i> Información del Avaluó ',
                        'class' => 'btn  btn-success ver'
                    );
                    echo (form_radio($data) . 'No');
                    ?>
                </div>
                <div class="span4" align="left">
                </div>
            </div> 
        </div> 
        <br>
        <?php
        echo form_button($data_info) . " ";
        echo anchor('bandejaunificada/', '<i class="fa fa-minus-circle"></i> Atras', 'class="btn btn-warning"');
        ?>

        <hr>
    </div

</div>     

<div class="alert alert-error" id="error_div" style="display: none"></div>   
</div>

<?php
echo form_close();
?>
<script>
    $("#ver").click(function() {
        $(".informacion").show();
    });
    $(".informacion").hide();
    function redireccionar(valor) {
        var url = '';
        if (valor == 'S') {
            $('#respuesta').val('S');
            $("#form1").submit();
        } else {
            $('#respuesta').val('N');
            $("#form1").submit();
        }
    }

</script>
