<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("procesojudicial/Verificar_AcuerdoPago", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s",strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $id_titulo);
    ?>
    <?php echo $custom_error; ?>
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div style="overflow: hidden; width: 80%; margin: 0 auto">
        <h2 class="text-center">Verificar Acuerdo de Pago</h2>
    </div>
    <div style="overflow: hidden; width: 60%; margin: 0 auto">
        <br>     
        <div class="columna_derecha"> 
            <input type="radio" checked="checked"  id="opc_res" name="opc_res" value="no_hay" >No Hubo cumplimiento en el Acuerdo de Pago
        </div>
        <div class="columna_izquierda">            
            <input type="radio"  id="opc_res1" name="opc_res" value="si_hay">Hubo cumplimiento en el Acuerdo de Pago
        </div>
    </div>
    <br><br>
    <div style="overflow: hidden; width: 40%; margin: 0 auto">
        <div class="text-center">
            <p class="pull-right">
                <?php echo anchor('procesojudicial/Lista_PagoRemateBienes', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php
                $data = array(
                    'name' => 'button',
                    'id' => 'submit-button',
                    'value' => 'Confirmar',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
                    'class' => 'btn btn-success'
                );

                echo form_button($data);
                ?>
            </p>

        </div>
    </div>

    <?php echo form_close(); ?>
    <style type="text/css">
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:40%;
              //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:45%;
               // border:solid lightblue 1px;

        }       
    </style>