<div align="center"><h2>CONTRATO DE APRENDIZAJE</h2></div>
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
        <td><button name="generar" id="generarapren" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalapren" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td><select>
                    <option id="regional">-Seleccionar-</option>
                    <?php foreach ($regional->result_array as $regional){?>
                    <option value="<?= $regional['COD_REGIONAL']?>"><?= $regional['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Estado de Cuenta</td>
            <td><select><option id="estadocuenta">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><select><option id="estado">-Seleccionar-</option></select></td>
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
        <td><button name="generar" id="generaraprendiz" class="btn btn-success">Generar</button></td>
    </tr>
    </table>
</div>
<br>
<br>
<div id="tablageneralapren" style="display: none;">
    <table id="estilotabla">
        <thead>
             <th>Regional</th>
            <th>Razón Social</th>
            <th>NIT</th>
            <th>Estado de Cuenta SGVA</th>
            <th>Número</th>
            <th>Fecha</th>
            <th>Valor</th>
            <th>Estado</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<br>
<div id="filtrarapren" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <?php // echo "<pre>";var_dump($regional); ?>
                <select>
                    <option id="regional1">-Seleccionar-</option>
                    <?php foreach($regional1->result_array as $regional22){?>
                    <option value="<?= $regional22['COD_REGIONAL']?>"><?= $regional22['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Estado de Cuenta</td>
            <td><select><option id="estadocuenta1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><select><option id="estado1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechaini1" id="fechaini1"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafin1" id="fechafin1"></td>
        </tr>
    </table>
    <br>
    <table align="center">
    <tr>
        <td><button name="generar" id="generaraprendiz1" class="btn btn-success">Generar</button></td>
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
    
    
    $('#generarapren').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalapren').css('display','block');
            $('#filtrarapren').css('display','none');
            $('#tablageneralapren').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarapren').css('display','block');
           $('#generalapren').css('display','none');
           $('#tablageneralapren').css('display','none');
       }
    });
    $('#generaraprendiz').click(function(){
        $('#tablageneralapren').css('display','block');
    });
    
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
 $("#fechaini1").datepicker();
 $("#fechafin1").datepicker();
</script>