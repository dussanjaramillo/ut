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
    <th>Sel</th>
    <th>Nro liquidación</th>
    <th>Fecha de vencimiento</th>
    <th>Días en mora</th>
    <th>Total Liquidado</th>
    <th>Saldo pendiente deuda</th>
    <th>Valor Intereses mora</th>
    <th>Valor total deuda a la fecha</th>
    <th>Valor a aplicar</th>
  </thead>
  <tbody>
    <?php
		$saldo_por_aplicar = $valor_pagado;
		if(!is_null($cartera)) :
    	foreach ($cartera as $obligacion) :
		?>
    <tr>
    	<td align="center">
				<?php
					$saldo_aplicado = $interes_mora = $dias_mora = 0;
					if($saldo_por_aplicar > 0) :
						if($obligacion['COD_TIPOPROCESO'] == "ACUERDO") :
							$SALDO_DEUDA = $obligacion['SALDO_DEUDA'];
							$interes_mora = $obligacion['SALDO_DEUDA'] = 0;
							foreach($obligacion['CUOTAS'] AS $cuota) :
								if($cuota['DIAS_MORA'] > 0) :
									$tmp_interes_mora = $this->aplicacionautomaticadepago_model->calcular_mora($cuota['FECHA_VENCIMIENTO'], $fecha_pago, $cuota['SALDO_CUOTA']);
									if($tmp_interes_mora < 0) : $tmp_interes_mora = 0; endif;
									$dias_mora += $cuota['DIAS_MORA'];
								else :
									$dias_mora += 0;
									$tmp_interes_mora = 0;
								endif;
								$interes_mora += $tmp_interes_mora;
								$obligacion['SALDO_DEUDA'] += $cuota['SALDO_CUOTA'];
								$cuot = $cuota['SALDO_CUOTA'] + $interes_mora;
								$saldo_por_aplicar;
								if($saldo_por_aplicar >= $cuot) :
									$saldo_aplicado += $cuot;
									$saldo_por_aplicar -= $cuot;
								else :
									$saldo_aplicado += $saldo_por_aplicar;
									$saldo_por_aplicar = 0;
								endif;
							endforeach;
							$obligacion['DIAS_MORA'] = $dias_mora;
						else :
							$interes_mora = $this->aplicacionautomaticadepago_model->calcular_mora($obligacion['FECHA_VENCIMIENTO'], $fecha_pago, $obligacion['SALDO_DEUDA']);
							if($interes_mora < 0) : $interes_mora = 0; endif;
							$SALDO_DEUDA = $obligacion['SALDO_DEUDA'];
							if($saldo_por_aplicar >= ($obligacion['SALDO_DEUDA'] + $interes_mora)) :
								$saldo_aplicado = ($obligacion['SALDO_DEUDA'] + $interes_mora);
								$saldo_por_aplicar -= ($obligacion['SALDO_DEUDA'] + $interes_mora);
							else :
								$saldo_aplicado = $saldo_por_aplicar;
								$saldo_por_aplicar = 0;
							endif;
						endif;
        ?>
        <i class="fa fa-check-square-o"></i>
        <input type="hidden" value="<?php echo $saldo_aplicado ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][valor_aplicar]" />
        <input type="hidden" value="<?php echo $obligacion['COD_FISCALIZACION'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cod_fiscalizacion]" />
        <input type="hidden" value="<?php echo $interes_mora ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][interes_mora]" />
        <input type="hidden" value="<?php echo $obligacion['TOTAL_CAPITAL'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][capital]" />
        <input type="hidden" value="<?php echo $obligacion['TOTAL_INTERESES'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][interes]" />
        <input type="hidden" value="<?php echo $obligacion['SALDO_CAPITAL'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][saldo_capital]" />
        <input type="hidden" value="<?php echo $obligacion['SALDO_INTERES'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][saldo_interes]" />
        <input type="hidden" value="<?php echo $obligacion['SALDO_DEUDA']+$interes_mora ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][adeudado]" />
        <input type="hidden" value="<?php echo $SALDO_DEUDA ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][saldo_deuda]" />
        <?php
					else :
				?>
        <i class="fa fa-square-o"></i>
        <?php
					endif;
        ?>
      </td>
      <td width="14%"><strong><?php echo $obligacion['NUM_LIQUIDACION'] ?></strong></td>
      <td width="7%"><?php echo $obligacion['FECHA_VENCIMIENTO'] ?></td>
      <td width="7%"><?php echo $obligacion['DIAS_MORA'] ?></td>
      <td>$ <div style="float: right"><?php echo number_format($obligacion['TOTAL_LIQUIDADO'], 0, ",", ".") ?></div></td>
      <td>$ <div style="float: right"><?php echo number_format($obligacion['SALDO_DEUDA'], 0, ",", ".") ?></div></td>
      <td>$ <div style="float: right"><?php echo number_format($interes_mora, 0, ",", ".") ?></div></td>
	  <td>$ <div style="float: right"><?php echo number_format($obligacion['SALDO_DEUDA']+$interes_mora, 0, ",", ".") ?></div></td>
      <td><strong>$ <div style="float: right"><?php echo number_format($saldo_aplicado, 0, ",", ".") ?></strong></div></td>
		</tr>
				<?php
        if($obligacion['COD_TIPOPROCESO'] == "ACUERDO") :
        ?>
		<tr>
    	<td colspan="9">
      	<table class="table table-bordered table-striped" style="width: 99%" align="center">
        	<caption class="titulo">Cuotas del Acuerdo de Pago de la Obligación Nro. <strong><?php echo $obligacion['NUM_LIQUIDACION']; ?></strong></caption>
          <thead class="btn-info">
            <th>Sel</th>
            <th>Nro cuota</th>
            <th>Fecha de vencimiento</th>
            <th>Días en mora</th>
            <th>Valor Cuota</th>
            <th>Capital</th>
            <th>Interesés históricos</th>
            <th>Interesés del acuerdo</th>
            <th>Valor Intereses mora</th>
            <th>Valor total cuota a la fecha</th>
            <th>Valor a aplicar</th>
          </thead>
					<tbody>
        	<?php foreach($obligacion['CUOTAS'] AS $cuota) : ?>
          	<tr>
              <td><?php
                $interes_mora = $aplicado = 0;
								$intacuerdo = $cuota['SALDO_INTACUERDO'];
								$intcorriente = $cuota['SALDO_INTCORRIENTE'];
								$capital = $cuota['SALDO_CAPITAL'];
                if($saldo_aplicado > 0) :
									if($cuota['DIAS_MORA'] > 0) :
	                  $interes_mora += $this->aplicacionautomaticadepago_model->calcular_mora($cuota['FECHA_VENCIMIENTO'], $fecha_pago, $cuota['SALDO_CUOTA']);
										if($interes_mora < 0) : $interes_mora = 0; endif;
									else: 
										$interes_mora += 0;
									endif;
                  $cuot = $cuota['SALDO_CUOTA'] + $interes_mora;
									//echo $saldo_aplicado;
                  if($saldo_aplicado >= $cuot) :
                    $aplicado = $cuot;
                    $saldo_aplicado -= $cuot;
                  else :
                    $aplicado = $saldo_aplicado;
                    $saldo_aplicado = 0;
                  endif;
                ?>
                <i class="fa fa-check-square-o"></i>
                <input type="hidden" value="<?php echo $aplicado ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][saldo_aplicado]" />
                <input type="hidden" value="<?php echo $cuota['SALDO_CAPITAL'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][capital]" />
                <input type="hidden" value="<?php echo $cuota['SALDO_INTCORRIENTE'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][intcorriente]" />
                <input type="hidden" value="<?php echo $cuota['SALDO_INTACUERDO'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][intacuerdo]" />
                <input type="hidden" value="<?php echo $interes_mora ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][interes_mora]" />
                <input type="hidden" value="<?php echo ($cuota['DIAS_MORA'] > 0)?$cuota['DIAS_MORA']:0 ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][dias_mora]" />
                <input type="hidden" value="<?php echo $cuota['SALDO_CUOTA']+$interes_mora ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][adeudado]" />
                <input type="hidden" value="<?php echo $cuota['NRO_ACUERDOPAGO'] ?>" name="deuda[<?php echo $obligacion['NUM_LIQUIDACION'] ?>][cuota][<?php echo $cuota['NO_CUOTA'] ?>][acuerdo]" />
                <?php
                else:
                ?>
                <i class="fa fa-square-o"></i>
                <?php
                endif;
              ?></td>
              <td><?php echo $cuota['NO_CUOTA'] ?></td>
              <td><?php echo $cuota['FECHA_VENCIMIENTO'] ?></td>
              <td><?php echo ($cuota['DIAS_MORA'] > 0)?$cuota['DIAS_MORA']:0; ?></td>
              <td><?php echo number_format($cuota['SALDO_CUOTA'], 0, ",", ".") ?></td>
              <td><?php echo number_format($capital, 0, ",", ".") ?></td>
              <td><?php echo number_format($intcorriente, 0, ",", ".") ?></td>
              <td><?php echo number_format($intacuerdo, 0, ",", ".") ?></td>
              <td><?php echo number_format($interes_mora, 0, ",", ".") ?></td>
              <td><?php echo number_format(($cuota['SALDO_CUOTA']+$interes_mora), 0, ",", ".") ?></td>
              <td><?php echo number_format($aplicado, 0, ",", ".") ?></td>
            </tr>
					<?php endforeach; ?>
					</tbody>
				</table>
        <br>
        <div class="alert alert-warning">
          La última cuota del acuerdo de pago es susceptible de ser recalculada debido al cambio de las tasas de interés.
        </div>
			</td>
		</tr>
          <?php
        endif;
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