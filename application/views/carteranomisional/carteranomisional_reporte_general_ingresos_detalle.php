<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<table id="tablaq">
	<thead>
	<tr>
		<th>IDENTIFICACION</th>
		<th>NOMBRE EMPLEADO</th>
		<th>TIPO</th>
		<th>TIPO DE CARTERA</th>
		<th>SALDO ANT.</th>
		<th>DESCUENTOS NOMINA</th>
		<th>PAGOS POR BOTÓN ELECTRONICO</th>
		<th>INTERESES CORRIENTES CANCELADOS</th>
		<th>INTERESES MORA CANCELADOS</th>
		<th>AMORTIZACION A CAPITAL</th>
		<th>NUEVO SALDO CAPITAL</th>
		<th>SALDO ANTERIOR INT NO PAGOS</th>
		<th>INT NO PAGOS NUEVO SALDO</th>
		<th>TASA ANUAL</th>
		<th>TASA INT. MENSUAL</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>&nbsp;a</td>
		<td>&nbsp;b</td>
		<td>&nbsp;c</td>
		<td>&nbsp;d</td>
		<td>&nbsp;e</td>
		<td>&nbsp;f</td>
		<td>&nbsp;g</td>
		<td>&nbsp;a</td>
		<td>&nbsp;b</td>
		<td>&nbsp;c</td>
		<td>&nbsp;d</td>
		<td>&nbsp;e</td>
		<td>&nbsp;f</td>
		<td>&nbsp;g</td>
		<td>&nbsp;g</td>
	
	</tr>		
		
	</tbody>
	
</table>
    

<script type="text/javascript" language="javascript" charset="utf-8">

 $('#tablaq').dataTable({
"sDom": 'T<"clear">lfrtip',
                    "oTableTools": {
                        "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                        "sRowSelect": "multi"
                    }

        
    });


            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

