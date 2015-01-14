<?php 
/**
* Formulario para la consulta de liquidaciones de proceso administrativo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/liquidar_expediente.php
* @last-modified  23/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 
// print_r($liquidacion);
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
		echo form_open('liquidaciones_credito/generarLiquidacion', $atributos_formulario);
	?>
	<h2 class="text-center">Generar Liquidación de Crédito</h2>
	<br><br>
	<table>
		<tr>
			<td><strong>NIT:</strong><br><span class="muted"><?php echo $liquidacion['NIT_EMPRESA']; ?></span></td>
			<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $liquidacion['RAZON_SOCIAL']; ?></span></td>
			<td><strong>Concepto:</strong><br><span class="muted"><?php echo $liquidacion['NOMBRE_CONCEPTO']; ?></span></td>
			<td><strong>Instancia:</strong><br><span class="muted"><?php echo $liquidacion['TIPOGESTION']; ?></span></td>
		</tr>
		<tr>
			<td><strong>Representante Legal:</strong><br><span class="muted"><?php echo $liquidacion['REPRESENTANTE_LEGAL']; ?></span></td>
			<td><strong>Teléfono:</strong><br><span class="muted"><?php echo $liquidacion['TELEFONO_FIJO']; ?></span></td>
			<td colspan = "2"><strong>Estado:</strong><br><span class="muted"><?php echo $liquidacion['NOMBRE_GESTION']; ?></span></td>
		</tr>
		<tr>
			<td><strong>Fecha Inicio:</strong><br><span class="muted"><?php echo $liquidacion['FECHA_INICIO']; ?></span></td>
			<td><strong>Fecha Fin:</strong><br><span class="muted"><?php echo $liquidacion['FECHA_FIN']; ?></span></td>
			<td><strong>Capital:</strong><br><span class="muted"><?php echo "$".number_format($liquidacion['TOTAL_CAPITAL'], 0, '.', '.'); ?></span></td>
			<td><strong>Intereses:</strong><br><span class="muted"><?php echo "$".number_format($liquidacion['TOTAL_INTERESES'], 0, '.', '.'); ?></span></td>
		</tr>
	</table>
	<hr>
	<table>
		<tr>
			<td colspan = "4" style = "text-align:center;">
				<?php 
				echo form_label('<strong>Fecha de Liquidación:&nbsp;&nbsp;</strong>','fechaLiquidacion');  
				$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'class'  => 'input-small uneditable-input', 'value' => $fecha, 'required' => 'required', 'readonly' => 'readonly' );
				echo form_input($atributos_fechaLiquidacion);
				?>
			</td>
		</tr>
		<tr>
			<th>Costas</th>
		</tr>
		<tr>
			<td>
				<?php
				echo form_label('Honorarios:&nbsp;&nbsp;','honorarios');  
				$atributos_honorarios = array ('name' => 'honorarios', 'id' => 'honorarios', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_honorarios), '</div>';
				?>
			</td>
		</tr>
		<tr>
			<th>Gastos Procesales</th>
		</tr>
		<tr>
			<td>
				<?php
				echo form_label('Gastos Procesales:&nbsp;&nbsp;','transporte');  
				$atributos_transporte = array ('name' => 'transporte', 'id' => 'transporte', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_transporte), '</div>';
				?>
			</td>
			<td>
				<?php
				// echo form_label('Arrendamientos:&nbsp;&nbsp;','arrendamiento');  
				// $atributos_arrendamientos = array ('name' => 'arrendamiento', 'id' => 'arrendamiento', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				// echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_arrendamientos), '</div>';
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				// echo form_label('Avisos:&nbsp;&nbsp;','avisos');  
				// $atributos_avisos = array ('name' => 'avisos', 'id' => 'avisos', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				// echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_avisos), '</div>';
				?>
			</td>
			<td>
				<?php
				// echo form_label('Otros:&nbsp;&nbsp;','otros');  
				// $atributos_otros = array ('name' => 'otros', 'id' => 'otros', 'value' => 0, 'class' => 'input-large', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				// echo '<div class="input-prepend"><span class="add-on">$</span>', form_input($atributos_otros), '</div>';
				?>
			</td>
		</tr>
		<!--<tr>
			<th>Observaciones</th>
		</tr>
		<tr>
			<td colspan = "2">
				<?php 
  				// $atributos_observaciones =  array('id' => 'observaciones' , 'name' => 'observaciones', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%');
				// echo form_textarea($atributos_observaciones);
  				?>
			</td>
		</tr>-->
		<tr>
			<td style = "text-align:center;">
				<a class="btn" href="<?php echo site_url(); ?>/liquidaciones_credito/formularioExpediente"><i class="icon-remove"></i> Cancelar</a>
			</td>
			<td style = "text-align:center;">
				<?php
				$datos_liquidacion = array('liquidacion' => serialize($liquidacion));
				echo form_hidden($datos_liquidacion);
				$atributos_boton = array('id' =>'liquidar', 'name' => 'liquidar', 'class' => 'btn btn-success', 'content' => '<i class="icon-briefcase icon-white"></i>  Liquidar', 'type' => 'submit');
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

<!-- Función datepicker /post / modal -->
<script type="text/javascript" language="javascript" charset="utf-8">
  $(function() {
	//Array para dar formato en español
	$.datepicker.regional['es'] = 
	{
		closeText: 'Cerrar', 
		prevText: 'Previo', 
		nextText: 'Próximo',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		monthStatus: 'Ver otro mes', yearStatus: 'Ver otro año',
		dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		dateFormat: 'dd/mm/yy', firstDay: 0, 
		 initStatus: 'Selecciona la fecha', isRTL: false
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$( "#fechaLiquidacion" ).datepicker({
		showOn: 'button',
		buttonText: 'Selecciona una fecha',
		buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
		buttonImageOnly: true,
		numberOfMonths: 1,
		maxDate: '29d',
		minDate: '0d'
   	 });
});
 </script>
<!-- Fin función DatePicker -->
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
function num(c)
{
	c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
}
</script>
<!-- Fin Función solo números -->

<!--
/* End of file consultar_expediente.php */
-->