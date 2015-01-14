<?php
/**
* Resultado de las fiscalizaciones asociadas a una empresa. Resultados de consultar.php
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/consultar_respuesta.php
* @last-modified  08/09/2014
*/

if( ! defined('BASEPATH') ) exit('No direct script access allowed');

if(isset($message)):
    echo $message;
 endif;
// var_dump($fiscalizacion);


if(isset($fiscalizacion)):
	$nit = $fiscalizacion[0]['CODEMPRESA'];
	$codigoAsignacion = $fiscalizacion[0]['COD_ASIGNACION_FISC'];
endif;
?>
<!-- Estilos personalizados -->
<style type="text/css">
#cabecera table{width: 100%; height: auto;}
#cabecera td{text-align: left; vertical-align: middle; width: 12%;}
.centrado{text-align:center !important; padding:10px;}
.derecha{text-align:right !important; padding:10px;}
#alerta{display: none;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Cargue de formulario -->
<div class="center-form-xlarge" id="cabecera">
	<h2 class = "text-center">Generar Liquidaciones</h2>
	<br><br>
	<table>
		<?php
		if(isset($nit)):
		?>
		<tr>
			<td colspan = "8" class = "derecha"><a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones/getFormSgva/<?php echo $nit; ?>/<?php echo $codigoAsignacion; ?>"><i class="fa fa-search"></i> Consultar Estado de Cuenta</a></td>
		</tr>
		<?php
		endif;
		?>
	</table>
	<div id = "cabecera">
		<table id = "fiscalizaciones" class = "table table-striped table-hover">
			<thead>
				<tr>
					<th>Expediente</th>
					<th>Nit</th>
					<th>Nombre</th>
					<th>Concepto</th>
					<th>Estado</th>
					<th>Periodo Inicial</th>
					<th>Periodo Final</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0;
					if(!empty($fiscalizacion)):

						foreach($fiscalizacion as $proceso):

							$total += 1;
				?>
				<tr>
					<td><?php echo $proceso['COD_FISCALIZACION']; ?></td>
					<td><?php echo $proceso['CODEMPRESA']; ?></td>
					<td><?php echo $proceso['RAZON_SOCIAL']; ?></td>
					<td><?php echo $proceso['NOMBRE_CONCEPTO']; ?></td>
					<td><?php echo $proceso['TIPOGESTION']; ?></td>
					<td><?php echo $proceso['PERIODO_INICIAL']; ?></td>
					<td><?php echo $proceso['PERIODO_FINAL']; ?></td>
					<td><a class="btn btn-info" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $proceso['COD_FISCALIZACION']; ?>"><i class="fa fa-eye"></i> Ver</a></td>
				</tr>
				<?php
						endforeach;

					endif;
				?>
			</tbody>
		</table>
	</div>
	<table>
		<tr>
			<td colspan = "8" class = "centrado"><a class="btn btn-warning" href="<?php echo site_url(); ?>/liquidaciones/liquidar"><i class="fa fa-minus-circle"></i> Cancelar</a></td>
		</tr>
	</table>
</div>
<!-- Fin cargue de formulario -->


<!-- Función validaciones interfaz -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready( function () {
	datatable('#fiscalizaciones');
} );

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
			{ "sClass": "center" },
			{ "sClass": "center" }
		]
	});
}
</script>
<!-- Fin Función validaciones interfaz -->

<!--
/* End of file consultar_respuesta.php */
-->