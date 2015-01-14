<div align="center"><h2>REPORTES FISCALIZACIÓN</h2></div>
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
        <td><button name="generar" id="generarasig" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalasig" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regionalasig">-Seleccionar-</option>
                    <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Fiscalizador</td>
            <td>
                <select>
                    <option id="fiscalizadorasig">-Seleccionar-</option>
                    <?php foreach ($fiscalizador->result_array as $cargo){?>
                    <option value="<?= $cargo['IDUSUARIO']?>"><?= $cargo['NOMBREUSUARIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Motivo Asignación</td>
            <td><select><option id="motivo">-Seleccionar-</option></select></td>
            <td>Estado</td>
            <td><select><option id="estadoasig">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Concepto</td>
            <td><select><option id="conceptoasig">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechain" id="fechaasig"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafi" id="fechafinasig"></td>
        </tr>
    </table>
    <br>
     <table align="center">
    <tr>
        <td><button name="generar" id="generarasigna" class="btn btn-success">Generar</button></td>
    </tr>
</table>
</div>
<br>
<br>
<div id="tablageneral" style="display: none;">
    <table id="estilotabla">
        <thead>
            <th>REGIONAL</th>
            <th>COD</th>
            <th>FISCALIZADOR</th>
            <th>RAZÓN SOCIAL</th>
            <th>NIT</th>
            <th>FECHA ASIGNACIÓN</th>
            <th>MOTIVO</th>
            <th>ESTADO</th>
            <th>CONCEPTO</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<br>
<div id="filtrarasig" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regionalasig1">-Seleccionar-</option>
                     <?php foreach ($regional->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Fiscalizador</td>
            <td>
                <select>
                    <option id="fiscalizadorasig1">-Seleccionar-</option>
                    <?php foreach ($fiscalizador->result_array as $cargo){?>
                    <option value="<?= $cargo['IDUSUARIO']?>"><?= $cargo['NOMBREUSUARIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Motivo Asignación</td>
            <td><select><option id="motivo1">-Seleccionar-</option></select></td>
            <td>Estado</td>
            <td><select><option id="estadoasig1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Concepto</td>
            <td><select><option id="conceptoasig1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechain" id="fechaasig1"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafi" id="fechafinasig1"></td>
        </tr>
    </table>
    <br>
     <table align="center">
    <tr>
        <td><button name="generar" id="generarasigna1" class="btn btn-success">Generar</button></td>
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
    
    
    $('#generarasig').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalasig').css('display','block');
            $('#filtrarasig').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarasig').css('display','block');
           $('#generalasig').css('display','none');
           $('#tablageneral').css('display','none');
       }
    });
    
    $('#generarasigna').click(function(){
        $('#tablageneral').css('display','block');
    });
    
 $("#fechaasig").datepicker();
 $("#fechafinasig").datepicker();
 $("#fechaasig1").datepicker();
 $("#fechafinasig1").datepicker();
</script>
