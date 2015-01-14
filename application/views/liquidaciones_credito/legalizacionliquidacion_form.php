 <?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
	<h2 class="text-center">Legalizar Liquidación</h2>
	<?php 
	if (isset($message)):
		echo $message;
	endif;
	if (isset($errorConsulta)):
		echo $errorConsulta;
	else:	
	?>
	<div class="row-fluid" id="cabecera">
		<span class="span3" ><strong>Nit: </strong><br><span class="muted"> <?php echo $empresa ['NITEMPRESA']; ?></span></span>
		<span class="span3"><strong>Empresa: </strong><br><span class="muted"><?php echo $empresa ['NOMBRE_EMPRESA']; ?></span></span>
		<span class="span3"><strong>Concepto: </strong><br><span class="muted"><?php echo $empresa ['NOMBRE_CONCEPTO']; ?> </span></span>
		<span class="span3"><strong>Número de Liquidación: </strong> <br><span class="muted"><?php echo $empresa ['NUM_LIQUIDACION']; ?></span></span>
	</div>
	<br><br>
	<?php 
		$atributos_form = array('method' => 'post', 'id' => 'legalizar');
		echo form_open_multipart('liquidaciones/loadSoporteLiquidacion', $atributos_form);
	?>
	<div id="formulario">
		<div class="row-fluid">
			<span class="span2 offset3">
				<?php echo form_label('Nro. de Radicado','numeroRadicado'); ?>
			</span>
			<span class="span4">
				<?php 
					$atributos_radicado = array ('name' => 'numeroRadicado', 'id' => 'numeroRadicado', 'class'  => 'input-large', 'type' => 'number', 'maxlength' => '15', 'required' => 'required', 'onkeypress' => 'return soloNumeros(event);');
					echo form_input($atributos_radicado);
				?>
			</span>
			<span id="alertaRadicado"></span>
		</div>
		<div class="row-fluid">
			<span class="span2 offset3">
				<?php echo form_label('Fecha Radicado','fechaRadicado') ?>
			</span>
			<span class="span4">
				<?php 
					$atributos_fecha = array ('name' => 'fechaRadicado', 'id' => 'fechaRadicado', 'class'  => 'input-large  uneditable-input', 'value' => $fecha,'readonly' => 'readonly');
					echo form_input($atributos_fecha);
				?>
			</span>
			<span id="alertaFechaRadicado"></span>
		</div>
		<div class="row-fluid">
			<span class="span2 offset3">
				<?php echo form_label('NIS','nis') ?>
			</span>
			<span class="span4">
				<?php 
					$atributos_nis = array ('name' => 'nis', 'id' => 'nis', 'class'  => 'input-large', 'required' => 'required');
					echo form_input($atributos_nis);
				?>
			</span>
			<span id="alertaNis"></span>
		</div>
		<div class="row-fluid">
			<span class="span2 offset3">
				<?php echo form_label('Adjuntar','adjuntar') ?>
			</span>
			<span class="span4">
				<?php 
					$atributos_adjuntar = array ('name' => 'userfile', 'id' => 'adjuntar', 'class'  => 'input-large', 'required' => 'required');
					echo form_upload($atributos_adjuntar);
				?>
			</span>
			<span class="span3"></span>
			<span class="span4 offset5" id="alertaAdjuntar"></span>
		</div>
	</div>
	<br><br>
	<span class="span3 offset3">
		<?php
			$atributos_boton = array('id' =>'submit-button', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="icon-file icon-white"></i> Legalizar Liquidación', 'type' => 'submit');
			echo form_button($atributos_boton) ;
			echo form_hidden('numeroLiquidacion', $empresa ['NUM_LIQUIDACION']);
			echo form_hidden('codigoConcepto', $empresa ['COD_CONCEPTO']);
			echo form_hidden('codigoFiscalizacion', $codigoFiscalizacion);
			echo form_close();
		?>	
	</span>
	<?php endif; ?>
	
</div>

<!-- Función datepicker -->
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
	$( "#fechaRadicado" ).datepicker({
		showOn: 'button',
		buttonText: 'Selecciona una fecha',
		buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
		buttonImageOnly: true,
		numberOfMonths: 1
    });
  });
</script>
<!-- Fin función datepicker -->

<!-- Función solo números -->
<script type="text/javascript" language="javascript" charset="utf-8">
function soloNumeros(e)
{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum>47 && keynum<58) || (keynum == 8))	
		return true;
	return /\d/.test(String.fromCharCode(keynum));
}
</script>
<!-- Fin Función solo números -->

<!-- Función validar extensión archivo cargado y longitud campos -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function()
{
	var max_chars = 15;
	$('#numeroRadicado').keyup(function() 
	{
        		var chars = $(this).val().length;
        		if (chars > max_chars) 
        		{ 
      			var data = $(this).val().substring(0, max_chars);
      			alert('El número de radicado no debe exceder los 15 caracteres');
      			$(this).val(data);
        		}
    	});
	$('#submit-button').click(function(){
		var exito = 0;
     		if ($("#numeroRadicado").val().length < 1) 
     		{
   			$('#alertaRadicado').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo obligatorio<p>');
   			$("#numeroRadicado").focus();
    			return false;
		}
		else 
		{
 			if(tiene_letras($("#numeroRadicado").val()) == 1)
 			{
 				$('#alertaRadicado').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo no valido<p>');
 			}
 			else
 			{
				$('#alertaRadicado').html('<p class="text-success"><i class="fa fa-check-square"></i> Campo valido<p>');
				exito += 1;
			}
		}
		if ($("#nis").val().length < 1) 
     		{
   			$('#alertaNis').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo obligatorio<p>');
   			$("#nis").focus();
    			return false;
		}
		else 
		{
			$('#alertaNis').html('<p class="text-success"><i class="fa fa-check-square"></i> Campo valido<b><p>');
			exito += 1;
		}
		if (!$("#adjuntar").val()) 
     		{
   			$('#alertaAdjuntar').html('<p class="text-warning"><b><i class="fa fa-exclamation"></i> Campo obligatorio<b><p>');
   			$("#adjuntar").focus();
    			return false;
		}
		else 
		{
			if(comprobarextension())
			{
				$('#alertaAdjuntar').html('<p class="text-success"><i class="fa fa-check-square"></i> Campo valido<b><p>');
				exito += 1;
			}
			$('#alertaAdjuntar').html('<p class="text-success"><i class="fa fa-check-square"></i> Campo valido<b><p>');
			
		}
		if (exito == 3)
		{
			$('#legalizar').submit();
		}
		else 
		{
			return false;
		}
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

 var numeros="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-.,;:_";

function tiene_letras(texto){
   for(i=0; i<texto.length; i++){
      if (numeros.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
}
</script>
<!-- Fin Función validar extensión archivo cargado -->

<!--
/* End of file legalizacionliquidacion_form.php */
/* Location: ./system/application/views/liquidaciones/legalizacionliquidacion_form.php */
/* Dev: @jdussan 13/03/2014 */
-->
