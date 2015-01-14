<div align="center"><h2>REPORTE RESOLUCIÓN</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" id="general" value="1" class="radio">General</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="filtros" value="2" class="radio">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generar2" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="general2" style="display: none;" style="border: 1px solid">
  <table align="center">
        <tr>
            <td><h3>REPORTE RESOLUCIÓN</h3></td>
        </tr>
    </table>
    <table id="estilotabla">
        <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Instancia</th>
            <th>Ciudad</th>
            <th>Número de Resolución</th>
            <th>Fecha de Resolución</th>
            <th>Fecha Ejecutoria</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Intereses Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente en Total</th>
            <th>Pagos/Abono</th>
            <th>Responsable</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtro1" style="display: none;">
    <table align="center" style="border: 1px solid; width:  2000px;height: 300px">
        <tr align="center">
            <td colspan="6"><h3>FILTROS A APLICAR</h3></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td><select><option id="regional1">-Seleccionar-</option></select></td>
            <td>Razón Social</td>
            <td><select><option id="razon1">-Seleccionar-</option></select></td>
            <td>Ciudad</td>
            <td><select><option id="cuidad1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Periodos</td>
            <td><select><option id="periodos1">-Seleccionar-</option></select></td>
            <td>Vigencias</td>
            <td><select><option id="vigencias1">-Seleccionar-</option></select></td>
            <td>Concepto</td>
            <td><select><option id="concepto1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Instancia</td>
            <td><select><option id="instancia">-Seleccionar-</option></select></td>
            <td>Número Resolución</td>
            <td><select><option id="resolucion">-Seleccionar-</option></select></td>
            <td>Responsable</td>
            <td><select><option id="responsable">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td colspan="5"><select><option id="estado">-Seleccionar-</option></select></td>
        </tr>    
        <tr>
            <td><b>Fecha Inicio</b></td>
            <td><input type="date" name="fechaini1" id="fechaini1"></td>
            <td><b>Fecha Fin</b></td>
            <td><input type="date" name="fechafin2" id="fechafin2"></td>
        </tr>  
        <tr>
            <td><b>Fecha Resolución</b></td>
            <td><input type="date" name="fechares" id="fechares"></td>
            <td><b>Fecha Ejecutoria</b></td>
            <td><input type="date" name="fechaeje" id="fechaeje"></td>
        </tr>  
        <tr>
            <td colspan="2"><input type="checkbox" name="pendientes" id="pendientes2">En tramite de remisibilidad</td>
        </tr>    
    </table>
    <br>
    <table align="center">
        <tr>
            <td><button name="reporte" id="reporte" class="btn btn-success">Reporte</button></td>
        </tr>
    </table>
    <br>
    <br>
    <table id="estilotabla1">
        <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Instancia</th>
            <th>Ciudad</th>
            <th>Número de Resolución</th>
            <th>Fecha de Resolución</th>
            <th>Fecha Ejecutoria</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Intereses Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente en Total</th>
            <th>Pagos/Abono</th>
            <th>Responsable</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<input type="hidden" name="opcion" id="opcion">
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
     $(document).ready(function() {
       $('#estilotabla1').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
            }
        });
        });
        
    $('.radio').click(function(){
       var radio = $(this).val();
       $('#opcion').val(radio);
    });
    
    $('#generar2').click(function(){
       var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#general2').css('display','block');
            $('#filtro1').css('display','none');
       }
       if(opcion == 2){
           $('#filtro1').css('display','block');
           $('#general2').css('display','none');
       } 
    });
    
    $("#fechaini1").datepicker();
    $("#fechafin1").datepicker();
    $("#fechares").datepicker();
    $("#fechaeje").datepicker();
</script>

