<?php
/**
* Formulario para la generación de recibos para liquidaciones
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/comprobanteAlterno.php
* @last-modified  31/08/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if(isset($message))
{
	echo $message;
}
// var_dump($liquidacion);
// var_dump($fecha);
 ?>
 <form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo site_url('/liquidaciones/pdf') ?>">
 	<textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
 	<input type="hidden" name="tipo" id="tipo" value="3">
 	<input type="hidden" name="nombre" id="nombre">
 	<input type="hidden" name="titulo" id="titulo" value="Comprobante">
 </form>
<!-- Estructura de Comprobante -->
 <div class="center-form-large" id="cabecera">
	<?php
	if (isset($message)):
		echo $message;
	endif;
	?>
	<table class = "table table-bordered table-striped" width = "100%">
		<tr>
			<td width = "50%" rowspan = "2" class = "text-center" style = "text-align:center; vertical-align:middle;"><img src="<?php echo base_url() . 'img/Logotipo.png'; ?>"></td>
			<td width = "50%" class = "text-left"><h3><?php echo 'Recibo de pago N° ' . $liquidacion['COD_FISCALIZACION']; ?></h3></td>
		</tr>
		<tr>
			<td width = "50%" class = "text-left"><strong>Fecha de Vencimiento:</strong> <?php echo $liquidacion['FECHA_VENCIMIENTO']; ?></td>
		</tr>
		<tr>
			<td width = "50%" class = "text-left"><strong>Razón Social:</strong> <?php echo $liquidacion['RAZON_SOCIAL']; ?></td>
			<td  width = "50%" class = "text-left"><strong>Nit:</strong> <?php echo $liquidacion['NITEMPRESA']; ?></td>
		</tr>
		<tr>
			<td  width = "50%" class = "text-left"><strong>Liquidación N°:</strong> <?php echo $liquidacion['NUM_LIQUIDACION']; ?> </td>
			<td  width = "50%" class = "text-left"><strong>Concepto:</strong> <?php echo $liquidacion['NOMBRE_CONCEPTO']; ?></td>
		</tr>
		<tr>
			<td  width = "50%" class = "text-left"><strong>Fecha:</strong> <?php echo $fecha; ?></td>
			<td  width = "50%" class = "text-left"></td>
		</tr>
	</table>
	<table class="table table-bordered table-striped" width="100%">
		<tr>
			<th width="33%">Detalle</th>
			<th width="33%">Valor</th>
			<th width="33%">Valor a pagar</th>
		</tr>
		<tr>
			<td width="33%">Capital</td>
			<td width="33%"><?php echo "$".number_format($liquidacion['TOTAL_CAPITAL'], 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($liquidacion['TOTAL_CAPITAL'], 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="33%">Intereses</td>
			<td width="33%"><?php echo "$".number_format($liquidacion['TOTAL_INTERESES'], 0, '.', '.'); ?></td>
			<td width="33%"><?php echo "$".number_format($liquidacion['TOTAL_INTERESES'], 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="33%">Valor Cuota</td>
			<td width="33%">&nbsp;</td>
			<td width="33%"><?php echo "$".number_format($liquidacion['TOTAL_LIQUIDADO'], 0, '.', '.'); ?></td>
		</tr>
	</table>
	<!-- Listado de Controles -->
	<table width = "100%">
		<tr>
			<?php
				if($liquidacion['COD_CONCEPTO'] == 5):
					echo '<td width = "50%" style="text-align:center"><a class="btn btn-warning" href="' . site_url() . '/multasministerio/"><i class="fa fa-minus-circle"></i> Cancelar</a></td>';
				else:
					echo '<td width = "50%" style="text-align:center"><a class="btn btn-warning" href="' . site_url() . '/liquidaciones/consultarLiquidacion/' .  $liquidacion['COD_FISCALIZACION'] . '"><i class="fa fa-minus-circle"></i> Cancelar</a></td>';
				endif;
			?>
			<td width = "50%" style="text-align:center"><a class="btn btn-info" onClick="javascript:pdf();"><i class="fa fa-print"></i>  Imprimir</a></td>
		</tr>
	</table>
	<!-- Fin Listado de Controles -->
</div>
<div id = "previa" style = "display:none;">
	<table width="100%" border="1" cellspacing="0px" cellpadding="12px">
		<tr>
			<td width="50%"></td>
			<td width="50%" style="background-color:#bbbbbb"><h3>Recibo de Pago N° <?php echo $liquidacion['COD_FISCALIZACION']; ?></h3></td>
		</tr>
		<tr>
			<td width="50%"></td>
			<td width="50%" style="background-color:#bbbbbb">Fecha de Vencimiento:</strong> <?php echo $liquidacion['FECHA_VENCIMIENTO']; ?></td>
		</tr>
		<tr>
			<td width="50%"><strong>Razón Social:</strong> <?php echo $liquidacion['RAZON_SOCIAL']; ?></td>
			<td width="50%"><strong>Nit:</strong> <?php echo $liquidacion['NITEMPRESA']; ?></td>
		</tr>
		<tr>
			<td width="50%"><strong>Liquidación N°:</strong> <?php echo $liquidacion['NUM_LIQUIDACION']; ?></td>
			<td width="50%"><strong>Concepto:</strong> <?php echo $liquidacion['NOMBRE_CONCEPTO']; ?></td>
		</tr>
		<tr>
			<td width="50%"></td>
			<td width="50%"><strong>Fecha:</strong> <?php echo $fecha; ?></td>
		</tr>
	</table>
	<br><br>
	<table width="100%" border="1" cellspacing="0px" cellpadding="12px">
		<tr style="background-color:#bbbbbb">
			<th width="50%"><b>Detalle</b></th>
			<th width="25%">Valor</th>
			<th width="25%">Valor a pagar</th>
		</tr>
		<tr>
			<td width="50%"><b>Capital</b></td>
			<td width="25%"><?php echo "$".number_format($liquidacion['TOTAL_CAPITAL'], 0, '.', '.'); ?></td>
			<td width="25%"><?php echo "$".number_format($liquidacion['TOTAL_CAPITAL'], 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="50%"><b>Intereses</b></td>
			<td width="25%"><?php echo "$".number_format($liquidacion['TOTAL_INTERESES'], 0, '.', '.'); ?></td>
			<td width="25%"><?php echo "$".number_format($liquidacion['TOTAL_INTERESES'], 0, '.', '.'); ?></td>
		</tr>
		<tr>
			<td width="50%"><b>Valor Cuota</b></td>
			<td width="25%">&nbsp;</td>
			<td width="25%"><?php echo "$".number_format($liquidacion['TOTAL_LIQUIDADO'], 0, '.', '.'); ?></td>
		</tr>
	</table>
	<br><br>
	<table width="100%" border="1" cellspacing="0px" cellpadding="12px">
		<tr>
			<td width="50%">&nbsp;</td>
			<td  style="background-color:#999999" width="25%"><b>Efectivo</b></td>
			<td width="25%">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%">&nbsp;</td>
			<td  style="background-color:#999999" width="25%"><b>Cheque</b></td>
			<td width="25%">&nbsp;</td>
		</tr>
	</table>
	<br>
</div>
<!-- Fin Estructura de Comprobante -->
<script type="text/javascript">
 function pdf()
{
	var informacion = document.getElementById("previa").innerHTML;
	document.getElementById("nombre").value ='Liquidacion';
	document.getElementById("html").value = informacion;
	$("#form2").submit();
}
</script>

<!--
/* End of file comprobante.php
-->