<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div align="center"><h1>CERTIFICADOS MASIVOS</h1></div>
<table align="center" style="border: 2px solid">
    <tr>
        <td>NIT</td>
        <td><input type="text" name="nit" id="nit"></td>
        <td>Razon Social</td>
        <td><input type="text" name="razon" id="razon" readonly="readonly" disabled="disabled"></td>
    </tr>
    <tr>
        <td>Tipo de certificado</td>
        <td colspan="2">
            <select>
                <option>-Seleccionar-</option>
                <?php foreach ($tipocertifi->result_array as $tipo) { ?>
                    <option value="<?= $tipo['COD_TIPO_CERTIFICADO'] ?>"><?= $tipo['NOMBRE_CERTIFICADO'] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
   
    <tr>
        <td>Periodo</td>
         <td><select>
             <option>-Seleccionar-</option>
                <?php 
        $year = date('Y');
        for($i = $year-5; $i <= $year; $i++){
        ?> 
                <option><?= $i ?></option>
                   <?php 
        }
        ?>
             </select></td>
            
        <td>Mes</td>
        <td><select>
                <option>-Seleccionar-</option>
                <option>Enero</option>
                <option>Febrero</option>
                <option>Marzo</option>
                <option>Abril</option>
                <option>Mayo</option>
                <option>Junio</option>
                <option>Julio</option>
                <option>Agosto</option>
                <option>Septiembre</option>
                <option>Octubre</option>
                <option>Noviembre</option>
                <option>Diciembre</option>
            </select></td>
    </tr>
</table>
<br>
<p>
<table align="center">
    <tr>
        <td width="100px"><button id="generar1" class="btn btn-success">Generar</button></td>
        <td><button id="cancelarg" class="btn btn-success">Cancelar</button></td>
    </tr>
</table>
<div id="masivos" class="cmasivos" style="display: none">
    <table>
        <tr>
            <td align="center"><h4>¿Cuales son los certificados que desea generar?</h4></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado1"  class="certificado1" id="certificado1">Certificacion Tributaria por Vigencia</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado2"  class="certificado2" id="certificado2">Certificado de Estado FIC por Periodo</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado3"  class="certificado3" id="certificado3">Certificado de Estado a la Fecha por Numero de Obra</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado4"  class="certificado4" id="certificado4">Certificado de Estado FIC a la Fecha por Numero de Resolucion o Liquidacion</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado5"  class="certificado5" id="certificado5">Certificado de Estado a la Fecha de Aportes</td>
        </tr>
        <tr>
            <td><input type="checkbox" name="certificado6"  class="certificado6" id="certificado6">Certificado de Deuda</td>
        </tr>
    </table>
</div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    $('#nit').change(function(){
       var nit = $('#nit').val();
       var url = "<?= base_url('index.php/pila/masivosnit') ?>";
       $.post(url,{nit : nit},function(data){
           $('#razon').val(data);
       });
       
    });
    $('#generar1').click(function(){
        $('#masivos').dialog({
                        title : 'GENERAR CERTIFICADOS',
                        width : 650,
                        height : 330,
                        modal : true,
                        buttons :[{
                                id : 'generar',
                                text : 'Generar Certificado',
                                class : 'btn btn-success'
                             }]
                    });
    });
    
    
</script>


