<div align="center"><h2>GESTIÓN DE RESULTADOS</h2></div>
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
        <td><button name="generar" id="generarempre" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalempresa" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Razón Social</td>
            <td colspan="3"><input type="text" style=" width: 555px" readonly="readonly"  disabled="disabled" name="razon" id="razon"></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regional">-Seleccionar-</option>
                     <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Tipo</td>
            <td><select><option id="tipo">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini" id="fechaini"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafin" id="fechafin"></td>
        </tr>
    </table>
    <br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarempresa" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<br>
<br>
<div id="tablageneralem" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Instancia</th>
            <th>Fiscalizador</th>
            <th>Abogado</th>
            <th>Acciones</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<div id="filtrarempresa" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Razón Social</td>
            <td colspan="3"><input type="text" style=" width: 555px" readonly="readonly"  disabled="disabled" name="razon" id="razon"></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regional1">-Seleccionar-</option>
                     <?php foreach ($regional1->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
            </td>
            <td>Concepto</td>
            <td><select><option id="concepto1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Tipo</td>
            <td><select><option id="tipo1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini1" id="fechaini"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafin1" id="fechafin"></td>
        </tr>
    </table>
    <br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarfiltro" class="btn btn-success">Generar</button></td>
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
    
    
    $('#generarempre').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalempresa').css('display','block');
            $('#filtrarempresa').css('display','none');
            $('#tablageneralem').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarempresa').css('display','block');
           $('#generalempresa').css('display','none');
           $('#tablageneralem').css('display','none');
       }
    });
    $('#generarempresa').click(function(){
        $('#tablageneralem').css('display','block'); 
    });
  
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
 $("#fechaini1").datepicker();
 $("#fechafin1").datepicker();
</script>
