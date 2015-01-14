<?php 
/**
* Formulario para mostrar errores de ejecución o consulta del proceso de liquidaciones de crédito
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/liquidaciones_error.php
* @last-modified  23/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

if (isset($message)){
    //echo $message;
   }
?>
<div class="center-form-xlarge" id="cabecera">
	<h2 class="text-center"><?php echo $titulo ?></h2>
	<?php 
	if (isset($message)):
    		echo $message;
	endif;
	?>
	<a class="btn btn-warning" href="<?php echo site_url(); ?>"><i class="fa fa-minus-circle"></i> Cancelar</a>
</div>

<!--
/* End of file liquidaciones_error.php */
-->