<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div>

<table id="tablaq">
	<thead>
	<tr>
		<th>REGIONAL</th>
		<th>CONCEPTO</th>
		<th>TIPO DE CARTERA</th>
		<th>CANTIDAD</th>
		<th>TOTAL SALDOS</th>
		<th>CANTIDAD MOROSOS</th>
		<th>TOTAL ADEUDADO POR MOROSOS</th>
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

