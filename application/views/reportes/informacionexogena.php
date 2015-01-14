<table align="center">
    <tr>
        <td><input type="radio" name="radio" class="radio" id="ingresosreci" value="1">Ingresos Recibidos</td>
    </tr>
    <tr>
        <td><input type="radio" name="radio" class="radio" id="saldoscuen" value="2">Saldos de Cuentas por Cobrar</td>
    </tr>
</table>
<br>
<table align="center">
    <tr>
        <td><button name="generar" id="generarexo" class="btn btn-success">Generar</button></td>
    </tr>
</table>
<br>
<br>
<div id="ingresos" style="display: none;">
    <table align="center">
        <tr align="center">
            <td><h3>REPORTE INFORMACIÓN EXÓGENA</h3></td>
        </tr>
        <tr align="center">
            <td><a target="_blank" href="http://www.dian.gov.co/contenidos/otros/prevalidadores.html">http://www.dian.gov.co/contenidos/otros/prevalidadores.html</a> </td>
        </tr>
        <tr align="center">
            <td>(Descargar ejecutable para generar información correspondiente)</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <td><h3>INGRESOS RECIBIDOS</h3></td>
        </tr>
    </table>
    <table style="border: 3px solid;border-color: #777; width: 1000px">
        <tr>
            <td>Año de Envio</td>
            <td><input type="text" name="envio" id="envio"></td>
            <td>Concepto</td>
            <td><select><option id="concepto">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Codigo de Formato</td>
            <td><input type="text" name="codigo" id="codigo"></td>
            <td>Versión del Formato</td>
            <td><input type="text" name="version" id="version"></td>
        </tr>
        <tr>
            <td>Número de Envio</td>
            <td><input type="text" name="numero" id="numero"></td>
            <td>Fecha Envio</td>
            <td><input type="date" name="fechaenvio" id="fechaenvio"></td>
        </tr>
        <tr>
            <td>Fecha Inicial</td>
            <td><input type="date" name="fechaini" id="fechaini"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechaini" id="fechafin"></td>
        </tr>
        <tr>
            <td>Cantidad de Registros</td>
            <td><input type="text" name="cantidad" id="cantidad"></td>
            <td>Valor Total</td>
            <td><input type="text" name="valor" id="valor"></td>
        </tr>
    </table>
    <br>
    <br>
    <table id="estilotabla">
        <thead>
            <th>Concepto</th>
            <th>Tipo de Documento</th>
            <th>Digito de Verificación</th>
            <th>Primer Apellido del Informado</th>
            <th>Segundo Apellido del Informado</th>
            <th>Primer Nombre del Informado</th>
            <th>Otros Nombres del Informado</th>
            <th>Razón Social Informado</th>
            <th>País de Residencia o Domicilio</th>
            <th>Ingresos Brutos Recibidos por Operaciones Propias</th>
            <th>Ingreso a Tavés de Consorcio o Uniones Temporales</th>
            <th>Ingreso a Tavés de Contratos de Mandato o Administración</th>
            <th>Ingreso a Tavés de Exploración y Explotación de Minerales</th>
            <th>Ingreso a Tavés de Fiducia</th>
            <th>Ingreso Recibidos a Tavés de Terceros</th>
            <th>Devolución, Rebajas y Descuentos</th>
    </thead>
    <tbody></tbody>
    </table>
</div>
<div id="saldos" style="display: none;">
    <table align="center">
        <tr align="center">
            <td><h3>REPORTE INFORMACIÓN EXÓGENA</h3></td>
        </tr>
        <tr align="center">
            <td><a target="_blank" href="http://www.dian.gov.co/contenidos/otros/prevalidadores.html">http://www.dian.gov.co/contenidos/otros/prevalidadores.html</a> </td>
        </tr>
        <tr align="center">
            <td>(Descargar ejecutable para generar información correspondiente)</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <td><h3>SALDOS CUENTAS POR COBRAR</h3></td>
        </tr>
    </table>
    <table style="border: 3px solid;border-color: #777; width: 1000px">
        <tr>
            <td>Año de Envio</td>
            <td><input type="text" name="envio1" id="envio1"></td>
            <td>Concepto</td>
            <td><select><option id="concepto1">-Seleccionar-</option></select></td>
        </tr>
        <tr>
            <td>Codigo de Formato</td>
            <td><input type="text" name="codigo1" id="codigo1"></td>
            <td>Versión del Formato</td>
            <td><input type="text" name="version1" id="version1"></td>
        </tr>
        <tr>
            <td>Número de Envio</td>
            <td><input type="text" name="numero1" id="numero1"></td>
            <td>Fecha Envio</td>
            <td><input type="date" name="fechaenvio1" id="fechaenvio1"></td>
        </tr>
        <tr>
            <td>Fecha Inicial</td>
            <td><input type="date" name="fechaini1" id="fechaini1"></td>
            <td>Fecha Final</td>
            <td><input type="date" name="fechaini1" id="fechafin1"></td>
        </tr>
        <tr>
            <td>Cantidad de Registros</td>
            <td><input type="text" name="cantidad1" id="cantidad1"></td>
            <td>Valor Total</td>
            <td><input type="text" name="valor1" id="valor1"></td>
        </tr>
    </table>
    <br>
    <br>
    <table id="estilotabla1">
        <thead>
            <th>Concepto</th>
            <th>Tipo de Documento</th>
            <th>Número de Identificación</th>
            <th>Digito de Verificación</th>
            <th>Primer Apellido del Deudor</th>
            <th>Segundo Apellido del Deudor</th>
            <th>Primer Nombre del Deudor</th>
            <th>Otros Nombres del Deudor</th>
            <th>Razón Social Deudor</th>
            <th>Dirección</th>
            <th>Codigo del Departamento</th>
            <th>Codigo del Municipio</th>
            <th>País de Residencia o Domicilio</th>
            <th>Saldo Cuenta por Cobrar al 31</th>
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
     $('#estilotabla1').dataTable({
                    "sDom": 'T<"clear">lfrtip',
                    "oTableTools": {
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }
                });  
    
    $('.radio').click(function(){
       var radio = $(this).val();
     $('#opcion').val(radio);       
    });
    
    
    $('#generarexo').click(function(){
      var opcion = $('#opcion').val(); 
       if(opcion == 1){
           $('#ingresos').css('display','block');
            $('#saldos').css('display','none');
           
       }
       if(opcion == 2){
           $('#saldos').css('display','block');
           $('#ingresos').css('display','none');
           
       }
    });
   
    
 $("#fechaini").datepicker();
 $("#fechafin").datepicker();
 $("#fechaenvio").datepicker();
 $("#fechaini1").datepicker();
 $("#fechafin1").datepicker();
 $("#fechaenvio1").datepicker();
 
</script>