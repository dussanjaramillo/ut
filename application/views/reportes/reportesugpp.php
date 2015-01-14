<div align="center"><h2>REPORTES UGPP</h2></div>
<br>
<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="generaledad" value="1">Actualizar información de contacto y ubicación aportantes</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtrosedad" value="2">Reporte consolidado(Mensual)</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="filtrosedad" value="3">Reporte desagregado(Trimestral)</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarugpp" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<br>
<div id='actualización' style="display: none;">
    <table align='center'>
    <tr>
        <td><h2>ACTUALIZAR INFORMACIÓN DE CONTACTO Y UBICACIÓN DE APORTANTES</h2></td>
    </tr>
    </table>
    <table id="estilotabla">
    <thead>
        <th>Administradora</th>
        <th>Nombre Administradora</th>
        <th>Nombre o Razon Social del Aportante</th>
        <th>Tipo de Documento</th>
        <th>Numero de Documento del Aportante</th>
        <th>Número de Digito de Verificación</th>
        <th>Dirección 1</th>
        <th>Codigo Departamento 1</th>
        <th>Codigo Municipio 1</th>
        <th>Nombre de Departamento</th>
    </thead>
    <tbody></tbody>
</table>
</div>
<div id='consolidado' style="display: none;">
    <table align='center'>
        <tr>
            <td><h2>REPORTE CONSOLIDADO(Mensual)</h2></td>
        </tr>
    </table>
    <table id="estilotabla1">
        <thead>
            <th>Administradora</th>
            <th>Nombre de Administradora</th>
            <th>Tipo de Cartera</th>
            <th>Origen de Cartera</th>
            <th>Edad de Cartera</th>
            <th>Numero de Periodos sin Pago</th>
            <th>Valor de la Cartera</th>
    </thead>
        <tbody></tbody>
    </table>
</div>
<br>
<div id="desagregado" style="display: none;">
    <table align='center'>
        <tr>
            <td><h2>REPORTE DESAGREGADO DE CARTERA</h2></td>
        </tr>
    </table>
    <table id="estilotabla2">
        <thead>
            <th>Administradora</th>
            <th>Nombre de Administradora</th>
            <th>Nombre o Razón Social del Aportante</th>
            <th>Tipo de Documento</th>
            <th>Número de Documento del Aportante</th>
            <th>Número de Digito de Verificación</th>
            <th>Tipo de Cartera</th>
            <th>Origen de Cartera</th>
            <th>Valor de Cartera</th>
            <th>Último Periodo con Pago Registrado</th>
            <th>Última Fecha de Pago</th>
            <th>Número de Perido sin Pago</th>
            <th>Última Acción de Cobro</th>
            <th>Fecha Última Acción de Cobro</th>
            <th>Estado del Aportante</th>
            <th>Calificación del Estado del aportante</th>
            <th>Convenio de Cobro</th>
    </thead>
         <tbody>
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
     $(document).ready(function() {
       $('#estilotabla2').dataTable({
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
    
    
    $('#generarugpp').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#actualización').css('display','block');
            $('#consolidado').css('display','none');
            $('#desagregado').css('display','none');
       }
       if(opcion == 2){
           $('#consolidado').css('display','block');
           $('#actualización').css('display','none');
           $('#desagregado').css('display','none');
       }
       if(opcion == 3){
           $('#desagregado').css('display','block');
           $('#actualización').css('display','none');
           $('#consolidado').css('display','none');
       }
    });
</script>
