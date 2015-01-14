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


//ATRIBUTOS FORMULARIO APORTES
$atributos_form = array('method' => 'post', 'id' => 'aportes');
$atributos_entidades = array ('EPR' => 'Entidades Privadas (2%)', 'EPU' => 'Entidades Públicas');
$atributos_entidadesPublicas = array ('D' => 'Entidades Descentralizadas (2%)', 'C' => 'Entidades Centralizadas (0,5%)');
$atributos_entidadesDebuger = array ('N' => 'No Activar Detalle', 'S' => 'Activar Detalle');
$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'value' => $fecha, 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
$atributos_general = array ('type' => 'text', 'class'  => 'input-small',  'value' => '0', 'maxlength' => '19', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: left; vertical-align: middle;}
</style>
<!-- Fin Estilos personalizados -->
<div class="center-form-xlarge" id="cabecera">
	<h2 class="text-center">Liquidación Aportes Parafiscales</h2>
		<?php
			echo form_open('liquidaciones/calcularLiquidacionAportes', $atributos_form);
		?>
	<br><br>
	<table>
		<tr>
			<td><strong>Regional:</strong><br><span class="muted"><?php echo $fiscalizacion['NOMBRE_REGIONAL']; ?></span></td>
			<td><strong>Estado Proceso:</strong><br><span class="muted"><?php echo $fiscalizacion['TIPOGESTION']; ?></span></td>
			<td colspan="2"><strong>CIIU:</strong><br><span class="muted"><?php echo $fiscalizacion['CIIU'], ' - ',  $fiscalizacion['DESCRIPCION']; ?></span></td>
		</tr>
		<tr>
			<td><strong>NIT:</strong><br><span class="muted"><?php echo $fiscalizacion['NIT_EMPRESA']; ?></span></td>
			<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $fiscalizacion['RAZON_SOCIAL']; ?></span></td>
			<td><strong>Periodo Inicial:</strong><br><span class="muted"><?php echo $fiscalizacion['PERIODO_INICIAL']; ?></span></td>
			<td><strong>Periodo Final:</strong><br><span class="muted"><?php echo $fiscalizacion['PERIODO_FINAL']; ?></span></td>
		</tr>
		<tr>
			<td>
				<?php
					echo form_dropdown('entidades', $atributos_entidades, set_value('entidades'), 'id = "entidades"');
				?>
			</td>
			<td>
				<?php
					echo form_dropdown('entidadesPublicas', $atributos_entidadesPublicas, set_value('entidadesPublicas'), 'id = "entidadesPublicas" style = "display:none;"');
				?>
			</td>
			<td colspan = "2">
				<?php
					echo form_label('<strong>Fecha de Liquidación: </strong>','fechaLiquidacion');
					echo form_input($atributos_fechaLiquidacion);
				?>
			</td>
		</tr>
		<tr>
			<td colspan = "4">
				<?php
					echo form_dropdown('debuger', $atributos_entidadesDebuger, set_value('debuger'));
				?>
			</td>
		</tr>
	</table>
	<br>
	<table class="table table-striped table-condensed">
		<thead>
		<tr>
			<th>Factores de Liquidación de Aportes</th>
			<th><?php if (isset($annoInicial)): echo "Año ". $annoInicial; endif;?></th>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;

				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
			?>
				<th>Año <?php echo $anno;?></th>
			<?php $anno --;
				endfor;
			endif;
			?>
		<tr>
		</thead>
		<tbody>
		<tr>
			<td>Sueldos</td>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$sueldo =  number_format(${'serie_'.$annoInicial}['VALORSUELDOS'], 0, '.', '.');
				else:
					$sueldo = 0;
				endif;
				$atributos_sueldos = array ('name' => 'sueldos_'.$annoInicial, 'id' => 'sueldos_'.$annoInicial,  'value' => $sueldo);
			?>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sueldos)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$sueldo =  number_format(${'serie_'.$anno}['VALORSUELDOS'], 0, '.', '.');
					else:
						$sueldo = 0;
					endif;
					$atributos_sueldos = array ('name' => 'sueldos_'.$anno, 'id' => 'sueldos_'.$anno, 'value' => $sueldo);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sueldos)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$sobresueldo =  number_format(${'serie_'.$annoInicial}['VALORSOBRESUELDOS'], 0, '.', '.');
				else:
					$sobresueldo = 0;
				endif;
				$atributos_sobresueldos = array ('name' => 'sobresueldos_'.$annoInicial, 'id' => 'sobresueldos_'.$annoInicial, 'value' => $sobresueldo);
			?>
			<td>Sobresueldos</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sobresueldos)); ?></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$sobresueldo =  number_format(${'serie_'.$anno}['VALORSOBRESUELDOS'], 0, '.', '.');
					else:
						$sobresueldo = 0;
					endif;
					 $atributos_sobresueldos = array ('name' => 'sobresueldos_'.$anno, 'id' => 'sobresueldos_'.$anno, 'value' => $sobresueldo);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sobresueldos)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$salariointegral =  number_format(${'serie_'.$annoInicial}['SALARIOINTEGRAL'], 0, '.', '.');
				else:
					$salariointegral = 0;
				endif;
				$atributos_salarioIntegral = array ('name' => 'salarioIntegral_'.$annoInicial, 'id' => 'salarioIntegral_'.$annoInicial, 'value' => $salariointegral);
			?>
			<td>Salario Integral 70%</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioIntegral)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$salariointegral =  number_format(${'serie_'.$anno}['SALARIOINTEGRAL'], 0, '.', '.');
					else:
						$salariointegral = 0;
					endif;
					$atributos_salarioIntegral = array ('name' => 'salarioIntegral_'.$anno, 'id' => 'salarioIntegral_'.$anno, 'value' => $salariointegral);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioIntegral)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$salarioespecie =  number_format(${'serie_'.$annoInicial}['SALARIOESPECIE'], 0, '.', '.');
				else:
					$salarioespecie = 0;
				endif;
				$atributos_salarioEspecie = array ('name' => 'salarioEspecie_'.$annoInicial, 'id' => 'salarioEspecie_'.$annoInicial, 'value' => $salarioespecie);
			?>
			<td>Salario en Especie</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioEspecie)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$salarioespecie =  number_format(${'serie_'.$anno}['SALARIOESPECIE'], 0, '.', '.');
					else:
						$salarioespecie = 0;
					endif;
					$atributos_salarioEspecie = array ('name' => 'salarioEspecie_'.$anno, 'id' => 'salarioEspecie_'.$anno, 'value' => $salarioespecie);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioEspecie)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$supernumerarios =  number_format(${'serie_'.$annoInicial}['SUPERNUMERARIOS'], 0, '.', '.');
				else:
					$supernumerarios = 0;
				endif;
				$atributos_supernumerarios = array ('name' => 'supernumerarios_'.$annoInicial, 'id' => 'supernumerarios_'.$annoInicial, 'value' => $supernumerarios);
			?>
			<td>Supernumerarios</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_supernumerarios)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$supernumerarios =  number_format(${'serie_'.$anno}['SUPERNUMERARIOS'], 0, '.', '.');
					else:
						$supernumerarios = 0;
					endif;
					$atributos_supernumerarios = array ('name' => 'supernumerarios_'.$anno, 'id' => 'supernumerarios_'.$anno, 'value' => $supernumerarios);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_supernumerarios)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$viaticos =  number_format(${'serie_'.$annoInicial}['VIATICOS'], 0, '.', '.');
				else:
					$viaticos = 0;
				endif;
				$atributos_viaticos = array ('name' => 'viaticos_'.$annoInicial, 'id' => 'viaticos_'.$annoInicial, 'value' => $viaticos);
			?>
			<td>Viaticos (Manutención y Alojamiento)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_viaticos)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$viaticos =  number_format(${'serie_'.$anno}['VIATICOS'], 0, '.', '.');
					else:
						$viaticos = 0;
					endif;
					$atributos_viaticos = array ('name' => 'viaticos_'.$anno, 'id' => 'viaticos_'.$anno, 'value' => $viaticos);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_viaticos)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$auxiliotransporte =  number_format(${'serie_'.$annoInicial}['AUXILIOTRANSPORTE'], 0, '.', '.');
				else:
					$auxiliotransporte = 0;
				endif;
				$atributos_auxilioTransporte = array ('name' => 'auxilioTransporte_'.$annoInicial, 'id' => 'auxilioTransporte_'.$annoInicial, 'value' => $auxiliotransporte);
			?>
			<td>Auxilio de Transporte</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioTransporte)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$auxiliotransporte =  number_format(${'serie_'.$anno}['AUXILIOTRANSPORTE'], 0, '.', '.');
					else:
						$auxiliotransporte = 0;
					endif;
					$atributos_auxilioTransporte= array ('name' => 'auxilioTransporte_'.$anno, 'id' => 'auxilioTransporte_'.$anno, 'value' => $auxiliotransporte);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioTransporte)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$horasextras =  number_format(${'serie_'.$annoInicial}['HORASEXTRAS'], 0, '.', '.');
				else:
					$horasextras = 0;
				endif;
				$atributos_horasExtras = array ('name' => 'horasExtras_'.$annoInicial, 'id' => 'horasExtras_'.$annoInicial, 'value' => $horasextras);
			?>
			<td>Horas Extras</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horasExtras)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$horasextras =  number_format(${'serie_'.$anno}['HORASEXTRAS'], 0, '.', '.');
					else:
						$horasextras = 0;
					endif;
					$atributos_horasExtras= array ('name' => 'horasExtras_'.$anno, 'id' => 'horasExtras_'.$anno, 'value' => $horasextras);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horasExtras)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$dominicales =  number_format(${'serie_'.$annoInicial}['DOMINICALES_FESTIVOS'], 0, '.', '.');
				else:
					$dominicales = 0;
				endif;
				$atributos_dominicales = array ('name' => 'dominicales_'.$annoInicial, 'id' => 'dominicales_'.$annoInicial, 'value' => $dominicales);
			?>
			<td>Dominicales y Festivos</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_dominicales)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$dominicales =  number_format(${'serie_'.$anno}['DOMINICALES_FESTIVOS'], 0, '.', '.');
					else:
						$dominicales = 0;
					endif;
					$atributos_dominicales = array ('name' => 'dominicales_'.$anno, 'id' => 'dominicales_'.$anno, 'value' => $dominicales);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_dominicales)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$recargo =  number_format(${'serie_'.$annoInicial}['RECARGONOCTURNO'], 0, '.', '.');
				else:
					$recargo = 0;
				endif;
				$atributos_recargoNocturno = array ('name' => 'recargoNocturno_'.$annoInicial, 'id' => 'recargoNocturno_'.$annoInicial, 'value' => $recargo);
			?>
			<td>Recargo Nocturno</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_recargoNocturno)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$recargo =  number_format(${'serie_'.$anno}['RECARGONOCTURNO'], 0, '.', '.');
					else:
						$recargo = 0;
					endif;
					$atributos_recargoNocturno = array ('name' => 'recargoNocturno_'.$anno, 'id' => 'recargoNocturno_'.$anno, 'value' => $recargo);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_recargoNocturno)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$jornales =  number_format(${'serie_'.$annoInicial}['JORNALES'], 0, '.', '.');
				else:
					$jornales = 0;
				endif;
				$atributos_jornales = array ('name' => 'jornales_'.$annoInicial, 'id' => 'jornales_'.$annoInicial, 'value' => $jornales);
			?>
			<td>Jornales</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_jornales)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$jornales =  number_format(${'serie_'.$anno}['JORNALES'], 0, '.', '.');
					else:
						$jornales = 0;
					endif;
					$atributos_jornales = array ('name' => 'jornales_'.$anno, 'id' => 'jornales_'.$anno, 'value' => $jornales);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_jornales)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$comisiones =  number_format(${'serie_'.$annoInicial}['COMISIONES'], 0, '.', '.');
				else:
					$comisiones = 0;
				endif;
				$atributos_comisiones = array ('name' => 'comisiones_'.$annoInicial, 'id' => 'comisiones_'.$annoInicial, 'value' => $comisiones);
			?>
			<td>Comisiones</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_comisiones)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$comisiones =  number_format(${'serie_'.$anno}['COMISIONES'], 0, '.', '.');
					else:
						$comisiones = 0;
					endif;
					$atributos_comisiones= array ('name' => 'comisiones_'.$anno, 'id' => 'comisiones_'.$anno, 'value' => $comisiones);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_comisiones)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$bonificaciones =  number_format(${'serie_'.$annoInicial}['BONIFICACIONES'], 0, '.', '.');
				else:
					$bonificaciones = 0;
				endif;
				$atributos_bonificacionesHabituales = array ('name' => 'bonificacionesHabituales_'.$annoInicial, 'id' => 'bonificacionesHabituales_'.$annoInicial, 'value' => $bonificaciones);
			?>
			<td>Bonificaciones Habituales</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_bonificacionesHabituales)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$bonificaciones =  number_format(${'serie_'.$anno}['BONIFICACIONES'], 0, '.', '.');
					else:
						$bonificaciones = 0;
					endif;
					$atributos_bonificacionesHabituales = array ('name' => 'bonificacionesHabituales_'.$anno, 'id' => 'bonificacionesHabituales_'.$anno, 'value' => $bonificaciones);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_bonificacionesHabituales)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$porcentajeventas =  number_format(${'serie_'.$annoInicial}['POR_SOBREVENTAS'], 0, '.', '.');
				else:
					$porcentajeventas = 0;
				endif;
				$atributos_porcentajeVentas = array ('name' => 'porcentajeVentas_'.$annoInicial, 'id' => 'porcentajeVentas_'.$annoInicial, 'value' => $porcentajeventas);
			?>
			<td>Porcentaje sobre ventas</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_porcentajeVentas)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$porcentajeventas =  number_format(${'serie_'.$anno}['POR_SOBREVENTAS'], 0, '.', '.');
					else:
						$porcentajeventas = 0;
					endif;
					$atributos_porcentajeVentas= array ('name' => 'porcentajeVentas_'.$anno, 'id' => 'porcentajeVentas_'.$anno, 'value' => $porcentajeventas);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_porcentajeVentas)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$vacaciones =  number_format(${'serie_'.$annoInicial}['VACACIONES'], 0, '.', '.');
				else:
					$vacaciones = 0;
				endif;
				$atributos_vacaciones = array ('name' => 'vacaciones_'.$annoInicial, 'id' => 'vacaciones_'.$annoInicial, 'value' => $vacaciones);
			?>
			<td>Vacaciones</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_vacaciones)); ?></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$vacaciones =  number_format(${'serie_'.$anno}['VACACIONES'], 0, '.', '.');
					else:
						$vacaciones = 0;
					endif;
					$atributos_vacaciones= array ('name' => 'vacaciones_'.$anno, 'id' => 'vacaciones_'.$anno, 'value' => $vacaciones);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_vacaciones)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$trabajodomicilio =  number_format(${'serie_'.$annoInicial}['TRAB_DOMICILIO'], 0, '.', '.');
				else:
					$trabajodomicilio = 0;
				endif;
				$atributos_trabajoDomicilio = array ('name' => 'trabajoDomicilio_'.$annoInicial, 'id' => 'trabajoDomicilio_'.$annoInicial, 'value' => $trabajodomicilio);
			?>
			<td>Trabajo a Domicilio</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_trabajoDomicilio)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$trabajodomicilio =  number_format(${'serie_'.$anno}['TRAB_DOMICILIO'], 0, '.', '.');
					else:
						$trabajodomicilio = 0;
					endif;
					$atributos_trabajoDomicilio= array ('name' => 'trabajoDomicilio_'.$anno, 'id' => 'trabajoDomicilio_'.$anno, 'value' => $trabajodomicilio);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_trabajoDomicilio)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$subcontrato =  number_format(${'serie_'.$annoInicial}['SUBCONTRATO'], 0, '.', '.');
				else:
					$subcontrato = 0;
				endif;
				$atributos_subcontratos = array ('name' => 'subcontratos_'.$annoInicial, 'id' => 'subcontratos_'.$annoInicial, 'value' => $subcontrato);
			?>
			<td>Contratos y subcontratos (Construcción)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_subcontratos)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$subcontrato =  number_format(${'serie_'.$anno}['SUBCONTRATO'], 0, '.', '.');
					else:
						$subcontrato = 0;
					endif;
					$atributos_subcontratos= array ('name' => 'subcontratos_'.$anno, 'id' => 'subcontratos_'.$anno, 'value' => $subcontrato);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_subcontratos)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$primatecnica =  number_format(${'serie_'.$annoInicial}['PRIMA_TEC_SALARIAL'], 0, '.', '.');
				else:
					$primatecnica = 0;
				endif;
				$atributos_primaTecnicaSalarial = array ('name' => 'primaTecnicaSalarial_'.$annoInicial, 'id' => 'primaTecnicaSalarial_'.$annoInicial, 'value' => $primatecnica);
			?>
			<td>Prima Técnia Salarial (Sector Público)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaTecnicaSalarial)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$primatecnica =  number_format(${'serie_'.$anno}['PRIMA_TEC_SALARIAL'], 0, '.', '.');
					else:
						$primatecnica = 0;
					endif;
					$atributos_primaTecnicaSalarial= array ('name' => 'primaTecnicaSalarial_'.$anno, 'id' => 'primaTecnicaSalarial_'.$anno, 'value' => $primatecnica);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaTecnicaSalarial)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$auxilioalimentacion =  number_format(${'serie_'.$annoInicial}['AUXILIO_ALIMENTACION'], 0, '.', '.');
				else:
					$auxilioalimentacion = 0;
				endif;
				$atributos_auxilioAlimentacion = array ('name' => 'auxilioAlimentacion_'.$annoInicial, 'id' => 'auxilioAlimentacion_'.$annoInicial, 'value' => $auxilioalimentacion);
			?>
			<td>Auxilio y/o Subsidio de alimentación</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioAlimentacion)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$auxilioalimentacion =  number_format(${'serie_'.$anno}['AUXILIO_ALIMENTACION'], 0, '.', '.');
					else:
						$auxilioalimentacion = 0;
					endif;
					$atributos_auxilioAlimentacion= array ('name' => 'auxilioAlimentacion_'.$anno, 'id' => 'auxilioAlimentacion_'.$anno, 'value' => $auxilioalimentacion);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioAlimentacion)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$primaservicio =  number_format(${'serie_'.$annoInicial}['PRIMA_SERVICIO'], 0, '.', '.');
				else:
					$primaservicio = 0;
				endif;
				$atributos_primaServicios = array ('name' => 'primaServicios_'.$annoInicial, 'id' => 'primaServicios_'.$annoInicial, 'value' => $primaservicio);
			?>
			<td>Prima de servicio (Sector Público)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaServicios)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$primaservicio =  number_format(${'serie_'.$anno}['PRIMA_SERVICIO'], 0, '.', '.');
					else:
						$primaservicio = 0;
					endif;
					$atributos_primaServicios= array ('name' => 'primaServicios_'.$anno, 'id' => 'primaServicios_'.$anno, 'value' => $primaservicio);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaServicios)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$primalocalizacion =  number_format(${'serie_'.$annoInicial}['PRIMA_LOCALIZACION'], 0, '.', '.');
				else:
					$primalocalizacion = 0;
				endif;
				$atributos_primaLocalizacion = array ('name' => 'primaLocalizacion_'.$annoInicial, 'id' => 'primaLocalizacion_'.$annoInicial, 'value' => $primalocalizacion);
			?>
			<td>Prima de Localización</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaLocalizacion)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$primalocalizacion =  number_format(${'serie_'.$anno}['PRIMA_LOCALIZACION'], 0, '.', '.');
					else:
						$primalocalizacion = 0;
					endif;
					$atributos_primaLocalizacion = array ('name' => 'primaLocalizacion_'.$anno, 'id' => 'primaLocalizacion_'.$anno, 'value' => $primalocalizacion);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaLocalizacion)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$primavivienda =  number_format(${'serie_'.$annoInicial}['PRIMA_VIVIENDA'], 0, '.', '.');
				else:
					$primavivienda = 0;
				endif;
				$atributos_primaVivienda = array ('name' => 'primaVivienda_'.$annoInicial, 'id' => 'primaVivienda_'.$annoInicial, 'value' => $primavivienda);
			?>
			<td>Prima de vivienda (Sector Público)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVivienda)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$primavivienda =  number_format(${'serie_'.$anno}['PRIMA_VIVIENDA'], 0, '.', '.');
					else:
						$primavivienda = 0;
					endif;
					$atributos_primaVivienda = array ('name' => 'primaVivienda_'.$anno, 'id' => 'primaVivienda_'.$anno, 'value' => $primavivienda);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVivienda)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$representacion =  number_format(${'serie_'.$annoInicial}['GAST_REPRESENTACION'], 0, '.', '.');
				else:
					$representacion = 0;
				endif;
				$atributos_gastosRepresentacion = array ('name' => 'gastosRepresentacion_'.$annoInicial, 'id' => 'gastosRepresentacion_'.$annoInicial, 'value' => $representacion);
			?>
			<td>Gastos de representación (Sector Público)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_gastosRepresentacion)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$representacion =  number_format(${'serie_'.$anno}['GAST_REPRESENTACION'], 0, '.', '.');
					else:
						$representacion = 0;
					endif;
					$atributos_gastosRepresentacion = array ('name' => 'gastosRepresentacion_'.$anno, 'id' => 'gastosRepresentacion_'.$anno, 'value' => $representacion);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_gastosRepresentacion)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$antiguedad =  number_format(${'serie_'.$annoInicial}['PRIMA_ANTIGUEDAD'], 0, '.', '.');
				else:
					$antiguedad = 0;
				endif;
				$atributos_primaAntiguedad = array ('name' => 'primaAntiguedad_'.$annoInicial, 'id' => 'primaAntiguedad_'.$annoInicial, 'value' => $antiguedad);
			?>
			<td>Prima o Incremento por antiguedad</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaAntiguedad)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$antiguedad =  number_format(${'serie_'.$anno}['PRIMA_ANTIGUEDAD'], 0, '.', '.');
					else:
						$antiguedad = 0;
					endif;
					$atributos_primaAntiguedad = array ('name' => 'primaAntiguedad_'.$anno, 'id' => 'primaAntiguedad_'.$anno, 'value' => $antiguedad);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaAntiguedad)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$productividad =  number_format(${'serie_'.$annoInicial}['PRIMA_EXTRALEGALES'], 0, '.', '.');
				else:
					$productividad = 0;
				endif;
				$atributos_primaProductividad = array ('name' => 'primaProductividad_'.$annoInicial, 'id' => 'primaProductividad_'.$annoInicial, 'value' => $productividad);
			?>
			<td>Prima de productividad</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaProductividad)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$productividad =  number_format(${'serie_'.$anno}['PRIMA_EXTRALEGALES'], 0, '.', '.');
					else:
						$productividad = 0;
					endif;
					$atributos_primaProductividad = array ('name' => 'primaProductividad_'.$anno, 'id' => 'primaProductividad_'.$anno, 'value' => $productividad);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaProductividad)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$vacaciones =  number_format(${'serie_'.$annoInicial}['PRIMA_VACACIONES'], 0, '.', '.');
				else:
					$vacaciones = 0;
				endif;
				$atributos_primaVacaciones = array ('name' => 'primaVacaciones_'.$annoInicial, 'id' => 'primaVacaciones_'.$annoInicial, 'value' => $vacaciones);
			?>
			<td>Prima de vacaciones</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVacaciones)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$vacaciones =  number_format(${'serie_'.$anno}['PRIMA_VACACIONES'], 0, '.', '.');
					else:
						$vacaciones = 0;
					endif;
					$atributos_primaVacaciones = array ('name' => 'primaVacaciones_'.$anno, 'id' => 'primaVacaciones_'.$anno, 'value' => $vacaciones);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVacaciones)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$navidad =  number_format(${'serie_'.$annoInicial}['PRIMA_NAVIDAD'], 0, '.', '.');
				else:
					$navidad = 0;
				endif;
				$atributos_primaNavidad = array ('name' => 'primaNavidad_'.$annoInicial, 'id' => 'primaNavidad_'.$annoInicial, 'value' => $navidad);
			?>
			<td>Prima de navidad (Sector Privado)</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaNavidad)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$navidad =  number_format(${'serie_'.$anno}['PRIMA_NAVIDAD'], 0, '.', '.');
					else:
						$navidad = 0;
					endif;
					$atributos_primaNavidad = array ('name' => 'primaNavidad_'.$anno, 'id' => 'primaNavidad_'.$anno, 'value' => $navidad);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaNavidad)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$contratosagricolas =  number_format(${'serie_'.$annoInicial}['CONTRATOS_AGRICOLAS'], 0, '.', '.');
				else:
					$contratosagricolas = 0;
				endif;
				$atributos_contratosAgricolas = array ('name' => 'contratosAgricolas_'.$annoInicial, 'id' => 'contratosAgricolas_'.$annoInicial, 'value' => $contratosagricolas);
			?>
			<td>Contratos agricolas</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_contratosAgricolas)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$contratosagricolas =  number_format(${'serie_'.$anno}['CONTRATOS_AGRICOLAS'], 0, '.', '.');
					else:
						$contratosagricolas = 0;
					endif;
					$atributos_contratosAgricolas = array ('name' => 'contratosAgricolas_'.$anno, 'id' => 'contratosAgricolas_'.$anno, 'value' => $contratosagricolas);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_contratosAgricolas)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$socios =  number_format(${'serie_'.$annoInicial}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.');
				else:
					$socios = 0;
				endif;
				$atributos_remuneracionSocios = array ('name' => 'remuneracionSocios_'.$annoInicial, 'id' => 'remuneracionSocios_'.$annoInicial, 'value' => $socios);
			?>
			<td>Remuneración socios industriales</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_remuneracionSocios)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$socios =  number_format(${'serie_'.$anno}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.');
					else:
						$socios = 0;
					endif;
					$atributos_remuneracionSocios = array ('name' => 'remuneracionSocios_'.$anno, 'id' => 'remuneracionSocios_'.$anno, 'value' => $socios);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_remuneracionSocios)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$catedra =  number_format(${'serie_'.$annoInicial}['HORA_CATEDRA'], 0, '.', '.');
				else:
					$catedra = 0;
				endif;
				$atributos_horaCatedra = array ('name' => 'horaCatedra_'.$annoInicial, 'id' => 'horaCatedra_'.$annoInicial, 'value' => $catedra);
			?>
			<td>Hora cátedra</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horaCatedra)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$catedra =  number_format(${'serie_'.$anno}['HORA_CATEDRA'], 0, '.', '.');
					else:
						$catedra = 0;
					endif;
					$atributos_horaCatedra = array ('name' => 'horaCatedra_'.$anno, 'id' => 'horaCatedra_'.$anno, 'value' => $catedra);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horaCatedra)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		<tr>
			<?php
				if(isset(${'serie_'.$annoInicial})):
					$otrospagos =  number_format(${'serie_'.$annoInicial}['OTROS_PAGOS'], 0, '.', '.');
				else:
					$otrospagos = 0;
				endif;
				$atributos_otrosPagos = array ('name' => 'otrosPagos_'.$annoInicial, 'id' => 'otrosPagos_'.$annoInicial, 'value' => $otrospagos);
			?>
			<td>Otros pagos denominados como</td>
			<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_otrosPagos)); ?></div></td>
			<?php if (isset($annos) && $annos > 0):
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					if(isset(${'serie_'.$anno})):
						$otrospagos =  number_format(${'serie_'.$anno}['OTROS_PAGOS'], 0, '.', '.');
					else:
						$otrospagos = 0;
					endif;
					$atributos_otrosPagos = array ('name' => 'otrosPagos_'.$anno, 'id' => 'otrosPagos_'.$anno, 'value' => $otrospagos);
			?>
				<td><div class="input-prepend"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_otrosPagos)); ?></div></td>
			<?php $anno --;
				endfor;
			endif;
			?>
		</tr>
		</tbody>
	</table>
	<br>
	<span class = "span3 offset2">
		<a class="btn btn-warning" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $fiscalizacion['COD_FISCALIZACION']?>"><i class="fa fa-minus-circle"></i> Cancelar</a>
	</span>
	<span class = "span3 offset2">
		<?php
			$atributos_boton = array('id' =>'aceptar', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="fa fa-dollar"></i> Calcular', 'type' => 'submit');
			echo form_button($atributos_boton) ;
		?>
	</span>
</div>
<?php
    if(isset($liquidacion_bloqueada)):

        echo form_hidden('liquidacion_bloqueada', $liquidacion_bloqueada);

    endif;

	echo form_hidden('fiscalizacion', serialize($fiscalizacion));
	echo form_hidden('liquidacion_previa', $previa);
	echo form_close();
?>

<!-- Función datepicker -->
<script  type="text/javascript" language="javascript" charset="utf-8">
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
	$( '' ).datepicker({
		showOn: 'button',
		buttonText: 'Selecciona una fecha',
		buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
		buttonImageOnly: true,
		numberOfMonths: 1,
		maxDate: '1m',
		minDate: '0d',
	});
	$("#entidades").change(function(){
		var entidad = this.value;
		if(entidad == 'EPU')
		{
			$('#entidadesPublicas').show("slow");
		}
		else
		{
			$('#entidadesPublicas').hide("slow");
		}
	});
});
</script>
<!-- Fin función datepicker -->

<!-- Función solo números -->
<script type="text/javascript" language="javascript" charset="utf-8">
	function num(c){
		c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  		var num = c.value.replace(/\./g,'');
		if(!isNaN(num))
		{
			num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
			num = num.split('').reverse().join('').replace(/^[\.]/,'');
			c.value = num;
		}
	}
</script>
<!-- Fin Función solo números -->

<!--
/* End of file liquidacionaportes_form.php */
-->