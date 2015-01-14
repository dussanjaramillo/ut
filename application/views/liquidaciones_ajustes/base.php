<?php
/**
* Formulario para pruebas y cargue.
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/liquidaciones_base.php
* @last-modified  24/10/2014
*/

if( ! defined('BASEPATH') ) exit('No direct script access allowed');

if (isset($message)){
 	echo $message;
}
?>
<table class = "table table-bordered table-striped">
	<tr>
		<th>Año</th>
		<th>Mes</th>
		<th>Dias</th>
		<th>Capital</th>
		<th>Intereses</th>
		<th>Tasa</th>
		<th>Int. Generado</th>
		<th>Int. Total</th>
	</tr>
	<?php
		foreach($liquidacion as $registro):
			if(is_array($registro)):
				foreach($registro as $meses):
	?>
	<tr>
		<td><?php echo $meses['anno']; ?></td>
		<td><?php echo $meses['mes']; ?></td>
		<td><?php echo $meses['dias']; ?></td>
		<td><?php echo "$".number_format($meses['capital'], 0, '.', '.'); ?></td>
		<td><?php echo "$".number_format($meses['interes'], 0, '.', '.'); ?></td>
		<td><?php echo $meses['tasa']; ?></td>
		<td><?php echo "$".number_format($meses['interes_generado'], 0, '.', '.'); ?></td>
		<td><?php echo "$".number_format($meses['total'], 0, '.', '.'); ?></td>
	</tr>
	<?php
				endforeach;
			endif;
		endforeach;
	?>
</table>
<?php
echo 'CAPITAL: ', "$".number_format($liquidacion['capital'], 0, '.', '.'), '</br>';
echo 'INTERESES: ', "$".number_format($liquidacion['intereses'], 0, '.', '.'), '</br>';
echo 'DIAS: ', $liquidacion['dias'], '</br>';
// var_dump($liquidacion);
?> <!--
/* End of file consultar.php */
-->