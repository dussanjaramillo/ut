<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<form id="form1" action="<?= base_url('index.php/mcremate/Gestion_PresentacionDeudor') ?>" method="post" >
    <?php $fecha_hoy = date("Y/m/d H:i:s"); ?>
    <input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha_hoy; ?>">  
    <input type="hidden" id="cod_avaluo" name="cod_avaluo" value="<?php echo $cod_avaluo; ?>">   
    <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">   
    <input type="hidden" id="respuesta" name="respuesta">  
</form>
<div class="informacion" id="informacion">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div class="avaluo" id="avaluo" style="background: white;width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <div class="controls controls-row">
        <center>

            <div class="span10" style="alignment-adjust: central">
                <?php
                echo form_label('<h3>¿El deudor se presentó a la citación?</h3>', 'recibio_documento');
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
                    'id' => 'EXISTE_NULIDAD2',
                    'value' => 'no',
                    'style' => 'margin:10px',
                    'onclick' => 'redireccionar(\'N\')'
                );

                echo (form_radio($data) . 'No');
                ?>
            </div>
        </center>      
    </div> 
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'info',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-eye"></i> Informacion del Avaluo',
        'class' => 'btn  btn-success info'
    );
    ?>
    <center>
        <?php
        echo form_button($data_1). ' ';
        echo anchor('bandejaunificada/', '<i class="fa fa-minus-circle"></i> Atras', 'class="btn btn-warning"');
        ?>
    </center>
    <br>
</div>

<?php
echo form_close();
?>
<script>
    $(".informacion").hide();
    $(".preload, .load").hide();
     $("#info").click(function() {
         $(".informacion").show();
     });
    function redireccionar(valor) {
        $(".preload, .load").show();
        if (valor == 'S') {
            $('#respuesta').val(<?php echo SE_PRESENTO_DEUDOR; ?>);
            $("#form1").submit();
        } else {
            $('#respuesta').val(<?php echo NO_SE_PRESENTO_1A_VEZ; ?>);
            $("#form1").submit();
        }
    }

</script>

