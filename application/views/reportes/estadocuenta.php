<div align="center"><h2>ESTADO DE CARTERA</h2></div>
<br>
<table align="center" style="border: 3px solid;border-color: #777; width: 400px">
    <tr align="center">
        <td width=" 100px" height="80px">NIT</td>
        <td><input type="text" name="nit" id="nit"></td>
        <td width=" 200px"><button name="buscar" id="buscar" class="btn btn-success">Buscar</button></td>
    </tr>
    <tr align="center">
        <td width=" 100px" height="80px">Fecha</td>
        <td><input type="date" name="fecha" id="fecha"></td>
    </tr>
</table>
<br>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generar" class="btn btn-success" align="center">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="consultaempresa" style="display: none;">
<table>
    <tr>
        <td>NIT</td>
        <td><input type="text" readonly="readonly"  disabled="disabled" name="nit" id="nit"></td>
    </tr>
    <tr>
        <td>Razón Social</td>
        <td><input type="text" name="razon" id="razon"></td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="buscar1" id="buscar1" class="btn btn-success">Buscar</button></td>
    </tr>
</table>
<br>
<br>
<br>
<br>
<table align="center" border="1" style="width: 570px; height: 150px">
    <tr align="center">
        <td></td>
        <td><b>NIT</b></td>
        <td><b>RAZON SOCIAL</b></td>
    </tr>
    <tr align="center">
        <td><input type="radio" name="radio" class="radio" id="detalle" value="1"></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr align="center">
        <td><input type="radio" name="radio" class="radio" id="detalle" value="1"></td>
        <td><br></td>
        <td><br></td>
    </tr>
    <tr align="center">
        <td><input type="radio" name="radio" class="radio" id="detalle" value="1"></td>
        <td><br></td>
        <td><br></td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="aceptar" id="aceptar" class="btn btn-success">Aceptar</button></td>
    </tr>
</table>
</div>
<br>
<br>
<br>
<div id="detalleempresa" style="display: none;">
    <table>
        <tr>
            <td align="center"><b>NIT</b></td>
            <td><input type="text" readonly="readonly"  disabled="disabled" name="nit1" id="nit1"></td>
        </tr>
        <tr>
            <td><b>RAZON SOCIAL</b></td>
            <td><input type="text" readonly="readonly"  disabled="disabled" name="razon1" id="razon1"></td>
        </tr>
    </table>
    <table id="estilotabla">
        <thead>
            <th>Concepto</th>
            <th>Tipo</th>
            <th>Número</th>
            <th>Fecha</th>
            <th>Regional</th>
            <th>Estado</th>
            <th>Valor</th>
            <th>Días en Mora</th>
            <th>Intereses</th>
            <th>Total</th>
            
        </thead>
        <tbody></tbody>
        <tfoot align="right">
            <th colspan="5"></th>
            <th><b>Total</b></th>
            <th width="100px"></th>
            <th width="100px"></th>
            <th width="100px"></th>
            <th width="100px"></th>
    </tfoot>
    </table>
</div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
    $(document).ready(function() {
       var tabla  = $('#estilotabla').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
            }
        });
        });
    
    $('#buscar').click(function(){
        $('#consultaempresa').dialog({
            autoOpen : true,
            width : 700,
            height : 500,
            modal : true
        });
    });
    $('#aceptar').click(function(){
        $('#consultaempresa').dialog('close');
        $('#detalleempresa').css('display','block');
    });
    
 $("#fecha").datepicker();
</script>