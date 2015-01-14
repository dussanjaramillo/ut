<?php 
/**
* Formulario para la consulta procesos de liquidaciones de crédito que pueden generar notificaciones web
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/consultar_expediente_web.php
* @last-modified  25/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

if (isset($message)){
    echo $message;
   }
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: center; vertical-align: middle; width: 50%;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Cargue de formulario -->
<div class="center-form-large" id="cabecera">
	<?php
		echo validation_errors(); //muestra los errores de validación del formulario
		$atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
		echo form_open('liquidaciones_credito/consultarExpedienteWeb', $atributos_formulario);
	?>
	<h2 class="text-center">Consultar Expedientes para Notificación Web</h2>
	<br><br>
	<table>
		<tr>
			<td>
				<?php echo form_label('Número de Expediente: ','expediente'); ?>
				<?php 
					$atributos_expediente = array ('id' => 'expediente', 'name' => 'expediente', 'type' => 'number', 'class'  => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);', 'value' => set_value('expediente'));
					echo form_input($atributos_expediente);
				?>
			</td>
			<td>
				<?php 
					$atributos_boton = array('id' =>'consultar', 'name' => 'consultar', 'class' => 'btn btn-success', 'content' => '<i class="icon-search icon-white"></i>  Consultar', 'type' => 'submit');
					echo form_button($atributos_boton); 
				?>
			</td>
		</tr>
		<tr>
			<td><span id = "alertaExpediente">&nbsp;</span></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<?php
		echo form_close();
	?>
</div>
<!-- Fin cargue de formulario -->

<!-- Función validaciones interfaz -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function()
{
	var max_chars = 15; //cantidad de caracteres máximos
	var exito = 0; //criterios de éxito
	
	$('#expediente').keydown(function() 
	{
        var chars = $(this).val().length; //cantidad de caracteres digitados
		
		if (chars > max_chars) //verificar la cantidad de caracteres
   		{ 
      		var data = $(this).val().substring(0, max_chars);
      		$('#alertaExpediente').html('<p class="text-warning"><i class="fa fa-warning"></i> El expediente no puede exceder los 15 digitos<p>');
      		$(this).val(data);
       	}
		else
		{	
			var data = $(this).val().substring(0, max_chars);
			$('#alertaExpediente').html('');
      		$(this).val(data);
		}
    });
	
	$('#consultar').click(function()
	{
		
		if ($("#expediente").val().length < 1) //verificar que el número de expediente no esta vacio
		{
			$('#alertaExpediente').html('<p class="text-warning"><i class="fa fa-warning"></i> El expediente no puede no puede ser un valor vacio<p>');
		}
		else 
		{
			exito++;
		}
		
		if (exito == 1) //verificación  de criterios de éxito
		{
			$('#liquidar').submit(); 
		}
		else 
		{
			return false;
		}
	});
});
</script>
<!-- Fin Función validaciones interfaz -->

<!-- Función solo números -->
<script type="text/javascript" language="javascript" charset="utf-8">
function num(c)
{
	c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
}
</script>
<!-- Fin Función solo números -->

<!--
/* End of file consultar_expediente_web.php */
-->