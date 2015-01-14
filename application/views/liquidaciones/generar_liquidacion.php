
<?php
/**
* Formulario para la impresión de liquidaciones de Aportes Parafiscales
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones/generar_liquidacion.php
* @last-modified  28/10/2014
* @copyright
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed');
//print_r($liquidacion);
if (isset($message))
{
    echo $message;
}
    // var_dump($maestro);
    // var_dump($detalle);
    // var_dump($fiscalizacion);
    // var_dump($observaciones);
    // var_dump($documentacion);

    // separación de fechas periodo inicial
    $datos_fecha_inicial = explode("/",$maestro ['fecha_inicial']);
    $dia_fecha_inicial = (int)$datos_fecha_inicial[0];
    $mes_fecha_inicial = (int)$datos_fecha_inicial[1];
    $anno_fecha_inicial = (int)$datos_fecha_inicial[2];

    //separación de fechas periodo final
    $datos_fecha_final = explode("/",$maestro ['fecha_final']);
    $dia_fecha_final = (int)$datos_fecha_final[0];
    $mes_fecha_final = (int)$datos_fecha_final[1];
    $anno_fecha_final = (int)$datos_fecha_final[2];
    $diferencia_annos = $anno_fecha_final - $anno_fecha_inicial;

    $annoInicial = $anno_fecha_final;
    //  if(isset(${'serie_'.$annoInicial})):
    // 	var_dump(${'serie_'.$annoInicial});
    // endif;
    // if ($diferencia_annos > 0):
    // 	$anno = ($annoInicial)-1;
    // 	for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
    // 		 var_dump(${'serie_'.$anno});
    // 		$anno--;
    // 	endfor;
    // endif;

?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px; background-color: #FFF;}
#cabecera td, th{padding: 5px; text-align: left; vertical-align: middle;}
#controles table{width: 100%; height: auto; margin: 10px; text-align: center;}
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
<!-- Fin Estilos personalizados -->
<form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo site_url('/liquidaciones/pdf') ?>">
 	<textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
 	<input type="hidden" name="tipo" id="tipo" value="3">
 	<input type="hidden" name="nombre" id="nombre">
 	<input type="hidden" name="titulo" id="titulo" value="Liquidación Aportes Parafiscales">
 </form>
<!-- Cargue de formulario -->
<div class="center-form-xlarge" id="cabecera">
	<h2 class="text-center">Liquidación de <?php echo $fiscalizacion['NOMBRE_CONCEPTO'] ?></h2>
	<br><br>
	<table class = "table table-bordered table-striped table-condensed">
		<tr>
			<td><span class = "text-center" style = "display:block;"><img src="<?php echo base_url() . 'img/Logotipo.png' ?>"></span></td>
			<td colspan = "2"><span class = "text-center" style = "display: block;">SERVICIO NACIONAL DE APRENDIZAJE SENA<br>NIT: <?php echo $fiscalizacion['NIT_EMPRESA']; ?> FORMATO LIQUIDACIÓN APORTES <!--Concepto: <?php //echo mb_strtoupper($fiscalizacion['NOMBRE_CONCEPTO'], 'UTF-8'); ?>--></span></td>
		</tr>
		<tr>
			<td><span>Regional: <?php echo $fiscalizacion['NOMBRE_REGIONAL']; ?></span></td>
			<td><span>Liquidación: <?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?></span></td>
			<td><span>Fecha: <?php echo $maestro['fechaLiquidacion']; ?></span></td>
		</tr>
		<tr>
			<td colspan = "3"><span>Nombre o Razón Social: <?php echo mb_strtoupper($fiscalizacion['RAZON_SOCIAL'], 'UTF-8'); ?></span></td>
		</tr>
		<tr>
			<td><span>NIT: <?php echo $fiscalizacion['NIT_EMPRESA']; ?></span></td>
			<td><span>Actividad Económica:<br><?php echo $fiscalizacion['CIIU'], ' - ', mb_strtoupper($fiscalizacion['DESCRIPCION'], 'UTF-8'); ?></span></td>
			<td><span>Representante Legal:<br><?php echo mb_strtoupper($fiscalizacion['REPRESENTANTE_LEGAL'], 'UTF-8'); ?><br>CC: </span></td>
		</tr>
		<tr>
			<td colspan = "2"><span>Dirección: <?php echo mb_strtoupper($fiscalizacion['DIRECCION'], 'UTF-8'); ?></span></td>
			<td><span>Municipio: <?php echo mb_strtoupper($fiscalizacion['NOMBREMUNICIPIO'], 'UTF-8'); ?></span></td>
		</tr>
		<tr>
			<td><span>Funcionario Entrevistado: </span></td>
			<td><span>Cargo: </span></td>
			<td><span>Teléfono: <?php echo $fiscalizacion['TELEFONO_FIJO']; ?></span></td>
		</tr>
		<tr>
			<td colspan = "3">La empresa pertenece al sector:</td>
		</tr>
		<tr>
			<td><span>Escritura: </span></td>
			<td><span>Notaria: </span></td>
			<td><span>Ciudad: <br>Fecha Constitución: </span></td>
		</tr>
		<tr>
			<td><span>Obligada a Contratar aprendices: </span></td>
			<td><span>Resolución: <br>Fecha de Expedición: </span></td>
			<td><span>Nro. de trabajadores actuales: </span></td>
		</tr>
		<tr>
			<td><span>Correo Electrónico: <?php echo mb_strtoupper($fiscalizacion['EMAILAUTORIZADO'], 'UTF-8'); ?></span></td>
			<td><span>Telefono 1: </span></td>
			<td><span>Telefono 2: <?php echo $fiscalizacion['FAX']; ?></span></td>
		</tr>
	</table>
	<table class="table table-bordered table-striped table-condensed">
		<tr>
			<th>Año Fiscal</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<th><?php echo ${'serie_'.$annoInicial}['ANO']; ?></th>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<th><?php echo  ${'serie_'.$anno}['ANO'];?></th>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Sueldos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VALORSUELDOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VALORSUELDOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Sobresueldos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VALORSOBRESUELDOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VALORSOBRESUELDOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Salario Integral 70%</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SALARIOINTEGRAL'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SALARIOINTEGRAL'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Salario en Especie</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SALARIOESPECIE'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SALARIOESPECIE'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Supernumerarios</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SUPERNUMERARIOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SUPERNUMERARIOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Viaticos (Manutención y Alojamiento)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VIATICOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VIATICOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Auxilio de Transporte</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['AUXILIOTRANSPORTE'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['AUXILIOTRANSPORTE'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Horas Extras</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['HORASEXTRAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['HORASEXTRAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Dominicales y Festivos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['DOMINICALES_FESTIVOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['DOMINICALES_FESTIVOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Recargo Nocturno</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['RECARGONOCTURNO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['RECARGONOCTURNO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Jornales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['JORNALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['JORNALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Comisiones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['COMISIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['COMISIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Bonificaciones Habituales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['BONIFICACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['BONIFICACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Porcentaje sobre ventas</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['POR_SOBREVENTAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['POR_SOBREVENTAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Vacaciones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VACACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VACACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Trabajo a Domicilio</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['TRAB_DOMICILIO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['TRAB_DOMICILIO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Contratos y subcontratos (Construcción)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SUBCONTRATO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SUBCONTRATO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima Técnia Salarial (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_TEC_SALARIAL'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_TEC_SALARIAL'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Auxilio y/o Subsidio de alimentación</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['AUXILIO_ALIMENTACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['AUXILIO_ALIMENTACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de servicio (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_SERVICIO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_SERVICIO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de Localización</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_LOCALIZACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_LOCALIZACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de vivienda (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_VIVIENDA'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_VIVIENDA'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Gastos de representación (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['GAST_REPRESENTACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['GAST_REPRESENTACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima o Incremento por antiguedad</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_ANTIGUEDAD'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_ANTIGUEDAD'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de productividad</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_EXTRALEGALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_EXTRALEGALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de vacaciones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_VACACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_VACACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de navidad (Sector Privado)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_NAVIDAD'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_NAVIDAD'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Contratos agricolas</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['CONTRATOS_AGRICOLAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['CONTRATOS_AGRICOLAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Remuneración socios industriales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Hora cátedra</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['HORA_CATEDRA'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['HORA_CATEDRA'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Otros pagos denominados como</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['OTROS_PAGOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['OTROS_PAGOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
	</table>
	<table class = "table table-striped table-bordered table-condensed">
		<tr>
			<th><span>Años</span></th>
			<?php
				$anno = $anno_fecha_final;
				$anno_deuda = $anno_fecha_final;
				$anno_intereses = $anno_fecha_final;
				$anno_subtotal = $anno_fecha_final;

				$total_capital = 0;
				$total_interes = 0;
				$total = 0;
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<th><span>', $anno, '</span></th>';
					$anno--;
				endfor;
			?>
		</tr>
		<tr>
			<th><span>Valor Capital Obligación</span></th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format(($detalle['deuda_'.$anno_deuda] - $detalle['aportes_'.$anno_deuda]), 0, '.', '.'), '</span></td>';
					$total_capital += $detalle['deuda_'.$anno_deuda];
					$anno_deuda--;
				endfor;
			?>
		</tr>
		<tr>
			<th><span>Intereses de Mora</span></th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format($detalle['intereses_'.$anno_intereses], 0, '.', '.'), '</span></td>';
					$total_interes += $detalle['intereses_'.$anno_intereses];
					$anno_intereses--;
				endfor;
			?>
		</tr>
		<tr>
			<th><span>Subtotal Año</span></th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format(($detalle['deuda_'.$anno_subtotal] + $detalle['intereses_'.$anno_subtotal]), 0, '.', '.'), '</span></td>';
					$total += ($detalle['deuda_'.$anno_subtotal] + $detalle['intereses_'.$anno_subtotal]);
					$anno_subtotal--;
				endfor;
			?>
		</tr>
	</table>
	<table class="table table-striped table-bordered">
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;"><span>TOTAL CAPITAL</span></th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php echo "$".number_format($total_capital, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;"><span>TOTAL INTERES</span></th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php  echo "$".number_format($total_interes, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;"><span>TOTAL</span></th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php echo "$".number_format($total, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td colspan = "4"><span><strong>III. OBSERVACIONES DE LA VISITA (Hallazgos, conclusiones, descripción del procedimiento, etc.)</strong></span></td>
		</tr>
		<tr>
			<td colspan = "4">
				<span>
				<p>ESTA LIQUIDACION GENERA INTERESES DE MORA DE ACUERDO A LA LEY 1066 DE JULIO 29 DE 2006 y LEY 1607 DEL 26 DE DICIEMBRE DE 2012</p>
				<?php echo $observaciones; ?>
				</span>
			</td>
		</tr>
		<tr>
			<td colspan = "4"><span><strong>IV. DOCUMENTACIÓN APORTADA COMO BASE PARA LA LIQUIDACIÓN</strong></span></td>
		</tr>
		<tr>
			<td colspan = "4"><span><?php echo $documentacion; ?></span></td>
		</tr>
		<tr>
			<td colspan = "4">Para efectuar el pago" a través de internet > pagina www.sena.edu.co > servicio al ciudadano > pagos en línea > recaudos sena >  registra su empresa, en còdigo de usuario el NIT sin DV sin puntos ni comas, y continúe con el procedimiento en la opción Aportes > liquidación de fiscalización No. <?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<table  class="table table-bordered">
			<tr>
				<td style = "width:33%;"><span>Nombre y firma del funcionario autorizado por el SENA</span></td>
				<td style = "width:34%;"><span>Nombre, Firma y/o Sello del funcionario autorizado por la empresa</span></td>
				<td style = "width:33%;"><span>V°.B°. Coordinador para Registro en Cartera</span></td>
			</tr>
			<tr  style = "height:200px;">
				<td style = "width:33%;"><span class="muted"></span></td>
				<td style = "width:34%;"><span class="muted"></span></td>
				<td style = "width:33%;"><span class="muted"></span></td>
			</tr>
			<tr>
				<td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($fiscalizador, 'UTF-8'); ?></span></td>
				<td style = "width:34%;"><span>Cargo: <?php //echo mb_strtoupper($fiscalizacion['REPRESENTANTE_LEGAL'], 'UTF-8'); ?></span></td>
				<td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($informacion_usuario['NOMBRE_COORDINADOR_RELACIONES'], 'UTF-8'); ?></span></td>
			</tr>
		</table>
	</table>
</div>
<!-- Fin cargue de formulario -->
<!-- Cargue de Vista Previa -->
<div id = "vistaPrevia_base" style = "display:none;" title = "Liquidación de <?php echo $fiscalizacion['NOMBRE_CONCEPTO'];?>">
	<table class = "table table-bordered table-striped table-condensed">
		<tr>
			<td colspan = "3"><span class = "text-center" style = "display: block;">SERVICIO NACIONAL DE APRENDIZAJE SENA<br>NIT: <?php echo $fiscalizacion['NIT_EMPRESA']; ?> FORMATO LIQUIDACIÓN APORTES <!--Concepto: <?php //echo mb_strtoupper($fiscalizacion['NOMBRE_CONCEPTO'], 'UTF-8'); ?>--></span></td>
		</tr>
		<tr>
			<td><span>Regional: <?php echo $fiscalizacion['NOMBRE_REGIONAL']; ?></span></td>
			<td><span>Liquidación: <?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?></span></td>
			<td><span>Fecha: <?php echo $maestro['fechaLiquidacion']; ?></span></td>
		</tr>
		<tr>
			<td colspan = "3">Nombre o Razón Social: <?php echo mb_strtoupper($fiscalizacion['RAZON_SOCIAL'], 'UTF-8'); ?></td>
		</tr>
		<tr>
			<td>NIT: <?php echo $fiscalizacion['NIT_EMPRESA']; ?></td>
			<td>Actividad Económica: <?php echo $fiscalizacion['CIIU'], ' - ', mb_strtoupper($fiscalizacion['DESCRIPCION'], 'UTF-8'); ?></td>
			<td>Representante Legal: <?php echo mb_strtoupper($fiscalizacion['REPRESENTANTE_LEGAL'], 'UTF-8'); ?></td>
		</tr>
		<tr>
			<td colspan = "2"><span>Dirección: <?php echo mb_strtoupper($fiscalizacion['DIRECCION'], 'UTF-8'); ?></span></td>
			<td><span>Municipio: <?php echo mb_strtoupper($fiscalizacion['NOMBREMUNICIPIO'], 'UTF-8'); ?></span></td>
		</tr>
		<tr>
			<td><span>Funcionario Entrevistado: </span></td>
			<td><span>Cargo: </span></td>
			<td><span>Teléfono: <?php echo $fiscalizacion['TELEFONO_FIJO']; ?></span></td>
		</tr>
		<tr>
			<td colspan = "3">La empresa pertenece al sector:</td>
		</tr>
		<tr>
			<td><span>Escritura: </span></td>
			<td><span>Notaria: </span></td>
			<td><span>Ciudad: <br>Fecha Constitución: </span></td>
		</tr>
		<tr>
			<td><span>Obligada a Contratar aprendices: </span></td>
			<td><span>Resolución: <br>Fecha de Expedición: </span></td>
			<td><span>Nro. de trabajadores actuales: </span></td>
		</tr>
		<tr>
			<td><span>Correo Electrónico: <?php echo mb_strtoupper($fiscalizacion['EMAILAUTORIZADO'], 'UTF-8'); ?></span></td>
			<td><span>Telefono 1: </span></td>
			<td><span>Telefono 2: <?php echo $fiscalizacion['FAX']; ?></span></td>
		</tr>
	</table>
    <p>&nbsp;</p>
	<table class="table table-bordered table-striped table-condensed">
		<tr>
			<th>Año Fiscal</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<th><?php echo ${'serie_'.$annoInicial}['ANO']; ?></th>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<th><?php echo  ${'serie_'.$anno}['ANO'];?></th>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Sueldos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VALORSUELDOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VALORSUELDOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Sobresueldos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VALORSOBRESUELDOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VALORSOBRESUELDOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Salario Integral 70%</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SALARIOINTEGRAL'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SALARIOINTEGRAL'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Salario en Especie</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SALARIOESPECIE'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SALARIOESPECIE'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Supernumerarios</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SUPERNUMERARIOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SUPERNUMERARIOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Viaticos (Manutención y Alojamiento)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VIATICOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VIATICOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Auxilio de Transporte</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['AUXILIOTRANSPORTE'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['AUXILIOTRANSPORTE'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Horas Extras</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['HORASEXTRAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['HORASEXTRAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Dominicales y Festivos</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['DOMINICALES_FESTIVOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['DOMINICALES_FESTIVOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Recargo Nocturno</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['RECARGONOCTURNO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['RECARGONOCTURNO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Jornales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['JORNALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['JORNALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Comisiones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['COMISIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['COMISIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Bonificaciones Habituales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['BONIFICACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['BONIFICACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Porcentaje sobre ventas</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['POR_SOBREVENTAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['POR_SOBREVENTAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Vacaciones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['VACACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['VACACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Trabajo a Domicilio</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['TRAB_DOMICILIO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['TRAB_DOMICILIO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Contratos y subcontratos (Construcción)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['SUBCONTRATO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['SUBCONTRATO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima Técnia Salarial (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_TEC_SALARIAL'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_TEC_SALARIAL'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Auxilio y/o Subsidio de alimentación</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['AUXILIO_ALIMENTACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['AUXILIO_ALIMENTACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de servicio (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_SERVICIO'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_SERVICIO'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de Localización</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_LOCALIZACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_LOCALIZACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de vivienda (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_VIVIENDA'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_VIVIENDA'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Gastos de representación (Sector Público)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['GAST_REPRESENTACION'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['GAST_REPRESENTACION'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima o Incremento por antiguedad</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_ANTIGUEDAD'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_ANTIGUEDAD'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de productividad</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_EXTRALEGALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_EXTRALEGALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de vacaciones</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_VACACIONES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_VACACIONES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Prima de navidad (Sector Privado)</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['PRIMA_NAVIDAD'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['PRIMA_NAVIDAD'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Contratos agricolas</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['CONTRATOS_AGRICOLAS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['CONTRATOS_AGRICOLAS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Remuneración socios industriales</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['REMU_SOCIOS_INDUSTRIALES'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Hora cátedra</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['HORA_CATEDRA'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['HORA_CATEDRA'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
		<tr>
			<th>Otros pagos denominados como</th>
			<?php
				 if(isset(${'serie_'.$annoInicial})):
			?>
			<td><span class="text-right" style="display:block;"><?php echo "$".number_format(${'serie_'.$annoInicial}['OTROS_PAGOS'], 0, '.', '.'); ?></span></td>
			<?php
				endif;
				if ($diferencia_annos > 0):
					$anno = ($annoInicial)-1;
					for ($conteo_annos = $diferencia_annos; $conteo_annos >= 1; $conteo_annos --):
			?>
			<td><span class="text-right" style="display:block;"><?php echo  "$".number_format(${'serie_'.$anno}['OTROS_PAGOS'], 0, '.', '.');?></span></td>
			<?php
						$anno--;
					endfor;
				endif;
			?>
		</tr>
	</table>
    <p>&nbsp;</p>
	<table class = "table table-striped table-bordered table-condensed">
		<tr>
			<th>Años</th>
			<?php
				$anno = $anno_fecha_final;
				$anno_deuda = $anno_fecha_final;
				$anno_intereses = $anno_fecha_final;
				$anno_subtotal = $anno_fecha_final;

				$total_capital = 0;
				$total_interes = 0;
				$total = 0;
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<th>', $anno, '</th>';
					$anno--;
				endfor;
			?>
		</tr>
		<tr>
			<th>Valor Capital Obligación</th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format($detalle['deuda_'.$anno_deuda], 0, '.', '.'), '</span></td>';
					$total_capital += $detalle['deuda_'.$anno_deuda];
					$anno_deuda--;
				endfor;
			?>
		</tr>
		<tr>
			<th>Intereses de Mora</th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format($detalle['intereses_'.$anno_intereses], 0, '.', '.'), '</span></td>';
					$total_interes += $detalle['intereses_'.$anno_intereses];
					$anno_intereses--;
				endfor;
			?>
		</tr>
		<tr>
			<th>Subtotal Año</th>
			<?php
				for ($i = $diferencia_annos; $i >= 0; $i--):
					echo '<td><span class="text-right" style="display:block;">', "$".number_format(($detalle['deuda_'.$anno_subtotal] + $detalle['intereses_'.$anno_subtotal]), 0, '.', '.'), '</span></td>';
					$total += ($detalle['deuda_'.$anno_subtotal] + $detalle['intereses_'.$anno_subtotal]);
					$anno_subtotal--;
				endfor;
			?>
		</tr>
	</table>
	<table class="table table-striped table-bordered">
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;">TOTAL CAPITAL</th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php echo "$".number_format($total_capital, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;">TOTAL INTERES</th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php  echo "$".number_format($total_interes, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<th style = "width:25%;">TOTAL</th>
			<th style = "width:25%;"><span class="text-right" style="display:block;"><?php echo "$".number_format($total, 0, '.', '.'); ?></span></th>
		</tr>
		<tr>
			<td colspan = "4"><span><strong>III. OBSERVACIONES DE LA VISITA (Hallazgos, conclusiones, descripción del procedimiento, etc.)</strong></span></td>
		</tr>
		<tr>
			<td colspan = "4">
				<span>
				<p>ESTA LIQUIDACION GENERA INTERESES DE MORA DE ACUERDO A LA LEY 1066 DE JULIO 29 DE 2006 y LEY 1607 DEL 26 DE DICIEMBRE DE 2012</p>
				<?php echo $observaciones; ?>
				</span>
			</td>
		</tr>
		<tr>
			<td colspan = "4"><span><strong>IV. DOCUMENTACIÓN APORTADA COMO BASE PARA LA LIQUIDACIÓN</strong></span></td>
		</tr>
		<tr>
			<td colspan = "4"><span><?php echo $documentacion; ?></span></td>
		</tr>
		<tr>
			<td colspan = "4">Para efectuar el pago" a través de internet > pagina www.sena.edu.co > servicio al ciudadano > pagos en línea > recaudos sena >  registra su empresa, en còdigo de usuario el NIT sin DV sin puntos ni comas, y continúe con el procedimiento en la opción Aportes > liquidación de fiscalización No. <?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?></td>
		</tr>
	</table>
    <p>&nbsp;</p>
	<table class="table table-bordered">
		<tr>
			<td style = "width:33%;"><span>Nombre y firma del funcionario autorizado por el SENA</span></td>
			<td style = "width:34%;"><span>Nombre, Firma y/o Sello del funcionario autorizado por la empresa</span></td>
			<td style = "width:33%;"><span>V°.B°. Coordinador para Registro en Cartera</span></td>
		</tr>
		<tr>
			<td style = "width:33%;"><span class="muted"><p>&nbsp;</p><p>&nbsp;</p></span></td>
			<td style = "width:34%;"><span class="muted"><p>&nbsp;</p><p>&nbsp;</p></span></td>
			<td style = "width:33%;"><span class="muted"><p>&nbsp;</p><p>&nbsp;</p></span></td>
		</tr>
		<tr>
			<td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($fiscalizador, 'UTF-8'); ?></span></td>
			<td style = "width:34%;"><span>Cargo: <?php //echo mb_strtoupper($fiscalizacion['REPRESENTANTE_LEGAL'], 'UTF-8'); ?></span></td>
			<td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($informacion_usuario['NOMBRE_COORDINADOR_RELACIONES'], 'UTF-8'); ?></span></td>
		</tr>
	</table>
</div>
<!-- Fin Cargue Vista Previa -->
<!-- Controles -->
<div id="controles">
	<table>
		<tr>
			<td><a class="btn btn-default" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $fiscalizacion['COD_FISCALIZACION']?>"><i class="fa fa-arrow-left"></i>Volver</a></td>
			<td><a class="btn btn-info"  id="vistaPrevia"><i class="fa fa-eye"></i>  Vista Previa</a></td>
			<td><a class="btn btn-info" onClick="javascript:pdf();"><i class="fa fa-print"></i>  Imprimir</a></td>
			<td><a class="btn btn-success" href="comprobanteAlterno/<?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?>"><i class="fa fa-bank"></i>  Comprobante</a>	</td>
			<td><a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones/legalizarLiquidacion/"><i class="fa fa-suitcase"></i>  Legalizar</a>	</td>
			<td><a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones/getLiquidacion/"><i class="fa fa-envelope-o"></i>  Carta Presentación</a>	</td>
		</tr>
	</table>
</div>
<!-- Fin Controles -->
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
<!-- Función Imprimir -->
<script type="text/javascript" language="javascript" charset="utf-8">
function base64_encode(data)
{
	var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	ac = 0,
	enc = '',
	tmp_arr = [];
	if (!data)
	{
		return data;
	}
	do
	{
		o1 = data.charCodeAt(i++);
		o2 = data.charCodeAt(i++);
		o3 = data.charCodeAt(i++);
		bits = o1 << 16 | o2 << 8 | o3;
		h1 = bits >> 18 & 0x3f;
		h2 = bits >> 12 & 0x3f;
		h3 = bits >> 6 & 0x3f;
		h4 = bits & 0x3f;
		tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	}
	while (i < data.length);
	enc = tmp_arr.join('');
	var r = data.length % 3;
	return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function pdf()
{
	var informacion = document.getElementById("vistaPrevia_base").innerHTML;
	document.getElementById("nombre").value ='liquidacionAportes_<?php echo $fiscalizacion['NRO_EXPEDIENTE']; ?>';
	document.getElementById("html").value = base64_encode(informacion);
	$("#form2").submit();
}

</script>
<!-- Fin Función Imprimir -->

<!--
/* End of file generar_liquidacion.php */
-->