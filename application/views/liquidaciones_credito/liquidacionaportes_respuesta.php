 <?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
	<h2 class="text-center">Liquidación Aportes Parafiscales</h2>
	<?php 
	if (isset($message)):
		echo $message;
	endif;
	// print_r($detalle);
	?>
	<table id="resumen" class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Total Aportes</th>
			<th>Pagos Aportes a Descontar</th>
			<th>Debe al SENA</th>
			<th>Intereses</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><?php if (isset($annoInicial)): echo $annoInicial; endif; ?></th>
			<td><?php echo "$".number_format($detalle['aportes_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['aportes_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['deuda_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['intereses_'.$annoInicial], 0, '.', '.'); ?></td>
			<td></td>
		</tr>
		<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
		?>
		<tr>	
			<th><?php echo $anno; ?></th>
			<td><?php echo "$".number_format($detalle['aportes_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['aportes_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['deuda_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['intereses_'.$anno], 0, '.', '.'); ?></td>
			<td></td>
		</tr>
		<?php 
					$anno --; 
				endfor; 
			endif; 
		?>
		<tr>
			<th>Total</th>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
	</table>
	<br><br>
	<?php
		$atributos_form = array('method' => 'post', 'id' => 'aportes');
		echo form_open('', $atributos_form);
	?>
	<div class="row-fluid">
  		<div class="span12"><strong>Observaciones</strong></div>
  		<div class="span12">
  			<?php 
  				$atributos_observaciones =  array('id' => 'observaciones' , 'name' => 'observaciones', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%');
				echo form_textarea($atributos_observaciones);
  			?>
  			<div id="contadorObservaciones"></div>
  			<br>
  		</div>
	<div class="row-fluid">
  		<div class="span12"><strong>Documentación Aportada</strong></div>
  		<div class="span12">
  			<?php 
  				$atributos_documentacion =  array('id' => 'documentacion' , 'name' => 'documentacion', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%');
				echo form_textarea($atributos_documentacion);
  			?>
  			<div id="contadorDocumentacion"></div>
  		</div>
	</div>
	<div class="row-fluid">
		<div class="span5 offset5">
			<br><br>
			<?php
				$atributos_boton = array('id' =>'aceptar', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="icon-cog icon-white"></i> Aceptar', 'type' => 'submit');
				echo form_button($atributos_boton) ;
				echo form_close();
			?>
		</div>
	</div>
</div>

<script  type="text/javascript">

var limite = 2000;

$(document).ready(function()
{
 	$("#observaciones").keyup(function(e)
    	{
        		// obtenemos el texto que está escrito en el textarea
        		var box = $(this).val();
        		// obtenemos cuántos caracteres quedan
        		var resta = limite - box.length;
        		//validamos el porcentaje alcanzado
        		var porcentaje = 0;
        		porcentaje = (box.length/limite)*100
        		// alert (porcentaje + '%')
        		// si aún no se llegó al límite
        		if(box.length <= limite)
        		{
           			// modificamos el texto que muestra la cantidad de caracteres que restan
            		$('#contadorObservaciones').html('<p class="text-info"><i class="fa fa-info-circle"></i> Te quedan '+ resta + ' caracteres<p>');
           			 // modificamos la clase del mensaje cuando supere el 80% del tamaño total
           			 if(porcentaje > 75)
           			 {
           			 	 $('#contadorObservaciones').html('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> Te quedan '+ resta + ' caracteres<p>');
           			 }
       		 }
        		else // si se llegó al límite no permitimos ingresar más caracteres
        		{
            		// evitamos que ingrese más caracteres
            		e.preventDefault();
        		}               
    	});
	
	$("#documentacion").keyup(function(e)
    	{
        		// obtenemos el texto que está escrito en el textarea
        		var box = $(this).val();
        		// obtenemos cuántos caracteres quedan
        		var resta = limite - box.length;
        		//validamos el porcentaje alcanzado
        		var porcentaje = 0;
        		porcentaje = (box.length/limite)*100
        		// alert (porcentaje + '%')
        		// si aún no se llegó al límite
        		if(box.length <= limite)
        		{
           			// modificamos el texto que muestra la cantidad de caracteres que restan
            		$('#contadorDocumentacion').html('<p class="text-info"><i class="fa fa-info-circle"></i> Te quedan '+ resta + ' caracteres<p>');
           			 // modificamos la clase del mensaje cuando supere el 80% del tamaño total
           			 if(porcentaje > 75)
           			 {
           			 	 $('#contadorDocumentacion').html('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> Te quedan '+ resta + ' caracteres<p>');
           			 }
       		 }
        		else // si se llegó al límite no permitimos ingresar más caracteres
        		{
            		// evitamos que ingrese más caracteres
            		e.preventDefault();
        		}               
    	});
});
</script>

<!--
/* End of file liquidacionaportes_respuesta.php */
/* Location: ./system/application/views/liquidaciones/liquidacionaportes_respuesta.php */
/* Dev: @jdussan 19/02/2014 */
-->