<div align="center"><h2>PROCESOS SANCIONATORIOS A CARGOS DE ABOGADO</h2></div>
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
        <td><button name="generar" id="generarinter" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalinter" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Abogado</th>
            <th>Regional</th>
            <th>Parte del Proceso</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Valor del Proceso</th>
            <th>Número del Estado Cuenta</th>
        </thead>
        <tbody></tbody>
    </table>
</table>
</div>
<div id="filtrosinter" style="display: none;">
    <table align="center" style="border: 3px solid; width: 900px; height: 50px;">
        <tr align="center">
            <td colspan="4"><h4>FILTRO A APLICAR</h4></td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio1" id="abogado" value="1">Abogado</td>
            <td><input type="radio" name="radio1" class="radio1" id="regionalinter" value="2">Regional</td>
            <td><input type="radio" name="radio1" class="radio1" id="inicio" value="3">Inicio del Proceso</td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio1" id="razoninter" value="5">Razón Social</td>
            <td><input type="radio" name="radio1" class="radio1" id="nitinter" value="6">NIT</td>
        </tr>
    </table>
    <br>
    <table align="center" style="border: 3px solid; width: 900px; height: 50px;">
        <tr align="center">
            <td colspan="4"><h4>CAMPOS A MOSTRAR</h4></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="abogadointer" value="1">Abogado</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="regionalinter1" value="2">Regional</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="parte" value="3">Parte del Proceso</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="iniciointer" value="4">Inicio del Proceso</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="nitinter1" value="5">NIT</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="razoninter1" value="6">Razón Social</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="valorinter" value="7">Valor del Proceso</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="numerointer" value="8">Número del Estado Cuenta</td>
        </tr>      
    </table>
    <br>
    <table align="center">
        <tr>
            <td><button id="generarinter1" class="btn btn-success">Generar</button></td>
        </tr>
    </table>
    <br>
    <br>
    <table align="center">
        <tr>
            <td>Regional</td>
            <td><select>
                    <option id="regionalinter2">-Seleccionar-</option>
                    <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td><button id="reporteinter" class="btn btn-success">Reporte</button></td>
        </tr>
    </table>
    <br>
    <br>
    <table id="estilotabla1">
        <thead>
            <th>Abogado</th>
            <th>Regional</th>
            <th>Parte del Proceso</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Valor del Proceso</th>
            <th>Número del Estado Cuenta</th>
    </thead>
        <tbody></tbody>
    </table>
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
    
    
    $('#generarinter').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalinter').css('display','block');
            $('#filtrosinter').css('display','none');
       }
       if(opcion == 2){
           $('#filtrosinter').css('display','block');
           $('#generalinter').css('display','none');
       }
    });
</script>