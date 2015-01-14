<div align="center"><h2>RECLASIFICACIÓN INGRESOS</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="generalcon" value="1">General</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtroscon" value="2">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarrecla" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<br>
<div id="generalrecla" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
            <td>Banco</td>
            <td><select><option id="banco">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini" id="fechaini"></td>
            <td>Fecha Fin</td>
            <td><input type="date" name="fechafin" id="fechaini"></td>
        </tr>
    </table>
     <br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarreclasi" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<br>
<br>
<div id="tablageneralre" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Razón Social</th>
            <th>NIT</th>
            <th>Fecha Pago</th>
            <th>Concepto Inicial</th>
            <th>Banco</th>
            <th>Concepto Final</th>
            <th>Fecha Reclasificación</th>
            <th>Observaciones</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtrarrecla" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Concepto</td>
            <td><select><option id="concepto1">-Seleccionar-</option></select></td>
            <td>Banco</td>
            <td><select><option id="banco1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini1" id="fechaini1"></td>
            <td>Fecha Fin</td>
            <td><input type="date" name="fechafin1" id="fechaini1"></td>
        </tr>
    </table>
     <br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarreclasi" class="btn btn-success">Generar</button></td>
    </tr>
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
    
    $('.radio').click(function(){
       var radio = $(this).val();
     $('#opcion').val(radio);       
    });
    
    
    $('#generarrecla').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalrecla').css('display','block');
            $('#filtrarrecla').css('display','none');
             $('#tablageneralre').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarrecla').css('display','block');
           $('#generalrecla').css('display','none');
            $('#tablageneralre').css('display','none');
       }
    });
     $('#generarreclasi').click(function(){
         $('#tablageneralre').css('display','block');
     });
    
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
 $("#fechaini1").datepicker();
 $("#fechafin1").datepicker();
</script>