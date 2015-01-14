    <?php 
$valorTotal    = $saldocapital;
$cuotaInicial  = $valorcuota;
$fechaPrimer   = $fpcuota;
$cuotas        = $ncuotas;
$date          = date("d/m/Y");
$interes       = $tasa;

?>
<tr>
    <td align='center'>0<input type='hidden' value='<?= 0 ?>' name="cuota[]"></td>
    <td align='center'><?= $date ?><input type='hidden' value='<?= $date ?>' name="fecha[]"></td>
    <td align='center'><?= number_format($saldocapital) ?><input type='hidden' value='<?= $saldocapital ?>' name="saldocapital[]"></td>
    <td align='center'><?= number_format($interesCorriente) ?><input type='hidden' value='<?= $interesCorriente ?>' name="intCorriente[]"></td>
    <td align='center'><?= number_format($intCorrienteCuotaCero) ?><input type='hidden' value='<?= $intCorrienteCuotaCero ?>' name="intCorrienteCuota[]"></td>
    <td align='center'>0<input type='hidden' value='0' name="interespormora[]"></td>
    <td align='center'><?= number_format($cuotaIn = ($valorTotal*$porcentaje)+$intCorrienteCuotaCero) ?><input type='hidden' value='<?= $cuotaIn ?>' name="valorcuota[]"></td>
    <td align='center'><?= number_format($capital = $cuotaIn-$intCorrienteCuotaCero) ?><input type='hidden' value='<?= $capital ?>' name="aportecapital[]"></td>
    <td align='center'><?= number_format($saldofinal = $saldocapital - $capital) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>    
    <td align="right">
            <button id="<?= $cuotaIn ?>,<?= $date ?>,<?= $saldocapital ?>,<?= $saldofinal ?>,<?= 0 ?>,<?= $interesCorriente ?>,<?= $intCorrienteCuotaCero ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" at="<?= 0 ?>" id="generar">RECIBO</button>
   </td>
</tr>
<?php    
    for ($i=1;$i<=$ncuotas;$i++){
        $fechas = explode('/',$fechaPrimer);//interesCorriente        
    ?>    
    <tr>
        <td align='center'><?= $i ?><input type='hidden' value='<?= $i ?>' name="cuota[]"></td>
        <td align='center'><?php echo $date = date("d/m/Y", mktime(0, 0, 0, $fechas[1]+($i-1), $fechas[0],$fechas[2])); ?><input type='hidden' value='<?= $date ?>' name="fecha[]"></td>
        <td align='center'><?= number_format($saldoInicial = $saldofinal) ?><input type='hidden' value='<?= $saldoInicial ?>' name="saldocapital[]"></td>
        <td align='center'><?= number_format($interescalc) ?><input type='hidden' value='<?= $interescalc ?>' name="intCorriente[]"></td> 
        <td align='center'><?= number_format($intCorrienteCuota) ?><input type='hidden' value='<?= $intCorrienteCuota ?>' name="intCorrienteCuota[]"></td>
        <td align='center'><?= number_format($interespormora = $saldoInicial*$interes) ?><input type='hidden' value='<?= $interespormora ?>' name="interespormora[]"></td>
        <td align='center'><?= number_format($valorcuota = $cuotaInicial+$intCorrienteCuota) ?><input type='hidden' value='<?= $valorcuota = $cuotaInicial+$intCorrienteCuota ?>' name="valorcuota[]"></td>
        <td align='center'><?= number_format($aporte = $cuotaInicial - $interespormora) ?><input type='hidden' value='<?= $aporte ?>' name="aportecapital[]"></td>
        <td align='center'><?php 
        $saldofinal = $saldoInicial - ($valorcuota-$interespormora-$intCorrienteCuota);
        //$saldofinal = $saldoInicial - ($cuotaInicial - ($intCorrienteCuota + $interespormora));
        echo number_format(abs(ceil($saldofinal))) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>
        <td align="right">
            <button id="<?= $cuotaInicial ?>,<?= $date ?>,<?= $saldoInicial ?>,<?= $saldofinal ?>,<?= $i ?>,<?= $interespormora ?>,<?= $intCorrienteCuota ?>" type="button"  onclick="recibo(this.id)" class="generar btn btn-success" at="<?= 0 ?>" id="generar">RECIBO</button>
        </td>
    </tr>
<?php $interescalc = $interescalc - $intCorrienteCuota; } ?>
<script>
    $("#preloadminicuota").hide(); 
//    $(".generar" ).hide();
</script>
   