<?php
/**
* Formulario para el calculo de liquidaciones de Aportes Parafiscales. Datos para las bases anuales y calcluo de intereses por mora o incumplimiento en el pago de aportes.
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/liquidacionaportes_form.php
* @last-modified  10/06/2014
* @copyright	
*/  
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if (isset($message)):
	echo $message;
endif;
// print_r($fiscalizacion)
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
			$atributos_form = array('method' => 'post', 'id' => 'aportes');
			echo form_open('liquidaciones/loadFormAportes', $atributos_form);
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
			<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $fiscalizacion['NOMBRE_EMPRESA']; ?></span></td></td>
			<td><strong>Periodo Inicial:</strong><br><span class="muted"><?php echo $fiscalizacion['PERIODO_INICIAL']; ?></span></td>
			<td><strong>Periodo Final:</strong><br><span class="muted"><?php echo $fiscalizacion['PERIODO_FINAL']; ?></span></td>
		</tr>
		<tr>
			<td colspan = "2">
				<?php 
					$atributos_entidadesPublicas = array ('name' => 'entidadesPublicas', 'id' => 'entidadesPublicas', 'value' => 'S');
					echo form_radio($atributos_entidadesPublicas);
					echo form_label('<strong>Entidades Públicas 0,5%</strong>','entidadesPublicas');  
				?>
			</td>
			<td colspan = "2">
				<?php 
					echo form_label('<strong>Fecha de Liquidación: </strong>','fechaLiquidacion');  
					$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'value' => $fecha, 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
					echo form_input($atributos_fechaLiquidacion);
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
				$atributos_general = array ('type' => 'number', 'min' => 0, 'class'  => 'input-small',  'value' => '0.0', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
				$atributos_sueldos = array ('name' => 'sueldos_'.$annoInicial, 'id' => 'sueldos_'.$annoInicial);
			?>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sueldos)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_sueldos = array ('name' => 'sueldos_'.$anno, 'id' => 'sueldos_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sueldos)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_sobresueldos = array ('name' => 'sobresueldos_'.$annoInicial, 'id' => 'sobresueldos_'.$annoInicial);?>
			<td>Sobresueldos</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sobresueldos)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					 $atributos_sobresueldos = array ('name' => 'sobresueldos_'.$anno, 'id' => 'sobresueldos_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_sobresueldos)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_salarioIntegral = array ('name' => 'salarioIntegral_'.$annoInicial, 'id' => 'salarioIntegral_'.$annoInicial);?>
			<td>Salario Integral 70%</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioIntegral)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_salarioIntegral = array ('name' => 'salarioIntegral_'.$anno, 'id' => 'salarioIntegral_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioIntegral)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_salarioEspecie = array ('name' => 'salarioEspecie_'.$annoInicial, 'id' => 'salarioEspecie_'.$annoInicial);?>
			<td>Salario en Especie</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioEspecie)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_salarioEspecie = array ('name' => 'salarioEspecie_'.$anno, 'id' => 'salarioEspecie_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_salarioEspecie)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_supernumerarios = array ('name' => 'supernumerarios_'.$annoInicial, 'id' => 'supernumerarios_'.$annoInicial);?>
			<td>Supernumerarios</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_supernumerarios)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_supernumerarios = array ('name' => 'supernumerarios_'.$anno, 'id' => 'supernumerarios_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_supernumerarios)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_jornales = array ('name' => 'jornales_'.$annoInicial, 'id' => 'jornales_'.$annoInicial);?>
			<td>Jornales</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_jornales)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_jornales = array ('name' => 'jornales_'.$anno, 'id' => 'jornales_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_jornales)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_auxilioTransporte = array ('name' => 'auxilioTransporte_'.$annoInicial, 'id' => 'auxilioTransporte_'.$annoInicial);?>
			<td>Auxilio de Transporte</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioTransporte)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_auxilioTransporte= array ('name' => 'auxilioTransporte_'.$anno, 'id' => 'auxilioTransporte_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioTransporte)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_horasExtras = array ('name' => 'horasExtras_'.$annoInicial, 'id' => 'horasExtras_'.$annoInicial);?>
			<td>Horas Extras</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horasExtras)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_horasExtras= array ('name' => 'horasExtras_'.$anno, 'id' => 'horasExtras_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horasExtras)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_dominicales = array ('name' => 'dominicales_'.$annoInicial, 'id' => 'dominicales_'.$annoInicial);?>
			<td>Dominicales y Festivos</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_dominicales)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_dominicales = array ('name' => 'dominicales_'.$anno, 'id' => 'dominicales_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_dominicales)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_recargoNocturno = array ('name' => 'recargoNocturno_'.$annoInicial, 'id' => 'recargoNocturno_'.$annoInicial);?>
			<td>Recargo Nocturno</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_recargoNocturno)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_recargoNocturno = array ('name' => 'recargoNocturno_'.$anno, 'id' => 'recargoNocturno_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_recargoNocturno)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_viaticos = array ('name' => 'viaticos_'.$annoInicial, 'id' => 'viaticos_'.$annoInicial);?>
			<td>Viaticos (Manutención y Alojamiento)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_viaticos)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_viaticos = array ('name' => 'viaticos_'.$anno, 'id' => 'viaticos_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_viaticos)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_bonificacionesHabituales = array ('name' => 'bonificacionesHabituales_'.$annoInicial, 'id' => 'bonificacionesHabituales_'.$annoInicial);?>
			<td>Bonificaciones Habituales</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_bonificacionesHabituales)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_bonificacionesHabituales = array ('name' => 'bonificacionesHabituales_'.$anno, 'id' => 'bonificacionesHabituales_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_bonificacionesHabituales)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_comisiones = array ('name' => 'comisiones_'.$annoInicial, 'id' => 'comisiones_'.$annoInicial);?>
			<td>Comisiones</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_comisiones)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_comisiones= array ('name' => 'comisiones_'.$anno, 'id' => 'comisiones_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_comisiones)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_porcentajeVentas = array ('name' => 'porcentajeVentas_'.$annoInicial, 'id' => 'porcentajeVentas_'.$annoInicial);?>
			<td>Porcentaje sobre ventas</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_porcentajeVentas)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_porcentajeVentas= array ('name' => 'porcentajeVentas_'.$anno, 'id' => 'porcentajeVentas_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_porcentajeVentas)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_vacaciones = array ('name' => 'vacaciones_'.$annoInicial, 'id' => 'vacaciones_'.$annoInicial);?>
			<td>Vacaciones</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span></span><?php echo form_input(array_merge($atributos_general, $atributos_vacaciones)); ?><span class="add-on">.00</span></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_vacaciones= array ('name' => 'vacaciones_'.$anno, 'id' => 'vacaciones_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_vacaciones)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_trabajoDomicilio = array ('name' => 'trabajoDomicilio_'.$annoInicial, 'id' => 'trabajoDomicilio_'.$annoInicial);?>
			<td>Trabajo a Domicilio</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_trabajoDomicilio)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_trabajoDomicilio= array ('name' => 'trabajoDomicilio_'.$anno, 'id' => 'trabajoDomicilio_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_trabajoDomicilio)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaTecnicaSalarial = array ('name' => 'primaTecnicaSalarial_'.$annoInicial, 'id' => 'primaTecnicaSalarial_'.$annoInicial);?>
			<td>Prima Técnia Salarial (Sector Público)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaTecnicaSalarial)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaTecnicaSalarial= array ('name' => 'primaTecnicaSalarial_'.$anno, 'id' => 'primaTecnicaSalarial_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaTecnicaSalarial)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_auxilioAlimentacion = array ('name' => 'auxilioAlimentacion_'.$annoInicial, 'id' => 'auxilioAlimentacion_'.$annoInicial);?>
			<td>Auxilio y/o Subsidio de alimentación</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioAlimentacion)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_auxilioAlimentacion= array ('name' => 'auxilioAlimentacion_'.$anno, 'id' => 'auxilioAlimentacion_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_auxilioAlimentacion)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaServicios = array ('name' => 'primaServicios_'.$annoInicial, 'id' => 'primaServicios_'.$annoInicial);?>
			<td>Prima de servicio (Sector Público)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaServicios)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaServicios= array ('name' => 'primaServicios_'.$anno, 'id' => 'primaServicios_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaServicios)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaLocalizacion = array ('name' => 'primaLocalizacion_'.$annoInicial, 'id' => 'primaLocalizacion_'.$annoInicial);?>
			<td>Prima de Localización</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaLocalizacion)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaLocalizacion = array ('name' => 'primaLocalizacion_'.$anno, 'id' => 'primaLocalizacion_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaLocalizacion)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaVivienda = array ('name' => 'primaVivienda_'.$annoInicial, 'id' => 'primaVivienda_'.$annoInicial);?>
			<td>Prima de vivienda (Sector Público)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVivienda)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaVivienda = array ('name' => 'primaVivienda_'.$anno, 'id' => 'primaVivienda_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVivienda)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_gastosRepresentacion = array ('name' => 'gastosRepresentacion_'.$annoInicial, 'id' => 'gastosRepresentacion_'.$annoInicial);?>
			<td>Gastos de representación (Sector Público)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_gastosRepresentacion)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_gastosRepresentacion = array ('name' => 'gastosRepresentacion_'.$anno, 'id' => 'gastosRepresentacion_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_gastosRepresentacion)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaAntiguedad = array ('name' => 'primaAntiguedad_'.$annoInicial, 'id' => 'primaAntiguedad_'.$annoInicial);?>
			<td>Prima o Incremento por antiguedad</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaAntiguedad)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaAntiguedad = array ('name' => 'primaAntiguedad_'.$anno, 'id' => 'primaAntiguedad_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaAntiguedad)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaExtralegal = array ('name' => 'primaExtralegal_'.$annoInicial, 'id' => 'primaExtralegal_'.$annoInicial);?>
			<td>Primas extralegales</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaExtralegal)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaExtralegal = array ('name' => 'primaExtralegal_'.$anno, 'id' => 'primaExtralegal_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaExtralegal)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaVacaciones = array ('name' => 'primaVacaciones_'.$annoInicial, 'id' => 'primaVacaciones_'.$annoInicial);?>
			<td>Primas de vacaciones</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVacaciones)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaVacaciones = array ('name' => 'primaVacaciones_'.$anno, 'id' => 'primaVacaciones_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaVacaciones)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_primaNavidad = array ('name' => 'primaNavidad_'.$annoInicial, 'id' => 'primaNavidad_'.$annoInicial);?>
			<td>Prima de navidad (Sector Privado)</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaNavidad)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_primaNavidad = array ('name' => 'primaNavidad_'.$anno, 'id' => 'primaNavidad_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_primaNavidad)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_contratosAgricolas = array ('name' => 'contratosAgricolas_'.$annoInicial, 'id' => 'contratosAgricolas_'.$annoInicial);?>
			<td>Contratos agricolas</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_contratosAgricolas)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_contratosAgricolas = array ('name' => 'contratosAgricolas_'.$anno, 'id' => 'contratosAgricolas_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_contratosAgricolas)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_remuneracionSocios = array ('name' => 'remuneracionSocios_'.$annoInicial, 'id' => 'remuneracionSocios_'.$annoInicial);?>
			<td>Remuneración socios industriales</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_remuneracionSocios)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_remuneracionSocios = array ('name' => 'remuneracionSocios_'.$anno, 'id' => 'remuneracionSocios_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_remuneracionSocios)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_horaCatedra = array ('name' => 'horaCatedra_'.$annoInicial, 'id' => 'horaCatedra_'.$annoInicial);?>
			<td>Hora cátedra</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horaCatedra)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_horaCatedra = array ('name' => 'horaCatedra_'.$anno, 'id' => 'horaCatedra_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_horaCatedra)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		<tr>
			<?php $atributos_otrosPagos = array ('name' => 'otrosPagos_'.$annoInicial, 'id' => 'otrosPagos_'.$annoInicial);?>
			<td>Otros pagos denominados como</td>
			<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_otrosPagos)); ?><span class="add-on">.00</span></div></td>
			<?php if (isset($annos) && $annos > 0): 
				$anno = ((int) $annoInicial)-1;
				for ($conteo_annos = $annos; $conteo_annos >= 1; $conteo_annos --):
					$atributos_otrosPagos = array ('name' => 'otrosPagos_'.$anno, 'id' => 'otrosPagos_'.$anno);
			?>
				<td><div class="input-prepend input-append"><span class="add-on">$</span><?php echo form_input(array_merge($atributos_general, $atributos_otrosPagos)); ?><span class="add-on">.00</span></div></td>
			<?php $anno --; 
				endfor; 
			endif; 
			?>
		</tr>
		</tbody>
	</table>
	<br>
	<span class="span7 offset5">
		<?php 
			$atributos_boton = array('id' =>'aceptar', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="icon-cog icon-white"></i> Calcular', 'type' => 'submit');
			echo form_button($atributos_boton) ;
		?>
	</span>
	</div>
	<?php
		$atributos_nit =  array('id' => 'nit' , 'name' => 'nit', 'value' => $fiscalizacion['NIT_EMPRESA'], 'type' => 'hidden' );
		echo form_input($atributos_nit);
		$atributos_cantidad = array('id' => 'cantidad', 'name' => 'cantidad', 'value' => $annos + 1, 'type' => 'hidden');
		echo form_input($atributos_cantidad);
		$atributos_annoInicial = array('id' => 'annoInicial', 'name' => 'annoInicial','value' => $annoInicial, 'type' => 'hidden');
		echo form_input($atributos_annoInicial);	
		$atributos_fechaInicial = array('id' => 'fechaInicial', 'name' => 'fechaInicial', 'value' => $fiscalizacion['PERIODO_INICIAL'], 'type' => 'hidden');
		echo form_input($atributos_fechaInicial);
		$atributos_fechaFinal = array('id' => 'fechaFinal', 'name' => 'fechaFinal', 'value' => $fiscalizacion['PERIODO_FINAL'], 'type' => 'hidden');
		echo form_input($atributos_fechaFinal);
		echo form_close();
	?>
</div>
<!-- Función datepicker -->
<script  type="text/javascript">
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
		maxDate: '1m',
		minDate: '0d',
    });
  });
</script>
<!-- Fin función datepicker -->

<!-- Función solo números -->
<script type="text/javascript" language="javascript" charset="utf-8">
	function num(c){
		c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
}
</script>
<!-- Fin Función solo números -->

<!--
/* End of file liquidacionaportes_form.php */
-->