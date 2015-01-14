<div align="center"><h2>CERTIFICACIONES</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="estandarc" value="1">Estandar</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtrosc" value="2">Filtros</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarc" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="estandarcer" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>Cod.Regional</th>
            <th>Regional</th>
            <th>Concepto</th>
            <th>Ciudad</th>
            <th>Nit</th>
            <th>Razón Social</th>
            <th>Valor</th>
            <th>Estado</th>
            <th>Fecha de Corte</th>
            <th>Fecha de Pago</th>
            <th>Responsable</th>
            <th>Nombre de Archivo</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtrocer" style="display: none;">
    <table align="center" style="border: 1px solid; width:  1000px;height: 300px">
        <tr align="center">
            <td colspan="6"><h3>FILTROS A APLICAR</h3></td>
        </tr>
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regionalcer">-Seleccionar-</option>
                    <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Razón Social</td>
            <td><select><option id="razoncer">-Seleccionar-</option></select></td>
            <td>Ciudad</td>
            <td>
                <select>
                    <option id="cuidadcer">-Seleccionar-</option>
                     <?php foreach ($ciudad->result_array as $ciudad){?>
                    <option value="<?= $ciudad['CODMUNICIPIO']?>"><?= $ciudad['NOMBREMUNICIPIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Tipo Certificación</td>
            <td><select><option id="vigencias">-Seleccionar-</option></select></td>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
        </tr>   
        <tr>
            <td><b>Fecha Inicio</b></td>
            <td><input type="date" name="fechaini" id="fechainicer"></td>
            <td><b>Fecha Fin</b></td>
            <td><input type="date" name="fechafin" id="fechafincer"></td>
        </tr>   
    </table>
    <br>
    <br>
    <table align="center">
        <tr>
            <td><button name="reporte" id="reportecer" class="btn btn-success">Reporte</button></td>
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
    
    
    $('#generarc').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#estandarcer').css('display','block');
            $('#filtrocer').css('display','none');
       }
       if(opcion == 2){
           $('#filtrocer').css('display','block');
           $('#estandarcer').css('display','none');
       }
    });
        
        $("#fechainicer").datepicker();
        $("#fechafincer").datepicker();
    </script>