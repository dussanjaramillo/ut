<table align="center" style="border: 1px solid; width: 900px; height: 60px">
    <tr>
        <td>NIT</td>
        <td><input type="text" name="nit" id="nit"></td>
        <td>No. Contrato</td>
        <td><input type="text" name="contrato" id="contrato"></td>
        <td><input type="checkbox" name="radio" class="radio" id="fiscalizacion" value="1">Fiscalización</td>
    </tr>
    <tr>
        <td>Razón Social</td>
        <td><input type="text" name="nit" id="razon"></td>
        <td>Regional</td>
        <td><input type="text" name="contrato" id="regional"></td>
        <td><input type="checkbox" name="radio" class="radio" id="liquidacion" value="1">Liquidación por Contrato</td>
    </tr>
    <tr>
        <td>Fecha Inicio</td>
        <td><input type="date" name="fechaini" id="fechaini"></td>
        <td>Fecha Fin</td>
        <td><input type="date" name="fechafin" id="fechaini"></td>
        <td><button name="buscar" id="buscar" class="btn btn-success">Buscar</button></td>
    </tr>
</table>
<br>
<br>
<br>
<div id="tablacontrato" style="display: none;">
    <table id="estilotabla">
    <thead>
        <th>No Contrato</th>
        <th>NIT</th>
        <th>Razón Social</th>
        <th>Regional</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Valor Contrato</th>
        <th>Valor a Pagar FIC</th>
        <th>Intereses</th>
        <th>Origen</th>
    </thead>
    <tbody></tbody>
</table>
</div>
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
        
   $('#buscar').click(function(){
       $('#tablacontrato').css('display','block');
   });
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
</script>