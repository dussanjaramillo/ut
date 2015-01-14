<?php
$dataguardar = array('name' => 'button', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success', 'style' => 'display:none');
$datacancel = array('name' => 'button', 'id' => 'cancel-button', 'value' => 'Cancelar', 'type' => 'button', 'onclick' => 'window.location=\'' . base_url() . 'index.php/bandejaunificada/procesos\';', 'content' => '<i class="fa fa-minus-circle"></i> Cancelar', 'class' => 'btn btn-warning');
$dataadicionales = array('name' => 'adicionales', 'id' => 'adicionales', 'style' => 'margin:10px', 'readonly' => 'readonly', 'disabled' => 'disabled');
$verificacion = array('name' => 'verificacion', 'id' => 'verificacion', 'type' => 'hidden');
$fiscalizacion = array('name' => 'cod_coactivo', 'id' => 'cod_coactivo', 'type' => 'hidden', 'value' => $cod_coactivo);
$tipo = array('name' => 'tipo', 'id' => 'tipo', 'type' => 'hidden', 'value' => $tipo);
$nitbutton = array('name' => 'nit', 'id' => 'nit', 'type' => 'hidden', 'value' => $nit);
$acuerdobutton = array('name' => 'acuerdo', 'id' => 'acuerdo', 'type' => 'hidden', 'value' => $acuerdo);
$attributes = array('name' => 'devolucionFrm', 'id' => 'devolucionFrm', 'class' => 'form-inline', 'method' => 'POST', 'enctype' => 'multipart/form-data');


echo form_open_multipart(current_url(), $attributes);
echo form_input($acuerdobutton);
echo form_input($fiscalizacion);
echo form_input($tipo);
echo form_input($nitbutton);
echo form_input($verificacion);
$mora = 0;
$cancela = 0;
?>
<table align='center'>
    <tr>
        <td align='center' colspan='2'><h2>Verificación de Pagos</h2><img style="display:none" class="preloadminicuota" id="preloadminicuota" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
    </tr>     
    <tr>
        <td align='center'>
            <b>Numero de Resolución: </b><?php echo $resolucion; ?><br>
        </td>
    </tr>
    <tr>
        <td>
            <table border="3" align="center" style="width: 1200px" class="table table-striped">
                <tr>     
                    <th align='center'>CUOTA</th>
                    <th align='center'>FECHA</th>
                    <th align='center' style="width: 80px">SALDO INICIAL</th>
                    <th align='center'>INTERES ACUERDO</th>
                    <th align='center'>SALDO INTERES ACUERDO</th>
                    <th align='center' style="width: 80px">INTERES CORRIENTE</th>
                    <th align='center' style="width: 80px">SALDO INTERES CORRIENTE</th>
                    <th align='center' style="width: 80px">VALOR CUOTA</th>  
                    <th align='center' style="width: 80px">SALDO CUOTA</th>
                    <th align='center' style="width: 80px">CAPITAL</th>
                    <th align='center' style="width: 80px">SALDO FINAL</th>                        
                    <th align='center'>ESTADO</th> 
                    <th align='center'>RECIBO</th> 
<!--                        <th align='center'>RECIBO</th> -->
                </tr>
                <?php
                $contador = 0;
                foreach ($datostabla->result_array as $datos) {
                    ?>
                    <tr>  
                        <td align='center'><?= $datos['PROYACUPAG_NUMCUOTA'] ?></td>
                        <td align='center'>
                            <?= $datos['PROYACUPAG_FECHALIMPAGO'] ?>
                            <input id="fecha<?= $contador ?>" type="hidden" value="<?= $datos['PROYACUPAG_FECHALIMPAGO'] ?>">
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_CAPITALDEBE']) ?></td>
                        <td align='center'>                        
                            <?= "$ " . number_format($datos['PROYACUPAG_VALORINTERESESMORA']) ?>                         
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_SALDO_INTACUERDO']) ?>
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_INTCORRIENTE_CUOTA']) ?>                         
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_SALDO_INTCORRIENTE']) ?>
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_VALORCUOTA']) ?>                        
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_SALDO_CUOTA']) ?>
                        </td>
                        <td align='center'>
                            <?= "$ " . number_format($datos['PROYACUPAG_CAPITAL_CUOTA']) ?><br>
                        </td>
                        <td align='center'>
                            <?php
                            $aporte = $datos['PROYACUPAG_CAPITALDEBE'] - $datos['PROYACUPAG_CAPITAL_CUOTA'];
                            echo "$ " . number_format($aporte);
                            ?><br>
                        </td>
                        <td align='center'>
                            <?php
                            if ($datos['PROYACUPAG_NUMCUOTA'] == 0) {
                                echo 'Cancelado';
                                $cancela++;
                            } else {
                                if ($datos['PROYACUPAG_ESTADO'] == 1) {
                                    echo 'Cancelado<br>';
                                    $cancela++;
                                } else if ($datos['PROYACUPAG_ESTADO'] == 0 && $datos['DIAS_MORA'] > 30) {
                                    echo 'En Mora<br>';
                                    $mora++;
                                } else {
                                    echo 'Pendiente Por Pagar';
                                }
                            }
                            ?>
                        </td>
                        <td align="right">  
                            <?php
                            //if (($datos['PROYACUPAG_ESTADO'] == 1 || $datos['PROYACUPAG_NUMCUOTA'] == 0) && (abs($datos['DIAS_MORA'])>0 && abs($datos['DIAS_MORA'])<30)): 
                            if ($datos['PROYACUPAG_ESTADO'] == 1):
                                ?>
                                <button disabled id="<?= $datos['PROYACUPAG_NUMCUOTA'] ?>,<?= $datos['PROYACUPAG_FECHALIMPAGO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTACUERDO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTCORRIENTE'] ?>,<?= $datos['PROYACUPAG_SALDO_CUOTA'] ?>,<?= $datos['PROYACUPAG_CAPITALDEBE'] ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" id="generar">
                                    RECIBO
                                </button>                                                                            
                                <?php
                            elseif ($datos['PROYACUPAG_ESTADO'] == 0 && $datos['DIAS_MORA'] > 30):
                                ?>
                                <button disabled id="<?= $datos['PROYACUPAG_NUMCUOTA'] ?>,<?= $datos['PROYACUPAG_FECHALIMPAGO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTACUERDO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTCORRIENTE'] ?>,<?= $datos['PROYACUPAG_SALDO_CUOTA'] ?>,<?= $datos['PROYACUPAG_CAPITALDEBE'] ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" id="generar">
                                    RECIBO
                                </button>                                                                            
                                <?php
                            else:
                                ?>
                                <button id="<?= $datos['PROYACUPAG_NUMCUOTA'] ?>,<?= $datos['PROYACUPAG_FECHALIMPAGO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTACUERDO'] ?>,<?= $datos['PROYACUPAG_SALDO_INTCORRIENTE'] ?>,<?= $datos['PROYACUPAG_SALDO_CUOTA'] ?>,<?= $datos['PROYACUPAG_CAPITALDEBE'] ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" id="generar">
                                    RECIBO
                                </button>                                                                            
                            <?php
                            endif;
                            ?>                            
                        </td>
                    </tr>
                    <?php
                    $contador ++;
                }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <td align='center' colspan='2'>
            <b>Se han Recibido los Pagos?</b>
        </td>
    </tr>
    <tr>
        <td id='check_decision' align='center' colspan='2'>
            <?php
            if ($mora == 0) {
                $checkTrue = true;
                $checkFalse = false;
            } else {
                $checkTrue = false;
                $checkFalse = true;
            }
            echo "&nbsp;" . form_radio($dataadicionales, "1", $checkTrue) . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
            echo "&nbsp;" . form_radio($dataadicionales, "0", $checkFalse) . "&nbsp;&nbsp;NO";
            ?>
        </td>
    </tr>   
    <tr>
        <td colspan='2' align='center'>
            <div style="display:none" id="error" class="alert alert-danger"></div>
        </td>
    </tr><tr><td><br></td></tr>
    <tr>
        <td align='center' colspan='2' id="botones">
            <?=
            form_button($dataguardar) . "&nbsp;&nbsp;";
            echo form_button($datacancel);
            ?>
        </td>
    </tr>           
</table>

<?php echo form_close(); ?>
<script>
    $(document).ready(function() {
        var total = <?= $contador ?>;
        var mora = <?= $mora ?>;
        var cancela = <?= $cancela ?>;
        //alert (cancela+'-'+total+'-'+mora);
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        var today = dd + '/' + mm + '/' + yyyy;

        for (var i = 0; i < total; i++) {
            var fecha = $('#fecha' + i).val();
            if (fecha == today) {
                var cuerpo = 'Buen dia <br>'
                        + ' Se Notifica Que hoy se vence el plazo, para cancelar la cuota correspondiente al mes en curso';
                enviarMail(cuerpo, fecha, i);
            }
        }

        if (cancela == total || mora > 0) {
            $('#guardar').show();
        }
    });

    function enviarMail(cuerpo, fecha, periodo) {
        var correo = 'saas02@gmail.com';
        var asunto = 'Fecha de Pago';
        var cuerpo = cuerpo;
        $.ajax({
            type: "post",
            url: "<?= base_url('index.php/acuerdodepagojuridico/enviaCorreo') ?>",
            data: {correo: correo, asunto: asunto, cuerpo: cuerpo},
            success:
                    function(data) {
                        alert('Correo Enviado Por el Periodo ' + periodo);
                    }
        });
    }



    $('#guardar').click(function() {
        var adicionales = $('input[name="adicionales"]:checked').val();
        var url = '<?php echo base_url('index.php/acuerdodepagojuridico/guardar_verificacion') ?>';
        if (adicionales == 1) {
            var verificacion = 'S';
        } else {
            var verificacion = 'N';
        }
        $("#verificacion").val(verificacion);
        $(".ajax_load").show("slow");
        $('#guardar').prop("disabled", true);
        $("#devolucionFrm").attr("action", url);
        $('#devolucionFrm').removeAttr('target');
        $('#devolucionFrm').submit();

    });

    function recibo(id) {
        $(".ajax_load").show("slow");
        var datos = id.split(',');
        var numcuota = datos[0];
        var fecha = datos[1];
        var saldoIntAcuerdo = datos[2];
        var saldoIntCorriente = datos[3];
        var saldoCuota = datos[4];
        var liquidacion = datos[5];
        var saldoDeuda = datos[6];
        var acuerdo = '<?= $acuerdo ?>';
        var nit = '<?= $nit ?>';
        var razonsocial = '<?= $razon ?>';
        var aporte = <?= $aporte ?>;
        var resolucion = '<?= $resolucion ?>';
        var cod_coactivo = '<?= $cod_coactivo ?>';
        var url = "<?= base_url('index.php/acuerdodepagojuridico/generarTotalrecibo') ?>";
        $('#modal').load(url, {numcuota: numcuota,
            fecha: fecha,
            saldoIntAcuerdo: saldoIntAcuerdo,
            saldoIntCorriente: saldoIntCorriente,
            saldoCuota: saldoCuota,
            acuerdo: acuerdo,
            nit: nit,
            razonsocial: razonsocial,
            cod_coactivo: cod_coactivo,
            liquidacion: liquidacion,
            saldoDeuda: saldoDeuda,
            aporte: aporte}, function(data) {
            $('#modal').modal('toggle');
            $(".preloadminicuota").hide();
            $(".generar").show();
            $(".ajax_load").hide("slow");
        });
    }
</script>    

