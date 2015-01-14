 <?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
	<h2 class="text-center">Carta Remisión de Liquidación</h2>
	<?php 
	if (isset($message)):
		echo $message;
	endif;
	?>
	<table class="table table-striped" id="opciones">
		<thead>
			<tr>
				<th>Ítem</th>
				<th>Combinación de Comuniación</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				//Cantidad de resultados de la consulta de yipos en la DB
				$filas =  count($tipos);
				//Arranque formulario
				$atributos_form = array('method' => 'post', 'id' => 'remision');
				echo form_open_multipart('liquidaciones/loadRemision', $atributos_form);
				//Ciclo para recorrer opciones
				for($i = 0; $i < $filas; $i++):
					$opciones = array('name' => 'opcion', 'id' => 'opcion'.$tipos[$i]['COD_COMB_TIP_APORTANTE'], 'value' => $tipos[$i]['COD_COMB_TIP_APORTANTE']);
			?>
				<tr>
					<td><?php echo form_radio($opciones); ?></td>
					<td><?php echo form_label($tipos[$i]['DESCRIPCION_COMB_TIPO'], 'opcion'.$tipos[$i]['COD_COMB_TIP_APORTANTE']); ?></td>
				</tr>
			<?php
				endfor;
			?>
		</tbody>
	</table>
	<div class="row-fluid">
		<span class="span4 offset4">
			<?php
				$atributos_boton = array('id' =>'enviar', 'name' => 'enviar', 'class' => 'btn btn-success', 'content' => '<i class="icon-pencil icon-white"></i> Legalizar Liquidación', 'type' => 'submit');
				echo form_button($atributos_boton) ;
				echo form_hidden('cod_gestion_liquidacion', $codigo);
				echo form_close();
			?>
		</span>
	</div>
</div>

<!-- Validar formulario para que al menos una opción este seleccionado -->
<script>
	$("#remision").submit( function(){
        var radio = $("input[type='radio']:checked").length; 
			if(radio == "")
			{
                alert("Debe seleccionar al menos una opción");
                return false;
            } 
			else 
			{
                return true;
            }   
    });
</script>
<!-- Fin validar formulario para que al menos una opción este seleccionado -->
<!--
/* End of file liquidacioncarta_form.php */
/* Location: ./system/application/views/liquidaciones/liquidacioncarta_form.php */
/* Dev: @jdussan 19/02/2014 */
-->