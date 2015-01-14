<?php 
/**
* Formulario para la impresión de liquidaciones de crédito en proceso jurídico
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location   application/views/liquidaciones_credito/generar_liquidacion.php
* @last-modified  23/06/2014
* @copyright	
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed');
if (isset($message))
{
    echo $message;
}
/*var_dump($proceso);
echo '<br>';
var_dump($fechaLiquidacion);
echo '<br>';
var_dump($honorarios);
echo '<br>';
var_dump($transporte);*/
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px; background-color: #FFF;}
#cabecera td, th{padding: 5px; text-align: left; vertical-align: middle;}
#controles table{width: 100%; height: auto; margin: 10px; text-align: center;}
</style>
<!-- Fin Estilos personalizados -->
<form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo site_url('/liquidaciones/pdf') ?>">
    <textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
    <input type="hidden" name="tipo" id="tipo" value="3">
    <input type="hidden" name="nombre" id="nombre">
    <input type="hidden" name="titulo" id="titulo" value="Liquidación de Crédito">
</form>
<!-- Cargue de formulario -->
<div class="center-form-xlarge" id="cabecera">
	<h2 class="text-center">Liquidación de Crédito</h2>
	<br><br>
	<table class = "table table-bordered">
		<tr>
			<td rowspan = "2"><img src="<?php echo base_url() . 'img/Logotipo.png' ?>"></td>
			<td rowspan = "2"><span class = "muted">Concepto: <?php echo $proceso[0]['CONCEPTO']; ?></span></td>
			<td><span class = "muted">No: <?php echo $proceso[0]['COD_EXPEDIENTE_JURIDICA']; ?></span></td>
		</tr>
		<tr>
			<td><span class = "muted">Fecha: <?php echo $fechaLiquidacion; ?></span></td>
		</tr>
		<tr>
			<td colspan = "3"><span class = "muted">Nombre o Razón Social: <?php echo $proceso[0]['EJECUTADO']; ?></span></td>
		</tr>
		<tr>
			<td><span class = "muted">NIT: <?php echo $proceso[0]['IDENTIFICACION']; ?></span></td>
			<td></td>
			<td><span class = "muted">Representante Legal: <?php echo $proceso[0]['REPRESENTANTE']; ?></span></td>
		</tr>
		<tr>
			<td><span class = "muted">Dirección: <?php echo $proceso[0]['DIRECCION']; ?></span></td>
			<td><span class = "muted">Ciudad: </span></td>
			<td><span class = "muted">Fax: </span></td>
		</tr>
		<tr>
			<td><span class = "muted">E-mail: <?php echo $proceso[0]['CORREO_ELECTRONICO']; ?></span></td>
			<td><span class = "muted">Telefono 1: </span></td>
			<td><span class = "muted">Telefono 2: </span></td>
		</tr>
	</table>
    <?php
    $total_capital = 0;
    $total_interes = 0;
    $total_deuda = 0;
    foreach($proceso as $liquidacion):
    ?>
        <table class = "table table-bordered table-striped">
            <tr>
                <td><span class = "muted">Liquidación:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', $liquidacion['NUM_LIQUIDACION'], '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Valor Capital Obligación:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_CAPITAL'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Intereses de Mora:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_INTERES'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Subtotal:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_DEUDA'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
        </table>
    <?php
    $total_capital += $liquidacion['SALDO_CAPITAL'];
    $total_interes += $liquidacion['SALDO_INTERES'];
    $total_deuda += $liquidacion['SALDO_DEUDA'];
    endforeach;
    ?>
	<table class="table table-bordered table-striped">
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;"><span class = "muted">TOTAL CAPITAL</span></td>
			<td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_capital, 0, '.', '.'); ?></span></td>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;"><span class = "muted">TOTAL INTERES</span></td>
			<td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_interes, 0, '.', '.'); ?></span></td>
		</tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">SUBTOTAL</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_deuda, 0, '.', '.'); ?></span></td>
        </tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;"><span class = "muted">Honorarios</span></td>
			<td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($honorarios, 0, '.', '.'); ?></span></td>
		</tr>
		<tr>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;">&nbsp;</td>
			<td style = "width:25%;"><span class = "muted">Gastos</span></td>
			<td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($transporte, 0, '.', '.'); ?></span></td>
		</tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">TOTAL A PAGAR</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_deuda + $honorarios + $transporte, 0, '.', '.'); ?></span></td>
        </tr>
	</table>
</div>
<!-- Fin cargue de formulario -->
<!-- Formulario de envio -->
<div id = "data">
	<?php
		$formulario_atributos = array('id' => 'datos', 'name' => 'datos');
		echo form_open('liquidaciones_credito/addAuto', $formulario_atributos);
		$datos_liquidacion = array('nit'  => $proceso[0]['IDENTIFICACION'] , 'cod_coactivo' => $codigoExpediente );
		echo form_hidden($datos_liquidacion);
		echo form_close();
	?>
</div>
<!-- Fin Formulario de envio -->
<!-- Formulario de Regreso -->
<div id = "expediente">
    <?php
    $formulario_atributos_volver = array('id' => 'expediente', 'name' => 'expediente');
    echo form_open('liquidaciones_credito/manage', $formulario_atributos_volver);
    $datos_proceso = array('cod_coactivo_liquidacion' => $codigoExpediente );
    echo form_hidden($datos_proceso);
    echo form_close();
    ?>
