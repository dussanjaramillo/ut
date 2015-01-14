<?php 
/**
* Formulario para la consulta de liquidaciones de proceso administrativo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/liquidaciones_consultar.php
* @last-modified  23/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 

if (isset($message)){
    echo $message;
   }
?>
<!-- Cargue de formulario -->
<div class="center-form-large" id="cabecera">
	<?php
		echo validation_errors();
		
		$atributos_formulario = array('class' => 'form-inline', 'id' => 'liquidar');
		echo form_open('liquidaciones/getLiquidacion', $atributos_formulario);
	?>
		<h2 class="text-center">Generar Liquidaciones</h2>
		<br><br>
		<div class="controls controls-row">
			<span class = "span1">&nbsp;</span>
			<span class = "span3">
			<?php echo form_label('Número de Expediente: ','expediente'); ?>
			<?php 
				$atributos_expediente = array ('id' => 'expediente', 'name' => 'expediente', 'type' => 'number', 'class'  => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);', 'value' => set_value('expediente'));
				echo form_input($atributos_expediente);
			?>
			</span>
			<span class = "span1">&nbsp;</span>
			<span class="span3">
			<?php 
    			$atributos_boton = array('id' =>'consultar', 'name' => 'consultar', 'class' => 'btn btn-success', 'content' => '<i class="icon-search icon-white"></i>  Consultar', 'type' => 'submit');
				echo form_button($atributos_boton); 
			?>
			</span>
			<span class = "span1">&nbsp;</span>
			<span class = "span6" id = "alertaExpediente">&nbsp;</span>
			<?php
				echo form_close();
			?>
			
        </div>
    </form>
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
	
	$('#consultar').click(function(){
		
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
	function soloNumeros(e) {
		key = e.keyCode || e.which;
		tecla = String.fromCharCode(key);//.toLowerCase();
		letras = '0123456789';//" áéíóúabcdefghijklmnñopqrstuvwxyz";
		especiales = [8, 37, 39, 46];

		tecla_especial = false;
		for(var i in especiales) {
			if(key == especiales[i]) {
				tecla_especial = true;
				break;
			}
		}	
		if(letras.indexOf(tecla) == -1 && !tecla_especial)
			return false;
	}
	
	function num(c){
		c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
}

	
</script>
<!-- Fin Función solo números -->

<!--
/* End of file liquidaciones_consultar.php */
/* Location: ./system/application/views/liquidaciones/liquidaciones_consultar.php */
/* Dev: @jdussan 02/04/2014 */
-->