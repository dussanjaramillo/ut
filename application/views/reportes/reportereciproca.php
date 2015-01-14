<div align="center"><h3>REPORTES RECIPROCAS</h3></div>
<table align="center" style="border: 3px solid;border-color: #777; width: 900px">
    <tr>
        <td>CONSTANTE</td>
        <td><input type="text" name="constante" id="constante"></td>
        <td>CODIGO DEL CONCEPTO DEL ARCHIVO</td>
        <td><input type="text" name="codigo" id="codigo"></td>
    </tr>
    <tr>
        <td>RANGO DE FECHAS</td>
        <td><input type="text" name="rango" id="rango"></td>
        <td>AÑO</td>
        <td><input type="text" name="anno" id="anno"></td>
    </tr>
    <tr>
        <td>CONCEPTO</td>
        <td><input type="text" name="concepto" id="concepto"></td>
    </tr>
</table>
<br>
<br>

<div align="center"><b>REPORTE DE RECIPROCAS</b></div>
<table id="estilotabla">
    <thead>
        <th>Constante</th>
        <th>Código</th>
        <th>NIT</th>
        <th>Valor Corriente</th>
        <th>Valor no Corriente</th>
    </thead>
    <tbody></tbody>
</table>
<script>
     $(document).ready(function() {
       $('#estilotabla').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
            }
        });
        });
    
</script>