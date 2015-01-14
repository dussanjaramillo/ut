    <?php 
$valorTotal    = $saldocapital;
$cuotaInicial  = $valorcuota;
$fechaPrimer   = $fpcuota;
$cuotas        = $ncuotas;
$date          = date("d/m/Y");
$interes       = $tasa;
$valorcuota = $cuotaInicial+$intCorrienteCuota;
?>
<!--<tr>
    <td align='center'>0<input type='hidden' value='<?= 0 ?>' name="cuota[]"></td>
    <td align='center'><?= $fechaPrimer ?><input type='hidden' value='<?= $fechaPrimer ?>' name="fecha[]"></td>
    <td align='center'><?= number_format($saldocapital) ?><input type='hidden' value='<?= $saldocapital ?>' name="saldocapital[]"></td>
    <td align='center'><?= number_format($interesCorriente) ?><input type='hidden' value='<?= $interesCorriente ?>' name="intCorriente[]"></td>
    <td align='center' style="display:none"><?= number_format($intCorrienteCuotaCero) ?><input type='hidden' value='<?= $intCorrienteCuotaCero ?>' name="intCorrienteCuota[]"></td>
    <td align='center'>0<input type='hidden' value='0' name="interespormora[]"></td>
    <td align='center'><?= number_format($cuotaIn = ($valorTotal*$porcentaje)+$intCorrienteCuotaCero) ?><input type='hidden' value='<?= $cuotaIn ?>' name="valorcuota[]"></td>
    <td align='center'><?= number_format($capital = $cuotaIn-$intCorrienteCuotaCero) ?><input type='hidden' value='<?= $capital ?>' name="aportecapital[]"></td>
    <td align='center'><?= number_format($saldofinal = $saldocapital - $capital) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>    
</tr>-->
<?php    
    for ($i=1;$i<=$ncuotas;$i++){
        $fechas = explode('/',$fechaPrimer);//interesCorriente   
        if ($i == $ncuotas){
                //$deuda = $this->modelacuerdodepago_model->consultadeuda($liquidacion);              
                //$deuda = recalcularLiquidacion_acuerdoPago($liquidacion);
                $acuerdo_juridico = $this->modelacuerdodepagojuridico_model->consultarDeudas($cod_coactivo,$estado);           
                $deuda = $acuerdo_juridico[0]['TOTAL_DEUDA'];
                if ($deuda == ''):
                    @$saldocapital1 = 0;
                    @$interesCorriente1 = 0;
                else:
                    @$saldocapital1 = $acuerdo_juridico[0]['TOTAL_CAPITAL'];
                    @$interesCorriente1 = $acuerdo_juridico[0]['TOTAL_INTERES'];
                endif;
                
                $fecha_hoy1 = date("d/m/Y");
                $tasaEfectiva1 = $this->modelacuerdodepagojuridico_model->tasaEfectiva($fecha_hoy1);
                $tasamul1 = ($tasaEfectiva1[0]['TASA_SUPERINTENDENCIA']) / 100;
                $tasaperiodica1 = pow((1 + $tasamul1), (1 / 12)) - 1;
                $porcentaje = $this->modelacuerdodepagojuridico_model->cuotainicial();
                $this->data['porcentaje'] = $porcentaje[0]['PORCENTAJE'] / 100;
                $saldoInicial1 = $saldocapital1 - ($saldocapital1 * ($porcentaje[0]['PORCENTAJE'] / 100));
                $valorcuotaFija1 = ($saldoInicial1 * ($tasaperiodica1 * pow(1 + $tasaperiodica1, $ncuotas))) / (pow((1 + $tasaperiodica1), $ncuotas) - 1);
                $intCorrienteCuotaCero1 = $interesCorriente1 * ($porcentaje[0]['PORCENTAJE'] / 100);
                $interescalc1 = $interesCorriente1 - $intCorrienteCuotaCero1;
                $intCorrienteCuota1 = $interescalc1 / $ncuotas;                
                $cuotafin = $valorcuotaFija1+$intCorrienteCuota1;
                        for ($j = 1; $j < $ncuotas; $j++){                            
                            if ($cuotafin != round($valorcuota)):
                                $total = round($valorcuota) - $cuotafin;
                                $total++;
                            endif;
                        }
                
                $valorcuota = round($valorcuota)-round($total);
                
            }                
    ?>    
    <tr>
        <td align='center'><?= $i ?><input type='hidden' value='<?= $i ?>' name="cuota[]"></td>
        <td align='center'><?php echo $date = date("d/m/Y", mktime(0, 0, 0, $fechas[1]+$i, $fechas[0],$fechas[2])); ?><input type='hidden' value='<?= $date ?>' name="fecha[]"></td>
        <td align='center'><?= number_format($saldoInicial = $saldofinal) ?><input type='hidden' value='<?= $saldoInicial ?>' name="saldocapital[]"></td>
        <td align='center'><?= number_format($interescalc) ?><input type='hidden' value='<?= $interescalc ?>' name="intCorriente[]"></td> 
        <td align='center' style="display:none"><?= number_format($intCorrienteCuota) ?><input type='hidden' value='<?= $intCorrienteCuota ?>' name="intCorrienteCuota[]"></td>
        <td align='center'><?= number_format($interespormora = $saldoInicial*$interes) ?><input type='hidden' value='<?= $interespormora ?>' name="interespormora[]"></td>
        <td align='center'><?= number_format($valorcuota) ?><input type='hidden' value='<?= $valorcuota ?>' name="valorcuota[]"></td>
        <td align='center'><?= number_format($aporte = $cuotaInicial - $interespormora) ?><input type='hidden' value='<?= $aporte ?>' name="aportecapital[]"></td>
        <td align='center'><?php 
        $saldofinal = $saldoInicial - ($valorcuota-$interespormora-$intCorrienteCuota);       
        if ($i == $ncuotas):
            $saldofinal = 0; 
        endif;
        echo number_format(round($saldofinal, 0, PHP_ROUND_HALF_DOWN)) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>
        <?php if ($i == 1){?>
        <td align="right"><button id="<?= $cuotaInicial ?>,<?= $date ?>,<?= $saldoInicial ?>,<?= $saldofinal ?>,<?= $i ?>,<?= $interespormora ?>,<?= $intCorrienteCuota ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" at="<?= 0 ?>" id="generar">RECIBO</button>
        <img class="preloadminicuota" id="preloadminicuota" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
        <?php } ?>
    </tr>
<?php $interescalc = $interescalc - $intCorrienteCuota; } ?>
<script>
    $("#preloadminicuota").hide(); 
    $(".generar" ).hide();
</script>
   