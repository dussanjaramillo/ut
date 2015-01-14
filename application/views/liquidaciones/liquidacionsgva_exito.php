<?php
/**
* Formulario para la busqueda de fiscalizaciones asociadas a una empresa.
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/error.php
* @last-modified  20/08/2014
*/

if( ! defined('BASEPATH') ) exit('No direct script access allowed');

?>
<div class="center-form-large" id="cabecera">
	<h2 class="text-center">Estado de Cuenta Sistema de Gestión Virtual de Aprendices</h2>
	<?php
	if (isset($message)):
    		echo $message;
	endif;
	?>
	<div class = "alert alert-success">Se ha almacenado correctamente el estado de cuenta con N° de liquidacion <?php echo $liquidacion; ?> y Expediente: <?php echo $expediente; ?> </div>
	<a class="btn btn-default" a href="<?php echo site_url(), '/liquidaciones/liquidar'; ?>" > <i class="fa fa-arrow-left"></i> Atrás </a>
</div>

<!--
/* End of file liquidaciones_error.php */
/* Location: ./system/application/views/liquidaciones/liquidaciones_error.php */
/* Dev: @jdussan 19/02/2014 */
-->