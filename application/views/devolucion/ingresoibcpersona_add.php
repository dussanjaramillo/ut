<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 90%; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("devolucion/Disminuir_DevPersonas", $attributes);
    echo form_hidden('cod_devolucion', $cod_devolucion);
    echo form_hidden('planillas', serialize($planillas));
    echo form_hidden('personas', serialize($personas));
    echo form_hidden('ibc_errados', serialize($ibc_incorrecto));
    echo form_hidden('aportes', serialize($aportes));
    ?>       
    <br>
    <center>
        <h2><?php echo $concepto->NOMBRE_CONCEPTO; ?></h2> </center>
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
        <?php if (($concepto->COD_CONCEPTO != 3) and ($concepto->COD_CONCEPTO != 4) and ($concepto->COD_CONCEPTO != 44) and ($concepto->COD_CONCEPTO != 5)) { ?>        
            <tr>
                <td colspan="7"><p>&nbsp;</p><div align="center"><p><strong>Porcentaje de aporte</strong></p><div class="input-prepend"><span class="add-on">%</span><input name="porcentaje" value="0" id="porcentaje" type="text" onkeypress="recalcular()" onkeyup="recalcular()" onblur="recalcular()"/></div></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="7"><p>&nbsp;</p><p><strong><?php echo $concepto->NOMBRE_CONCEPTO; ?></strong></p></td>
        </tr>
        <?php
        switch ($concepto->COD_CONCEPTO) {
            case 44:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>NOMBRE APORTANTE</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC REGISTRADO</strong></div></td>
                    <td width="19%"><div align="center"><strong>PAGO ERRADO</strong></div></td>
                    <td width="17%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                $atributos_secundarios = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19');
                $atributos_total = array('name' => 'total', 'id' => 'total', 'value' => 0);

                for ($i = 0; $i < sizeof($planillas); $i++) {
                    $pago_incorrecto[$i] = $aportes[$i];
                    $ibc_correcto[$i] = $ibc_incorrecto[$i];
                    $pago_correcto[$i] = 0;
                    $subtotal_devolucion[$i] = $pago_incorrecto[$i] - $pago_correcto[$i];
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo $nombre_persona[$i]->PRIMER_NOMBRE . ' ' . $nombre_persona[$i]->SEGUN_NOMBRE . ' ' . $nombre_persona[$i]->PRIMER_APELLIDO . ' ' . $nombre_persona[$i]->SEGUN_APELLIDO; ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($aportes[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($pago_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($subtotal_devolucion[$i], 0, '.', '.'); ?></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><?php echo "$" . number_format(array_sum($subtotal_devolucion), 0, '.', '.'); ?></td>
                </tr>
                <?php
                echo form_hidden('pago_incorrecto', serialize($pago_incorrecto));
                echo form_hidden('ibc_correcto', serialize($ibc_correcto));
                echo form_hidden('pago_correcto', serialize($pago_correcto));
                echo form_hidden('subtotal_devolucion', serialize($subtotal_devolucion));
                break;
            case 3:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>NOMBRE APORTANTE</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC REGISTRADO</strong></div></td>
                    <td width="19%"><div align="center"><strong>PAGO ERRADO</strong></div></td>
                    <td width="17%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                $atributos_secundarios = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19');
                $atributos_total = array('name' => 'total', 'id' => 'total', 'value' => 0);
                for ($i = 0; $i < sizeof($planillas); $i++) {
                    $atributos_general = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19', 'onkeypress' => 'num(this,this.id,' . $aportes[$i] . ');', 'onblur' => 'num(this,this.id,' . $aportes[$i] . ');', 'onkeyup' => 'num(this,this.id,' . $aportes[$i] . ');');
                    $atributos_ibc = array('name' => 'ibc[]', 'id' => $personas[$i] . $ibc_incorrecto[$i], 'value' => 0);
                    $atributos_pago = array('name' => 'pago[]', 'id' => 'pago' . $personas[$i] . $ibc_incorrecto[$i], 'value' => 0, 'readonly' => 'readonly');
                    $atributos_subtotal = array('name' => 'subtotal[]', 'id' => 'subtotal' . $personas[$i] . $ibc_incorrecto[$i], 'value' => 0, 'readonly' => 'readonly');
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo $nombre_persona[$i]->PRIMER_NOMBRE . ' ' . $nombre_persona[$i]->SEGUN_NOMBRE . ' ' . $nombre_persona[$i]->PRIMER_APELLIDO . ' ' . $nombre_persona[$i]->SEGUN_APELLIDO; ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($aportes[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_ibc)); ?></div></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_secundarios, $atributos_pago)); ?></div></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_secundarios, $atributos_subtotal)); ?></div></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_total)); ?></div></td>
                </tr>
                <?php
                break;
            case 4:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>NOMBRE APORTANTE</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC REGISTRADO</strong></div></td>
                    <td width="19%"><div align="center"><strong>PAGO ERRADO</strong></div></td>
                    <td width="17%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                $atributos_secundarios = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19');
                $atributos_total = array('name' => 'total', 'id' => 'total', 'value' => 0);

                for ($i = 0; $i < sizeof($planillas); $i++) {
                    $pago_incorrecto[$i] = $aportes[$i];
                    $ibc_correcto[$i] = $ibc_incorrecto[$i] * 0.7;
                    $pago_correcto[$i] = $ibc_correcto[$i] * 0.02;
                    $subtotal_devolucion[$i] = $pago_incorrecto[$i] - $pago_correcto[$i];
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo $nombre_persona[$i]->PRIMER_NOMBRE . ' ' . $nombre_persona[$i]->SEGUN_NOMBRE . ' ' . $nombre_persona[$i]->PRIMER_APELLIDO . ' ' . $nombre_persona[$i]->SEGUN_APELLIDO; ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($aportes[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($pago_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($subtotal_devolucion[$i], 0, '.', '.'); ?></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><?php echo "$" . number_format(array_sum($subtotal_devolucion), 0, '.', '.'); ?></td>
                </tr>
                <?php
                echo form_hidden('pago_incorrecto', serialize($pago_incorrecto));
                echo form_hidden('ibc_correcto', serialize($ibc_correcto));
                echo form_hidden('pago_correcto', serialize($pago_correcto));
                echo form_hidden('subtotal_devolucion', serialize($subtotal_devolucion));
                break;
            case 5:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>NOMBRE APORTANTE</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC REGISTRADO</strong></div></td>
                    <td width="19%"><div align="center"><strong>PAGO ERRADO</strong></div></td>
                    <td width="17%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                $atributos_secundarios = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19');
                $atributos_total = array('name' => 'total', 'id' => 'total', 'value' => 0);

                for ($i = 0; $i < sizeof($planillas); $i++) {
                    $pago_incorrecto[$i] = $aportes[$i];
                    $ibc_correcto[$i] = $ibc_incorrecto[$i];
                    $pago_correcto[$i] = 0;
                    $subtotal_devolucion[$i] = $pago_incorrecto[$i] - $pago_correcto[$i];
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo $nombre_persona[$i]->PRIMER_NOMBRE . ' ' . $nombre_persona[$i]->SEGUN_NOMBRE . ' ' . $nombre_persona[$i]->PRIMER_APELLIDO . ' ' . $nombre_persona[$i]->SEGUN_APELLIDO; ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($aportes[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($pago_correcto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($subtotal_devolucion[$i], 0, '.', '.'); ?></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><?php echo "$" . number_format(array_sum($subtotal_devolucion), 0, '.', '.'); ?></td>
                </tr>
                <?php
                echo form_hidden('pago_incorrecto', serialize($pago_incorrecto));
                echo form_hidden('ibc_correcto', serialize($ibc_correcto));
                echo form_hidden('pago_correcto', serialize($pago_correcto));
                echo form_hidden('subtotal_devolucion', serialize($subtotal_devolucion));
                break;
            default:
                ?>
                <tr>
                    <td width="13%"><div align="center"><strong>NUMERO DE PLANILLA</strong></div></td>
                    <td width="11%"><div align="center"><strong>NOMBRE APORTANTE</strong></div></td>
                    <td width="15%"><div align="center"><strong>IBC REGISTRADO</strong></div></td>
                    <td width="19%"><div align="center"><strong>PAGO ERRADO</strong></div></td>
                    <td width="17%"><div align="center"><strong>IBC CORRECTO</strong></div></td>
                    <td width="15%"><div align="center"><strong>VALOR CORRECTO</strong></div></td>
                    <td width="10%"><div align="center"><strong>DEVOLUCION</strong></div></td>
                </tr>
                <?php
                $atributos_secundarios = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19');
                $atributos_total = array('name' => 'total', 'id' => 'total', 'value' => 0);
                for ($i = 0; $i < sizeof($planillas); $i++) {
                    $atributos_general = array('type' => 'text', 'class' => 'input-small', 'value' => '0', 'maxlength' => '19', 'onkeypress' => 'num(this,this.id,' . $aportes[$i] . ');', 'onblur' => 'num(this,this.id,' . $aportes[$i] . ');', 'onkeyup' => 'num(this,this.id,' . $aportes[$i] . ');');
                    $atributos_ibc = array('name' => 'ibc[]', 'id' => $personas[$i] . $ibc_incorrecto[$i], 'value' => 0);
                    $atributos_pago = array('name' => 'pago[]', 'id' => 'pago' . $personas[$i] . $ibc_incorrecto[$i], 'value' => 0, 'readonly' => 'readonly');
                    $atributos_subtotal = array('name' => 'subtotal[]', 'id' => 'subtotal' . $personas[$i] . $ibc_incorrecto[$i], 'value' => 0, 'readonly' => 'readonly');
                    ?>
                    <tr>
                        <td><div align="right"><?php echo 'PLANILLA ' . $planillas[$i]; ?></td>
                        <td><div align="right"><?php echo $nombre_persona[$i]->PRIMER_NOMBRE . ' ' . $nombre_persona[$i]->SEGUN_NOMBRE . ' ' . $nombre_persona[$i]->PRIMER_APELLIDO . ' ' . $nombre_persona[$i]->SEGUN_APELLIDO; ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($ibc_incorrecto[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><?php echo "$" . number_format($aportes[$i], 0, '.', '.'); ?></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_ibc)); ?></div></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_secundarios, $atributos_pago)); ?></div></div></td>
                        <td><div align="right"><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_secundarios, $atributos_subtotal)); ?></div></div></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td><b>Total Devolucion:</b></td>
                    <td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_total)); ?></div></td>
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
    <script type="text/javascript" language="javascript" charset="utf-8">
        function num(c, id, valor_errado) {
            c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
            var dato = c.value.replace(/\./g, '');
            var causal = '<?php echo $concepto->COD_CONCEPTO; ?>';
            var descuento;
            if (causal == '5') {
                descuento = parseFloat($('#porcentaje').val()) / 100;
            } else {
                descuento = parseFloat('<?php echo $detalle_planilla[0]->TARIFA; ?>');

            }
            var valor = parseInt(dato) * descuento;
            var valor_subtotal = parseInt(valor_errado) - parseInt(valor);
            /*
             * Calcular total
             */
            var planillas =<?php echo json_encode($planillas); ?>;
            var ibc_incorrecto =<?php echo json_encode($ibc_incorrecto); ?>;
            var personas =<?php echo json_encode($personas); ?>;
            var total = 0;
            var valorsumar = 0;
            for (var i = 0; i < planillas.length; i++) {
                valorsumar = $('#subtotal' + personas[i] + ibc_incorrecto[i]).val();
                valorsumar = valorsumar.replace('.', '');
                total = total + parseInt(valorsumar);
            }
            total = colocar_puntos(total);
            if (isNaN(valor)) {
                $('#pago' + id).val(0);
                $('#subtotal' + id).val(0);
            } else {
                valor = colocar_puntos(parseInt(valor));
                if (valor_subtotal < 0) {
                    valor_subtotal = 0;
                } else {
                    valor_subtotal = colocar_puntos(valor_subtotal);
                }
                $('#pago' + id).val(valor);
                $('#subtotal' + id).val(valor_subtotal);
                $('#subtotal' + id).val(valor_subtotal);
                $('#total').val(total);
                var num = colocar_puntos(dato);
                //alert(num);
                c.value = num;
            }

        }
        function recalcular() {
            /*
             * Calcular total
             */
            var planillas =<?php echo json_encode($planillas); ?>;
            var ibc_incorrecto =<?php echo json_encode($ibc_incorrecto); ?>;
            var personas =<?php echo json_encode($personas); ?>;
            var aportes =<?php echo json_encode($aportes); ?>;
            var descuento = parseFloat($('#porcentaje').val()) / 100;
            var ibc_correcto = 0;
            var valor_correcto = 0;
            var devolucion = 0;
            for (var i = 0; i < planillas.length; i++) {
                ibc_correcto = parseInt($('#' + personas[i] + ibc_incorrecto[i]).val().replace(/\./g, ''));
                valor_correcto = parseInt(ibc_correcto * descuento);
                devolucion = parseInt(parseInt(aportes[i]) - valor_correcto);
                if (isNaN(valor_correcto) || isNaN(devolucion) || valor_correcto < 0 || devolucion < 0) {
                    $('#pago' + personas[i] + ibc_incorrecto[i]).val(0);
                    $('#subtotal' + personas[i] + ibc_incorrecto[i]).val(0);
                } else {
                    $('#pago' + personas[i] + ibc_incorrecto[i]).val(colocar_puntos(valor_correcto));
                    $('#subtotal' + personas[i] + ibc_incorrecto[i]).val(colocar_puntos(devolucion));
                }
            }
            var total = 0;
            var valorsumar = 0;
            for (var i = 0; i < planillas.length; i++) {
                valorsumar = $('#subtotal' + personas[i] + ibc_incorrecto[i]).val();
                valorsumar = valorsumar.replace('.', '');
                total = total + parseInt(valorsumar);
            }
            total = colocar_puntos(total);
            $('#total').val(total);
        }

        function colocar_puntos(valor) {
            valor = valor.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            valor = valor.split('').reverse().join('').replace(/^[\.]/, '');
            //alert('funcion'+valor);
            return valor;
        }
    </script>