<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<form id="form1" action="<?= base_url('index.php/mcremate/Gestion_ResultadoRemate') ?>" method="post" >
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
                echo form_label('<h3>¿Se realizó la diligencia del remate?</h3>', 'recibio_documento');
                $data = array(
                    'name' => 'recibio_documento',
                    'id' => 'recibio_documento1',
                    'value' => 'si',
                    'style' => 'margin:10px',
                    'onclick' => 'Opcion1(\'S\')'
                );

                echo (form_radio($data) . 'Si');
                $data = array(
                    'name' => 'recibio_documento',
                    'id' => 'EXISTE_NULIDAD2',
                    'value' => 'no',
                    'style' => 'margin:10px',
                    'onclick' => 'Opcion1(\'N\')'
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
        echo form_button($data_1) . ' ';
        echo anchor('mcremate/Menu_GestionRemateA', '<i class="fa fa-reply"></i> Atras', 'class="btn"');
        ?>
    </center>
    <br>
</div>

<?php
echo form_close();
?>
<script>
    $(".informacion").hide();
    $(".Resultado").hide();
    $(".preload, .load").hide();
    $("#info").click(function() {
        $(".informacion").show();
    });
    function Opcion1(valor) {
        $(".preload, .load").show();
        if (valor == 'N') {
            $('#respuesta').val(<?php echo NO_SE_REALIZA_DILIGENCIA; ?>);
        } else {
            $('#respuesta').val(<?php echo SE_REALIZA_DILIGENCIA; ?>);

        }
        $("#form1").submit();
    }


</script>

