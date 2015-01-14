<div align="center"><h2>REPORTE DE CONCILIACIÓN DE CARTERA</h2></div>
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
        <td><button name="generar" id="generarcon" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="estandar1" style="display: none;">
    <table id="tabla">
        <thead>
            <th>Codigo Regional</th>
            <th>Nombre Regional</th>
            <th>Identificación</th>
            <th>Razón Social</th>
            <th>Concepto</th>
            <th>Transacción</th>
            <th>Medio Origen</th>
            <th>Periodo Pagado</th>
            <th>Valor</th>
            <th>Operador</th>
            <th>Fecha Pago</th>
            <th>Fecha Conciliación</th>
            <th>Fecha Cargue</th>
            <th>Instancia</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<div id="filtros3" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Movimientos</td>
            <td><select><option id="movimientos">-Seleccionar-</option></select></td>
            <td>Instancia</td>
            <td><select><option id="instancia">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Tipo de Fecha</td>
            <td><select><option id="movimientos">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td colspan="3"><input type="checkbox" name="radio" class="radio1" id="periodo" value="1">Periodo Pagado</td>
        </tr>
        <tr>
            <td>Mes</td>
            <td>
                <select>
                    <option id="mes">-Seleccionar-</option>
                    <option id="enero">Enero</option>
                    <option id="febrero">Febrero</option>
                    <option id="marzo">Marzo</option>
                    <option id="abril">Abril</option>
                    <option id="mayo">Mayo</option>
                    <option id="junio">Junio</option>
                    <option id="julio">Julio</option>
                    <option id="agosto">Agosto</option>
                    <option id="septiembre">Septiembre</option>
                    <option id="Octubre">Octubre</option>
                    <option id="noviembre">Noviembre</option>
                    <option id="diciembre">Diciembre</option>
                </select>
            </td>
            <td>Año</td>
            <td><select><option id="anno">-Seleccionar-</option></select></td>
        </tr> 
        <tr>
            <td>No. Documento Identificación</td>
            <td><select><option id="doc">-Seleccionar-</option></select></td>
            <td>Razón Social</td>
            <td><select><option id="razon">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Nombre Regional</td>
            <td>
                <select>
                    <option id="razon">-Seleccionar-</option>
                     <?php foreach ($regional->result_array as $regional){?>
                    <option value="<?= $regional['COD_REGIONAL']?>"><?= $regional['COD_REGIONAL']?>-<?= $regional['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
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
        <td><button name="generar" id="generarconci" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<input type="hidden" name="opcion" id="opcion">
<script>
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
    
    
    $('#generarcon').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#estandar1').css('display','block');
            $('#filtros3').css('display','none');
       }
       if(opcion == 2){
           $('#filtros3').css('display','block');
           $('#estandar1').css('display','none');
       }
    });
    
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
</script>