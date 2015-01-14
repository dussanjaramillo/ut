<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div>

<table id="tablaq">
	<thead>
	<tr>
		<th>PERIODOS CANCELADOS</th>
		<th>SALDO ANTERIOR DE CAPITAL</th>
		<th>VALOR CUOTA PACTADA</th>
		<th>VALOR CUOTA CANCELADA</th>
		<th>VALOR CESANTIAS APLICADAS</th>
		<th>INTERESES CORRIENTES CANCELADOS</th>
		<th>AMORTIZACION A CAPITAL</th>
		<th>NUEVO SALDO DE CAPITAL</th>
		<th>SALDO INTERESES NO PAGOS ACUMULADOS</th>
	</tr>
	</thead>
	<tbody>
	<tr>
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

