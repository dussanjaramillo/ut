<?php
/**
* Formulario para consultar liquidaciones en el proceso de cobro persuasivo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/gestionliquidacion_list.php
* @last-modified  29/10/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if(isset($message))
{
    echo $message;
}
?>
<!-- Lista de liquidaciones disponibles -->
<div class = "center-form-xlarge" id = "cabecera">
	<h2 class = "text-center"><?php echo $titulo; ?></h2>
	<br>
	<div id = "contenido" >
		<table id="liquidaciones" class = "table table-striped table-hover">
			<thead>
				<tr>
				<th>NIT</th>
				<th>Razon Social</th>
				<th>Concepto</th>
				<th>Num. Liquidación</th>
				<th>Cod. Fiscalizacion</th>
				<th>Gestionar</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$total = 0;
				if (!empty($liquidaciones_seleccionadas)):

					foreach ($liquidaciones_seleccionadas as $data):

						$total += 1;
			?>
			<tr>
				<td><?php echo  $data -> CODEMPRESA ?></td>
				<td><?php echo  $data -> RAZON_SOCIAL ?></td>
				<td><?php echo  $data -> NOMBRE_CONCEPTO ?></td>
				<td><?php echo  $data -> NUM_LIQUIDACION ?></td>
				<td><?php echo  $data -> COD_FISCALIZACION ?></td>
				<td><div style="cursor: pointer" class="push"  cf=""><input type="radio" onclick="f_enviar(this.value);" value="<?php echo $data -> COD_FISCALIZACION ?>"></div></td>
			</tr>
			<?php
					endforeach;

				endif;
			?>
			</tbody>
		</table>
	</div>
	<br>
	<table width = "100%">
		<tr>
			<td class="text-center"><a class="btn btn-warning" href="<?php echo site_url(); ?>"><i class="fa fa-minus-circle"></i> Cancelar</a></td>
		</tr>
	</table>
</div>
<!-- Fin lista de liquidaciones disponibles -->
<!-- Formulario selección de liquidación -->
<form id="form1" action="<?php echo  base_url($ruta) ?>" method="post" >
	<input type="hidden" id="cod_gestion_liquidacion" name="cod_gestion_liquidacion" >
</form>
<!-- Fin formulario selección de liquidación -->

<script type="text/javascript" language="javascript" charset="utf-8">
    function f_enviar(cod_FISCALIZACION) {
        //alert(cod_FISCALIZACION);
        $(".preload, .load").show();
        var cod_gestion_liquidacion = cod_FISCALIZACION;//$(this).attr('cf');
        $('#cod_gestion_liquidacion').val(cod_gestion_liquidacion);
        $('#form1').submit();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    }
$(document).ready( function () {
	datatable('#liquidaciones');


});

function datatable (div) {
	$(div).dataTable({
		"bJQueryUI": true,
		"bServerSide" : false,
		"bProcessing" : true,
		"bSort" : true,
		"bPaginate" : true,
		"iDisplayStart" : 0,
		"iDisplayLength" : 10,
		"sPaginationType": "full_numbers",
		"oLanguage": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"fnInfoCallback": null,
  		},
		'iTotalRecords' : <?php echo $total ?>,
		'iTotalDisplayRecords' : <?php echo $total ?>,
		'aLengthMenu' : [10,50],
		'sEcho' : 1,
		"aaSorting": [[ 0, "desc" ]],
		"bLengthChange": false,
		"aoColumns": [
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "sClass": "center" },
			{ "sClass": "center" },
		]
	});
}
</script>
<!--
/* End of file gestionliquidacion_list.php */
-->