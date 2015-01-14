<div align="center"><h2>REPORTE CARTERA PRESUNTA Y REAL</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" id="general1" value="1" class="radio">General</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="filtros1" value="2" class="radio">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generar3" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="general3" style="display: none;">
    <table align="center">
        <tr>
            <td><h3>REPORTE CARTERA PRESUNTA Y REAL</h3></td>
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
            <th>Valor Resolución</th>
            <th>Saldo Resolución</th>
            <th>Pagos/Abono</th>
            <th>Valor Inicial Liquidación</th>
            <th>Estado Liquidación</th>
            <th>Periodo</th>
            <th>Liquidación</th>
            <th>Vigencia</th>
            <th>Fecha de Corte</th>
            <th>Fecha de Pago</th>
            <th>Responsable</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<br>
<br>
<div id="filtro2" style="display: none;">
    <table align="center" style="border: 1px solid; width:  2000px;height: 300px">
        <tr align="center">
            <td colspan="6"><h3>FILTROS A APLICAR</h3></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td><select><option id="regional2">-Seleccionar-</option></select></td>
            <td>Razón Social</td>
            <td><select><option id="razon2">-Seleccionar-</option></select></td>
            <td>Ciudad</td>
            <td><select><option id="cuidad2">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Periodos</td>
            <td><select><option id="periodos2">-Seleccionar-</option></select></td>
            <td>Vigencias</td>
            <td><select><option id="vigencias2">-Seleccionar-</option></select></td>
            <td>Concepto</td>
            <td><select><option id="concepto2">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Instancia</td>
            <td><select><option id="instancia1">-Seleccionar-</option></select></td>
            <td>Número Resolución</td>
            <td><select><option id="resolucion1">-Seleccionar-</option></select></td>
            <td>Responsable</td>
            <td><select><option id="responsable1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td><b>Fecha Inicio</b></td>
            <td><input type="date" name="fechaini2" id="fechaini2"></td>
            <td><b>Fecha Fin</b></td>
            <td><input type="date" name="fechafin3" id="fechafin3"></td>
        </tr>  
        <tr>
            <td><b>Fecha Resolución</b></td>
            <td><input type="date" name="fechares1" id="fechares1"></td>
            <td><b>Fecha Ejecutoria</b></td>
            <td><input type="date" name="fechaeje1" id="fechaeje1"></td>
        </tr>  
        <tr>
            <td colspan="2"><input type="checkbox" name="pendientes" id="pendientes1">Liquidaciones pendientes por pago</td>
            <td colspan="2"><input type="checkbox" name="gestion" id="gestion1">Liquidaciones pendientes por gestión(Más de 30 días)</td>
         </tr>    
    </table>
    <br>
    <br>
    <br>
    <table align="center">
        <tr>
            <td><button name="reporte" id="reporte" class="btn btn-success">Reporte</button></td>
        </tr>
    </table>
    </div>
    <br>
    <br>
   <div id="filtrotabla" style="display: none;">
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
            <th>Valor Resolución</th>
            <th>Saldo Resolución</th>
            <th>Pagos/Abono</th>
            <th>Valor Inicial Liquidación</th>
            <th>Estado Liquidación</th>
            <th>Periodo</th>
            <th>Liquidación</th>
            <th>Vigencia</th>
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
    
    $('#generar3').click(function(){
       var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#general3').css('display','block');
            $('#filtro2').css('display','none');
            $('#filtrotabla').css('display','none');
       }
       if(opcion == 2){
           $('#filtro2').css('display','block');
           $('#general3').css('display','none');
       } 
    });
    $('#reporte').click(function(){
             $('#filtrotabla').css('display','block');
         
       
    });
    
    $("#fechaini2").datepicker();
    $("#fechafin3").datepicker();
    $("#fechares1").datepicker();
    $("#fechaeje1").datepicker();
</script>

