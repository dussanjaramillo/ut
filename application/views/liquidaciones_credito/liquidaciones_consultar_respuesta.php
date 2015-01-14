<?php 
/**
* Vista de respuesta una vez se genera una consulta sobre modulo de generar liquidaciones sin utilizar la traza del caso
*
* @package		Cartera
* @subpackage	Views
* @author 		jdussan
* @location   	application/views/liquidaciones_consultar_respuesta.php
* @last-modified  11/05/2014
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 
if (isset($message))
{
	echo $message;
}
?>

<!-- Cargue de formulario -->
<div class="center-form-large" id="cabecera">
	<h2 class="text-center">Generar Liquidaciones</h2>
	<span class = "span2"><b>Número de Expediente:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['COD_FISCALIZACION']?></span>
	<span class = "span2"><b>Concepto:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['NOMBRE_CONCEPTO']?></span>
	<span class = "span2"><b>Nit:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['NIT_EMPRESA']?></span>
	<span class = "span2"><b>Razón Social:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['NOMBRE_EMPRESA']?></span>
	<span class = "span2"><b>Periodo Inicial:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['PERIODO_INICIAL']?></span>
	<span class = "span2"><b>Periodo Final:</b></span>
	<span class = "span2"><?php echo $fiscalizacion['PERIODO_FINAL']?></span>
	<p>&nbsp;</p>
	<?php		
		$atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
		echo form_open('liquidaciones/administrar', $atributos_formulario);
		
		$atributos_codigoFiscalizacion = array ('id' => 'codigoFiscalizacion', 'name' => 'codigoFiscalizacion', 'type' => 'hidden', 'value' => $expediente);
		echo form_input($atributos_codigoFiscalizacion);
		
	?>
	<span class = "span2">&nbsp;</span>
	<span class="span2">
	<a class="btn" href="<?php echo site_url(); ?>/liquidaciones/liquidar"><i class="icon-remove"></i> Cancelar</a>
	</span>
	<span class="span2">
	<?php
		$atributos_boton = array('id' =>'liquidar', 'name' => 'liquidar', 'class' => 'btn btn-success', 'content' => '<i class="icon-briefcase icon-white"></i>  Liquidar', 'type' => 'submit');
		echo form_button($atributos_boton) ; 
		
		echo form_close();
	?>
	</span>
	<span class = "span2">&nbsp;</span>
</div>
<!-- Fin Cargue de formulario -->
<br><hr>

<!-- Loader -->
<div id="loader" style="display:none; text-align:center;">
	<i class="fa fa-refresh fa-spin fa-3x"></i>
</div>
<!-- Fin Loader -->

<!-- Vista de traza -->
<div id='loaddataTable2'> </div>
<!-- Fin Vista de traza -->

<!-- Función para la carga de loader y traza -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function()
{
	var id = $("#codigoFiscalizacion").val();
	var url = "<?php echo site_url('/fiscalizacion/dataTableGestion') ?>";

	$('#loader').fadeIn('slow');	
	$('#loaddataTable2').load(url,{id : id}, function() {$('#loader').fadeOut('fast');});
});
</script>
<!-- Fin Función para la carga de loader y traza -->

<?php 
/**
* End of file liquidaciones_consultar_respuesta.php
*/
?>