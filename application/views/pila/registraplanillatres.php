
<table align='center' border='2' style="border-color: #008000">
    <tr style="background: #008000; color: #ffffff">
        <td><b>Tipo Registro</b></td>
        <td><b>Tipo Concepto</b></td>
        <td><b>Valor</b></td>
        <td><b>Dias Mora</b></td>
        <td><b>Valor Mora</b></td>
        <td><b>Valor Total</b></td>
    </tr>
    <?php foreach($consulta->result_array as $consultatipotres):?>
    <tr>
        <td><?= $consultatipotres['TIPO_REGISTRO'] ?></td>
        <td>Aporte</td>
        <td><?= number_format($consultatipotres['APORTE_OBLIG']) ?></td>
        <td><?= number_format($consultatipotres['DIAS_MORA']) ?></td>
        <td><?= number_format($consultatipotres['MORA_APORTES']) ?></td>
        <td><?= number_format($consultatipotres['TOTAL_APORTES']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

