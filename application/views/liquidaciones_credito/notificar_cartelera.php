<?php 
/**
* Formulario para la generación de notificaciones web para liquidaciones de crédito
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/notificar_cartelera.php
* @last-modified  25/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 
//print_r($liquidacion);
if (isset($message))
{
    echo $message; 
}
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td, th{padding: 5px; text-align: left; vertical-align: middle;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Cargue de formulario -->
<div class="center-form-large" id="cabecera">
	<?php
		echo validation_errors(); //muestra los errores de validación del formulario
		$atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
		echo form_open('liquidaciones_credito/grabarNotificacionCarteleras', $atributos_formulario);
	?>
	<h2 class="text-center">Generar Notificaciones de Liquidación de Crédito por Carteleras</h2>
	<br><br>
	<table>
		<tr>
			<td><strong>NIT:</strong><br><span class="muted"><?php echo $liquidacion['nit']; ?></span></td>
			<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $liquidacion['razonSocial']; ?></span></td>
		</tr>
		<tr>	
			<td><strong>Concepto:</strong><br><span class="muted"><?php echo $liquidacion['concepto']; ?></span></td>
			<td><strong>Instancia:</strong><br><span class="muted"><?php echo $liquidacion['instancia']; ?></span></td>
		</tr>
		<tr>
			<td><strong>Representante Legal:</strong><br><span class="muted"><?php echo $liquidacion['representanteLegal']; ?></span></td>
			<td><strong>Teléfono:</strong><br><span class="muted"><?php echo $liquidacion['telefono']; ?></span></td>
		</tr>
		<tr>	
			<td colspan = "2"><strong>Estado:</strong><br><span class="muted"><?php echo $liquidacion['estado']; ?></span></td>
		</tr>
	</table>
	<textarea id="informacion" style="width: 100%;height: 400px">
		<?php 
			echo($plantilla);
		?>
   </textarea>
   <table>
		<tr>
			<td style = "text-align:center;">
				<a class="btn" href="<?php echo site_url(); ?>/liquidaciones_credito/formularioExpedienteCarteleras"><i class="icon-remove"></i> Cancelar</a>
			</td>
			<td style = "text-align:center;">
				<?php
				$atributos_boton = array('id' =>'notificar', 'name' => 'notificar', 'class' => 'btn btn-success', 'content' => '<i class="icon-ok icon-white"></i>  Notificar', 'type' => 'submit');
				echo form_button($atributos_boton) ; 
				?>
			</td>
		</tr>
	</table>
	<?php
		echo form_close();
	?>
</div>
<!-- Fin cargue de formulario -->

<!-- Función Editor Tiny -->
<script type="text/javascript" language="javascript" charset="utf-8">
tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        language : 'es',
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor"// moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });
</script>
<!-- Fin Función Editor Tiny -->

<!--
/* End of file notificar_cartelera.php */
-->