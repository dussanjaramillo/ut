<?php
$attributes = array("id" => "myform");
echo form_open('devolucion/Aprobar_SolicitudesOtrosConceptos', $attributes);
echo form_hidden('cod_devolucion', $cod_devolucion);
?>
<input type="hidden" id="id_solicitudes" name="id_solicitudes" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<br>
<center>
    <br>
    <h1><?php echo 'Aprobación para Devoluciones' ?></h1>
    <h2><?php echo 'Otros Conceptos'; ?></h2>
</center>
<br>
<table width="90%" border="1" align="center" class="table table-bordered table-striped">
    <tr>
        <td width="11%"><div align="center"><strong>Nro. de Solicitud</strong></div></td>
        <td width="11%"><div align="center"><strong>Valor Total Pagado</strong></div></td>
        <td width="11%"><div align="center"><strong>Fecha Transacción</strong></div></td>
        <td width="11%"><div align="center"><strong>Valor Solicitado</strong></div></td>
        <td width="11%"><div align="center"><strong>Fecha Solicitud</strong></div></td>
        <td width="7%"><div align="center"><strong>Aprobar</strong></div></td>
    </tr>
    <?php
    $atributos_general = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);', 'onkeyup' => 'num(this);');
    if (!empty($estados_seleccionados)) {
        foreach ($estados_seleccionados as $data) {
            ?>
            <tr>
                <td><div align = "center"><?= $data->COD_DEVOLUCION ?></div></td> 
                <td><div align="center"><?php echo '$ ' . number_format($data->VALOR_PAGADO, 0, '.', '.'); ?></div></td> 
                <td><div align="center"><?= $data->FECHA_TRANSACCION ?></div></td> 
                <td><div align="center"><?php echo '$ ' . number_format($data->VALOR_DEVUELTO, 0, '.', '.'); ?></div></td> 
                <td><div align="center"><?= $data->FECHA_DEVOLUCION ?></div></td> 
                <td><div align="center"><input id="list_pagosdevolver" name='list_pagosdevolver[]' type="checkbox" onclick="recibir(<?= $data->COD_PAGO_DEVUELTO ?>);" value="<?= $data->COD_PAGO_DEVUELTO ?>"></div></td> 
            </tr>
            <?php
        }
    }
    ?>
</table>
<?php
$data_1 = array(
    'name' => 'button',
    'id' => 'enviar',
    'value' => 'Enviar',
    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
    'class' => 'btn btn-success enviar'
);
?>
<br>

<center>
    <?php
    echo form_button($data_1) . " ";
    echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
    ?>
</center>

<?php echo form_close(); ?>

<script type="text/javascript" language="javascript" charset="utf-8">

    //generación de la tabla mediante json
    jQuery(".preload, .load").hide();
    $('#cancelar').click(function() {
        window.history.back(-1);
    });
    function recibir($id) {
        var cod_solicitud = $('#id_solicitudes').val();
        cod_solicitud = cod_solicitud + "-" + $id;
        $('#id_solicitudes').val(cod_solicitud);
    }
    $('.enviar').click(function() {
        var cod_planillas = $('#id_planillas').val();
        if (cod_planillas != '') {
            if (confirm("¿Desea Confirmar?")) {
                $('#myform').submit();
            }
        } else {
            alert('No se puede calcular la devolucion, no ha seleccionado ninguna planilla');
        }
    });
    function num(c) {
        c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
        // x = c.value;
        // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        var num = c.value.replace(/\./g, '');
        if (!isNaN(num))
        {
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/, '');
            c.value = num;
        }
    }
</script> 