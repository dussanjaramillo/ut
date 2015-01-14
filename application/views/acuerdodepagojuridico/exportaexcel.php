
<table id="tableExcel" class="table table-striped">
    <thead>
    <th>PERIODO</th>
    <th>FECHA</th>
    <th>SALDO A CAPITAL</th>
    <th>INTERESES</th>
    <th>VALOR CUOTA</th>
    <th>APORTE A CAPITAL</th>
    <th>SALDO FINAL</th>
    </thead>
<?php    

    for ($i=1;$i<=$cuotas;$i++){
    ?>    
    <tr>
        <td align='center'><?= @$cuota[$i] ?></td>
        <td align='center'><?= @$fecha[$i] ?></td>
        <td align='center'><?= @number_format($saldocapital[$i]) ?></td>
        <td align='center'><?= @number_format($interespormora[$i]) ?></td>
        <td align='center'><?= @number_format($valorcuota[$i]) ?></td>
        <td align='center'><?= @number_format($valorcuota[$i]-$interespormora[$i]) ?></td>
        <td align='center'><?= @number_format($saldofinal[$i]) ?></td>        
    </tr>
<?php } ?>
</table>