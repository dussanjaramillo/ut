
<!--<tr>
    <td align="right">0<input type='hidden' value='<?= 0 ?>' name="cuota[]"></td>
    <td align="right"><?= $finicial ?><input type='hidden' value='<?= $finicial ?>' name="fecha[]"></td>
    <td align="right"><?= number_format($saldocapital)  ?> <input type='hidden' value='<?= $saldocapital ?>' name="saldocapital[]"></td>
    <td align='center'>0<input type='hidden' value='0' name="intCorriente[]"></td>
    <td align='center'>0<input type='hidden' value='0' name="intCorrienteCuota[]"></td>
    <td align="right">0<input type='hidden' value='0' name="interespormora[]"></td>
    <td align="right"><?= number_format($vinicuota,0) ?><input type='hidden' value='<?= $vinicuota ?>' name="valorcuota[]"></td>
    <td align='center'><?= number_format ($vinicuota,0) ?><input type='hidden' value='<?= $vinicuota ?>' name="aportecapital[]"></td>
    <td align="right"><?php $saldofinal = $saldocapital-$vinicuota; echo number_format($saldofinal,0) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>
</tr>-->

<?php 
if($juridico == 2)$saldofinal = $saldocapital;
for ($i = 1; $i<=$ncuotas ; $i++){ ?>
<?php $fechas = explode('/',$fpcuota); ?>
<tr>
    <td align="right"><?= $i ?><input type='hidden' value='<?= $i ?>' name="cuota[]"></td>    
    <td align="right"><?php echo $date = date("d/m/Y", mktime(0, 0, 0, $fechas[1]+($i-1), $fechas[0],$fechas[2])); ?><input type='hidden' value='<?= $date ?>' name="fecha[]"></td>
    <td align="right"><?php $saldocapital = $saldofinal; echo number_format($saldocapital) ?><input type='hidden' value='<?= $saldocapital ?>' name="saldocapital[]"></td>   
    <td align='center'><?php echo number_format(0)  ?><input type='hidden' value='0' name="intCorriente[]"></td>
    <td align='center'><?php echo number_format(0)  ?><input type='hidden' value='0' name="intCorrienteCuota[]"></td>
    <td align="right"><?php $interespormora = $saldocapital*$tasa ; echo number_format($interespormora,0)  ?><input type='hidden' value='<?= $interespormora ?>' name="interespormora[]"></td>
    <td align="right"><?php echo number_format($valorcuota)  ?><input type='hidden' value='<?= $valorcuota ?>' name="valorcuota[]"></td>
    <td align='center'><?= number_format($aporte = $valorcuota - $interespormora) ?><input type='hidden' value='<?= $aporte ?>' name="aportecapital[]"></td>
    <td align="right"><?php 
    $saldofinal = $saldocapital-($valorcuota-$interespormora) ; 
    echo number_format(abs(floor($saldofinal)))?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"> </td>
    <td align="right"></td>
</tr>    
<?php  } ?>

<script>

</script>
   