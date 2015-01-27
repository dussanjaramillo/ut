<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-xlarge">
	<?php //echo $custom_error; ?>
  <div class="text-center">
    <h2>Procesos para Auto de Cierre y Terminación del Proceso</h2>
  </div>
  <?php if (isset($message)) echo $message; ?>
  <table id="tablaq">
  	<thead>
    	<tr>
      	<th>N° PROCESO</th>
        <th>REGIONAL</th>
        <th>IDENTIFICACIÓN<BR>EJECUTADO</th>
        <th>EJECUTADO</th>
        <th>ESTADO</th>
        <th>ASIGNADO A</th>
        <th>GESTIÓN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>
<br>
<div style="text-align: center;">  <form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
        <button class="btn btn-warning btn-lg"> Cancelar </button>
    </form></div>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
function dataTable() {
	$('#tablaq').dataTable({
		"sServerMethod": "POST",
		"bAutoWidth": false,
		"bJQueryUI": true,
		"bProcessing": true,  
		"bServerSide": true,
		"bPaginate" :  true ,
		"iDisplayStart" : 0,
		"iDisplayLength" : 10,
		"sSearch": '',
		"sPaginationType": "full_numbers",
		"bSort": false,
		"aLengthMenu": [10, 50],
		"sEcho": 0,
		"iTotalRecords": 10,
		"iTotalDisplayRecords": 10,
		"sAjaxSource": "<?php echo base_url(); ?>index.php/verfpagosprojuridicos/datatable",          
		"aoColumns": [
			{ "mDataProp": "COD_PROCESOPJ", "sWidth": "10%" },
                        { "mDataProp": "NOMBRE_REGIONAL", "sWidth": "10%" },
			{ "mDataProp": "NIT_EMPRESA", "sWidth": "10%" },
			{ "mDataProp": "NOMBRE_EMPRESA", "sWidth": "17%" },
			{ "mDataProp": "TIPOGESTION", "sWidth": "35%" },
			{ "mDataProp": "NOMBRE_ASIGNADO", "sWidth": "10%" },
			{ "sClass": "center", "mDataProp": "BOTON", "sWidth": "8%", "bSearchable": false, "bSortable": false }
		]
	});
}

function ver(id) {
	window.location = "<?php echo base_url() ?>index.php/verfpagosprojuridicos/auto/" + id;
}

$(document).ready(function() {
	dataTable();          
});
</script> 