<div align="center"><h4>MINISTERIO DE TRABAJO</h4></div>
<div align="center"><h5>Servicio Nacional de Aprendizaje<br>SENA</h5></div>
<div align="center"><h4>CERTIFICA:</h4></div>
<div align="center">Que el contribuyente <?= $obra->result_array[0]['NOM_APORTANTE']?> con NIT <?= $obra->result_array[0]['N_INDENT_APORTANTE']?>, canceló por<br>
    concepto de Fondo Nacional de Formación Profesional de la Industria de la Construcción FIC con el<br>
    número de transacción <?= $obra->result_array[0]['COD_OPERADOR']?> el valor y de acuerdo a la siguiente información de pagos<br>
    realizados por el botón electronico de pagos:</div>
<br>
<table align="center" border="1" style="width: 800px; height: 100px;">
    <tr bgcolor="green" style="color: white" align="center">
        <td><b>No. Obra</b></td>
        <td><b>Nom. obra</b></td>
        <td><b>Empleados</b></td>
        <td><b>Valor Pago</b></td>
        <td><b>Periodos</b></td>
    </tr>
    <tr align="center">
        <td>xxxxxxx</td>
        <td>xxxxxxx</td>
        <td><?= $obra->result_array[0]['N_TOTAL_EMPLEADOS']?></td>
        <td>xxxxxxx</td>
        <td><?= $obra->result_array[0]['PERIDO_PAGO']?></td>
    </tr>
</table>
<br>
<br>
<div align="center">Expedido por el SENA, a los  <?= date('d') ?> días del mes  <?= date('m') ?> de  <?= date('Y') ?></div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div align="center"><h5>"LA EXPEDICIÓN DE ESTA CERTIFICACIÓN, NO IMPIDE QUE EL SENA VERIFIQUE LA BASE DE LIQUIDACION DE<br>
        FIC Y QUE CONSTATE EL CUMPLIMIENTO EN FONDO NACIONAL DE FORMACION PROFECIONAL DE LA INDUSTRIA DE LA CONSTRUCCION FIC"<br><br>
        NO TIENE VALIDEZ PARA FINES TRIBUTARIOS</h5><br><h4>SENA, MÁS TRABAJO</h4></div>
