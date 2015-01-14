<?php 
/**
* Formulario para el cargue de validaciónd de notificaciones de liquidaciones de crédito por cartelera
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/verificar_cartelera.php
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
		$atributos_form = array('method' => 'post', 'id' => 'legalizar');
		echo form_open_multipart('liquidaciones_credito/cargarSoporteCartelera', $atributos_form);
	?>
	<h2 class="text-center">Validar Notificaciones de Liquidación de Crédito por Carteleras</h2>
	<br><br>
	<table>
		<tr>
			<td>
				<?php 
					echo form_label('<b>Fecha de Liquidación:</b>','fechaLiquidacion');
					$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'class'  => 'input-small uneditable-input', 'value' => $fecha, 'required' => 'required', 'readonly' => 'readonly' );
					echo form_input($atributos_fechaLiquidacion);
				?>
				</td>
		</tr>
		<tr>	
			<td>
				<?php 
					$atributos_adjuntar = array ('name' => 'userfile', 'id' => 'adjuntar', 'class'  => 'input-large', 'required' => 'required');
					echo form_upload($atributos_adjuntar);
				?>
				<span id="alertaAdjuntar"></span>
			</td>
		</tr>
		<tr>
			<td>Observaciones:<br>
				<?php 
  					$atributos_observaciones =  array('id' => 'observaciones' , 'name' => 'observaciones', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%');
					echo form_textarea($atributos_observaciones);
  				?>
			</td>
		</tr>
	</table>
   <table>
		<tr>
			<td style = "text-align:center;">
				<a class="btn" href="<?php echo site_url(); ?>/liquidaciones_credito/formularioExpedienteCarteleras"><i class="icon-remove"></i> Cancelar</a>
			</td>
			<td style = "text-align:center;">
				<?php
					$atributos_boton = array('id' =>'notificar', 'name' => 'notificar', 'class' => 'btn btn-success', 'content' => '<i class="icon-file icon-white"></i>  Verificar', 'type' => 'submit');
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

<!-- Función Datepicker -->
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
		minDate: '0d'
   	 });
});

function comprobarextension() 
{
	if ($("#adjuntar").val() != "") 
	{
      		var archivo = $("#adjuntar").val();
      		var extensiones_permitidas = new Array(".pdf");
      		var mierror = "";
      		//recupero la extensión de este nombre de archivo
      		var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
      		//alert (extension);
      		//compruebo si la extensión está entre las permitidas
      		var permitida = false;
      		for (var i = 0; i < extensiones_permitidas.length; i++) 
      		{
        			if (extensiones_permitidas[i] == extension) 
        			{
          				permitida = true;
          				break;
        			}
     		 }
      		if (!permitida) 
      		{
        		jQuery("#adjuntar").val("");
        		mierror = "Comprueba la extensión de los archivos a subir en el campo adjuntar.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
      		}
      		//si estoy aqui es que no se ha podido submitir
      		if (mierror != "") 
      		{
        			alert(mierror);
        			return false;
      		}
      	return true;
    	}
  }
</script>
<!-- Fin Función datepicker -->

<!--
/* End of file notificar_cartelera.php */
-->