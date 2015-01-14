<div align="center"><h2>REPORTE ACUERDOS DE PAGO EN PERSUASIVO</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" id="general2" value="1" class="radio">General</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" id="filtros2" value="2" class="radio">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generar4" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="general4" style="display: none;">
    <table align="center">
        <tr>
            <td><h3>REPORTE ACUERDOS DE PAGO EN PERSUASIVO</h3></td>
        </tr>
    </table>
    <table id="estilotabla">
        <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Estado</th>
            <th>Ciudad</th>
            <th>Número de Acuerdo de Pago</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Interes Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente Total</th>
            <th>Fecha de Liquidación</th>
            <th>Periodo</th>
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
<div id="filtro3" style="display: none;">
    <table align="center" style="border: 1px solid; width:  2000px;height: 300px">
        <tr align="center">
            <td colspan="6"><h3>FILTROS A APLICAR</h3></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td><select><option id="regional3">-Seleccionar-</option></select></td>
            <td>Razón Social</td>
            <td><select><option id="razon3">-Seleccionar-</option></select></td>
            <td>Ciudad</td>
            <td><select><option id="cuidad3">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><select><option id="estado1">-Seleccionar-</option></select></td>
            <td>Responsable</td>
            <td><select><option id="responsable">-Seleccionar-</option></select></td>
            <td>Concepto</td>
            <td><select><option id="concepto3">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>No. Acuerdo de Pago</td>
            <td><select><option id="acuerdo1">-Seleccionar-</option></select></td>
            <td>Fecha Acuerdo de Pago</td>
            <td><input type="date" name="fechapago" id="fechapago"></td>
        </tr>
        <tr>
            <td><b>Fecha Inicio</b></td>
            <td><input type="date" name="fechaini3" id="fechaini3"></td>
            <td><b>Fecha Fin</b></td>
            <td><input type="date" name="fechafin4" id="fechafin4"></td>
        </tr>  
     </table>
    <br>
    <br>
    <table align="center">
        <tr>
            <td><button name="reporte" id="generarr" class="btn btn-success">Generar</button></td>
        </tr>
    </table>
</div>
    <br>
    <div id="filtro4" style="display: none;">
        <table id="estilotabla1">
       <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Estado</th>
            <th>Ciudad</th>
            <th>Número de Acuerdo de Pago</th>
            <th>Valor Capital Inicial</th>
            <th>Valor Interes Inicial</th>
            <th>Saldo Pendiente Capital</th>
            <th>Saldo Pendiente Intereses</th>
            <th>Saldo Pendiente Total</th>
            <th>Fecha de Liquidación</th>
            <th>Periodo</th>
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
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }
                });  
                });  
    $('#estilotabla1').dataTable({
                    "sDom": 'T<"clear">lfrtip',
                    "oTableTools": {
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }
                });              
    
    $('.radio').click(function(){
       var radio = $(this).val();
       $('#opcion').val(radio);
    });
    
    $('#generar4').click(function(){
       var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#general4').css('display','block');
            $('#filtro3').css('display','none');
             $('#filtro4').css('display','none');
       }
       if(opcion == 2){
           $('#filtro3').css('display','block');
           $('#general4').css('display','none');
       } 
    });
    
    $('#generarr').click(function(){
            $('#filtro4').css('display','block');
       });
    
    $("#fechapago").datepicker();
    $("#fechaini3").datepicker();
    $("#fechafin4").datepicker();
    
</script>