</div>
<!-- Fin formulario de regreso -->
<!-- Cargue de Vista Previa -->
<div id = "vistaPrevia_base" class = "vistaPrevia_base" style = "display:none;" title = "Liquidación de Crédito">
    <table border = "1" cellspacing = "2"  cellpadding = "8" width = "100%" class = "table table-bordered">
        <tr>
            <td rowspan = "2">&nbsp;</td>
            <td rowspan = "2"><span class = "muted">Concepto: <?php echo $proceso[0]['CONCEPTO']; ?></span></td>
            <td><span class = "muted">No: <?php echo $proceso[0]['COD_EXPEDIENTE_JURIDICA']; ?></span></td>
        </tr>
        <tr>
            <td><span class = "muted">Fecha: <?php echo $fechaLiquidacion; ?></span></td>
        </tr>
        <tr>
            <td colspan = "3"><span class = "muted">Nombre o Razón Social: <?php echo $proceso[0]['EJECUTADO']; ?></span></td>
        </tr>
        <tr>
            <td><span class = "muted">NIT: <?php echo $proceso[0]['IDENTIFICACION']; ?></span></td>
            <td></td>
            <td><span class = "muted">Representante Legal: <?php echo $proceso[0]['REPRESENTANTE']; ?></span></td>
        </tr>
        <tr>
            <td><span class = "muted">Dirección: <?php echo $proceso[0]['DIRECCION']; ?></span></td>
            <td><span class = "muted">Ciudad: </span></td>
            <td><span class = "muted">Fax: </span></td>
        </tr>
        <tr>
            <td><span class = "muted">E-mail: <?php echo $proceso[0]['CORREO_ELECTRONICO']; ?></span></td>
            <td><span class = "muted">Telefono 1: </span></td>
            <td><span class = "muted">Telefono 2: </span></td>
        </tr>
    </table>
    <?php
    $total_capital = 0;
    $total_interes = 0;
    $total_deuda = 0;
    foreach($proceso as $liquidacion):
        ?>
        <table border = "1" cellspacing = "2"  cellpadding = "8" width = "100%" class = "table table-bordered table-striped">
            <tr>
                <td><span class = "muted">Liquidación:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', $liquidacion['NUM_LIQUIDACION'], '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Valor Capital Obligación:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_CAPITAL'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Intereses de Mora:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_INTERES'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
            <tr>
                <td><span class = "muted">Subtotal:</span></td>
                <td>
                    <?php
                    echo '<span class = "muted">', "$".number_format($liquidacion['SALDO_DEUDA'], 0, '.', '.'), '</span>';
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $total_capital += $liquidacion['SALDO_CAPITAL'];
        $total_interes += $liquidacion['SALDO_INTERES'];
        $total_deuda += $liquidacion['SALDO_DEUDA'];
    endforeach;
    ?>
    <table border = "1" cellspacing = "2"  cellpadding = "8" width = "100%" class="table table-bordered table-striped">
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">TOTAL CAPITAL</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_capital, 0, '.', '.'); ?></span></td>
        </tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">TOTAL INTERES</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_interes, 0, '.', '.'); ?></span></td>
        </tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">SUBTOTAL</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_deuda, 0, '.', '.'); ?></span></td>
        </tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">Honorarios</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($honorarios, 0, '.', '.'); ?></span></td>
        </tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">Gastos</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($transporte, 0, '.', '.'); ?></span></td>
        </tr>
        <tr>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;">&nbsp;</td>
            <td style = "width:25%;"><span class = "muted">TOTAL A PAGAR</span></td>
            <td style = "width:25%;"><span class = "muted"><?php echo "$".number_format($total_deuda + $honorarios + $transporte, 0, '.', '.'); ?></span></td>
        </tr>
    </table>
</div>
<!-- Fin Cargue Vista Previa -->
<!-- Controles -->
<div id="controles">
	<table>
		<tr>
			<td>
                <a class="btn btn-warning" onClick="javascript:document.expediente.submit();"><i class="fa fa-minus-circle"></i> Cancelar</a>
			</td>
            <td>
				<a class="btn btn-info"  id="vistaPrevia"><i class="icon-eye-open icon-white"></i>  Vista previa</a>
			</td>
			<td>
                <a class="btn btn-info" onClick="javascript:pdf();"><i class="fa fa-print"></i>  Imprimir</a>
			</td>
			<td>
				<a class="btn btn-success" onClick="javascript:document.datos.submit();" ><i class="icon-file icon-white"></i>  Generar Auto</a>
			</td>
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
   				width: 1100,
   				height: 500,
   			 	modal: true
   			});
  		});
  	});
});
</script>
<!-- Fin Función Vista Previa -->
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
        document.getElementById("nombre").value ='liquidacionCredito_<?php echo $proceso[0]['COD_EXPEDIENTE_JURIDICA']; ?>';
        document.getElementById("html").value = base64_encode(informacion);
        $("#form2").submit();
    }

</script>
<!-- Fin Función Imprimir -->

<!--
/* End of file consultar_expediente.php */
-->