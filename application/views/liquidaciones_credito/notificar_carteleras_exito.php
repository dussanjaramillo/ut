<?php 
/**
* Respuesta tras la generación de notificación Web
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/notificar_carteleras_exito.php
* @last-modified  25/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

if (isset($message)){
    echo $message;
   }
?>
<div class="center-form-large" id="cabecera">
	<h2 class="text-center">Generar Notificaciones de Liquidación de Crédito por Carteleras</h2>
	<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado una nueva notificación por Carteleras con éxito </div>
	<a class="btn" href="<?php echo site_url(); ?>/liquidaciones_credito/formularioExpedienteCarteleras"><i class="icon-arrow-left"></i> Volver</a>
</div>

<!--
/* End of file notificar_carteleras_exito.php */
-->