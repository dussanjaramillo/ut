<?php
/**
 * Formulario para la impresión de liquidaciones FIC
 *
 * @package    Cartera
 * @subpackage Views
 * @author     jdussan
 * @location   application/views/liquidaciones/generar_liquidacion_fic.php
 * @last-modified  17/10/2014
 * @copyright
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// print_r($maestro);
var_dump($resultado);
// var_dump($maestro);
// echo "<pre>";
// var_dump($maestro);
// print_r($FicNormativo);
// var_dump($liquidacion);
// var_dump($detalle);
// echo "</pre>";//die();
// var_dump($observaciones);
// var_dump($documentacion);
// separación de fechas periodo inicial
$datos_fecha_inicial = explode("/", $maestro ['PERIODO_INICIAL']);
$dia_fecha_inicial = $datos_fecha_inicial[0];
$mes_fecha_inicial = $datos_fecha_inicial[1];
$anno_fecha_inicial = $datos_fecha_inicial[2];

// separación de fechas periodo final
$datos_fecha_final = explode("/", $maestro ['PERIODO_FINAL']);
$dia_fecha_final = $datos_fecha_final[0];
$mes_fecha_final = $datos_fecha_final[1];
$anno_fecha_final = $datos_fecha_final[2];
$diferencia_annos = $anno_fecha_final - $anno_fecha_inicial;
if (isset($message))
{
    echo $message;
}
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
<!-- Cargue de formulario -->
<div class="center-form-xlarge" id="cabecera">
    <h2 class="text-center">Liquidación de <?php echo $maestro['NOMBRE_CONCEPTO']; ?></h2>
    <br><br>
    <table class = "table table-bordered table-striped table-condensed">
        <tr>
            <td colspan="2"><span class = "text-center" style ="display:block;"><img src="<?php echo base_url() . 'img/Logotipo.png' ?>"></span></td>
            <td colspan="2">
                <span  style="text-align:center !important; display:block;">
                    SERVICIO NACIONAL DE APRENDIZAJE SENA<br>
                    Nit o C.C: <?php echo $datos_cabecera['NIT_EMPRESA']; ?><br>
                    FORMATO LIQUIDACIÓN FIC<br>
                </span>
                </span></td>
        </tr>
        <tr>
            <td><span>N° Liquidación:</span></td>
			<td><span><?php echo $datos_cabecera['NRO_EXPEDIENTE']; ?></span></td>
            <td><span>Fecha:</span></td>
			<td><span><?php echo $liquidacion['fechaLiquidacion']; ?></span></td>
        </tr>
        <tr>
            <td><span>Nombre o Razón Social:</span></td>
			<td colspan="3"><?php echo $datos_cabecera['RAZON_SOCIAL']; ?></td>
        </tr>
        <tr>
            <td><span>Nit o C.C:</span></td>
			<td><span><?php echo $datos_cabecera['NIT_EMPRESA']; ?></span></td>
            <td><span>Actividad Económica:</span></td>
			<td><span><?php echo $datos_cabecera['DESCRIPCION']; ?></span></td>
        </tr>
        <tr>
            <td><span>Código:</span></td>
			<td><span><?php echo $datos_cabecera['CIIU']; ?></span></td>
            <td><span>Representante Legal:</span></td>
			<td><span><?php echo $datos_cabecera['REPRESENTANTE_LEGAL']; ?></span></td>
        </tr>
        <tr>
            <td><span>Dirección:</span></td>
			<td><span><?php echo $datos_cabecera['DIRECCION']; ?></span></td>
            <td><span>Ciudad:<br><?php echo $datos_cabecera['NOMBREMUNICIPIO']; ?></span></td>
            <td><span>Fax:<br><?php echo $datos_cabecera['FAX']; ?></span></td>
        </tr>
        <tr>
            <td><span>E-mail</span></td>
			<td><span><?php echo $datos_cabecera['EMAILAUTORIZADO']; ?></span></td>
            <td><span>Teléfono 1:<br><?php echo $datos_cabecera['TELEFONO_FIJO']; ?></span></td>
            <td><span>Teléfono 2:<br><?php echo $datos_cabecera['TELEFONO_FIJO']; ?></span></td>
        </tr>
        <tr>
            <td><span>Escritua de Constitución N°:</span></td>
			<td><span><?php echo $datos_cabecera['NRO_ESCRITURAPUBLICA']; ?></span></td>
            <td><span>Notaría:</span></td>
			<td><span><?php echo $datos_cabecera['NOTARIA']; ?></span></td>
        </tr>
    </table>
    <table  class = "table table-bordered table-striped table-condensed">
        <tr class="text-nowrap">
            <td><span>Base de Liquidación</span></td>
            <?php
            $anno = $anno_fecha_final;
            $anno_deuda = $anno_fecha_final;
            $anno_intereses = $anno_fecha_final;
            $anno_subtotal = $anno_fecha_final;
            $valor_inversion = (int)str_replace(".","",$liquidacion['valorInversion']) / $cantidad;
            $gastosFinanciacion = (int)str_replace(".","",$liquidacion['gastosFinanciacion']) / $cantidad;
            $impuestosLegales = (int)str_replace(".","",$liquidacion['impuestosLegales']) / $cantidad;
            $valorLote = (int)str_replace(".","",$liquidacion['valorLote']) / $cantidad;
            $indenmizacion = (int)str_replace(".","",$liquidacion['indenmizacion']) / $cantidad;
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span> AÑO ', $i, '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Mes Inicio y Fin Fiscalización</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):

                echo '<td><span>' . $resultado['periodo_' . $i] . '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Valor de la Inversión</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($valor_inversion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Valor del Lote</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($valorLote, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Gastos de Financiación</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($gastosFinanciacion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Indemnización a Terceros</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($indenmizacion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Impuestos-Legales</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($impuestosLegales, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Total Valor Contrato Todo Costo</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorContratoCosto_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
                $Total_Final_Costo;
            endfor;
            ?>
        </tr>
        <tr><td><span style="text-align:right;"><div  style="text-align:right;"><b>Valor FIC 0.25%</b></div></span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorFicCosto_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span>Total Valor Contratos Mano de Obra</span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorContratoObra_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span><div  style="text-align:right;"><b>Valor FIC 1%</b></div></span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorFicObra_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span>Total Número de Trabajadores Utilizados</span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">' . number_format($FicNormativo['empleados']['empleados_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span>Factor por cada Trabajador SMLMV/40</span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format((($liquidacion[$i]) / 40), 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span>Valor FIC N° de Trabajadores</span></td>
<?php
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format($FicNormativo['valor_anno']['valor_total_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span class="text-right" style="display:block;"><b>VALOR TOTAL FIC</b></span></td>
<?php
$total_fic_normativo = 0;
for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
    echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['TotalFic_' . $i], 0, '.', '.'), '</span></td>';
    $anno--;
endfor;
?>
        </tr>
        <tr><td><span>MENOS: VALOR PAGADO</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                //$valor_pagado_.$i=0;
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['Aportes_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>TOTAL NETO A PAGAR</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['TotalFic_' . $i] - $resultado['Aportes_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>INTERESES DE MORA</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['Intereses_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span><div  style="text-align:right;"><b>DEUDA VALOR ACTUALIZADO</b></div></span></td>
            <?php
            $valor_total_fic = 0;
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                $total_neto = $resultado['TotalFic_' . $i] - $resultado['Aportes_' . $i];
                $deuda_actualizada = $total_neto + $resultado['Intereses_' . $i];
                $valor_total_fic+=$deuda_actualizada;
                echo '<td><span class="text-right" style="display:block;">$' . number_format($deuda_actualizada, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        </tr>
    </table>
    <table class = "table table-bordered table-striped table-condensed" >
        <tr><td colspan="1"><span><div  style="text-align:right;"><b>VALOR TOTAL FIC</b></div></span></td>
            <td colspan="2"><span class="text-right" style="display:block;">$<?php echo number_format($valor_total_fic, 0, '.', '.'); ?></span></td>
        <tr><td colspan="3"><span class="muted"><div  style="text-align:center;"><b>APLICATIVO WEB WWW.SENA.EDU.CO/ATENCIÓN AL CIUDADANO link <a href = "https://www.e-collect.com/p_express/secure/loginUser.aspx" target = "_blank">PAGOS EN LINEA</a></b></div></span></td>
        </tr>
        <tr>
            <td colspan="4">
                <span>Observaciones</span><br>
                <div style="width:auto;; height:30px; "></div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span>Documentación aportada como base</span><br>
                <div style="width:auto;; height:30px; "></div>
            </td>
        </tr>
    </table>
    <table  class="table table-bordered">
        <table  class="table table-bordered table-striped table-condensed">
            <tr>
                <td style = "width:33%;"><span>Nombre y firma del funcionario autorizado por el SENA</span></td>
                <td style = "width:34%;"><span>Nombre, Firma y/o Sello del funcionario autorizado por la empresa</span></td>
                <td style = "width:33%;"><span>V°.B°. Coordinador para Registro en Cartera</span></td>
            </tr>
            <tr  style = "height:200px;">
                <td style = "width:33%;"><span></span></td>
                <td style = "width:34%;"><span></span></td>
                <td style = "width:33%;"><span></span></td>
            </tr>
            <tr>
                <td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($dato_usuario, 'UTF-8'); ?></span></td>
                <td style = "width:34%;"><span>Cargo: <?php //echo mb_strtoupper($empresa['REPRESENTANTE_LEGAL'], 'UTF-8'); ?></span></td>
                <td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($info_usuario['NOMBRE_COORDINADOR_RELACIONES'], 'UTF-8'); ?></span></td>
            </tr>
        </table>
    </table>
</div>
<!-- Fin cargue de formulario -->
<!-- Cargue de Vista Previa -->
<div id = "vistaPrevia_base" class = "vistaPrevia_base" style="display:none"  title = "Liquidación de <?php echo $maestro['NOMBRE_CONCEPTO']; ?>">
    <!--<h2 style="text-align:center">Liquidación de <?php //echo $maestro['NOMBRE_CONCEPTO']; ?></h2>-->
    <table class = "table table-bordered table-condensed table-striped">
        <tr>
            <td colspan="4" >
                <span  style="text-align:center !important; display:block;">
                    SERVICIO NACIONAL DE APRENDIZAJE SENA<br>
                    Nit o C.C: <?php echo $datos_cabecera['NIT_EMPRESA']; ?><br>
                    FORMATO LIQUIDACIÓN FIC<br>
                </span>
                </span></td>
        </tr>
        <tr>
            <td><span>N° Liquidación:</span></td>
			<td><span><?php echo $datos_cabecera['NRO_EXPEDIENTE']; ?></span></td>
            <td><span>Fecha:</span></td>
			<td><span><?php echo $liquidacion['fechaLiquidacion']; ?></span></td>
        </tr>
        <tr>
            <td colspan="1"><span>Nombre o Razón Social:</span></td>
			<td colspan="3"><?php echo $datos_cabecera['RAZON_SOCIAL']; ?></td>
        </tr>
        <tr>
            <td><span>Nit o C.C:</span></td>
			<td><span><?php echo $datos_cabecera['NIT_EMPRESA']; ?></span></td>
            <td><span>Actividad Económica:</span></td>
			<td><span><?php echo $datos_cabecera['DESCRIPCION']; ?></span></td>
        </tr>
        <tr>
            <td><span>Código:</span></td>
			<td><span><?php echo $datos_cabecera['CIIU']; ?></span></td>
            <td><span>Representante Legal:</span></td>
			<td><span><?php echo @$datos_cabecera['REPRESENTANTE_LEGAL']; ?></span></td>
        </tr>
        <tr>
            <td><span>Dirección:</span></td>
			<td><span><?php echo $datos_cabecera['DIRECCION']; ?></span></td>
            <td><span>Ciudad:<?php echo $datos_cabecera['NOMBREMUNICIPIO']; ?></span></td>
            <td><span>Fax:<?php echo $datos_cabecera['FAX']; ?></span></td>
        </tr>
        <tr>
            <td><span>E-mail</span></td>
			<td><span><?php echo $datos_cabecera['EMAILAUTORIZADO']; ?></span></td>
            <td><span>Teléfono 1: </span><?php echo $datos_cabecera['TELEFONO_FIJO']; ?></td>
            <td><span>Teléfono 2: </span><?php echo $datos_cabecera['TELEFONO_FIJO']; ?></td>
        </tr>
        <tr>
            <td><span>Escritua de Constitución N°:</span></td>
			<td><span><?php echo $datos_cabecera['NRO_ESCRITURAPUBLICA']; ?></span></td>
            <td><span>Notaría:</span></td>
			<td><span><?php echo $datos_cabecera['NOTARIA']; ?></span></td>
        </tr>
    </table>
    <table class = "table table-bordered table-condensed table-striped">
        <tr><td><span>Base de Liquidación</span></td>
            <?php
            $anno = $anno_fecha_final;
            $anno_deuda = $anno_fecha_final;
            $anno_intereses = $anno_fecha_final;
            $anno_subtotal = $anno_fecha_final;
            $valor_inversion = $liquidacion['valorInversion'] / $cantidad;
            $gastosFinanciacion = $liquidacion['gastosFinanciacion'] / $cantidad;
            $impuestosLegales = $liquidacion['impuestosLegales'] / $cantidad;
            $valorLote = $liquidacion['valorLote'] / $cantidad;
            $indenmizacion = $liquidacion['indenmizacion'] / $cantidad;

            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span> AÑO ', $i, '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Mes Inicio y Fin Fiscalización</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span>ENE-DIC</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Valor de la Inversión</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span  class="text-right" style="display:block;">$' . number_format($valor_inversion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Valor del Lote</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span  class="text-right" style="display:block;">$' . number_format($valorLote, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Gastos de Financiación</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($gastosFinanciacion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Indemnización a Terceros</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($indenmizacion, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Menos:Impuestos-Legales</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($impuestosLegales, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>

        <tr><td><span>Total Valor Contrato Todo Costo</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorContratoCosto_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
                $Total_Final_Costo;
            endfor;
            ?>
        </tr>
        <tr><td><span><div  style="text-align:right;"><b>Valor FIC 0.25%</b></div></span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span  class="text-right" style="display:block;">$' . number_format($resultado['ValorFicCosto_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Total Valor Contratos Mano de Obra</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorContratoObra_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span><div  style="text-align:right;"><b>Valor FIC 1%</b></div></span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['ValorFicObra_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        <br><br>
        </tr>
        <tr><td><span>Total Número de Trabajadores Utilizados</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">' . number_format($FicNormativo['empleados']['empleados_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>Factor por cada Trabajador SMLMV/40</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format((($liquidacion[$i]) / 40), 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span><div>Valor FIC N° de Trabajadores</div></span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($FicNormativo['valor_anno']['valor_total_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span><b>VALOR TOTAL FIC</b></span></td>
            <?php
            $total_fic_normativo = 0;
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['TotalFic_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>MENOS: VALORPAGADO</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['Aportes_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>TOTAL NETO A PAGAR</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):

                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['TotalFic_' . $i] - $resultado['Aportes_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span>INTERESES DE MORA</span></td>
            <?php
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                echo '<td><span class="text-right" style="display:block;">$' . number_format($resultado['Intereses_' . $i], 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
        <tr><td><span><div  style="text-align:right;"><b>DEUDA VALOR ACTUALIZADO</b></div></span></td>
            <?php
            $valor_total_fic = 0;
            for ($i = $anno_fecha_inicial; $i <= $anno_fecha_final; $i++):
                $total_neto = $resultado['TotalFic_' . $i] - $resultado['Aportes_' . $i];
                $deuda_actualizada = $total_neto + $resultado['Intereses_' . $i];
                $valor_total_fic+=$deuda_actualizada;
                echo '<td><span class="text-right" style="display:block;">$' . number_format($deuda_actualizada, 0, '.', '.'), '</span></td>';
                $anno--;
            endfor;
            ?>
        </tr>
    </table>
    <table class = "table table-bordered table-condensed table-striped">
        <tr>
			<td><span><div  style="text-align:right;"><b>VALOR TOTAL FIC</b></div></span></td>
            <td colspan="3"><span class="text-right" style="display:block;">$<?php echo number_format($valor_total_fic, 0, '.', '.'); ?></span></td>
        <tr>
			<td colspan="4"><span class="muted"><div  style="text-align:center;"><b>APLICATIVO WEB WWW.SENA.EDU.CO/ATENCIÓN AL CIUDADANO link <a href = "https://www.e-collect.com/p_express/secure/loginUser.aspx" target = "_blank">PAGOS EN LINEA</a></b></div></span></td>
        </tr>
        <tr>
            <td colspan="4">
                <span>Observaciones</span><br>
                <div style="width:auto;; height:30px; "></div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span>Documentación aportada como base</span><br>
                <div style="width:auto;; height:30px; "></div>
            </td>
        </tr>
        <table  class="table table-bordered">
            <table  class="table table-bordered table-condensed table-striped">
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
                    <td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($dato_usuario, 'UTF-8'); ?></span></td>
                    <td style = "width:34%;"><span>Cargo: <?php //echo mb_strtoupper($empresa['REPRESENTANTE_LEGAL'], 'UTF-8'); ?></span></td>
                    <td style = "width:33%;"><span>Nombre: <?php echo mb_strtoupper($info_usuario['NOMBRE_COORDINADOR_RELACIONES'], 'UTF-8'); ?></span></td>
                </tr>
            </table>
        </table>
    </div>
    <!-- Fin Cargue Vista Previa -->
    <!-- Controles -->
            <form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo base_url('index.php/liquidaciones/pdf') ?>">
                <textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
                <input type="hidden" name="tipo" id="tipo" value="3">
                <input type="hidden" name="nombre" id="nombre">
                <input type="hidden" name="titulo" id="titulo" value="Comprobante">

            </form>
            <div id="controles">
                <table>
                    <tr>
                        <td><a class="btn btn-default" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $datos_cabecera['COD_FISCALIZACION']?>"><i class="fa fa-arrow-left"></i>Volver</a></td>
                        <td><a class="btn btn-info"  id="vistaPrevia"><i class="fa fa-eye"></i>  Vista Previa</a></td>
                        <td><a class="btn btn-info" onClick="pdf()"><i class="fa fa-print"></i>  Imprimir</a></td>
                        <td><a class="btn btn-success" href="comprobanteAlterno/<?php echo $datos_cabecera['NRO_EXPEDIENTE']; ?>"><i class="fa fa-bank"></i>  Comprobante</a></td>
                        <td><a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones/legalizarLiquidacion/"><i class="fa fa-suitcase"></i>  Legalizar</a></td>
                        <td><a class="btn btn-success" href="<?php echo site_url(); ?>/liquidaciones/getLiquidacion/"><i class="fa fa-envelope-o"></i>  Carta Presentación</a>  </td>
                        <td>
                            <!--<form id="form" name="form"  method="post" action="<?php echo base_url('index.php/liquidaciones/administrar') ?>">
                                <input type="hidden" name="codigoFiscalizacion" id="codigoFiscalizacion" value="<?php echo $maestro['COD_FISCALIZACION'] ?>">
                                <input type="submit" name="cancelar" id="cancelar" class="btn btn-warning"value=" Cancelar">
                            </form>-->
                        </td>
                    </tr>
                </table>
            </div>
            <!-- Fin Controles -->
<!-- Cargue detalle -->
<?php
if(isset($debuger)):
?>
<div id = "detalle" style ="display:none;">
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
                <td><?php echo $linea['tasa_EA']; ?></td>
                <td><?php echo $linea['tasa_mensual']; ?></td>
                <td><?php echo $linea['tasa_diaria']; ?></td>
                <td><?php echo "$".number_format($linea['intereses'], 0, '.', '.'); ?></td>
            </tr>
        <?php
        endforeach;
        ?>
    </table>
</div>
<table width = "100%">
    <tr class = "text-center"><td><a class="btn btn-info"  id="vistaDetalle"><i class="fa fa-eye"></i>  Ver Detalle</a></td></tr>
</table>
<?php
endif;
?>
<!-- Fin Cargue detalle -->
            <!-- Función Vista Previa -->
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
                    document.getElementById("nombre").value = 'Liquidacion';
                    document.getElementById("html").value = base64_encode(informacion);
                    $("#form2").submit();
                }
                $(function() {
                    $('#vistaPrevia').click(function()
                    {
                        $(function()
                        {
                            $("#vistaPrevia_base").dialog({
                                dialogClass: 'dialogo',
                                width: 1100,
                                height: 500,
                                modal: true
                            });
                        });
                    });
                    $('#vistaDetalle').click(function()
                    {
                        $(function()
                        {
                            $("#detalle").dialog({
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
/* End of file generar_liquidacion_fic.php */
-->