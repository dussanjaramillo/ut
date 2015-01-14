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
        <td><button name="generar" id="generarestado" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="generalestado" style="display: none;">
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
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechain" id="fechaestado"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafi" id="fechafinestado"></td>
        </tr>
    </table>
    <br>
    <table align="center">
        <tr>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="gestion" value="1">Gestión</td>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="liquidacion" value="1">Liquidación</td>
            <td width="150px"><input type="checkbox" name="radio" class="radio1" id="via" value="1">Vía Guvernativa</td>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="pago" value="1">Pago</td>
            <td width="150px"><input type="checkbox" name="radio" class="radio1" id="total" value="1">Total Recaudo</td>
        </tr>
    </table>
    <br>
     <table align="center">
    <tr>
        <td><button name="generar" id="generaresta" class="btn btn-success">Generar</button></td>
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
            <th>TOTAL EMPRESAS</th>
            <th>GESTIÓN</th>
            <th>LIQUIDACIÓN</th>
            <th>VÍA GUBERNATIVA</th>
            <th>PAGO</th>
            <th>SIN GESTIÓN</th>
            <th>TOTAL RECAUDO</th>   
    </thead>
    <tbody></tbody>
     </table>
</div>
<div id="filtrarestado" style="display: none;">
    <table align="center" style="border: 3px solid;border-color: #777; width: 700px">
        <tr>
            <td>Regional</td>
            <td>
                <select>
                    <option id="regionalasig">-Seleccionar-</option>
                    <?php foreach ($regional1->result_array as $region){?>
                    <option value="<?= $region['COD_REGIONAL']?>"><?= $region['NOMBRE_REGIONAL']?></option>
                    <?php } ?>
                </select>
            </td>
            <td>Fiscalizador</td>
            <td>
                <select>
                    <option id="fiscalizadorasig">-Seleccionar-</option>
                    <?php foreach ($fiscalizador1->result_array as $cargo){?>
                    <option value="<?= $cargo['IDUSUARIO']?>"><?= $cargo['NOMBREUSUARIO']?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Fecha Inicio</td>
            <td><input type="date" name="fechain" id="fechaestado"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechafi" id="fechafinestado"></td>
        </tr>
    </table>
    <br>
    <table align="center">
        <tr>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="gestion" value="1">Gestión</td>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="liquidacion" value="1">Liquidación</td>
            <td width="150px"><input type="checkbox" name="radio" class="radio1" id="via" value="1">Vía Guvernativa</td>
            <td width="100px"><input type="checkbox" name="radio" class="radio1" id="pago" value="1">Pago</td>
            <td width="150px"><input type="checkbox" name="radio" class="radio1" id="total" value="1">Total Recaudo</td>
        </tr>
    </table>
    <br>
    <table align="center">
    <tr>
        <td><button name="generar" id="generares" class="btn btn-success">Generar</button></td>
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
    
    
    $('#generarestado').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#generalestado').css('display','block');
            $('#filtrarestado').css('display','none');
       }
       if(opcion == 2){
           $('#filtrarestado').css('display','block');
           $('#generalestado').css('display','none');
            $('#tablageneral').css('display','none');
       }
    });
    $('#generaresta').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#tablageneral').css('display','block');
       }
        });

 $("#fechaestado").datepicker();
 $("#fechafinestado").datepicker();
</script>