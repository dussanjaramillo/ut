<table align='center' style="border : 1px solid">
    <tr>
        <td>Nombre del Archivo</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="archivo" id="archivo1"></td>
    </tr>
    <tr>
        <td>Fecha de Recaudo</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="recaudo" id="recaudo" </td>
    </tr>
    <tr>
        <td>Fecha de Cargue de Datos</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="cargue" id="cargue" </td>
    </tr>
    <tr>
        <td>Banco</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="banco" id="banco" </td>
    </tr>
</table>
<br>
<table align='center' style="border : 1px solid">
    <tr>
        <td>Planilla</td>
        <td><?= $consulta->result_array[0]['N_RADICACION']?></td>
    </tr>
    <tr>
        <td>ID Aportante</td>
        <td><?= $consulta->result_array[0]['N_INDENT_APORTANTE']?></td>
    </tr>
    <tr>
        <td>Nombre</td>
        <td><?= $consulta->result_array[0]['NOM_APORTANTE']?> </td>
    </tr>
    <tr>
        <td>Valor</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="valor" id="valor" </td>
    </tr>
    <tr>
        <td>Codigo Banco</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="codigo" id="codigobanco" </td>
    </tr>
    <tr>
        <td>Registros</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="registros" id="registros" </td>
    </tr>
    <tr>
        <td>Secuencia</td>
        <td> </td>
    </tr>
    <tr>
        <td>Hora</td>
        <td><input type="text" readonly="readonly" disabled="disabled" name="hora" id="hora" </td>
    </tr>
</table>


