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
    <td align='center'><input type='hidden' value=' ' name="interespormora[]"></td>
    <td align='center'><?= number_format($cuotaIn = $valorTotal*0.30) ?><input type='hidden' value='<?= $cuotaIn ?>' name="valorcuota[]"></td>
    <td align='center'><?= number_format($saldofinal = $saldocapital - $cuotaIn) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>
</tr>
<?php    
    for ($i=1;$i<=$ncuotas;$i++){
        $fechas = explode('/',$fechaPrimer);
    ?>    
    <tr>
        <td align='center'><?= $i ?><input type='hidden' value='<?= $i ?>' name="cuota[]"></td>
        <td align='center'><?php echo $date = date("d/m/Y", mktime(0, 0, 0, $fechas[1]+($i-1), $fechas[0],$fechas[2])); ?><input type='hidden' value='<?= $date ?>' name="fecha[]"></td>
        <td align='center'><?= number_format($saldoInicial = $saldofinal) ?><input type='hidden' value='<?= $saldoInicial ?>' name="saldocapital[]"></td>
        <td align='center'><?= number_format($interespormora = $saldoInicial*$interes) ?><input type='hidden' value='<?= $interespormora ?>' name="interespormora[]"></td>
        <td align='center'><?= number_format($cuotaInicial) ?><input type='hidden' value='<?= $cuotaInicial ?>' name="valorcuota[]"></td>
        <td align='center'><?= number_format($saldofinal = $saldoInicial - ($cuotaInicial-$interespormora)) ?><input type='hidden' value='<?= $saldofinal ?>' name="saldofinal[]"></td>
    </tr>
<?php } ?>
<script>
   $("#preloadminicuota").hide();  
        $(".generar" ).on( "click", function() {
            $(".generar" ).hide(); 
            $("#preloadminicuota").show(); 
            var saldocapital = $(this).attr('saldocapital');
            var saldofinal = $(this).attr('saldofinal');
            var cuota = $(this).attr('cuota');
            var intereses = $(this).attr('intereses');
            var fecha = $(this).attr('fecha');
            var valorcuota = $(this).attr('valorcuota');
            var nit = "<?= $nit ?>";
            var razonsocial = "<?= $razonsocial ?>";
            var cuota = $(this).attr('at');
            var url = "<?= base_url('index.php/acuerdodepago/generarrecibodepagocuotainicial') ?>";
            $('#load').load(url,{nit : nit,
                razonsocial : razonsocial, 
                valorcuota : valorcuota,
                fecha : fecha, 
                saldocapital : saldocapital,
                saldofinal : saldofinal,
                cuota : cuota,
                intereses : intereses},function(data){                            
                 $(".preloadminicuota").hide();
                 $(".generar" ).show();
                });
        });
</script>
   