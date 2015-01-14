<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php if (isset($error)) : ?>
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $error['text']; ?>
</div>
<?php endif; ?>
<table class="table table-bordered table-striped" style="width: 99%" align="center">
  <caption class="titulo">Obligaciones de cartera <?php echo $titulo; ?></caption>
  <thead class="btn-success">
  	<th>No.</th>
    <th>Sel</th>
    <th>Nro referencia</th>
    <th>Nro cuota.</th>
    <th>Mes proyectado</th>
    <th>Fecha de vencimiento</th>
    <th>Días en mora</th>
    <th>Valor Cuota</th>
    <th>Valor Intereses mora</th>
		<th>Valor total cuota a la fecha</th>
    <th>Valor a aplicar</th>
  </thead>
  <tbody>
    <?php
		$saldo_por_aplicar = $valor_pagado;
		$x = 0;
		if(!is_null($cartera)) :
    	foreach ($cartera as $obligacion) :
		?>
    <tr>
    	<td><?php echo ++$x; ?></td>
    	<td align="center">
				<?php
					$saldo_aplicado = $interes_mora = 0;
					if($saldo_por_aplicar > 0) :
						$interes_mora = $obligacion['SALDO_DEUDA'] = 0;
						if($obligacion['DIAS_MORA'] > 0) :
							$interes_mora = $this->aplicacionautomaticadepago_model
																	 ->calcular_mora_nomisional($obligacion['CALCULO_MORA'], $obligacion['COD_CARTERA_NOMISIONAL'], 
																														  $obligacion['VALOR_T_MORA'], $obligacion['FECHA_LIM_PAGO'], $fecha_pago, 
																														  $obligacion['SALDO_AMORTIZACION'], $dato['DIAS_MORA']);
						else :
							$interes_mora = 0;
						endif;
						$cuota = $obligacion['SALDO_CUOTA'] + $interes_mora;
						if($saldo_por_aplicar >= $cuota) :
							$saldo_aplicado += $cuota;
							$saldo_por_aplicar -= $cuota;
						else :
							$saldo_aplicado = $saldo_por_aplicar;
							$saldo_por_aplicar = 0;
						endif;
        ?>
        <i class="fa fa-check-square-o"></i>
        <input type="hidden" value="<?php echo $obligacion['SALDO_CUOTA']+$interes_mora ?>" name="deuda[<?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?>][<?php echo $obligacion['NO_CUOTA'] ?>][adeudado]" />
        <input type="hidden" value="<?php echo $saldo_aplicado ?>" name="deuda[<?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?>][<?php echo $obligacion['NO_CUOTA'] ?>][saldo_aplicado]" />
				<input type="hidden" value="<?php echo $interes_mora ?>" name="deuda[<?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?>][<?php echo $obligacion['NO_CUOTA'] ?>][interes_mora]" />
        <input type="hidden" value="<?php echo $obligacion['SALDO_AMORTIZACION'] ?>" name="deuda[<?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?>][<?php echo $obligacion['NO_CUOTA'] ?>][capital]" />
        <input type="hidden" value="<?php echo $obligacion['SALDO_INTERES_C'] ?>" name="deuda[<?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?>][<?php echo $obligacion['NO_CUOTA'] ?>][interes]" />
        <?php
					else :
				?>
        <i class="fa fa-square-o"></i>
        <?php
					endif;
        ?>
      </td>
      <td width="14%"><strong><?php echo $obligacion['COD_CARTERA_NOMISIONAL'] ?></strong></td>
      <td><?php echo $obligacion['NO_CUOTA'] ?></td>
      <td><?php echo $obligacion['MES_PROYECTADO'] ?></td>
      <td width="7%"><?php echo $obligacion['FECHA_LIM_PAGO'] ?></td>
      <td width="7%"><?php echo $obligacion['DIAS_MORA'] ?></td>
			<td><?php echo number_format($obligacion['SALDO_CUOTA'], 0, ",", ".") ?></td>
      <td><?php echo number_format($interes_mora, 0, ",", ".") ?></td>
      <td><?php echo number_format(($obligacion['SALDO_CUOTA']+$interes_mora), 0, ",", ".") ?></td>
      <td><strong>$ <div style="float: right"><?php echo number_format($saldo_aplicado, 0, ",", ".") ?></strong></div></td>
		</tr>
		<?php
    	endforeach;
		endif;
    ?>
  </tbody>
</table>
<?php
if(isset($saldo_por_aplicar) and $saldo_por_aplicar > 0) :
	$data = array(
		'name' => 'saldo_por_aplicar',
		'id' => 'saldo_por_aplicar',
		'value' => $saldo_por_aplicar,
		'readonly' => "readonly",
		'type' => 'hidden'
	);

	echo form_input($data);
	?>
<div class="alert alert-danger">
	Existe un saldo de <strong>$ <?php echo number_format($saldo_por_aplicar, 0, ",", ".") ?></strong> por aplicar del valor de la consignación.
  <p>Por favor registre el pago como "pago sin identificar" en el concepto "OTROS PAGOS" para que posteriormente pueda reclasificarlo.</p>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(
		function() {
			$("#submit-button").attr("disabled", true);
		}
	);
</script>
  <?php
endif;
?>