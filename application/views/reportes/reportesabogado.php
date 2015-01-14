<div align="center"><h2>REPORTE ABOGADOS</h2></div>
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
        <td><button name="generar" id="generarabo" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalabo" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Estado</th>
            <th>Vía Gubernativa</th>
            <th>Ciudad</th>
            <th>Codigo CIIU</th>
            <th>Descripción Actividad</th>
            <th>Fecha de Liquidación</th>
            <th>Nombre del Fiscalizador</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtrosabo" style="display: none;">
    <table align="center" style="border: 3px solid; width: 900px; height: 100px;">
        <tr align="center">
            <td colspan="4"><h4>FILTRO A APLICAR</h4></td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio1" id="regionalciu" value="1">Regional</td>
            <td><input type="radio" name="radio1" class="radio1" id="razonciu" value="2">Razón Social</td>
            <td><input type="radio" name="radio1" class="radio1" id="conceptociu" value="3">Concepto</td>
            <td><input type="radio" name="radio1" class="radio1" id="estadociu" value="4">Estado</td>
            <td><input type="radio" name="radio1" class="radio1" id="viaguber" value="5">Vía Gubernativa</td>
        </tr>
        <tr>
            <td><input type="radio" name="radio1" class="radio1" id="cuidadciu" value="6">Ciudad</td>
            <td><input type="radio" name="radio1" class="radio1" id="ciu" value="7">Codigo CIIU</td>
            <td><input type="radio" name="radio1" class="radio1" id="fechaciu" value="8">Fecha de Fiscalización</td>
            <td><input type="radio" name="radio1" class="radio1" id="nombreciu" value="9">Nombre del Fiscalizador</td>
        </tr>
    </table>
    <br>
    <table align="center" style="border: 3px solid; widtd: 900px; height: 100px;">
        <tr align="center">
            <td colspan="4"><h4>CAMPOS A MOSTRAR</h4></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="regionalciu1" value="1">Regional</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="nitciu" value="2">Nit</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="razonciu1" value="3">Razon Social</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="conceptociu1" value="4">Concepto</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="estadociu1" value="5">Estado</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="ciudadciu" value="6">Ciudad</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="codigociu" value="7">Codigo CIIU</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="descripcionciu" value="8">Descripción Actividad</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="fechaciu1" value="9">Fecha de Fiscalización</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="radio2" class="radio2" id="nombreciu1" value="10">Nombre del Fiscalizador</td>
            <td><input type="checkbox" name="radio2" class="radio2" id="viagube" value="11">Vía Gubernativa</td>
         </tr>
    </table>
    <br>
    <table align="center">
        <tr>
            <td><button id="generaraboga" class="btn btn-success">Generar</button></td>
        </tr>
    </table>
</div>
<br>
<br>
<div id="botonfiltro" style="display: none;">
    <table align="center">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regionalabo">-Seleccionar-</option>
                     <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td><button id="reporteabo" class="btn btn-success">Reporte</button></td>
        </tr>
    </table>
</div>
<br>
<br>
<div id="tablafiltros" style="display: none;">
    <table id="tabla">
        <thead>
            <th>Regional</th>
            <th>NIT</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Estado</th>
            <th>Vía Gubernativa</th>
            <th>Ciudad</th>
            <th>Codigo CIIU</th>
            <th>Descripción Actividad</th>
            <th>Fecha de Liquidación</th>
            <th>Nombre del Fiscalizador</th>
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
    $(document).ready(function() {            
    $('#tabla').dataTable({
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
    
    
    $('#generarabo').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalabo').css('display','block');
            $('#filtrosabo').css('display','none');
            $('#botonfiltro').css('display','none');
            $('#tablafiltros').css('display','none');
       }
       if(opcion == 2){
           $('#filtrosabo').css('display','block');
           $('#generalabo').css('display','none');
           $('#tablafiltros').css('display','none');
           $('#botonfiltro').css('display','none');
       }
    });
    $('#generaraboga').click(function(){
       $('#botonfiltro').css('display','block'); 
        $('#filtrosabo').css('display','none');
    });
    $('#reporteabo').click(function(){
       $('#tablafiltros').css('display','block');  
    });
</script>