 <?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- Estructura de Comprobante -->
 <div class="center-form-large" id="cabecera">
	<?php 
	if (isset($message)):
		echo $message;
	endif;
	print_r($liquidacion);
	?>
	<span class = "span4"><img src="<?php echo base_url() . 'img/logotipo.png'; ?>"></span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4"><h3><?php echo 'Recibo de pago N° ' . $liquidacion['COD_INTERES_MULTA_MIN']; ?></h3></span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4"><strong>Fecha de Vencimiento:</strong> <?php echo $liquidacion['FECHA_LIQUIDACION']; ?></span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4"><strong>Razón Social:</strong> <?php echo $liquidacion['NOMBRE_EMPRESA']; ?></span>
	<span class = "span4"><strong>Nit:</strong> <?php echo $liquidacion['NIT_EMPRESA']; ?></span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4"><strong>Liquidación N°:</strong> 12345</span>
	<span class = "span4"><strong>Concepto:</strong> Intereses Multas de Ministerio</span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4">&nbsp;</span>
	<span class = "span4"><strong>Fecha:</strong> <?php echo $fecha; ?></span>	
	<br><hr>
	<span class = "span8">&nbsp;</span>
		<table class="table table-striped" width="100%">
			<tr>
				<th width="33%">Detalle</th>
				<th width="33%">Valor</th>
				<th width="33%">Valor a pagar</th>
			</tr>
			<tr>
				<td width="33%">Capital</td>
				<td width="33%">$150.000.000</td>
				<td width="33%">$150.000.000</td>
			</tr>
			<tr>
				<td width="33%">Intereses</td>
				<td width="33%">$20.000.000</td>
				<td width="33%">$20.000.000</td>
			</tr>
			<tr>
				<td width="33%">Valor Cuota</td>
				<td width="33%">&nbsp;</td>
				<td width="33%">$170.000.000</td>
			</tr>
		</table>
		<table class="table table-striped" width="100%">
			<tr>
				<td width="33%">&nbsp;</td>
				<td width="33%">Efectivo</td>
				<td width="33%">&nbsp;</td>
			</tr>
			<tr>
				<td width="33%">&nbsp;</td>
				<td width="33%">Cheque</td>
				<td width="33%">&nbsp;</td>
			</tr>
		</table>
	
</div>
<!-- Fin Estructura de Comprobante -->
<br><hr>
<!-- Listado de Controles -->
<div id="controles" class="controls controls-row">
	<span class="span5"></span>
	<span class="span2"><a class="btn btn-success" onClick="javascript:printdiv('cabecera');"><i class="icon-print icon-white"></i>  Imprimir</a></span>	
	<span class="span5"></span>
</div>
<!-- Fin Listado de Controles -->
<script type="text/javascript">
function printdiv(id_cabecera)
{
	var tittle = document.getElementById(id_cabecera);
	// var content = document.getElementById(id_cuerpo);
	var printscreen = window.open('','','left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
	printscreen.document.write(tittle.innerHTML);
	// printscreen.document.write(content.innerHTML);
	printscreen.document.close();

	var css = printscreen.document.createElement("link");
	css.setAttribute("href", "<?php echo base_url()?>/css/bootstrap.css");
	css.setAttribute("rel", "stylesheet");
	css.setAttribute("type", "text/css");

	var css1 = printscreen.document.createElement("link");
	css1.setAttribute("href", "<?php echo base_url()?>/css/style.css");
	css1.setAttribute("rel", "stylesheet");
	css1.setAttribute("type", "text/css");

	printscreen.document.head.appendChild(css);
	printscreen.document.head.appendChild(css1);

	printscreen.focus();
	printscreen.print();
	printscreen.close();
}
</script>
	
<!--
/* End of file comprobante.php */
/* Location: ./system/application/views/liquidaciones/comprobante.php */
/* Dev: @jdussan 07/04/2014 */
-->