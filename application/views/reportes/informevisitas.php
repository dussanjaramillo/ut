<div align="center"><h2>VISITAS GENERAL</h2></div>
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
        <td><button name="generar" id="generarinfor" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalinfor" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regional">-Seleccionar-</option>
                     <?php foreach ($regional->result_array as $regional){?>
                    <option value="<?= $regional['COD_REGIONAL']?>"><?= $regional['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>NIT</td>
            <td><select><option id="nit">-Seleccionar-</option></select></td>
            <td>Fiscalizador</td>
            <td>
                <select>
                    <option id="fiscalizador">-Seleccionar-</option>
                    <?php foreach ($fiscalizador->result_array as $cargo){?>
                    <option value="<?= $cargo['IDUSUARIO']?>"><?= $cargo['NOMBREUSUARIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechainfor" id="fechainfor"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechainfor" id="fechafin"></td>
        </tr>
    </table>
    <br>
     <table align="center">
    <tr>
        <td><button name="generar" id="generarvisi1" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<div id="filtrarinfor" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regional1">-Seleccionar-</option>
                    <?php foreach ($regional1->result_array as $regional){?>
                    <option value="<?= $regional['COD_REGIONAL']?>"><?= $regional['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Concepto</td>
            <td><select><option id="concepto1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>NIT</td>
            <td><select><option id="nit1">-Seleccionar-</option></select></td>
            <td>Fiscalizador</td>
            <td>
                <select>
                    <option id="fiscalizador1">-Seleccionar-</option>
                    <?php foreach ($fiscalizador1->result_array as $cargo){?>
                    <option value="<?= $cargo['IDUSUARIO']?>"><?= $cargo['NOMBREUSUARIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechainfor" id="fechainfor1"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechainfor" id="fechafin1"></td>
        </tr>
    </table>
    <br>
     <table align="center">
    <tr>
        <td><button name="generar" id="generarvisi" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<br>
<br>
<div id="tablafiltro" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>REGIONAL</th>
            <th>CONCEPTO</th>
            <th>EMPRESA</th>
            <th>NÚMERO VISITA</th>
            <th>FECHA</th>
            <th>FISCALIZADOR</th>
            <th>COMENTARIOS</th>
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
    
    $('.radio').click(function(){
       var radio = $(this).val();
     $('#opcion').val(radio);       
    });
    
    
    $('#generarinfor').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalinfor').css('display','block');
            $('#filtrarinfor').css('display','none');
            $('#tablafiltro').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarinfor').css('display','block');
           $('#generalinfor').css('display','none');
       }
    });
    
    $('#generarvisi').click(function(){
        $('#tablafiltro').css('display','block');
    });
    
 $("#fechainfor").datepicker();
 $("#fechafin").datepicker();
 $("#fechainfor1").datepicker();
 $("#fechafin1").datepicker();
</script>