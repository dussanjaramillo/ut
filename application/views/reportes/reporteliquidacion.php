<div align="center"><h2>REPORTE LIQUIDACIÓN</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="estandar" value="1">Estandar</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtros" value="2">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generar" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="estandar1" style="display: none;">
    <table align="center">
        <tr>
            <td><h3>REPORTE LIQUIDACIÓN</h3></td>
        </tr>
    </table>
    <table id="estilotabla">
        <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>Número Liquidación</th>
            <th>Concepto</th>
            <th>Ciudad</th>
            <th>Nit</th>
            <th>Razón Social</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Interes Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente Total</th>
            <th>Estado</th>
            <th>Periodo</th>
            <th>Vigencia</th>
            <th>Fecha de Liquidación</th>
            <th>Fecha de Corte</th>
            <th>Fecha de Pago</th>
            <th>Responsable</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtro" style="display: none;">
    <table align="center" style="border: 1px solid; width:  1000px;height: 300px">
        <tr align="center">
            <td colspan="6"><h3>FILTROS A APLICAR</h3></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td><select><option id="regional">-Seleccionar-</option></select></td>
            <td>Razón Social</td>
            <td><select><option id="razon">-Seleccionar-</option></select></td>
            <td>Ciudad</td>
            <td><select><option id="cuidad">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Periodos</td>
            <td><select><option id="periodos">-Seleccionar-</option></select></td>
            <td>Vigencias</td>
            <td><select><option id="vigencias">-Seleccionar-</option></select></td>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td colspan="5"><select><option id="estado">-Seleccionar-</option></select></td>
        </tr>    
        <tr>
            <td><b>Fecha Inicio</b></td>
            <td><input type="date" name="fechaini" id="fechaini"></td>
            <td><b>Fecha Fin</b></td>
            <td><input type="date" name="fechafin" id="fechafin"></td>
        </tr>  
        <tr>
            <td colspan="2"><input type="checkbox" name="pendientes" id="pendientes">Liquidaciones pendientes por pago</td>
            <td colspan="2"><input type="checkbox" name="gestion" id="gestion">Liquidaciones pendientes por gestión(Más de 30 días)</td>
         </tr>    
    </table>
    <br>
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
            <th>Número Liquidación</th>
            <th>Concepto</th>
            <th>Ciudad</th>
            <th>Nit</th>
            <th>Razón Social</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Interes Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente Total</th>
            <th>Estado</th>
            <th>Periodo</th>
            <th>Vigencia</th>
            <th>Fecha de Liquidación</th>
            <th>Fecha de Corte</th>
            <th>Fecha de Pago</th>
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
    
    
    $('#generar').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#estandar1').css('display','block');
            $('#filtro').css('display','none');
       }
       if(opcion == 2){
           $('#filtro').css('display','block');
           $('#estandar1').css('display','none');
       }
    });
    
    $("#fechaini").datepicker();
    $("#fechafin").datepicker();
</script>