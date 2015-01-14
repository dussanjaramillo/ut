<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
	<h2 class="text-center">Legalizar Liquidación</h2>
	<?php 
	if (isset($message))
	{
    		echo $message;
	}
	?>
	<div class="alert alert-success">
		Se ha registrado exitosamente la legalización de la liquidación: <strong><em> <?php echo $liquidacion; ?> </em></strong>	
	</div>
	<a class="btn btn-primary" a href="#" onClick="history.go(-2); return false;"> <i class="icon-arrow-left"></i> Volver </a>
</div>

<!--
/* End of file legalizacionliquidacion_exito.php */
/* Location: ./system/application/views/liquidaciones/legalizacionliquidacion_exito.php */
/* Dev: @jdussan 30/01/2014 */
-->