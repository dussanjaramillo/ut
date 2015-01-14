<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<?php 
if (isset($message)){
    echo $message;
   }
?>
<!-- Cargue de cabeceras -->
<div class="center-form-large" id="cabecera">
	<h2 class="text-center">Estado de Cuenta Sistema de Gestión Virtual de Aprendices</h2>
	<br><br>
	<div class="controls controls-row">
		<span class="span4"><strong>Nit:</strong><br><span class="muted"><?php echo '820004342'; ?></span></span>
		<span class="span4"><strong>Razón Social:</strong><br><span class="muted"><?php echo 'GIMNASIO CAMPESTRE DEL NORTE'; ?></span></span>
		<p><br><br></p>
		<span class="span2"><strong>Número Estado de Cuenta:</strong><br><span class="muted"><?php echo '11-149-5393'; ?></span></span>
		<span class="span2"><strong>Creado por:</strong><br><span class="muted"><?php echo 'GIMNASIO CAMPESTRE DEL NORTE'; ?></span></span>
		<span class="span2"><strong>Fecha Inicio:</strong><br><span class="muted"><?php echo '21/04/2012'; ?></span></span>
		<span class="span2"><strong>Fecha Fin:</strong><br><span class="muted"><?php echo '21/04/2013'; ?></span></span>
		<p><br><br></p>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Regulaciones: <?php echo '[3]';  ?></strong</td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<th>RESOLUCIÓN</th>
				<th>FECHA RESOLUCIÓN</th>
				<th>TRABAJADORES</th>
				<th>CUOTA</th>
				<th>EJECUTORIA</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
				<th>2012</th>
				<th>2013</th>
			</tr>
			<tr>
				<td>3076</td>
				<td>27/07/2011</td>
				<td>Trab[478] Horas[22944]</td>
				<td>24</td>
				<td>12/08/2011</td>
				<td>01/01/2012</td>
				<td>19/04/2012</td>
				<td>109</td>
				<td>0</td>
			</tr>
			<tr>
				<td>0938</td>
				<td>27/03/2012</td>
				<td>Trab[379] Horas[18232]</td>
				<td>19</td>
				<td>20/04/2012</td>
				<td>20/05/2012</td>
				<td>16/09/2012</td>
				<td>117</td>
				<td>0</td>
			</tr>
			<tr>
				<td>3247</td>
				<td>10/08/2012</td>
				<td>Trab[560] Horas[26880]</td>
				<td>28</td>
				<td>17/09/2012</td>
				<td>17/10/2012</td>
				<td>30/11/2013</td>
				<td>74</td>
				<td>330</td>
			</tr>
			<tr>
				<td colspan="7">TOTAL DÍAS ASIGNADOS</td>
				<!--<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>-->
				<td>300</td>
				<td>330</td>
			</tr>
			<tr>
				<td colspan="7">TOTAL DÍAS POR CUOTA DE APRENDIZ</td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td>6911</td>
				<td>9240</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Contrato: <?php echo '[112]';  ?></strong</td>
			</tr>
		</table>
		<span class="span2"><small>[1] = Aprendiz SENA</small></span>
		<span class="span2"><small>[2] = Técnico - Tecnólogo Educación Superior</small></span>
		<span class="span2"><small>[3] = Aprendiz de instituciones de formación para el trabajo</small></span>
		<span class="span2"><small>[4] = Aprendiz de la media Técnica (Colegio)</small></span>
		<span class="span2"><small>[5] = Aprendiz de universitario profesional</small></span>
		<span class="span2"><small>[6] = Entidades de Formación con certificación de calidad</small></span>
		<span class="span2"><small>[7] = Emoresas Formadoras</small></span>
		<span class="span2"><small>[8] = Aprendices con autorización de Colciencias</small></span>
		<table class="table table-striped table-bordered">
			<tr>
				<th>APRENDIZ</th>
				<th>DOCUMENTO</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
				<th>ACUERDO 11</th>
				<th>TOTAL DIAS</th>
				<th>2012</th>
				<th>2013</th>
			</tr>
			<tr>
				<td>[1] CLEIDYS JADITH TORDECILLA CONEO</td>
				<td>1047395415</td>
				<td>20/06/2011</td>
				<td>06/12/2011</td>
				<td>05/01/2011</td>
				<td>5</td>
				<td>5</td>
				<td>0</td>
			</tr>
			<tr>
				<td>[1] SIXTA ELVIRA GOMEZ SANCHEZ</td>
				<td>22801406</td>
				<td>13/06/2011</td>
				<td>12/12/2011</td>
				<td>11/01/2012</td>
				<td>11</td>
				<td>11</td>
				<td>0</td>
			</tr>
			<tr>
				<td>[5] ANA GABRIEL DIAZ MARIN</td>
				<td>1020430591</td>
				<td>05/07/2011</td>
				<td>04/12/2012</td>
				<td>03/01/2013</td>
				<td>363</td>
				<td>360</td>
				<td>3</td>
			</tr>
			<tr>
				<td>[5] IVAN DARIO VENGOECHEA NIETO</td>
				<td>1020729663</td>
				<td>25/07/2012</td>
				<td>24/01/2013</td>
				<td>23/02/2013</td>
				<td>209</td>
				<td>156</td>
				<td>53</td>
			</tr>
			<tr>
				<td>[5] JUAN DAVID MARIN MAZUERA</td>
				<td>1136879378</td>
				<td>25/07/2012</td>
				<td>24/01/2013</td>
				<td>23/02/2013</td>
				<td>209</td>
				<td>156</td>
				<td>53</td>
			</tr>
			<tr>
				<td>[5] KARENN OMAIRA PARRAGA OTALORA</td>
				<td>1017188996</td>
				<td>16/07/2012</td>
				<td>15/07/2013</td>
				<td>14/08/2013</td>
				<td>389</td>
				<td>165</td>
				<td>224</td>
			</tr>
			<tr>
				<td>[5] ROBERT ALEXANDER URQUIJO CORREDOR</td>
				<td>1030626795</td>
				<td>12/02/2013</td>
				<td>11/08/2013</td>
				<td>10/09/2013</td>
				<td>209</td>
				<td>0</td>
				<td>209</td>
			</tr>
			<tr>
				<td>[5] ROJAS CALDERON ROLAND FERNELL</td>
				<td>1015410438</td>
				<td>15/08/2013</td>
				<td>14/11/2013</td>
				<td>14/12/2013</td>
				<td>106</td>
				<td>0</td>
				<td>106</td>
			</tr>
			<tr>
				<td>[5] CAROLINA SOTO LONDOÑO</td>
				<td>1035862442</td>
				<td>15/07/2013</td>
				<td>14/07/2014</td>
				<td>13/08/2014</td>
				<td>136</td>
				<td>0</td>
				<td>136</td>
			</tr>
			<tr>
				<td>[6] EDWIN YESID BERNAL MORENO</td>
				<td>1019054858</td>
				<td>02/07/2013</td>
				<td>08/09/2013</td>
				<td>08/10/2013</td>
				<td>97</td>
				<td>0</td>
				<td>97</td>
			</tr>
			<tr>
				<td>[6] ALEJANDRA GOMEZ AGUILAR</td>
				<td>1050951465</td>
				<td>04/09/2013</td>
				<td>03/03/2014</td>
				<td>02/04/2014</td>
				<td>87</td>
				<td>0</td>
				<td>87</td>
			</tr>
			<tr>
				<td>[6] NAYIBE ALEJANDRA MARTINEZ HIGUERA</td>
				<td>1023935505</td>
				<td>05/08/2013</td>
				<td>04/08/2014</td>
				<td>03/09/2014</td>
				<td>116</td>
				<td>0</td>
				<td>116</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="5">TOTAL DIAS CUMPLIDOS</td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td>18836</td>
				<td>7726</td>
				<td>11110</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Monetización del Período: <?php echo '[10]';  ?></strong</td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<th>PERIODO DE PAGO</th>
				<th>FECHA DE PAGO</th>
				<th>TRANSACCIÓN</th>
				<th>PAGO NETO</th>
				<th>INTERESES</th>
				<th>PAGO TOTAL</th>
			</tr>
			<tr>
				<td>01/01/2012</td>
				<td>06/02/2012</td>
				<td>16691061</td>
				<td>$ 3.305.750.00</td>
				<td>$ 0.00</td>
				<td>$ 3.305.750.00</td>
			</tr>
			<tr>
				<td>01/02/2012</td>
				<td>13/03/2012</td>
				<td>16770872</td>
				<td>$ 3.966.900.00</td>
				<td>$ 0.00</td>
				<td>$ 3.966.900.00</td>
			</tr>
			<tr>
				<td>01/03/2012</td>
				<td>02/04/2012</td>
				<td>16810662</td>
				<td>$ 3.966.900.00</td>
				<td>$ 0.00</td>
				<td>$ 3.966.900.00</td>
			</tr>
			<tr>
				<td>01/03/2012</td>
				<td>04/05/2012</td>
				<td>16879091</td>
				<td>$ 566.700.00</td>
				<td>$ 0.00</td>
				<td>$ 566.700.00</td>
			</tr>
			<tr>
				<td>01/09/2012</td>
				<td>04/10/2012</td>
				<td>17194816</td>
				<td>$ 3.400.200.00</td>
				<td>$ 0.00</td>
				<td>$ 3.400.200.00</td>
			</tr>
			<tr>
				<td>01/11/2012</td>
				<td>06/12/2012</td>
				<td>17344702</td>
				<td>$ 2.266.800.00</td>
				<td>$ 0.00</td>
				<td>$ 2.266.800.00</td>
			</tr>
			<tr>
				<td>01/01/2013</td>
				<td>06/02/2013</td>
				<td>17470049</td>
				<td>$ 2.593.800.00</td>
				<td>$ 0.00</td>
				<td>$ 2.593.800.00</td>
			</tr>
			<tr>
				<td>01/02/2013</td>
				<td>06/03/2013</td>
				<td>17536537</td>
				<td>$ 1.179.000.00</td>
				<td>$ 0.00</td>
				<td>$ 1.179.000.00</td>
			</tr>
			<tr>
				<td>01/03/2013</td>
				<td>04/04/2013</td>
				<td>17594734</td>
				<td>$ 589.500.00</td>
				<td>$ 0.00</td>
				<td>$ 589.500.00</td>
			</tr>
			<tr>
				<td>01/11/2013</td>
				<td>03/12/2013</td>
				<td>18302567</td>
				<td>$ 1.768.500.00</td>
				<td>$ 0.00</td>
				<td>$ 1.768.500.00</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Resumen año: <?php echo '[2012]';  ?></strong</td>
			</tr>
		</table>
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
				<td>Días Asignados</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>19</td>
				<td>11</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>16</td>
				<td>14</td>
				<td>30</td>
				<td>30</td>
				<td>300</td>
			</tr>
			<tr>
				<td>Días Asignados * Nro. Cuota</td>
				<td>720</td>
				<td>720</td>
				<td>720</td>
				<td>456</td>
				<td>209</td>
				<td>570</td>
				<td>570</td>
				<td>570</td>
				<td>304</td>
				<td>392</td>
				<td>840</td>
				<td>840</td>
				<td>6911</td>
			</tr>
			<tr>
				<td>Días Cumplido</td>
				<td>720</td>
				<td>499</td>
				<td>483</td>
				<td>456</td>
				<td>209</td>
				<td>570</td>
				<td>570</td>
				<td>570</td>
				<td>304</td>
				<td>392</td>
				<td>757</td>
				<td>764</td>
				<td>6294</td>
			</tr>
			<tr>
				<td>Días Incumplidos</td>
				<td>0</td>
				<td>221</td>
				<td>237</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>83</td>
				<td>76</td>
				<td>617</td>
			</tr>
			<tr>
				<td><small>(B) Salario Minimo Legal Mensual</small></td>
				<td>$ 566.700.00</td>
				<td colspan="10"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 11.655.130.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td><small>(C) Factor Base de Líquidación = (B)/30</small></td>
				<td>$ 18.890.00</td>
				<td colspan="10"><small>(E) Valor Cancelado por Monetización</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 17.473.250.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="10"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ -5.818.120.00</td>
				<!--<td></td>-->
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Resumen año: <?php echo '[2013]';  ?></strong</td>
			</tr>
		</table>
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
				<td>Días Asignados</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>0</td>
				<td>330</td>
			</tr>
			<tr>
				<td>Días Asignados * Nro. Cuota</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>0</td>
				<td>9240</td>
			</tr>
			<tr>
				<td>Días Cumplido</td>
				<td>753</td>
				<td>838</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>0</td>
				<td>9151</td>
			</tr>
			<tr>
				<td>Días Incumplidos</td>
				<td>87</td>
				<td>2</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>89</td>
			</tr>
			<tr>
				<td><small>(B) Salario Minimo Legal Mensual</small></td>
				<td>$ 589.500.00</td>
				<td colspan="10"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 1.748.500.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td><small>(C) Factor Base de Líquidación = (B)/30</small></td>
				<td>$ 19.650.00</td>
				<td colspan="10"><small>(E) Valor Cancelado por Monetización</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 6.130.800.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="10"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ -4.381.900.00</td>
				<!--<td></td>-->
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Valor Total Estado de Cuenta: <?php echo '$ -10.200.070.00';  ?></strong</td>
			</tr>
		</table>
	</div>
