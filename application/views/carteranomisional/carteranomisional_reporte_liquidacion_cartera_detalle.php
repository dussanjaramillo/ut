<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div>

<table id="tablaq">
	<thead>
	<tr>
		<th>PERIODOS EN MORA</th>
		<th>SALDO I</th>
		<th>CUOTA MENSUAL PACTADA NO CANCELADA</th>
		<th>INTERESES CORRIENTES</th>
		<th>AMORTIZACION A CAPITAL</th>
		<th>INTERESES DE MORA</th>
		<th>NUEVO SALDO DE CAPITAL</th>
		<th>SALDO INTERESES ACUMULADOS NO PAGOS</th>
		<th>DIAS DE MORA</th>
		<th>TASA DE INTERES DE MORA</th>
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
		<td>&nbsp;e</td>
		<td>&nbsp;f</td>
		<td>&nbsp;g</td>
	</tr>		
		
	</tbody>
	
</table>
    
</div>

<div id="detalle_reporte"></div>

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

