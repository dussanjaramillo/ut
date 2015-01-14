<form id="form1" action="<?= base_url('index.php/mcremate/Gestion_VisualizarSaldo') ?>" method="post" >
<input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">  
<input type="hidden" id="cod_avaluo" name="cod_avaluo" value="<?php echo $cod_avaluo; ?>">   
<input type="hidden" id="respuesta" name="respuesta">  
</form>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$attributes = array("id" => "myform", "class" => "myform");
echo form_open_multipart("mcremate/Gestion_ConsignacionDinero", $attributes);
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="informacion" id="informacion">
    <?php require_once('encabezado.php'); ?>
</div>
<br>
<div class="avaluo" id="avaluo" style="background: white;width: 90%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
    <div class="controls controls-row">
        <center>

            <div class="span10 opciones" id="opciones" style="alignment-adjust: central">
                <?php echo form_label('<h3>Saldo a la Fecha</h3>', 'recibio_documento'); ?>
                <div style="color: red"><h2><?php echo "$" . number_format($valor, 0, '.', '.'); ?></h2></div> <br>
                        <?php
                        if ($valor > 0) {
                            echo '<h2>El Saldo es a Favor del SENA - Se Procede a Inicializar La Investigacion de Bienes</h2>';
                        } else if ($valor < 0) {
                            echo '<h2>Se ha Verificado el Pago en el Sistema - Se Procede a Realizar Devolucion de Dinero al Deudor</h2>';
                        } else {
                            echo '<h2>Se ha Verificado el Pago en el Sistema - Se Procede a Finalizar el Proceso</h2>';
                        }
                        ?>
            </div>
        </center>      
    </div> <br>
    <br>
    <?php
    $data_1 = array(
        'name' => 'button',
        'id' => 'info',
        'value' => 'seleccionar',
        'content' => '<i class="fa fa-eye"></i> Informacion del Avaluo',
        'class' => 'btn  btn-success info'
    );
    $data_2 = array(
        'name' => 'button',
        'id' => 'aceptar',
        'value' => 'seleccionar',
        'content' => '<i class="icon-ok"></i> Continuar',
        'class' => 'btn btn-primary aceptar'
    );
    ?>
    <center>
        <?php
        echo form_button($data_2) . ' ' . form_button($data_1) . ' ';
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
    $(".subir_documento").hide();
    $("#info").click(function() {
        $(".informacion").show();
    });
    $("#aceptar").click(function() {
        var valor = <?php echo $valor; ?>;
        if (valor < 0) {
            $('#respuesta').val(<?php echo DEUDA_DEVOLVER_SENA; ?>);
        } else if (valor > 0) {
            $('#respuesta').val(<?php echo DEUDA_A_FAVOR_SENA; ?>);
        } else if (valor == 0) {
            $('#respuesta').val(<?php echo DEUDA_IGUAL_0; ?>);
        }
        $("#form1").submit();
    });
</script>