</div>
<!-- Fin cargue de Cabeceras -->
<br><br>

<div id="vistaPrevia_base" style="display:none;" title="Estado de Cuenta Contratos de Aprendizaje">
	<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Regulaciones: <?php echo '[3]';  ?></strong</td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<th>RESOLUCIÓN</th>
				<th>FECHA RESOLUCIÓN</th>
				<th>TRABAJADORES</th>
				<th>CUOTA</th>
				<th>EJECUTORIA</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
				<th>2012</th>
				<th>2013</th>
			</tr>
			<tr>
				<td>3076</td>
				<td>27/07/2011</td>
				<td>Trab[478] Horas[22944]</td>
				<td>24</td>
				<td>12/08/2011</td>
				<td>01/01/2012</td>
				<td>19/04/2012</td>
				<td>109</td>
				<td>0</td>
			</tr>
			<tr>
				<td>0938</td>
				<td>27/03/2012</td>
				<td>Trab[379] Horas[18232]</td>
				<td>19</td>
				<td>20/04/2012</td>
				<td>20/05/2012</td>
				<td>16/09/2012</td>
				<td>117</td>
				<td>0</td>
			</tr>
			<tr>
				<td>3247</td>
				<td>10/08/2012</td>
				<td>Trab[560] Horas[26880]</td>
				<td>28</td>
				<td>17/09/2012</td>
				<td>17/10/2012</td>
				<td>30/11/2013</td>
				<td>74</td>
				<td>330</td>
			</tr>
			<tr>
				<td colspan="7">TOTAL DÍAS ASIGNADOS</td>
				<!--<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>-->
				<td>300</td>
				<td>330</td>
			</tr>
			<tr>
				<td colspan="7">TOTAL DÍAS POR CUOTA DE APRENDIZ</td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td>6911</td>
				<td>9240</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Contrato: <?php echo '[112]';  ?></strong</td>
			</tr>
		</table>
		<span class="span2"><small>[1] = Aprendiz SENA</small></span>
		<span class="span2"><small>[2] = Técnico - Tecnólogo Educación Superior</small></span>
		<span class="span2"><small>[3] = Aprendiz de instituciones de formación para el trabajo</small></span>
		<span class="span2"><small>[4] = Aprendiz de la media Técnica (Colegio)</small></span>
		<span class="span2"><small>[5] = Aprendiz de universitario profesional</small></span>
		<span class="span2"><small>[6] = Entidades de Formación con certificación de calidad</small></span>
		<span class="span2"><small>[7] = Emoresas Formadoras</small></span>
		<span class="span2"><small>[8] = Aprendices con autorización de Colciencias</small></span>
		<table class="table table-striped table-bordered">
			<tr>
				<th>APRENDIZ</th>
				<th>DOCUMENTO</th>
				<th>FECHA INICIAL</th>
				<th>FECHA FINAL</th>
				<th>ACUERDO 11</th>
				<th>TOTAL DIAS</th>
				<th>2012</th>
				<th>2013</th>
			</tr>
			<tr>
				<td>[1] CLEIDYS JADITH TORDECILLA CONEO</td>
				<td>1047395415</td>
				<td>20/06/2011</td>
				<td>06/12/2011</td>
				<td>05/01/2011</td>
				<td>5</td>
				<td>5</td>
				<td>0</td>
			</tr>
			<tr>
				<td>[1] SIXTA ELVIRA GOMEZ SANCHEZ</td>
				<td>22801406</td>
				<td>13/06/2011</td>
				<td>12/12/2011</td>
				<td>11/01/2012</td>
				<td>11</td>
				<td>11</td>
				<td>0</td>
			</tr>
			<tr>
				<td>[5] ANA GABRIEL DIAZ MARIN</td>
				<td>1020430591</td>
				<td>05/07/2011</td>
				<td>04/12/2012</td>
				<td>03/01/2013</td>
				<td>363</td>
				<td>360</td>
				<td>3</td>
			</tr>
			<tr>
				<td>[5] IVAN DARIO VENGOECHEA NIETO</td>
				<td>1020729663</td>
				<td>25/07/2012</td>
				<td>24/01/2013</td>
				<td>23/02/2013</td>
				<td>209</td>
				<td>156</td>
				<td>53</td>
			</tr>
			<tr>
				<td>[5] JUAN DAVID MARIN MAZUERA</td>
				<td>1136879378</td>
				<td>25/07/2012</td>
				<td>24/01/2013</td>
				<td>23/02/2013</td>
				<td>209</td>
				<td>156</td>
				<td>53</td>
			</tr>
			<tr>
				<td>[5] KARENN OMAIRA PARRAGA OTALORA</td>
				<td>1017188996</td>
				<td>16/07/2012</td>
				<td>15/07/2013</td>
				<td>14/08/2013</td>
				<td>389</td>
				<td>165</td>
				<td>224</td>
			</tr>
			<tr>
				<td>[5] ROBERT ALEXANDER URQUIJO CORREDOR</td>
				<td>1030626795</td>
				<td>12/02/2013</td>
				<td>11/08/2013</td>
				<td>10/09/2013</td>
				<td>209</td>
				<td>0</td>
				<td>209</td>
			</tr>
			<tr>
				<td>[5] ROJAS CALDERON ROLAND FERNELL</td>
				<td>1015410438</td>
				<td>15/08/2013</td>
				<td>14/11/2013</td>
				<td>14/12/2013</td>
				<td>106</td>
				<td>0</td>
				<td>106</td>
			</tr>
			<tr>
				<td>[5] CAROLINA SOTO LONDOÑO</td>
				<td>1035862442</td>
				<td>15/07/2013</td>
				<td>14/07/2014</td>
				<td>13/08/2014</td>
				<td>136</td>
				<td>0</td>
				<td>136</td>
			</tr>
			<tr>
				<td>[6] EDWIN YESID BERNAL MORENO</td>
				<td>1019054858</td>
				<td>02/07/2013</td>
				<td>08/09/2013</td>
				<td>08/10/2013</td>
				<td>97</td>
				<td>0</td>
				<td>97</td>
			</tr>
			<tr>
				<td>[6] ALEJANDRA GOMEZ AGUILAR</td>
				<td>1050951465</td>
				<td>04/09/2013</td>
				<td>03/03/2014</td>
				<td>02/04/2014</td>
				<td>87</td>
				<td>0</td>
				<td>87</td>
			</tr>
			<tr>
				<td>[6] NAYIBE ALEJANDRA MARTINEZ HIGUERA</td>
				<td>1023935505</td>
				<td>05/08/2013</td>
				<td>04/08/2014</td>
				<td>03/09/2014</td>
				<td>116</td>
				<td>0</td>
				<td>116</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="5">TOTAL DIAS CUMPLIDOS</td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td>18836</td>
				<td>7726</td>
				<td>11110</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Monetización del Período: <?php echo '[10]';  ?></strong</td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<th>PERIODO DE PAGO</th>
				<th>FECHA DE PAGO</th>
				<th>TRANSACCIÓN</th>
				<th>PAGO NETO</th>
				<th>INTERESES</th>
				<th>PAGO TOTAL</th>
			</tr>
			<tr>
				<td>01/01/2012</td>
				<td>06/02/2012</td>
				<td>16691061</td>
				<td>$ 3.305.750.00</td>
				<td>$ 0.00</td>
				<td>$ 3.305.750.00</td>
			</tr>
			<tr>
				<td>01/02/2012</td>
				<td>13/03/2012</td>
				<td>16770872</td>
				<td>$ 3.966.900.00</td>
				<td>$ 0.00</td>
				<td>$ 3.966.900.00</td>
			</tr>
			<tr>
				<td>01/03/2012</td>
				<td>02/04/2012</td>
				<td>16810662</td>
				<td>$ 3.966.900.00</td>
				<td>$ 0.00</td>
				<td>$ 3.966.900.00</td>
			</tr>
			<tr>
				<td>01/03/2012</td>
				<td>04/05/2012</td>
				<td>16879091</td>
				<td>$ 566.700.00</td>
				<td>$ 0.00</td>
				<td>$ 566.700.00</td>
			</tr>
			<tr>
				<td>01/09/2012</td>
				<td>04/10/2012</td>
				<td>17194816</td>
				<td>$ 3.400.200.00</td>
				<td>$ 0.00</td>
				<td>$ 3.400.200.00</td>
			</tr>
			<tr>
				<td>01/11/2012</td>
				<td>06/12/2012</td>
				<td>17344702</td>
				<td>$ 2.266.800.00</td>
				<td>$ 0.00</td>
				<td>$ 2.266.800.00</td>
			</tr>
			<tr>
				<td>01/01/2013</td>
				<td>06/02/2013</td>
				<td>17470049</td>
				<td>$ 2.593.800.00</td>
				<td>$ 0.00</td>
				<td>$ 2.593.800.00</td>
			</tr>
			<tr>
				<td>01/02/2013</td>
				<td>06/03/2013</td>
				<td>17536537</td>
				<td>$ 1.179.000.00</td>
				<td>$ 0.00</td>
				<td>$ 1.179.000.00</td>
			</tr>
			<tr>
				<td>01/03/2013</td>
				<td>04/04/2013</td>
				<td>17594734</td>
				<td>$ 589.500.00</td>
				<td>$ 0.00</td>
				<td>$ 589.500.00</td>
			</tr>
			<tr>
				<td>01/11/2013</td>
				<td>03/12/2013</td>
				<td>18302567</td>
				<td>$ 1.768.500.00</td>
				<td>$ 0.00</td>
				<td>$ 1.768.500.00</td>
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Resumen año: <?php echo '[2012]';  ?></strong</td>
			</tr>
		</table>
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
				<td>Días Asignados</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>19</td>
				<td>11</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>16</td>
				<td>14</td>
				<td>30</td>
				<td>30</td>
				<td>300</td>
			</tr>
			<tr>
				<td>Días Asignados * Nro. Cuota</td>
				<td>720</td>
				<td>720</td>
				<td>720</td>
				<td>456</td>
				<td>209</td>
				<td>570</td>
				<td>570</td>
				<td>570</td>
				<td>304</td>
				<td>392</td>
				<td>840</td>
				<td>840</td>
				<td>6911</td>
			</tr>
			<tr>
				<td>Días Cumplido</td>
				<td>720</td>
				<td>499</td>
				<td>483</td>
				<td>456</td>
				<td>209</td>
				<td>570</td>
				<td>570</td>
				<td>570</td>
				<td>304</td>
				<td>392</td>
				<td>757</td>
				<td>764</td>
				<td>6294</td>
			</tr>
			<tr>
				<td>Días Incumplidos</td>
				<td>0</td>
				<td>221</td>
				<td>237</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>83</td>
				<td>76</td>
				<td>617</td>
			</tr>
			<tr>
				<td><small>(B) Salario Minimo Legal Mensual</small></td>
				<td>$ 566.700.00</td>
				<td colspan="10"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 11.655.130.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td><small>(C) Factor Base de Líquidación = (B)/30</small></td>
				<td>$ 18.890.00</td>
				<td colspan="10"><small>(E) Valor Cancelado por Monetización</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 17.473.250.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="10"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ -5.818.120.00</td>
				<!--<td></td>-->
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Resumen año: <?php echo '[2013]';  ?></strong</td>
			</tr>
		</table>
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
				<td>Días Asignados</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>30</td>
				<td>0</td>
				<td>330</td>
			</tr>
			<tr>
				<td>Días Asignados * Nro. Cuota</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>0</td>
				<td>9240</td>
			</tr>
			<tr>
				<td>Días Cumplido</td>
				<td>753</td>
				<td>838</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>840</td>
				<td>0</td>
				<td>9151</td>
			</tr>
			<tr>
				<td>Días Incumplidos</td>
				<td>87</td>
				<td>2</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>89</td>
			</tr>
			<tr>
				<td><small>(B) Salario Minimo Legal Mensual</small></td>
				<td>$ 589.500.00</td>
				<td colspan="10"><small>(D) Valuar Anual Liquidado = (C)*N° Total de días incumplidos</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 1.748.500.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td><small>(C) Factor Base de Líquidación = (B)/30</small></td>
				<td>$ 19.650.00</td>
				<td colspan="10"><small>(E) Valor Cancelado por Monetización</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ 6.130.800.00</td>
				<!--<td></td>-->
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td colspan="10"><small>(3)Valor Total Neto Liquidado = (D) - (E)</small></td>
				<!--<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>-->
				<td colspan="2">$ -4.381.900.00</td>
				<!--<td></td>-->
			</tr>
		</table>
		<p><br><br></p>
		<table class="table table-bordered">
			<tr class="info">
				<td style="text-align:center;"><strong>Valor Total Estado de Cuenta: <?php echo '$ -10.200.070.00';  ?></strong</td>
			</tr>
		</table>
