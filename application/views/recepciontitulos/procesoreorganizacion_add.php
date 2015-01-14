<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("recepciontitulos/Proceso_Reorganizacion", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s");
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $cod_titulo);
    ?>
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div style="overflow: hidden; width: 80%; margin: 0 auto">
        <h2 class="text-center">Verificar Sentencia</h2>
    </div>
    <div style="overflow: hidden; width: 70%; margin: 0 auto">
        <br>     
        <?php
        $data = array(
            'name' => 'id_opcion',
            'id' => 'id_opcion',
            'style' => 'margin: 5px'
        );
        ?>
        <br>
        <div class="columna_izquierda">                                    
            <?php echo form_radio($data, '1',"checked") . "Reorganizacion"; ?>
        </div>
        <div class="columna_derecha">  
            <?php echo form_radio($data, '2') . "No Reorganizacion"; ?>    
        </div>
        <br><br><br>
    </div>
    <div style="overflow: hidden; width: 35%; margin: 0 auto">

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
        <?php echo anchor('recepciontitulos/Menu_ProcesoReorganizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
    </div>

    <?php echo form_close(); ?>
    <style>
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:30%;
            //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:40%;
            // border:solid lightblue 1px;

        }       
    </style>