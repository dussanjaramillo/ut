<?php
/**
* Formulario para el calculo de intereses por Resolución de Multas del Ministerio de Trabajo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/comprobante.php
* @last-modified  31/08/2014
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
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: left; vertical-align: middle;}
#informe{display:none; width:90%; margin-left:auto; margin-right:auto;}
#loader{display:none; text-align:center;}
#vistaPrevia_contenedor, #cabecera_base{display:none;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Estructura de Comprobante -->
 <div class="center-form-large" id="cabecera">
	<?php
	if (isset($message)):
		echo $message;
	endif;
	//print_r($liquidacion);
	?>
	<table class = "table table-bordered tabled striped" width = "100%">
		<tr>
			<td width = "50%" rowspan = "2"><img src="<?php echo base_url() . 'img/Logotipo.png'; ?>"></td>
			<td width = "50%" class = "text-left"><h3><?php echo 'Recibo de pago N° ' . $liquidacion['COD_INTERES_MULTA_MIN']; ?></h3></td>
		</tr>
		<tr>
			<td width = "50%" class = "text-left"><strong>Fecha de Vencimiento:</strong> <?php echo $liquidacion['FECHA_LIQUIDACION']; ?></td>
		</tr>
		<tr>
			<td width = "50%" class = "text-left"><strong>Razón Social:</strong> <?php echo $liquidacion['NOMBRE_EMPRESA']; ?></td>
			<td  width = "50%" class = "text-left"><strong>Nit:</strong> <?php echo $liquidacion['NIT_EMPRESA']; ?></td>
		</tr>
		<tr>
			<td  width = "50%" class = "text-left"><strong>Liquidación N°:</strong> 12345</td>
			<td  width = "50%" class = "text-left"><strong>Concepto:</strong> Intereses Multas de Ministerio</td>
		</tr>
		<tr>
			<td  width = "50%" class = "text-left"><strong>Fecha:</strong> <?php echo $fecha; ?></td>
			<td  width = "50%" class = "text-left"></td>
		</tr>
	</table>
	<br><hr>
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
	<table>
		<tr>
			<td></td>
			<td></td>
		</tr>
	</table>
	<span class="span5"></span>
	<span class="span2"><a class="btn btn-info" onClick="javascript:printdiv('cabecera');"><i class="fa fa-print"></i>  Imprimir</a></span>
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
-->