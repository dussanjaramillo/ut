<div align="center"><h2>REPORTE DE EDAD DE CARTERA</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="generaledad" value="1">General</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtrosedad" value="2">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generaredad" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generale" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Codigo Regional</th>
            <th>Nombre Regional</th>
            <th>Identificación</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Número Resolución</th>
            <th>Fecha Resolución</th>
            <th>Fecha Ejecutoria</th>
            <th>Instancia</th>
            <th>Valor Capital Inicial Resolución</th>
            <th>Valor Intereses Inicial Resolución</th>
            <th>Valor Capital Vencida 0.30</th>
            <th>Valor Intereses Vencida 0.30</th>
            <th>Valor Capital Vencida 31.60</th>
            <th>Valor Intereses Vencida 31.60</th>
            <th>Valor Capital Vencida 61.90</th>
            <th>Valor Intereses Vencida 61.90</th>
            <th>Valor Capital Vencida >90</th>
            <th>Valor Intereses Vencida >90</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtrosed" style="display: none;">
    <table align="center" style="border: 1px solid">
        <tr>
            <td>Cartera Misional</td>
            <td><select><option id="cartera">-Seleccionar-</option></td>
            <td>Cartera no Misional</td>
            <td><select><option id="misional">-Seleccionar-</option></td>
        </tr>
        <tr>
            <td>No. Documento Indentificación</td>
            <td><select><option id="doc">-Seleccionar-</option></td>
            <td>Razón Social</td>
            <td><select><option id="razon">-Seleccionar-</option></td>
        </tr>
        <tr>
            <td>Codigo Regional</td>
            <td><select><option id="cod">-Seleccionar-</option></td>
            <td>Nombre Regional</td>
            <td><select><option id="nombre">-Seleccionar-</option></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini" id="fechaini1"></td>
            <td>Fecha Fin</td>
            <td><input type="date" name="fechaini" id="fechaini2"></td>
        </tr>
    </table>
    <br>
    <br>
    <table align="center">
    <tr>
        <td><button name="generar" id="generared" class="btn btn-success">Generar</button></td>
    </tr>
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
        
    
    $('.radio').click(function(){
       var radio = $(this).val();
     $('#opcion').val(radio);       
    });
    
    
    $('#generaredad').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generale').css('display','block');
            $('#filtrosed').css('display','none');
       }
       if(opcion == 2){
           $('#filtrosed').css('display','block');
           $('#generale').css('display','none');
       }
    });
    
  $("#fechaini1").datepicker();
 $("#fechafin2").datepicker();
</script>

