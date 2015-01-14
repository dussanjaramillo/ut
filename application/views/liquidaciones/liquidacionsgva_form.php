<?php
/**
 * Formulario para la impresión y captura de liquidaciones de Contratos de Aprendizaje
 *
 * @package    Cartera
 * @subpackage Views
 * @author     jdussan
 * @location   application/views/liquidaciones/liquidacionsgva_form.php
 * @last-modified  20/11/2014
 * @copyright
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (isset($message))
{
    echo $message;
}
echo '<pre>';
//print_r($info);
//echo '<br>';
//print_r($regulaciones);
//echo '<br>';
print_r($contratos);
echo '<br>';
print_r($contratos2);
//echo '<br>';
//print_r($monetizacion);
//echo '<br>';
//print_r($annoInicio);
//print_r($annoFinal);
//if($annoInicio == $annoFinal):

//    print_r(${'resumen_'.$annoInicio});

//elseif($annoFinal > $annoInicio):

    //for($anno = $annoInicio; $anno <= $annoFinal; $anno++):

        //print_r(${'resumen_'.$anno});
        //print_r(${'smlv_' . $anno});
        //print_r(${'monetizacion_' . $anno});

    //endfor;

//endif;
echo '</pre>';
$fechaInicio = explode(' ', $info['estado_fecha_inicio']);
$fechaFin = explode(' ', $info['estado_fecha_fin']);
$acumulador_cuota = 0;
$acumuladorTC = 0;
?>
<style>
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
<!-- Formulario de envío para impresión -->
<form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo site_url('/liquidaciones/pdf') ?>">
 	<textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
 	<input type="hidden" name="tipo" id="tipo" value="3">
 	<input type="hidden" name="nombre" id="nombre">
 	<input type="hidden" name="titulo" id="titulo" value="Liquidación Contratos de Aprendizaje">
 </form>
<!-- Fin formulario de envío para impresión -->
<!-- Cargue de cabeceras -->
<div class="center-form-xlarge" id="cabecera">
	<h2 class="text-center">Estado de Cuenta Sistema de Gestión Virtual de Aprendices</h2>
	<br>
	<div class="controls controls-row">
		<table class="table table-bordered">
			<tr>
				<td><strong>Nit:</strong><br><span class="muted"><?php echo $info['estado_nit_empresa']; ?></span></td>
				<td colspan = "3"><strong>Razón Social:</strong><br><span class="muted"><?php echo utf8_encode($info['empresa_RSocial']); ?></span></td>
			</tr>
			<tr>
				<td><strong>Número Estado de Cuenta:</strong><br><span class="muted"><?php echo $info['estado_nro_estado'] ; ?></span></td>
				<td><strong>Creado por:</strong><br><span class="muted"><?php echo utf8_encode($info['estado_creado_por']); ?></span></td>
				<td><strong>Fecha Inicio:</strong><br><span class="muted"><?php echo $fechaInicio[0]; ?></span></span></td>
				<td><strong>Fecha Fin:</strong><br><span class="muted"><?php echo $fechaFin[0]; ?></span></td>
			</tr>
		</table>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Regulaciones: <?php if(empty($regulaciones)): echo '[0]'; else: echo '[' . count($regulaciones) . ']'; endif;?></strong</td>
			</tr>
		</table>
		<?php if(empty($regulaciones)):?>
		<div class="alert alert-warning text-center"> No se encuentran datos de Regulaciones disponibles</div>
		<?php else: ?>
		<table class="table table-striped table-bordered" width = "90%">
			<tr>
				<th>RESOLUCIÓN</th>
				<th>FECHA RESOLUCIÓN</th>
				<th>TRABAJADORES</th>
				<th>CUOTA</th>
				<th>EJECUTORIA</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
                <?php
                for($annoRegulacion = $annoInicio; $annoRegulacion <= $annoFinal; $annoRegulacion++):
                ?>
                <th><?php echo $annoRegulacion; ?></th>
                <?php
                endfor;
                ?>
			</tr>
			<?php
                for($annoRegulacion = $annoInicio; $annoRegulacion <= $annoFinal; $annoRegulacion++):
                    ${'acumulador_'.$annoRegulacion} = 0;
                endfor;
                foreach($regulaciones as $fila):
                    ?>
                    <tr>
                        <td><?php echo $fila['resolucion'] ; ?></td>
                        <td><?php echo $fila['fecha_resolucion'] ; ?></td>
                        <td><?php echo 'Trab[' . $fila['trab'] . '] <br> Horas[' .  ($fila['horas']) . ']' ; ?></td>
                        <td><?php echo $fila['cuota'] ; ?></td>
                        <td><?php echo $fila['ejecutoria'] ; ?></td>
                        <td><?php echo $fila['fecha_inicial'] ; ?></td>
                        <td><?php echo $fila['fecha_final'] ; ?></td>
                        <?php
                        for($annoRegulacion = $annoInicio; $annoRegulacion <= $annoFinal; $annoRegulacion++):
                            ?>
                            <td><?php if(isset($fila['a'.$annoRegulacion])): echo $fila['a'.$annoRegulacion]; else: echo '0'; endif; ?></td>
                        <?php
                            ${'acumulador_'.$annoRegulacion} += $fila['a'.$annoRegulacion];
                        endfor;
                        $acumulador_cuota += $fila['cuota'];
                        ?>
                    </tr>
                <?php
                endforeach;
            ?>
			<tr>
				<td colspan="7">TOTAL DÍAS ASIGNADOS</td>
                <?php
                for($annoRegulacion = $annoInicio; $annoRegulacion <= $annoFinal; $annoRegulacion++):
                ?>
				<td><?php echo (int)${'acumulador_'.$annoRegulacion}; ?></td>
                <?php
                endfor;
                ?>
			</tr>
			<tr>
				<td colspan="7">TOTAL DÍAS POR CUOTA DE APRENDIZ</td>
                <?php
                for($annoRegulacion = $annoInicio; $annoRegulacion <= $annoFinal; $annoRegulacion++):
                ?>
				<td><?php echo (int)${'acumulador_'.$annoRegulacion} * $acumulador_cuota; ?></td>
                <?php
                endfor;
                ?>
			</tr>
		</table>
		<?php endif;?>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Contrato: <?php if(empty($contratos)): echo '[0]'; else: echo '[' . count($contratos) . ']'; endif; ?></strong</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<span class="span2"><small>[1] = Aprendiz SENA</small></span>
					<span class="span2"><small>[2] = Técnico - Tecnólogo Educación Superior</small></span>
					<span class="span2"><small>[3] = Aprendiz de instituciones de formación para el trabajo</small></span>
					<span class="span2"><small>[4] = Aprendiz de la media Técnica (Colegio)</small></span>
					<span class="span2"><small>[5] = Aprendiz de universitario profesional</small></span>
					<span class="span2"><small>[6] = Entidades de Formación con certificación de calidad</small></span>
					<span class="span2"><small>[7] = Emoresas Formadoras</small></span>
					<span class="span2"><small>[8] = Aprendices con autorización de Colciencias</small></span>
				</td>
			</tr>
		</table>
		<?php if(empty($contratos)):?>
		<div class="alert alert-warning text-center"> No se encuentran datos de Contratos disponibles</div>
		<?php else: ?>
		<table class="table table-striped table-bordered">
			<tr>
				<th>APRENDIZ</th>
				<th>DOCUMENTO</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
				<th>ACUERDO 11</th>
                <?php
                for($annoContrato = $annoInicio; $annoContrato <= $annoFinal; $annoContrato++):
                    ${'acumuladorC_'.$annoContrato} = 0;
                endfor;
                for($annoContrato = $annoInicio; $annoContrato <= $annoFinal; $annoContrato++):
                    ?>
                    <th><?php echo $annoContrato; ?></th>
                <?php
                endfor;
                ?>
                <th>TOTAL DIAS</th>
			</tr>
			<?php foreach($contratos as $fila):?>
			<tr>
				<td><?php echo '[' . $fila['t_estudiante_id'] . ']  ' . utf8_encode($fila['aprendiz_nombres']) . ' ' . utf8_encode($fila['aprendiz_apellidos']); ?></td>
				<td><?php echo $fila['contrato_docid'] ; ?></td>
				<td><?php echo $fila['fecha_inicial'] ; ?></td>
				<td><?php echo $fila['fecha_final'] ; ?></td>
				<td><?php echo $fila['acuerdo_11'] ; ?></td>
                <?php
                $acumulador_fila = (int)0;
                for($annoContrato = $annoInicio; $annoContrato <= $annoFinal; $annoContrato++):
                    ?>
                    <td><?php echo $fila['a' . $annoContrato]; ?></td>
                <?php
                    $acumulador_fila += $fila['a' . $annoContrato];
                    ${'acumuladorC_'.$annoContrato} += $fila['a'.$annoContrato];
                endfor;
                ?>
                <td><?php echo $acumulador_fila ; ?></td>
                <?php
                    $acumuladorTC += $acumulador_fila;
                ?>
			</tr>
			<?php
				endforeach;
			?>
			<tr>
				<td colspan="5">TOTAL DIAS CUMPLIDOS</td>
                <?php
                for($annoContrato = $annoInicio; $annoContrato <= $annoFinal; $annoContrato++):
                ?>
				<td><?php echo  ${'acumuladorC_'.$annoContrato}; ?></td>
                <?php
                endfor;
                ?>
                <td><?php echo $acumuladorTC;?></td>
			</tr>
		</table>
		<?php endif;?>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Monetización del Período:  <?php if(empty($monetizacion)): echo '[0]'; else: echo '[' . count($monetizacion) . ']'; endif; ?></strong</td>
			</tr>
		</table>
		<?php if(empty($monetizacion)):?>
		<div class="alert alert-warning text-center"> No se encuentran datos de Monetización disponibles</div>
		<?php else: ?>
		<table class="table table-striped table-bordered">
			<tr>
				<th>PERIODO DE PAGO</th>
				<th>FECHA DE PAGO</th>
				<th>TRANSACCIÓN</th>
				<th>PAGO NETO</th>
				<th>INTERESES</th>
				<th>PAGO TOTAL</th>
			</tr>
			<?php foreach($monetizacion as $fila):?>
			<tr>
				<td><?php echo $fila['periodo_pago'] ; ?></td>
				<td><?php echo $fila['fecha_pago'] ; ?></td>
				<td><?php echo $fila['monetizacion_transaccion'] ; ?></td>
				<td><?php echo "$".number_format((int)($fila['monetizacion_pago_neto']), 0, '.', '.') ; ?></td>
				<td><?php echo "$".number_format((int)($fila['monetizacion_intereses']), 0, '.', '.') ; ?></td>
				<td><?php echo "$".number_format((int)($fila['monetizacion_pago_total']), 0, '.', '.') ; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php endif; ?>
		<p><br><br></p>
        <?php
        for($annoResumen = $annoInicio; $annoResumen <= $annoFinal; $annoResumen++):
        ?>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Resumen año: <?php echo '[' . $annoResumen . ']';  ?></strong</td>
			</tr>
		</table>
        <?php if(empty(${'resumen_'.$annoResumen})):?>
            <div class="alert alert-warning text-center"> No se encuentran datos de resumen</div>
        <?php else: ?>
        <table class="table table-striped table-bordered">
			<tr>
                <td>DESCRIPCIÓN</td>
                <td>ENE</td>
                <td>FEB</td>
                <td>MAR</td>
                <td>ABR</td>
                <td>MAY</td>
                <td>JUN</td>
                <td>JUL</td>
                <td>AGO</td>
                <td>SEP</td>
                <td>OCT</td>
                <td>NOV</td>
                <td>DIC</td>
                <td>TOTAL</td>
			</tr>
			<tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[0] as $mes => $valor):
                ?>
                <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
			</tr>
			<tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[1] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
			</tr>
			<tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[2] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
			</tr>
			<tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[3] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
			</tr>
			<tr>
				<td colspan="5"><small>(B) Salario Minimo Legal Mensual</small></td>
				<td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']), 0, '.', '.'); ?></td>
				<td colspan="5"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
				<td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30 * (${'resumen_'.$annoResumen}[3]['total'])), 0, '.', '.'); ?></td>
			</tr>
			<tr>
				<td colspan="5"><small>(C) Factor Base de Líquidación = (B)/30</small></td>
				<td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30), 0, '.', '.'); ?></td>
				<td colspan="5"><small>(E) Valor Cancelado por Monetización</small></td>
				<td colspan="2"><?php echo "$". number_format((${'monetizacion_' . $annoResumen}['MONETIZACION']), 0, '.', '.'); ?></td>
			</tr>
			<tr>
				<td colspan="5"></td>
				<td colspan="2"></td>
				<td colspan="5"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
				<td colspan="2"><?php echo "$". number_format((((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30) * (${'resumen_'.$annoResumen}[3]['total'])) - ${'monetizacion_' . $annoResumen}['MONETIZACION']), 0, '.', '.'); ?></td>
			</tr>
		</table>
        <?php
            endif;

		endfor;

        $total = (int)$info['estado_valor_final'];
		?>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr <?php if($total > 0): ?>class="success"<?php else: ?>class="error"<?php endif;?>>
				<td style = "text-align:center;" <?php if($total > 0): ?>class = "text-success"<?php else: ?>class = "text-error"<?php endif;?>><strong>Valor Total Estado de Cuenta: <?php echo "$". number_format($total, 0, '.', '.');  ?></strong</td>
			</tr>
		</table>
	</div>
</div>
<!-- Fin cargue de Cabeceras -->
<!-- Cargue de vista previa -->
<br><br>
<link href="<?php echo base_url(); ?>css/bootstrap.css" media="print" rel="stylesheet">
<div id="vistaPrevia_base" style="display:none;" title="Estado de Cuenta Contratos de Aprendizaje">
    <table class="table table-bordered">
        <tr>
            <td><strong>Nit:</strong><br><span class="muted"><?php echo $info['estado_nit_empresa']; ?></span></td>
            <td colspan = "3"><strong>Razón Social:</strong><br><span class="muted"><?php echo utf8_encode($info['empresa_RSocial']); ?></span></td>
        </tr>
        <tr>
            <td><strong>Número Estado de Cuenta:</strong><br><span class="muted"><?php echo $info['estado_nro_estado'] ; ?></span></td>
            <td><strong>Creado por:</strong><br><span class="muted"><?php echo utf8_encode($info['estado_creado_por']); ?></span></td>
            <td><strong>Fecha Inicio:</strong><br><span class="muted"><?php echo $fechaInicio[0]; ?></span></span></td>
            <td><strong>Fecha Fin:</strong><br><span class="muted"><?php echo $fechaFin[0]; ?></span></td>
        </tr>
    </table>
	<p><br><br></p>
    <?php
    for($annoResumen = $annoInicio; $annoResumen <= $annoFinal; $annoResumen++):
        ?>
        <table class="table table-bordered">
            <tr class="info">
                <td style="text-align:center;"><strong>Resumen año: <?php echo '[' . $annoResumen . ']';  ?></strong</td>
            </tr>
        </table>
    <?php if(empty(${'resumen_'.$annoResumen})):?>
        <div class="alert alert-warning text-center"> No se encuentran datos de resumen</div>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <tr>
                <td>DESCRIPCIÓN</td>
                <td>ENE</td>
                <td>FEB</td>
                <td>MAR</td>
                <td>ABR</td>
                <td>MAY</td>
                <td>JUN</td>
                <td>JUL</td>
                <td>AGO</td>
                <td>SEP</td>
                <td>OCT</td>
                <td>NOV</td>
                <td>DIC</td>
                <td>TOTAL</td>
            </tr>
            <tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[0] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
            </tr>
            <tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[1] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
            </tr>
            <tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[2] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
            </tr>
            <tr>
                <?php
                foreach(${'resumen_'.$annoResumen}[3] as $mes => $valor):
                    ?>
                    <td><?php echo $valor; ?></td>
                <?php
                endforeach;
                ?>
            </tr>
            <tr>
                <td colspan="5"><small>(B) Salario Minimo Legal Mensual</small></td>
                <td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']), 0, '.', '.'); ?></td>
                <td colspan="5"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
                <td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30 * (${'resumen_'.$annoResumen}[3]['total'])), 0, '.', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="5"><small>(C) Factor Base de Líquidación = (B)/30</small></td>
                <td colspan="2"><?php echo "$". number_format((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30), 0, '.', '.'); ?></td>
                <td colspan="5"><small>(E) Valor Cancelado por Monetización</small></td>
                <td colspan="2"><?php echo "$". number_format((${'monetizacion_' . $annoResumen}['MONETIZACION']), 0, '.', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="2"></td>
                <td colspan="5"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
                <td colspan="2"><?php echo "$". number_format((((${'smlv_' . $annoResumen}['SALARIO_MINIMO']/30) * (${'resumen_'.$annoResumen}[3]['total'])) - ${'monetizacion_' . $annoResumen}['MONETIZACION']), 0, '.', '.'); ?></td>
            </tr>
        </table>
    <?php

        endif;

    endfor;

    $total = (int)$info['estado_valor_final'];
    ?>
    <p><br><br></p>
    <table class="table table-bordered">
        <tr <?php if($total > 0): ?>class="success"<?php else: ?>class="error"<?php endif;?>>
            <td style = "text-align:center;" <?php if($total > 0): ?>class = "text-success"<?php else: ?>class = "text-error"<?php endif;?>><strong>Valor Total Estado de Cuenta: <?php echo "$". number_format($total, 0, '.', '.');  ?></strong</td>
        </tr>
    </table>
</div>
<!-- Fin cargue de vista previa -->
<br><br>
<!-- Lista de datos -->
<?php
	$atributos = array('id' => 'estadoCuenta');
	echo form_open('liquidaciones/getFormSgva_exito', $atributos);
	echo form_hidden('codigoAsignacion', $codigoAsignacion);
	echo form_hidden('periodoInicial', $fechaInicio[0]);
	echo form_hidden('periodoFinal', $fechaFin[0]);
	echo form_hidden('nit', $nit);
	echo form_hidden('total', $total);
	echo form_close();
?>
<!-- Fin lista de datos -->
<!-- Listado de controles -->
<?php if(empty($regulaciones)): ?>
	<div class="alert alert-danger text-center"><strong><i class="fa fa-exclamation-triangle"></i> No hay datos suficientes para cobrar un estado de cuenta</strong></div>
	<table width = "100%">
		<tr>
			<td width = "100%" style = "text-align:center;"><a class="btn btn-default" href="<?php echo site_url(); ?>/liquidaciones/"><i class="fa fa-arrow-left"></i> Volver a Liquidaciones</a></td>
		</tr>
	</table>
<?php else: ?>
	<div id="controles" class="controls controls-row" style="display:block;">
		<table width = "100%">
			<tr>
				<td width = "25%" style = "text-align:center;"><a class="btn btn-warning" href="<?php echo site_url(); ?>/liquidaciones/"><i class="fa fa-minus-circle"></i> Cancelar</a></td>
				<td width = "25%" style = "text-align:center;"><a class="btn btn-info"  id="vistaPrevia"><i class="fa fa-eye"></i>  Vista previa</a></td>
				<td width = "25%" style = "text-align:center;"><a class="btn btn-info" onClick="javascript:pdf();"><i class="fa fa-print"></i>  Imprimir</a></td>
				<?php
					if($total > 0):
				?>
				<td width = "25%" style = "text-align:center;"><a class="btn btn-success" href="javascript:enviar_formulario()"><i class="fa fa-usd"></i>  Cobrar Estado de Cuenta</a></td>
				<?php
					endif;
				?>
			</tr>
		</table>
	</div>
<?php endif; ?>
<!-- Fin listado de controles -->
<!-- Función vista previa -->
<script>
  $(function() {
	$('#vistaPrevia').click(function()
	{
		$(function()
		{
    			$( "#vistaPrevia_base" ).dialog({
                    dialogClass: 'dialogo',
                    width: 1100,
      				height: 500,
     			 	modal: true
    			});
  		});
  	});
});
 </script>
<!-- Fin función vista previa -->
<!--Función carga e impresión -->
<script type="text/javascript" language="javascript" charset="utf-8">
function enviar_formulario()
{
   	$("#estadoCuenta").submit();
}

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
	document.getElementById("nombre").value ='estadoCuenta_<?php //echo $fiscalizacion['NRO_EXPEDIENTE']; ?>';
	document.getElementById("html").value = base64_encode(informacion);
	$("#form2").submit();

}

</script>
<!-- Fin función paginador -->
<!--
/* End of file liquidacionsgva_form.php */
-->