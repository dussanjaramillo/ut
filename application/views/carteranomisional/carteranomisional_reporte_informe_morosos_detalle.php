<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div>

<table id="tablaq">
	<thead>
	<tr>
		<th>IDENTIFICACION DEUDOR</th>
		<th>NOMBRE DEUDOR</th>
		<th>TIPO DE CARTERA</th>
		<th>FECHA DE ULTIMO PAGO</th>
		<th>ULTIMA CUOTA CANCELADA</th>
		<th>SALDO DEUDA DE CAPITAL</th>
		<th>INTERESES ACUMULADOS NO PAGOS</th>
		<th>VALOR TOTAL DEUDA</th>
		<th>CUOTAS MES EN MORA</th>
		<th>ESTADO  ACTUAL DEL COBRO</th>
		<th>OBSERVACIONES</th>
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
		<td>&nbsp;d</td>
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

