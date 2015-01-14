<?php
$observaciones = array(
    'name' => 'observaciones',
    'id' => 'observaciones',
    'cols' => '40',
    'rows' => '3',
    'style' => 'height: 50; width: 100%; margin-left: 0; background-color: #E6E6E6;'
);
?>

<form id="titulofisico" name="titulofisico" action="fisico" method="post">
    <table width="90%" cellspacing="10" cellpadding="10">
        <tr>
            <td style="vertical-align: central; text-align: right; width: 45%;">
                <input type="checkbox" id="fisico" name="fisico" value="1" />
                <input type="hidden" id="accion" name="accion" value="" />
                <input type="hidden" id="cod_recepcion" name="cod_recepcion" value="<?php echo $cod_recepcion ?>" />
                <input type="hidden" id="expediente" name="expediente" value="<?php echo $expediente ?>" />
            </td>
            <td style="vertical-align: central; text-align: left;">
                <?php echo form_label('Entrega de titulo en físico?', 'fisico') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo form_label('Observaciones', 'observaciones') ?>
                <?php echo form_textarea($observaciones) ?>
            </td>

        </tr>
    </table>
</form>
