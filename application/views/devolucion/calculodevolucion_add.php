<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 100%; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("devolucion/Disminuir_Devolucion", $attributes);
    echo form_hidden('cod_devolucion', $cod_devolucion);
    echo form_hidden('datos_planilla', $datos_planilla);
    echo form_hidden('descuento', $descuento);
    ?>       
    <br>
    <center>
        <h2><?php echo 'Beneficios Pago de Planillas'; ?></h2> </center>
    <br><br>
    <table width="90%" border="1" align="center" class="table table-bordered table-striped">
        <tr>
            <td colspan="7"><div align="center"><strong>EMPRESA : <?php echo $detalle_planilla[0]->NOMBRE; ?></strong></div></td>
        </tr>
        <tr>
            <td colspan="7"><div align="center"><strong>SENA DIRECCION GENERAL</strong></div></td>
        </tr>
        <tr>
            <td colspan="7"><div align="center"><strong>NIT <?php echo number_format($detalle_planilla[0]->NIT, 0, '.', '.'); ?></strong></div></td>
        </tr>
        <tr>
            <td colspan="7"><p>&nbsp;</p><p><strong><?php echo $concepto->NOMBRE_CONCEPTO; ?></strong></p></td>
        </tr>
        <?php
        switch ($concepto->COD_CONCEPTO) {
            case 1:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>IBC INCORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="19%"><div align="center"><strong>DIFERENCIA DE IBC</strong></div></td>
                    <td width="17%"><div align="center"><strong>VALOR PAGADO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($subtotal); $i++) {
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($diferencia_ibc[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($valor_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($valor_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($subtotal[$i], 0, '.', '.'); ?></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><?php echo "$" . number_format($total, 0, '.', '.'); ?></td>
                </tr>
                <?php
                break;
            case 2:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="17%"><div align="center"><strong>VALOR PAGADO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($subtotal); $i++) {
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($valor_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($valor_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($subtotal[$i], 0, '.', '.'); ?></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><?php echo "$" . number_format($total, 0, '.', '.'); ?></td>
                </tr>
                <?php
                break;
        }
        ?>        
    </table>
    <br>
    <center>
        <?php
        $data_1 = array(
            'name' => 'button',
            'id' => 'enviar',
            'value' => 'Enviar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'disabled' => 'disabled',
            'class' => 'btn btn-danger enviar'
        );
        $data_2 = array(
            'name' => 'button',
            'id' => 'enviar',
            'value' => 'Enviar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Realizar Disminución',
            'class' => 'btn btn-success enviar'
        );
        ?>
        <div class="Boton_Agregar">
            <?php
            echo form_button($data_2) . " ";
            echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
            ?>
        </div>
    </center>
    <?php echo form_close(); ?>
    <style>
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:40%;
            //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:40%;
            // border:solid lightblue 1px;

        }       
    </style>
