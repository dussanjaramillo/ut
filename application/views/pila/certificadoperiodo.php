<div align="center"><h4>MINISTERIO DE TRABAJO</h4></div>
<div align="center"><h5>Servicio Nacional de Aprendizaje<br>SENA</h5></div>
<div align="center"><h4>CERTIFICA:</h4></div>
<div align="center">Que el contribuyente <?= $periodo->result_array[0]['NOM_APORTANTE']?><br>con NIT <?= $periodo->result_array[0]['N_INDENT_APORTANTE']?>, canceló por concepto de 
        Fondo Nacional de Formacion Profesional de la<br>Industria de la Construccion FIC, el valor
        y de acuerdo a la siguiente información de pagos<br>realizados por el botón electronico de Pagos:</div>
<br>
<table align="center" border="1" style="width: 600px; height: 200px;">
    <tr>
        <td align="center" bgcolor="green" style="color: white" ><b>Obra:</b></td>
        <td align="center">xxxxxxxxxxxxx</td>
    </tr>
    <tr bgcolor="green" style="color: white" align="center">
        <td><b>Empleados</b></td>
        <td><b>Pago</b></td>
        <td><b>Transacción</b></td>
        <td><b>Periodo</b></td>
    </tr>
    <tr align="center">
        <td><?= $periodo->result_array[0]['N_TOTAL_EMPLEADOS']?></td>
        <td>xxxxxxxxxxxxx</td>
        <td>xxxxxxxxxxxxxx</td>
        <td><?= $periodo->result_array[0]['PERIDO_PAGO']?></td>
    </tr>
    <tr bgcolor="green" style="color: white" align="center">
        <td><b>Empleados</b></td>
        <td><b>Pago</b></td>
        <td><b>Transacción</b></td>
        <td><b>Periodo</b></td>
    </tr>
    <tr align="center">
        <td>xxxxxxxxxxxxxx</td>
        <td>xxxxxxxxxxxxx</td>
        <td>xxxxxxxxxxxxxx</td>
        <td>xxxxxxxxxxxxxx</td>
    </tr>
    <tr bgcolor="green" style="color: white" align="center">
        <td colspan="2"><b>Total Trabajadores: xxx</b></td>
        <td colspan="2"><b>Total Pago: xxxxxxx</b></td>
    </tr>
</table>
<br>
<div align="center">Expedido por el SENA, a los <?= date('d')?> días del mes <?= date('m')?> de <?= date('Y')?></div>
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
