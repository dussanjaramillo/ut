<?php
$attributes = array("id" => "myform", "name" => "myform");
echo form_open('devolucion/Aprobar_Solicitudes', $attributes);
echo form_hidden('cod_devolucion', $cod_devolucion);
?>
<input type="hidden" id="id_planillas" name="id_planillas" readonly> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" /> 
<br />
<center>
    <br>
    <h1><?php echo 'Aprobación para Devoluciones' ?></h1>
    <h2><?php echo 'Devolución de Planilla Unica'; ?></h2>
</center>
<br />
<table width="90%" border="1" align="center" class="table table-bordered table-striped">
    <tr>
        <td width="11%"><div align="center"><strong>Nro. de Planilla</strong></div></td>
        <td width="11%"><div align="center"><strong>Nombre Aportante</strong></div></td>
        <td width="11%"><div align="center"><strong>Periodo Pagado</strong></div></td>
        <td width="12%"><div align="center"><strong>Fecha Radicacion</strong></div></td>
        <td width="10%"><div align="center"><strong>Valor Pagado</strong></div></td>
        <td width="11%"><div align="center"><strong>Valor Solicitado</strong></div></td>
        <td width="11%"><div align="center"><strong>Valor Aprobado</strong></div></td>
        <td width="7%"><div align="center"><strong>Aprobar</strong></div></td>
    </tr>
    <?php
    if (!empty($estados_seleccionados)) {
        $i = 0;
        foreach ($estados_seleccionados as $data) {
            if ($data->VALOR_DEVOLUCION < 0) {
                $data->VALOR_DEVOLUCION = 0;
            }
            $atributos_general = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19', 'onkeypress' => 'num(this,' . $data->VALOR_DEVOLUCION . ');', 'onblur' => 'num(this,' . $data->VALOR_DEVOLUCION . ');', 'onkeyup' => 'num(this,' . $data->VALOR_DEVOLUCION . ');', 'focus' => 'num(this,' . $data->VALOR_DEVOLUCION . ');');
            $atributos_devolver = array('name' => 'devolver[]', 'id' => 'devolver', 'value' => number_format($data->VALOR_DEVOLUCION, 0, '.', '.'));
            ?>             
            <tr>          
                <td><div align="center"><?= $data->COD_PLANILLAUNICA ?></div></td> 
                <td><div align="center"><?= $data->NOM_APORTANTE ?></div></td> 
                <td><div align="center"><?= $data->PERIDO_PAGO ?></div></td> 
                <td><div align="center"><?= $data->FECHA_RADICACION ?></div></td> 
                <td><div align="center"><?php echo '$ ' . number_format($data->VALOR_INCORRECTO, 0, '.', '.'); ?></div></td> 
                <td><div align="center"><?php echo '$ ' . number_format($data->VALOR_DEVOLUCION, 0, '.', '.'); ?></div></td> 
                <td><div align="center"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_devolver)); ?></div></div></td>
                <td><div align="center"><input id="list_planillas" name='list_planillas[<?= $i ?>]' type="checkbox" onclick="recibir(<?= $data->COD_APROBACION ?>);" value="<?= $data->COD_APROBACION ?>"></div></td> 
            </tr>
            <?php
            $i++;
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
<br />

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
        var cod_planillas = $('#id_planillas').val();
        cod_planillas = cod_planillas + "-" + $id;
        $('#id_planillas').val(cod_planillas);
    }
    $('.enviar').click(function() {
        //    var cod_planillas = $('#id_planillas').val();
        //    if (cod_planillas != '') {
//        var sel = validarSeleccion()                
//        if (sel) {
//            var res = validarRegistros()                
//            if (res) {    
        if (confirm("¿Desea Confirmar?")) {
            $('#myform').submit();
        }
//            } 
//        } else {
//                alert('No se puede calcular la devolucion, no ha seleccionado ninguna planilla');
//        }
    });

    function num(c, validar) {
        c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
        // x = c.value;
        // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        var num = c.value.replace(/\./g, '');
        if (num > validar) {
            num = num.substring(0, num.length-1);
        }
        if (!isNaN(num))
        {
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/, '');
            c.value = num;
        }
    }

    // Verifica cada registro en la devolucion de la planilla unica cuando se da clic en confirmar
    function validarRegistros() {
        var numPla = 0;
        var valSol = 0;
        var valApr = 0;
        var valReg;
        var form = document.myform;

<?php $data = json_encode($estados_seleccionados); ?>
        eval('miObjReg =<?php echo $data; ?>');

        for (i in miObjReg) {
            numPla = miObjReg[i]['COD_PLANILLAUNICA'];
            valSol = miObjReg[i]['VALOR_DEVOLUCION'];
            valApr = form.elements["devolver"][i].value;
            valApr = valApr.replace(/\./g, '');
            valReg = form.elements["list_planillas"][i].checked;
            if (valReg) {
                if (valSol < valApr) {
                    alert('En Planilla (' + numPla + ') el Valor Aprobado ($ ' + valApr + ') es Mayor al Valor Solicitado ($ ' + valSol + ')');
                    return false;
                }
            }
        }
        return true;
    }

    // Determina si han seleccionado registros para procesar
    function validarSeleccion() {
        var form = document.myform;
        var valReg;
<?php $datasel = json_encode($estados_seleccionados); ?>
        eval('miObjSel =<?php echo $datasel; ?>');
        for (i in miObjSel) {
            valReg = form.elements["list_planillas"][i].checked;
            if (valReg) {
                return true;
            }
        }
        return false;
    }

</script> 