</div>
<!-- Fin listado de interes -->
<br><br>
<!-- LIstado de Controles -->

<!-- LIstado de Controles -->
<div id="controles" class="controls controls-row" style="display:block;">
	<span class="span2"></span>
	<span class="span2"><a class="btn btn-success"  id="vistaPrevia"><i class="icon-eye-open icon-white"></i>  Vista previa</a></span>
	<span class="span1"></span>
	<span class="span2"><a class="btn btn-success" onClick="javascript:printdiv('cabecera','vistaPrevia_base');"><i class="icon-print icon-white"></i>  Imprimir</a></span>
	<span class="span1"></span>
	<span class="span2"><a class="btn btn-success" href="#"><i class="icon-file icon-white"></i>  Comprobante</a></span>
	<span class="span2"></span>
</div>
<!-- Función datepicker /post / modal -->
<script>
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
		maxDate: '29d',
		minDate: '0d'
   	 });

	$('#calcularInteres').click(function()
	{
		if ($('#fechaLiquidacion').val() == "")
		{
			alert('Se ha producido un error de ejecución: Debe indicar una fecha de liquidación')
		}
		else
		{
			$('#loader').fadeIn('slow');	
			$.post('<?php echo site_url();?>/liquidaciones/calcularInteresMultasMinisterio',
			{
				codigoMulta: $('#codigoMulta').val(),
				valorMulta: $('#valorMulta').val(),
				fechaElaboracion: $('#fechaElaboracion').val(),
				fechaEjecutoria: $('#fechaEjecutoria').val(),
				fechaLiquidacion: $('#fechaLiquidacion').val()				
			}
			)
			.done(function(data)
			{	
				$('#loader').fadeOut('fast');
				$('#vistaPrevia_base').html(data);
				$('#informe').html(data);
				$('#calcularInteres').attr("disabled", "disabled");	
				$('#informe').css("display","block");
				var p = new Paginador(
    					document.getElementById('paginador'),
    					document.getElementById('intereses'),
						6
				);
				p.Mostrar();
				$('#controles').css("display","block");	
			})
			.fail(function(e)
			{
				alert('Ha ocurrido un error de ejecución: No fue posible enviar la información');
				$('#loader').fadeOut('fast');
			});
		}
	});

	$('#vistaPrevia').click(function()
	{
		$(function() 
		{
    			$( "#vistaPrevia_base" ).dialog({
      				width: 800,
      				height: 500,
     			 	modal: true
    			});
  		});
  	});
});
 </script>
