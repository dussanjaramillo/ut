<?php
/**
* Formulario para el calculo de liquidaciones de Aportes Parafiscales. Datos para las bases anuales y calcluo de intereses por mora o incumplimiento en el pago de aportes.
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/liquidacionaportes_form.php
* @last-modified  14/11/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');

if (isset($message)):

    echo $message;

endif;

//var_dump($fiscalizacion);

if(isset($documentacion)):

    $observaciones = $documentacion['OBSERVACIONES'];
	$documentaciones = $documentacion['DOCU_APORTADA'];

endif;

//ATRIBUTOS FORMULARIO APORTES
$atributos_form = array('method' => 'post', 'id' => 'aportes');
$atributos_observaciones =  array('id' => 'observaciones' , 'name' => 'observaciones', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%', 'value' => $observaciones, 'placeholder' => 'Observaciones');
$atributos_documentacion =  array('id' => 'documentacion' , 'name' => 'documentacion', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%', 'value' => $documentaciones, 'placeholder' => 'Documentación Aportada');
?>
<style type="text/css">
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{z-index: 15000;}
    .dialogo
    {
        margin: 0;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 20px;
        color: #333333;
        background-color: #ffffff;
    }
</style>
<div class="center-form-xlarge">
	<h2 class="text-center">Liquidación Aportes Parafiscales</h2>
	<table id="resumen" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th></th>
			<th>Base Anual</th>
			<th>Total Aportes</th>
			<th>Pagos Aportes a Descontar</th>
			<th>Debe al SENA</th>
			<th>Intereses</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><?php
					$acumulador_base = 0;
					$acumulador_deuda = 0;
					$acumulador_aportes = 0;
					$acumulador_capital = 0;
					$acumulador_interes = 0;
					$acumulador_total = 0;
					if (isset($annoInicial)): echo $annoInicial; endif;
				?>
			</th>
			<td><?php echo "$".number_format($detalle['base_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['deuda_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['aportes_'.$annoInicial], 0, '.', '.'); ?></td>
			<td><?php
					$deuda = $detalle['deuda_'.$annoInicial] - $detalle['aportes_'.$annoInicial];
					echo "$".number_format($deuda, 0, '.', '.');
				?>
			</td>
			<td><?php echo "$".number_format($detalle['intereses_'.$annoInicial], 0, '.', '.'); ?></td>
			<td>
				<?php
					echo "$".number_format(($deuda + $detalle['intereses_'.$annoInicial]), 0, '.', '.');
				?>
			</td>
		</tr>
		<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
		?>
		<tr>
			<th><?php echo $anno; ?></th>
			<td><?php echo "$".number_format($detalle['base_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['deuda_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($detalle['aportes_'.$anno], 0, '.', '.'); ?></td>
			<td><?php
					$deuda = $detalle['deuda_'.$anno] - $detalle['aportes_'.$anno];
					echo "$".number_format($deuda, 0, '.', '.');
				?>
			</td>
			<td><?php echo "$".number_format($detalle['intereses_'.$anno], 0, '.', '.'); ?></td>
			<td><?php echo "$".number_format($deuda + $detalle['intereses_'.$anno], 0, '.', '.');?></td>
		</tr>
		<?php
					$acumulador_base += $detalle['base_'.$anno];
					$acumulador_deuda += $detalle['deuda_'.$anno];
					$acumulador_aportes += $detalle['aportes_'.$anno];
					$acumulador_capital += $deuda;
					$acumulador_interes += $detalle['intereses_'.$anno];
					$acumulador_total += $deuda + $detalle['intereses_'.$anno];
					$anno --;
				endfor;
			endif;
		?>
		<tr>
			<th>Total</th>
			<th><?php echo "$".number_format(($detalle['base_'.$annoInicial] + $acumulador_base), 0, '.', '.'); ?></th>
			<th><?php echo "$".number_format(($detalle['deuda_'.$annoInicial] + $acumulador_deuda), 0, '.', '.'); ?></th>
			<th><?php echo "$".number_format(($detalle['aportes_'.$annoInicial] + $acumulador_aportes), 0, '.', '.'); ?></th>
			<th><?php echo "$".number_format(($detalle['deuda_'.$annoInicial] + $acumulador_capital), 0, '.', '.'); ?></th>
			<th><?php echo "$".number_format(($detalle['intereses_'.$annoInicial] + $acumulador_interes), 0, '.', '.'); ?></th>
			<th><?php echo "$".number_format(((($detalle['deuda_'.$annoInicial] - $detalle['aportes_'.$annoInicial]) + $detalle['intereses_'.$annoInicial]) + $acumulador_total), 0, '.', '.'); ?></th>
		</tr>
	</tbody>
	</table>
	<br><br>
	<?php
		echo form_open('liquidaciones/crearLiquidacionAportes', $atributos_form);
	?>
	<div class="row-fluid">
  		<div class="span12"><strong>Observaciones</strong></div>
  		<div class="span12">
  			<?php
				echo form_textarea($atributos_observaciones);
  			?>
  			<div id="contadorObservaciones"></div>
  			<br>
  		</div>
	<div class="row-fluid">
  		<div class="span12"><strong>Documentación Aportada</strong></div>
  		<div class="span12">
  			<?php
				echo form_textarea($atributos_documentacion);
  			?>
  			<div id="contadorDocumentacion"></div>
  		</div>
	</div>
	<div class="row-fluid">
		<span class = "span3 offset3">
			<a class="btn btn-warning" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $fiscalizacion['COD_FISCALIZACION']?>"><i class="fa fa-minus-circle"></i> Cancelar</a>
		</span>
		<span class = "span3 offset2">
			<?php
				echo form_hidden('informacion', serialize($detalle));
				echo form_hidden('maestro', serialize($maestro));
				echo form_hidden('fiscalizacion', serialize($fiscalizacion));
				$atributos_boton = array('id' =>'aceptar', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="fa fa-check"></i> Aceptar', 'type' => 'submit');
				echo form_button($atributos_boton) ;
			?>
		</span>
	</div>
	<?php
		echo form_close();
	?>
</div>
<?php
	if(isset($debuger)):
?>
<div id = "vistaPrevia_base" style ="display:none;" title="Detalle Liquidación Aportes Parafiscales">
	<table class = "table table-bordered table-striped">
	<tr>
		<th>Año</th>
		<th>Mes</th>
		<th>Capital</th>
		<th>Pago Mes</th>
		<th>Año Interes</th>
		<th>Mes Interes</th>
		<th>Dias</th>
		<th>Dias Acumulados</th>
		<th>Tasa EA</th>
		<th>Tasa Mensual</th>
		<th>Tasa Diaria</th>
		<th>Intereses</th>
	</tr>
	<?php
		$linea = 0;
		foreach($calculos as  $linea):
	?>
	<tr>

		<td><?php echo $linea['anno']; ?></td>
		<td><?php echo $linea['mes']; ?></td>
		<td><?php echo "$".number_format($linea['capital'], 0, '.', '.'); ?></td>
		<td><?php echo "$".number_format($linea['aportes_mes'], 0, '.', '.'); ?></td>
		<td><?php echo $linea['anno_interes']; ?></td>
		<td><?php echo $linea['mes_interes']; ?></td>
		<td><?php echo $linea['dias']; ?></td>
		<td><?php echo $linea['total_dias']; ?></td>
		<td><?php echo $linea['tasa_EA'] . '%'; ?></td>
		<td><?php echo number_format($linea['tasa_mensual'], 9, ',', '.'); ?></td>
		<td><?php echo $linea['tasa_diaria']; ?></td>
		<td><?php echo "$".number_format($linea['intereses'], 0, '.', '.'); ?></td>
	</tr>
	<?php
			// endforeach;
		endforeach;
	?>
	</table>
</div>
<table width = "100%">
	<tr class = "text-center"><td><a class="btn btn-info"  id="vistaPrevia"><i class="fa fa-eye"></i>  Ver Detalle</a></td></tr>
</table>
<?php
	endif;
?>
<script  type="text/javascript" language="javascript" charset="utf-8">

var limite = 1995;

$(document).ready(function()
{
 	$("#observaciones").keyup(function(e)
    	{

        		var box = $(this).val();
        		var resta = limite - box.length;
        		var porcentaje = 0;
        		porcentaje = (box.length/limite)*100

        		if(box.length <= limite)
        		{

            		$('#contadorObservaciones').html('<p class="text-info"><i class="fa fa-info-circle"></i> Le quedan '+ resta + ' caracteres<p>');

           			 if(porcentaje > 75)
           			 {

           			 	 $('#contadorObservaciones').html('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> Te quedan '+ resta + ' caracteres<p>');

           			 }
       		    }
        		else
        		{

            		e.preventDefault();

        		}
    	});

	$("#documentacion").keyup(function(e)
    	{

        		var box = $(this).val();
        		var resta = limite - box.length;
        		var porcentaje = 0;
        		porcentaje = (box.length/limite)*100

        		if(box.length <= limite)
        		{

            		$('#contadorDocumentacion').html('<p class="text-info"><i class="fa fa-info-circle"></i> Le quedan '+ resta + ' caracteres<p>');

           			 if(porcentaje > 75)
           			 {
           			 	 $('#contadorDocumentacion').html('<p class="text-warning"><i class="fa fa-exclamation-triangle"></i> Te quedan '+ resta + ' caracteres<p>');
           			 }
       		 }
        		else
        		{

            		e.preventDefault();

        		}
    	});
});
</script>

<!-- Función Vista Previa -->
<script type="text/javascript" language="javascript" charset="utf-8">
$(function() {
	$('#vistaPrevia').click(function()
	{
		$(function()
		{
   			$("#vistaPrevia_base" ).dialog({
                dialogClass: 'dialogo',
                width: 1100,
   				height: 500,
   			 	modal: true
   			});
  		});
  	});
});
</script>
<!-- Fin Función Vista Previa -->

<!--
/* Location: ./system/application/views/liquidaciones/liquidacionaportes_respuesta.php *
-->