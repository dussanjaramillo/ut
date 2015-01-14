 <?php
/**
* Formulario para legalizar liquidaciones en el proceso de cobro persuasivo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/legalizacionliquidacion_form.php
* @last-modified  18/09/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if(isset($message))
{
    echo $message;
}
?>

<!-- Estilos personalizados -->
<style type="text/css">
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: left; vertical-align: middle;}
</style>
<!-- Fin Estilos personalizados -->

<div class="center-form-xlarge">
	<h2 class="text-center">Legalizar Liquidación</h2>
	<?php
		if (isset($message)) {
		echo $message;
		}
	?>
	<form id="form1" action="<?= base_url('index.php/liquidaciones/Gestion_EliminarSoporte') ?>" method="post" >
		<input type="hidden" id="cod_soporte" name="cod_soporte">
		<?php
			echo form_hidden('cod_fiscalizacion', $fiscalizacion);
			echo form_hidden('num_liquidacion', $liquidacion);
		?>
	</form>
	<div>
		<br>
		<table class="table table-striped table-bordered table-hover" width="100%">
			<tr class="warning">
				<th width = "25%" rowspan = "2"  style = "text-align:center !important; vertical-align:middle !important;">Documento</th>
				<th width = "25%" rowspan = "2"  style = "text-align:center !important; vertical-align:middle !important;">Fecha Radicación</th>
				<th width = "25%" rowspan = "2"  style = "text-align:center !important; vertical-align:middle !important;">Numero Radicación</th>
				<th colspan="2"  style = "text-align:center !important;">Gestión</th>
			</tr>
			<tr>
				<!-- <th width = "75%" colspan = "3"></th> -->
				<th width = "12%" style = "text-align:center !important;">Eliminar</th>
				<th width = "13%" style = "text-align:center !important;">Visualizar</th>
			</tr>
			<?php
				for ($i = 0; $i < sizeof($archivos); $i++):
					$data_1 = array(
						'name' => 'eliminar',
						'id' => 'eliminar',
						'value' => '',
						'style' => 'margin:10px',
						'onclick' => 'eliminacion(' . $archivos[$i]['COD_SOPORTE_LIQUIDACION'] . ')'
					);
					$data_2 = array(
						'name' => 'button',
						'id' => 'ir',
						'value' => 'seleccionar',
						'content' => '<i class="fa fa-repeat"></i> Continuar con el Proceso',
						'class' => 'btn btn-success ir'
					);
			?>
			<tr>
				<td><?php echo $archivos[$i]['NOMBRE_ARCHIVO']; ?></td>
				<td><?php echo $archivos[$i]['FECHA_RADICADO']; ?></td>
				<td><?php echo $archivos[$i]['NRO_RADICADO']; ?></td>
				<td>
					<div align="center">
					<?php 
						// var_dump($archivos);
						if($archivos !== FALSE):
						
							echo form_radio($data_1); 
						
						endif;
					?>
					</div>
				</td>
				<?php $atributos = array('class' => 'btn btn-info', 'target' => '_blank');?>
				<td><center>
					<?php 
						if($archivos !== FALSE):
						
							echo anchor(base_url() . 'uploads/fiscalizaciones/' . $fiscalizacion . '/liquidaciones/' . $archivos[$i]['NOMBRE_ARCHIVO'], '<i class="fa fa-eye"></i> Ver', $atributos); 
						
						endif;
					?>
					</center>
				</td>
			</tr>
			<?php
				endfor;
			?>
		</table>
	</div>
	<center>
	<?php echo anchor('liquidaciones/legalizarLiquidacion', '<i class="fa fa-arrow-left"></i> Regresar', 'class="btn btn-default"'); ?>
	</center>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
function eliminacion(valor)
{
	$('#cod_soporte').val(valor);
	$("#form1").submit();
}
</script>
<!--
/* End of file legalizacionliquidacion_exito.php */
-->