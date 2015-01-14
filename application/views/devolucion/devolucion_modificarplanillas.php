<?php
$nit_modificarplanilla = array('name' => 'nit_modificarplanilla', 'class' => 'search-query', 'id' => 'nit_modificarplanilla', 'readonly' => 'true', 'value' => $datos_planillas->N_INDENT_APORTANTE);
$digito_verificacion = array('name' => 'digito_verificacion', 'class' => 'search-query', 'id' => 'digito_verificacion', 'class' => 'input-mini', 'readonly' => 'true', 'value' => $datos_planillas->DIG_VERIF_NIT);
$numeroplanilla_modificar = array('name' => 'numeroplanilla_modificar', 'class' => 'search-query', 'id' => 'numeroplanilla_modificar', 'readonly' => 'true', 'value' => $datos_planillas->COD_PLANILLAUNICA);
$pago_modificar = array('name' => 'pago_modificar', 'class' => 'search-query', 'id' => 'pago_modificar', 'readonly' => 'true', 'value' => number_format($datos_planillas->APORTE_OBLIG));
$periodo_modificar = array('name' => 'periodo_modificar', 'class' => 'search-query', 'id' => 'periodo_modificar', 'readonly' => 'true', 'value' => $datos_planillas->PERIDO_PAGO);
$nuevonit_modificarplanilla = array('name' => 'nuevonit_modificarplanilla', 'class' => 'search-query', 'id' => 'nuevonit_modificarplanilla');
$digito_verificacion1 = array('name' => 'digito_verificacion1', 'class' => 'search-query', 'id' => 'digito_verificacion1', 'class' => 'input-mini');
//$ajustepago_modificar = array('name' => 'ajustepago_modificar', 'class' => 'search-query', 'id' => 'ajustepago_modificar');
$nuevoperiodo_modificar = array('name' => 'nuevoperiodo_modificar', 'class' => 'search-query', 'id' => 'nuevoperiodo_modificar');
$button = array('name' => 'descartar_planilla', 'id' => 'descartar_planilla', 'value' => 'Descartar Planilla', 'content' => '<i class=""></i> Descartar planilla', 'class' => 'btn btn-success btn1');
$button1 = array('name' => 'modificar_planilla', 'id' => 'modificar_planilla', 'type' => 'submit', 'value' => 'Modificar', 'content' => '<i class=""></i> Modificar', 'class' => 'btn btn-success btn1');
$button2 = array('name' => 'cancelar_planilla', 'id' => 'cancelar_planilla', 'content' => '<i class=""></i> Cancelar', 'class' => 'btn btn-success btn1');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <?= form_open(base_url('index.php/devolucion/guardar_modpla')) ?>
    Datos de la Planilla
    <table>
        <tr>
            <td>Nit Planilla</td><td><?= form_input($nit_modificarplanilla) ?></td><td>Digito de Verificacion</td><td><?= form_input($digito_verificacion) ?></td>
        </tr>
        <tr>
            <td>Numero Planilla</td><td><?= form_input($numeroplanilla_modificar) ?></td>
        </tr>
        <tr>
            <td>Pago Planilla</td><td><?= form_input($pago_modificar) ?></td>
        </tr>
        <tr>
            <td>Periodo Planilla</td><td><?= form_input($periodo_modificar) ?></td>
        </tr>
    </table>
    <br>
    Datos a Modificar
    <table>
        <tr>
            <td>Nuevo Nit</td><td><?= form_input($nuevonit_modificarplanilla) ?></td><td>Digito de Verificacion</td><td><?= form_input($digito_verificacion1) ?></td>
        </tr>
    <!--    <tr>
            <td>Ajuste al Valor pagado</td><td><?//=form_input($ajustepago_modificar)?></td>
        </tr>-->
        <tr>
            <td>Nuevo Periodo</td><td><?= form_input($nuevoperiodo_modificar) ?></td>
        </tr>
    </table>
    <table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button) ?></td>
            <td><?= form_button($button1) ?></td>
            <td><?= form_button($button2) ?></td>
        </tr>
    </table>
    <?= form_close() ?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    $(function() {
        $("#cancelar_planilla").confirm({
            title: "Confirmacion",
            text: "Esta seguro de cancelar?",
            confirm: function(button) {
                location.href = "<?= base_url('index.php/') ?>";
            },
            cancel: function(button) {

            },
            confirmButton: "SI",
            cancelButton: "NO"
        });
    });

</script>