<!-- Fin función DatePicker /post / modal -->
 <!--Función paginador -->
<script type="text/javascript">
Paginador = function(divPaginador, tabla, tamPagina)
{
	this.miDiv = divPaginador; //un DIV donde irán controles de paginación
    	this.tabla = tabla;           //la tabla a paginar
    	this.tamPagina = tamPagina; //el tamaño de la página (filas por página)
    	this.pagActual = 1;         //asumiendo que se parte en página 1
    	this.paginas = Math.floor((this.tabla.rows.length - 1) / this.tamPagina); //¿?
 
    	this.SetPagina = function(num)
    	{
        		if (num < 0 || num > this.paginas)
            	return;
 
        		this.pagActual = num;
        		var min = 1 + (this.pagActual - 1) * this.tamPagina;
        		var max = min + this.tamPagina - 1;
 
        		for(var i = 1; i < this.tabla.rows.length; i++)
        		{
            		if (i < min || i > max)
                		this.tabla.rows[i].style.display = 'none';
           		 else
                		this.tabla.rows[i].style.display = '';
        		}
        	this.miDiv.firstChild.rows[0].cells[1].innerHTML = this.pagActual;
	}
 
	this.Mostrar = function()
	{
        	//Crear la tabla
        	var tblPaginador = document.createElement('table');
 
        	//Agregar una fila a la tabla
        	var fil = tblPaginador.insertRow(tblPaginador.rows.length);
 
        	//Ahora, agregar las celdas que serán los controles
        	var ant = fil.insertCell(fil.cells.length);
        	ant.innerHTML = 'Anterior';
        	ant.className = 'btn btn-inverse'; //con eso le asigno un estilo
        	var self = this;
        	ant.onclick = function()
        	{
            	if (self.pagActual == 1)
            	{
            		return;	
            	}	
                	
            	self.SetPagina(self.pagActual - 1);
        	}
 
        	var num = fil.insertCell(fil.cells.length);
        	num.innerHTML = ''; //en rigor, aún no se el número de la página
        	num.className = 'badge';

	var pag = fil.insertCell(fil.cells.length);
         	pag.innerHTML ='  de  ';
        

         	var total = fil.insertCell(fil.cells.length);
         	total.innerHTML =(self.paginas+1);
         	total.className = 'badge';
 
        	var sig = fil.insertCell(fil.cells.length);
        	sig.innerHTML = 'Siguiente';
        	sig.className = 'btn btn-inverse';
        	sig.onclick = function()
        	{
            	if (self.pagActual == self.paginas)
                	{
            		return;	
            	}
            	self.SetPagina(self.pagActual + 1);
        	}
 
       	 //Como ya tengo mi tabla, puedo agregarla al DIV de los controles
        	this.miDiv.appendChild(tblPaginador);
 
        	//Control de paginas
        	if (this.tabla.rows.length - 1 > this.paginas * this.tamPagina)
            	this.paginas = this.paginas + 1;
 
        	this.SetPagina(this.pagActual);
    	}
}

function printdiv(id_cabecera, id_cuerpo)
{
	var tittle = document.getElementById(id_cabecera);
	var content = document.getElementById(id_cuerpo);
	var printscreen = window.open('','','left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
	printscreen.document.write(tittle.innerHTML);
	printscreen.document.write(content.innerHTML);
	printscreen.document.close();

	var css = printscreen.document.createElement("link");
	css.setAttribute("href", "<?php echo base_url()?>/css/bootstrap.css");
	css.setAttribute("rel", "stylesheet");
	css.setAttribute("type", "text/css");

	var css1 = printscreen.document.createElement("link");
	css1.setAttribute("href", "<?php echo base_url()?>/css/style.css");
	css1.setAttribute("rel", "stylesheet");
	css1.setAttribute("type", "text/css");

	printscreen.document.head.appendChild(css);
	printscreen.document.head.appendChild(css1);


	printscreen.focus();
	printscreen.print();
	printscreen.close();
}
</script>
<!-- Fin función paginador -->
<!--
/* End of file interesmultasmin_form.php */
/* Location: ./system/application/views/liquidaciones/interesmultasmin_form.php */
/* Dev: @jdussan 13/03/2014 */
-->