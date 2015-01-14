<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form-large">
    <h2 align="center">Resultado Cargue Cartera No Misional</h2>
    <?php //$oculto = array('archivo' => $archivo) ?>
    <?php //echo form_open("cargarextractoasobancaria/exporta", '', $oculto) ?>
    <table align="center">
        <tr align="center">
            <td align="center">
                <textarea style="width: 700px; height: 300px;" name="errors"><?php
                    echo trim("----------------------------------------------------") . "\r\n";
                    ?><?php
                    foreach ($errores as $value) {
                        echo trim($value) . "\r\n";
                        // break;
                    }
                    echo trim("----------------------------------------------------") . "\r\n";
                    ?><?php
                    echo trim("Número total lineas cargadas: " . $numlineas) . "\r\n";
//                    echo trim("Número total Archivos cargados: " . $positivos) . "\r\n";
//                    echo trim("Número total Archivos no cargados: " . $negativos) . "\r\n";
                    echo trim("----------------------------------------------------") . "\r\n";
                    ?></textarea>

            </td>
        </tr>
        <tr align="center">
            <td align="center">
                <?php echo anchor('cnm_servicio_medico/manage', '<i class="icon-remove"></i> Regresar', 'class="btn"'); ?>

                <?php
                $data = array(
                    'name' => 'button',
                    'id' => 'submit-button',
                    'value' => 'Exportar',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Exportar',
                    'class' => 'btn btn-success'
                );
                //echo form_button($data);
                ?>
            </td>
        </tr>
    </table> 
    <?php echo form_close(); ?>
</div>