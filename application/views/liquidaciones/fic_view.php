<?php
/**
* Formulario para la captura de información para el calculo de liquidaciones por aportes del Fondo de Formación Profesional de la Industria de la Construcción FIC en los procesos administrativos
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/fic_view.php
* @last-modified  10/12/2014
* @copyright
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (isset($message)){
  echo $message;
}
$atributos_entidadesDebuger = array ('N' => 'No Activar Detalle', 'S' => 'Activar Detalle');
$atributos_observaciones =  array('id' => 'observaciones' , 'name' => 'observaciones', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%', 'placeholder' => 'Observaciones');
$atributos_documentacion =  array('id' => 'documentacion' , 'name' => 'documentacion', 'value' => '', 'rows' => '5', ' maxlength' => '1995', 'style' => 'width:98%', 'placeholder' => 'Documentación Aportada');
echo "<pre>";
// print_r($codigoGestion);
if ($transacciones == null):
    echo 'alert';
endif;
echo "</pre>";
?>

<!-- Estilos personalizados -->
<style type="text/css">
  #cabecera table, #datos_generales table{
    width: 100%; height: auto; margin: 10px;
  }
  #cabecera td, #datos_generales td{
    padding: 5px; text-align: left; vertical-align: middle;
  }
  #datos_generales{
    border:1px solid #CFCFCF;
  }
</style>
<!-- Fin Estilos personalizados -->

<div class="center-form-xlarge">
  <?php
    $atributos_form = array('method' => 'post', 'id' => 'fic', 'class' => 'form-inline');
    echo form_open('liquidaciones/loadFormFic', $atributos_form);
  ?>

  <h2 class="text-center">Liquidación Fondo de Formación Profesional de la Industria de la Construcción FIC</h2>
  <br><br>

  <!--Inicio de datos del proceso y la empresa -->
  <div id="cabecera">
    <table>
      <tr>
        <td><b>Razón Social : </b><?php echo $codigoGestion['RAZON_SOCIAL'] ?></td>
        <td><b>NIT : </b><?php echo $codigoGestion['NIT_EMPRESA'] ?></td>
        <td><b>Regional : </b><?php echo $codigoGestion['NOMBRE_REGIONAL'] ?></td>
      </tr>
      <tr>
        <td><b>Periodo Inicial : </b><?php echo $codigoGestion['PERIODO_INICIAL'] ?></td>
        <td><b>Periodo Fin : </b><?php echo $codigoGestion['PERIODO_FINAL'] ?></td>
        <td>
          <?php
            echo form_label('<b>Fecha de Liquidación :&nbsp;</b>','fechaLiquidacion');
            $atributos_fechaLiquidacion = array ('type' => 'input', 'name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'value' => $fecha, 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
            echo form_input($atributos_fechaLiquidacion);
          ?>
        </td>
      </tr>
      </tr>
        <tr>
            <td colspan = "3">
                <?php
                echo form_dropdown('debuger', $atributos_entidadesDebuger, set_value('debuger'));
                ?>
            </td>
        </tr>
    </table>
  </div>
  <!--Fin de datos del proceso y la empresa -->

  <!--Inicio de valores generales para la liquidación -->
  <div id="datos_generales" class="row-fluid">
    <table>
      <tr>
        <td>
          <div class="input-prepend input-append">
          <?php
            echo form_label('<b>Valor Inversión :&nbsp;</b>','valorInversion');
            echo '<span class="add-on">$</span>';
            $atributos_valorInversion = array ('type' => 'text', 'name' => 'valorInversion', 'id' => 'valorInversion', 'value' => 0, 'class' => 'input-small', 'maxlength' => '19',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_valorInversion);
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php
            echo form_label('<b>Gastos de Financiación :&nbsp;</b>','gastosFinanciacion');
            echo '<span class="add-on">$</span>';
            $atributos_gastosFinanciacion = array ('type' => 'text', 'name' => 'gastosFinanciacion', 'id' => 'gastosFinanciacion', 'value' => 0, 'class' => 'input-small', 'maxlength' => '19',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_gastosFinanciacion);
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php
            echo form_label('<b>Impuestos Legales :&nbsp;</b>','impuestosLegales');
            echo '<span class="add-on">$</span>';
            $atributos_impuestosLegales = array ('type' => 'text', 'name' => 'impuestosLegales', 'id' => 'impuestosLegales', 'value' => 0, 'class' => 'input-small', 'maxlength' => '19',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_impuestosLegales);
          ?>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="input-prepend input-append">
          <?php
            echo form_label('<b>Valor del Lote :&nbsp;</b>','valorLote');
            echo '<span class="add-on">$</span>';
            $atributos_valorLote = array ('type' => 'text', 'name' => 'valorLote', 'id' => 'valorLote', 'value' => 0, 'class' => 'input-small', 'maxlength' => '19',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_valorLote);
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php
            echo form_label('<b>Indenmización a Terceros :&nbsp;</b>','indenmizacion');
            echo '<span class="add-on">$</span>';
            $atributos_indenmizacion = array ('type' => 'text', 'name' => 'indenmizacion', 'id' => 'indenmizacion', 'value' => 0, 'class' => 'input-small', 'maxlength' => '19',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_indenmizacion);
          ?>
          </div>
        </td>
        <td></td>
      </tr>
    </table>
    <span class="span12" id="alertas">&nbsp;</span>
  </div>
  <!--Fin de valores generales para la liquidación -->

  <br><br>

  <!--Inicio de valores específicos para la liquidación -->
  <div id="liquidaciones">
    <div class="tabbable">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#normativa" data-toggle="tab">Liquidación Normativa</a></li>
        <li><a href="#presuntiva" data-toggle="tab">Liquidación Presuntiva</a></li>
      </ul>
    <div class="tab-content">
         <div id="mensaje"></div>
      <!--Inicio de la Liquidación Normativa -->
      <div class="tab-pane active" id="normativa">
        <h3>Número de Trabajadores</h3>
        <?php
          for($indicador = $annoFinal; $indicador >= $annoInicial; $indicador--):
            echo '<p>Salario Mínimo Legal Vigente ', $indicador, ':  ' , "$".number_format($smlv['smlv_'.$indicador], 0, '.', '.') , '</p>';
          endfor;
        ?>
        <table class="table table-striped">
          <tr>
            <th></th>
            <th>Enero</th>
            <th>Febrero</th>
            <th>Marzo</th>
            <th>Abril</th>
            <th>Mayo</th>
            <th>Junio</th>
            <th>Julio</th>
            <th>Agosto</th>
            <th>Septiembre</th>
            <th>Octubre</th>
            <th>Noviembre</th>
            <th>Diciembre</th>
            <th>Total</th>
          </tr>
          <?php
            for ($year = $annoFinal; $year >= $annoInicial; $year--):
          ?>
          <tr>
            <td><b><?php echo $year ?></b></td>
            <?php
              $anno = array('type' => 'hidden', 'name' => $year, 'id' => $year, 'value' => $smlv['smlv_'.$year]);
              echo form_input($anno);
              for($i = 1; $i <= 12; $i++):
                echo '<td>';
                $atributos_data = array ( 'name' => $year.'_'.$i, 'id' => $year.'_'.$i, 'value' => set_value($i,'0'), 'class' => 'input-small',  'maxlength'=>'6', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);', 'onchange' => 'calcularValor(this);');
                echo form_input($atributos_data);
                echo '</td>';
              endfor;
            ?>
          </tr>
          <tr>
            <td></td>
            <?php
              for($i = 1; $i <= 12; $i++):
                echo '<td><div class="input-prepend"><span class="add-on">$</span>';
                $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_data', 'id' => $year.'_'.$i.'_data', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                echo form_input($atributos_data);
              endfor;
            ?>
            <td>
              <div class="input-prepend"><span class="add-on">$</span>
              <?php
                $atributos_data = array ('type' => 'text', 'name' => 'total_'.$year, 'id' => 'total_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                echo form_input($atributos_data);
              ?>
            </td>
          </tr>
          <?php
          endfor;
          ?>
        </table>
      </div>
      <!--Fin de la Liquidación Normativa -->
      <!--Inicio de la Liquidación Presuntiva -->
      <div class="tab-pane" id="presuntiva">
        <div class="tabbable">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#costo" data-toggle="tab">Contrato a Todo Costo</a></li>
            <li><a href="#obra" data-toggle="tab">Contrato Mano de Obra</a></li>
          </ul>
        </div>
        <div class="tab-content">
          <!--Inicio Liquidación Presuntiva contrato a todo costo-->
          <div class="tab-pane active" id="costo">

            <h3>Contrato a Todo Costo</h3>
            <select name="valor_costo" id="valor_costo" onchange="ingresar_valor('valor_costo',this.value)">

                  <option value="valor_contrato" >Valor Contrato</option>
                  <option value="valor_anual">Valor Anual</option>
              </select>
            <div id="cont_costo">
              <table class="table">
                <a name="contrato_todo_costo"></a>
                <tr>
                  <td><label for="contrato"><b>Contrato:</b></label></td>
                  <td>
                    <?php
                    $atributos_contrato = array ('type' => 'number', 'name' => 'contrato', 'id' => 'contrato', 'value' => set_value('contrato','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15'    );
                    echo form_input($atributos_contrato);
                    ?>
                  </td>
                  <td><label for="fechaInicio">Inicio:</label></td>
                  <td>
                    <?php
                    $atributos_fechaInicio = array ('type' => 'text', 'name' => 'fechaInicio', 'id' => 'fechaInicio', 'value' => set_value('fechaInicio'),'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
                    echo form_input($atributos_fechaInicio);
                    ?>
                  </td>
                  <td><label for="fechaFin">Fin:</label></td>
                  <td>
                    <?php
                    $atributos_fechaFin = array ('type' => 'text', 'name' => 'fechaFin', 'id' => 'fechaFin', 'value' => set_value('fechaFin'), 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
                    echo form_input($atributos_fechaFin);
                    ?>
                  </td>
                  <td><label for="valor"><b>Valor:<b></label></td>
                  <td>
                    <div class="input-prepend">
                      <?php
                      echo '<span class="add-on">$</span>';
                      $atributos_valor = array ('type'=>'text', 'name' => 'valor', 'id' => 'valor', 'value' => set_value('valor','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', 'onkeypress'=>'soloNumeros(event)' );
                      echo form_input($atributos_valor);
                      ?>
                    </div>
                  </td>
                  <td><a href="#contrato_todo_costo" class="btn btn-success" id="agregar_contrato_costo"><i class="icon-white icon-plus"></i> Agregar Contrato</a></td>
                </tr>
              </table>
            </div>
              <br><br>
              <div id="contrato_costo" style="display:none">
                  <table id="ContratoCosto" class="table">
                      <tr id="cabecera_costo">
                      <th>Número Contrato</th>
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Valor</th>
                      <th>Eliminar Contrato</th>

                      </tr>
                      <tr>
                      <td><input type="hidden" name="contratos_costo[]" id="contrato_costo_" value=''></td>
                      <td><input type="hidden" name="fecha_inicio_costo[]" id="fecha_inicio_costo" value=''></td>
                      <td><input type="hidden" name="fecha_fin_costo[]" id="fecha_fin_costo" value=''></td>
                      <td><input type="hidden" name="valor_contr_costo[]" id="valor_contr_costo" value=''></td>
                      <td></td>
                      </tr>
                  </table>
              </div>
              <table class="table table-striped">
              <tr>
                <th></th>
                <th id="costo_1">Enero</th>
                <th id="costo_2">Febrero</th>
                <th id="costo_3">Marzo</th>
                <th id="costo_4">Abril</th>
                <th id="costo_5">Mayo</th>
                <th id="costo_6">Junio</th>
                <th id="costo_7">Julio</th>
                <th id="costo_8">Agosto</th>
                <th id="costo_9">Septiembre</th>
                <th id="costo_10">Octubre</th>
                <th id="costo_11">Noviembre</th>
                <th id="costo_12">Diciembre</th>
                <th>Total</th>
              </tr>
              <?php
                for ($year = $annoFinal; $year >= $annoInicial; $year--):
              ?>
              <tr>
                <td><b><?php echo $year ?></b></td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td id="a_costo_'.$year.'_'.$i.'"><div class="input-prepend"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_costo', 'id' => $year.'_'.$i.'_costo', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                  endfor;
                ?>
                <?php
                    echo '<td><div id='.$year.'_costoValor'.' class="input-prepend" style="display:block">';
                    $atributos_data = array ('type' => 'text','readonly'=>'readonly', 'name' => $year.'_val_costo', 'id' => $year.'_val_costo', 'value' => '0', 'class' => 'input-small','onkeyup'=>'num(this);', 'onblur'=>'num(this);','onkeypress'=>'num(this);');
                    echo form_input($atributos_data);
                    $atributos_data = array ('type' => 'hidden','readonly'=>'readonly', 'name' => $year.'_TotalContratoCosto', 'id' => $year.'_TotalContratoCosto', 'value' => '0', 'class' => 'input-small', 'onkeypress' => 'num(this);',);
                    echo form_input($atributos_data);
                     echo '</div></td>';
                ?>

              </tr>
              <tr>
                  <td>

                  </td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td id="b_costo_'.$year.'_'.$i.'"><div class="input-prepend"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_dataCosto', 'id' => $year.'_'.$i.'_dataCosto', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '</td>';
                  endfor;
                ?>
                <td>
                  <div class="input-prepend" id="<?php echo "div_total_costo_".$year ?>"><span class="add-on">$</span>
                  <?php
                    $atributos_data = array ('type' => 'text', 'name' => 'totalCosto_'.$year, 'id' => 'totalCosto_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                  ?>
                  </div>
                </td>
              </tr>
              <?php
              endfor;
              ?>
            </table>
          </div>
          <!--Fin Liquidación Presuntiva a Mano de Obra -->
          <div class="tab-pane" id="obra">
            <h3>Contrato Mano de Obra</h3>
            <select name="valor_obra" id="valor_obra" onchange="ingresar_valor('valor_obra',this.value)">
                  <option value="valor_contrato" >Valor Contrato</option>
                  <option value="valor_anual">Valor Anual</option>
              </select>
              <div id="cont_obra">
            <table class="table">
                <tr>
                  <td><label for="contratoObra"><b>Contrato:</b></label></td>
                  <td>
                    <?php
                    $atributos_contratoObra = array ('type' => 'number', 'name' => 'contratoObra', 'id' => 'contratoObra', 'value' => set_value('contratoObra','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
                    echo form_input($atributos_contratoObra);
                    ?>
                  </td>
                  <td><label for="fechaInicioObra">Inicio:</label></td>
                  <td>
                    <?php
                    $atributos_fechaInicioObra = array ('type' => 'text', 'name' => 'fechaInicioObra', 'id' => 'fechaInicioObra', 'value' => set_value('fechaInicioObra'), 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
                    echo form_input($atributos_fechaInicioObra);
                    ?>
                  </td>
                  <td><label for="fechaFinObra">Fin:</label></td>
                  <td>
                    <?php
                    $atributos_fechaFinObra = array ('type' => 'text', 'name' => 'fechaFinObra', 'id' => 'fechaFinObra', 'value' => set_value('fechaFinObra'), 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
                    echo form_input($atributos_fechaFinObra);
                    ?>
                  </td>
                  <td><label for="valorObra"><b>Valor:<b></label></td>
                  <td>
                    <div class="input-prepend">
                      <?php
                      echo '<span class="add-on">$</span>';
                      $atributos_valorObra = array ('type' => 'number', 'name' => 'valorObra', 'id' => 'valorObra', 'value' => set_value('valorObra','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', );
                      echo form_input($atributos_valorObra);
                      ?>
                    </div>
                  </td>
                  <td><a href="#" class="btn btn-success" id="agregar_contrato_obra"><i class="icon-white icon-plus"></i> Agregar Contrato</a></td>
                </tr>
              </table>
              </div>
              <br><br>
                  <div id="contrato_obra" style="display:none">
                  <table id="ContratoObra" class="table">
                      <tr>
                      <th>Número Contrato</th>
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Valor</th>
                      <th>Eliminar Contrato</th>

                      </tr>
                      <tr  >
                          <td><input type="hidden" name="contratos_obra[]" id="contrato_obra_" value=''></td>
                          <td><input type="hidden" name="fecha_inicio_obra[]" id="fecha_inicio_obra" value=''></td>
                          <td><input type="hidden" name="fecha_fin_obra[]" id="fecha_fin_obra" value=''></td>
                     <td><input type="hidden" name="valor_contr_obra[]" id="valor_contr_obras" value=''></td>
                       <td></td>
                      </tr>
                  </table>
              </div>
              <table class="table table-striped">
              <tr>
                <th></th>
                <th id="obra_1">Enero</th>
                <th id="obra_2">Febrero</th>
                <th id="obra_3">Marzo</th>
                <th id="obra_4">Abril</th>
                <th id="obra_5">Mayo</th>
                <th id="obra_6">Junio</th>
                <th id="obra_7">Julio</th>
                <th id="obra_8">Agosto</th>
                <th id="obra_9">Septiembre</th>
                <th id="obra_10">Octubre</th>
                <th id="obra_11">Noviembre</th>
                <th id="obra_12">Diciembre</th>
                <th>Total</th>
              </tr>
              <?php
                for ($year = $annoFinal; $year >= $annoInicial; $year--):
              ?>
              <tr>
                <td><b><?php echo $year ?></b></td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td id="a_obra_'.$year.'_'.$i.'"><div class="input-prepend"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_obra', 'id' => $year.'_'.$i.'_obra', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '</div></td>';
                  endfor;
                  ?><td>

                      <?php
                    echo '<div class="input-prepend" id='.$year.'_obraValor'.' style="display:block" >';
                    $atributos_data = array ('type' => 'text','readonly'=>'readonly', 'name' => $year.'_val_obra', 'id' => $year.'_val_obra', 'value'=>'0', 'class' => 'input-small', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this)');
                    echo form_input($atributos_data);
                     $atributos_data = array ('type' => 'hidden','readonly'=>'readonly', 'name' => $year.'_TotalContratoObra', 'id' => $year.'_TotalContratoObra', 'value' => '0', 'class' => 'input-small', 'onkeypress' => 'num(this);',);
                    echo form_input($atributos_data);
                    echo '</div>';
                ?></td>
              </tr>
              <tr>
                <td></td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td id="b_obra_'.$year.'_'.$i.'"><div class="input-prepend"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_dataObra', 'id' => $year.'_'.$i.'_dataObra', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '</div></td>';
                  endfor;

                ?>

                <td>

                  <div class="input-prepend"  id="<?php echo "div_total_obra_".$year ?>"><span class="add-on">$</span>
                  <?php
                    $atributos_data = array ('type' => 'text', 'name' => 'totalObra_'.$year, 'id' => 'totalObra_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                  ?>
                  </div>
                </td>
              </tr>
              <?php
              endfor;
              ?>
            </table>
          </div>
        </div>
      </div>
      <!--Fin de la Liquidación Presuntiva -->
    </div>
    </div>
    <p>&nbsp;</p>
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
    </div>
    <p>&nbsp;</p>
    <div class="row-fluid">
        <div class="span12"><strong>Transacciones Asociadas</strong></div>
        <?php
        $attributos_formulario_transacciones = array('class' => 'form-inline', 'id' => 'transacciones');
        $atributos_buscar = array ('type' => 'text', 'id' => 'transaccion', 'name' => 'transaccion', 'class'  => 'input-large', 'maxlength' => '20', 'placeholder' => 'Transacción');
        // echo form_open('', $attributos_formulario_transacciones);
        ?>
        <a name="transacciones" id="transacciones_link"></a>
        <table>
            <tr>
                <td width = "200">Número de Transacción: </td>
                <td width = "250"><?php echo form_input($atributos_buscar); ?></td>
                <td width = "150"><a id = "consultar" class="btn btn-info" href="#transacciones"><i class="fa fa-search"></i>  Buscar</a></td>
            </tr>
        </table>
        <?php
        //echo form_close();
        ?>
        <hr>
        <p>Información Asociada</p>
        <table class = "table table-striped table-bordered" id ="informacionAsociada">
          <thead class = "table table-striped table-bordered">
            <tr>
                <th>NIT</th>
                <th>Periodo Pagado</th>
                <th>Nro Licencia Contrato</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Valor</th>
                <th>Acción</th>
            </tr>
          </thead>
          <tbody id = "transacciones">
          </tbody>
        </table>
        <p>Transacciones Registradas</p>
        <table class = "table table-striped table-bordered">
          <thead class = "table table-striped table-bordered">
            <tr>
                <th>ID</th>
                <th>Nit</th>
                <th>Contrato</th>
                <th>Valor</th>
            </tr>
          </thead>
          <tbody id = "transacciones_asociadas">
              <?php
                if ($transacciones == null):
                    echo '<tr id = "0"><td colspan = "4" >No se han encontrado registros asociados</td></tr>';
                else:
                    echo '';
                endif;
              ?>
          </tbody>
        </table>
    </div>
  <!--Fin de valores específicos para la liquidación -->
  <table style = "text-align:center; width:100%; margin-top:10px;">
    <tr>
      <td style = "width:50%;">
        <a class="btn btn-warning" href="<?php echo site_url(); ?>/liquidaciones/consultarLiquidacion/<?php echo $codigoGestion['COD_FISCALIZACION']?>"><i class="fa fa-minus-circle"></i> Cancelar</a>
      </td>
      <td style = "width:50%;">
            <?php
              $atributos_boton = array('id' =>'aceptar', 'name' => 'aceptar', 'class' => 'btn btn-success', 'content' => '<i class="icon-cog icon-white"></i> Calcular', 'type' => 'submit');
              echo form_button($atributos_boton) ;
            ?>
      </td>
    </tr>
  </table>
</div>
<?php

  echo form_hidden('maestro', serialize($codigoGestion));

  echo form_close();
?>

<!-- Función conteo de caracteres -->
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
<!-- Fin Función conteo de caracteres -->

<!-- Función buscar Transacciones -->
<script type="text/javascript" language="javascript" charset="utf-8">
  $('#consultar').click(function()
  {
    if ($('#transaccion').val() == "")
    {
        alert('Se ha producido un error de ejecución: Debe indicar una transacción a buscar')
    }
    else
    {
      //$('#loader').fadeIn('slow');
      $.post('<?php echo site_url();?>/liquidaciones/consultarTransaccionesFic',
      {
        transaccion: $('#transaccion').val(),
        nit: <?php echo $codigoGestion['NIT_EMPRESA']; ?>
        /*codigoFiscalizacion: $('#codigoFiscalizacion').val(),
        nit: $('#nit').val(),
        valorMulta: $('#valorMulta').val(),
        fechaElaboracion: $('#fechaElaboracion').val(),
        fechaEjecutoria: $('#fechaEjecutoria').val(),
        fechaLiquidacion: $('#fechaLiquidacion').val()*/
      }
      )
      .done(function(data)
      {
        $('#transacciones').append(data);
        //.fadeOut('fast');
        /*$('#vistaPrevia_base').html(data);
        $('#informe').html(data);
        $('#fechaLiq').html($('#fechaLiquidacion').val());
        $('#calcularInteres').attr("disabled", "disabled");
        $('#calcularInteres').css("display", "none");
        $('#cancelar').attr("disabled", "disabled");
        $('#cancelar').css("display", "none");
        $('#mensajePrevia').css("display", "none");
        $('#informe').css("display","block");
        var p = new Paginador(
        document.getElementById('paginador'),
        document.getElementById('intereses'),
        6
        );
        p.Mostrar();
        $('#controles').css("display","block");*/
      })
      .fail(function(e)
      {
        alert('Ha ocurrido un error de ejecución: No fue posible enviar la información');
        $('#loader').fadeOut('fast');
      });
    }
  });
</script>
<!-- Fin Función buscar Transacciones -->

<!-- Función agregar transacción -->
<script type="text/javascript" language="javascript" charset="utf-8">
  function agregarTransaccion(id) {
    var nit = document.getElementById('nitEmpresa_'+id).value;
    alert(nit);
    if(document.getElementById('periodoTransaccion_'+id) != undefined) {
      var periodo = document.getElementById('periodoTransaccion_'+id).value;
      alert(periodo);
      var valor = document.getElementById('valor_'+id).value;
      alert(valor);
    } else {
      var licencia = document.getElementById('licenciaContrato_'+id).value;
      alert(licencia);
      var fechaInicio = document.getElementById('fechaInicio_'+id).value;
      alert(fechaInicio);
      var fechaFin = document.getElementById('fechaFin_'+id).value;
      alert(fechaFin);
      var valor = document.getElementById('valor_'+id).value;
      alert(valor);
    }



    /*if ($('#transaccion').val() == "")
    {
        alert('Se ha producido un error de ejecución: Debe indicar una transacción a buscar')
    }
    else
    {
      $.post('<?php echo site_url();?>/liquidaciones/consultarTransaccionesFic',
      {
        transaccion: $('#transaccion').val()
      }
      )
      .done(function(data)
      {
        $('#transacciones').append(data);
      })
      .fail(function(e)
      {
        alert('Ha ocurrido un error de ejecución: No fue posible enviar la información');
        $('#loader').fadeOut('fast');
      });
    }*/
    var row = document.getElementById(id);
    row.parentNode.removeChild(row);
  }
</script>
<!-- Fin Función agregar transacción -->

<!-- Función datepicker -->
<script  type="text/javascript">

  function num(c)
  {
    c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
    var num = c.value.replace(/\./g,'');
    if(!isNaN(num))
    {
      num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
      num = num.split('').reverse().join('').replace(/^[\.]/,'');
      c.value = num;
    }
  }

  function ingresar_valor(tipo_valor,opcion)
  {

    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var num_contrato=$("#contrato").val();
    var valor_contrato = $("#valor").val();
    var dia_inicial = parseInt(fecha_inicial_separado[0]);
    var mes_inicial= parseInt(fecha_inicial_separado[1]);
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var dia_final = parseInt(fecha_final_separado[0]);
    var mes_final = parseInt(fecha_final_separado[1]);
    var anno_final = parseInt(fecha_final_separado[2]);
    if(tipo_valor='valor_costo')//Contrato a Todo Costo
      {
       if(opcion=='valor_contrato')
       {
          for(i= anno_inicial; i<=anno_final; i++)
          {
             $("#"+i+"_val_costo").prop('readonly',true);
             $("#cont_costo").show();
             //visualiza el total
               $("#div_total_costo_"+i).show();
               for(j=1; j<=12; j++ )
             {     $("#costo_"+j).show();
                   $("#a_costo_"+i+"_"+j).show();
                   $("#b_costo_"+i+"_"+j).show();
             }
          }
       }
       else if(opcion=='valor_anual')
       {
            for(i= anno_inicial; i<=anno_final; i++)
          {
             $("#"+i+"_val_costo").prop('readonly',false);
             $("#cont_costo").hide();
             //Oculto los input de los meses de cada año
             $("#div_total_costo_"+i).hide();
             for(j=1; j<=12; j++ )
             {     $("#costo_"+j).hide();
                   $("#a_costo_"+i+"_"+j).hide();
                   $("#b_costo_"+i+"_"+j).hide();
             }
          }
       }
      }

    if(tipo_valor='valor_obra')//Contrato  Mano Obra
      {
         if(opcion=='valor_contrato')
       {
          for(i= anno_inicial; i<=anno_final; i++)
          {
              $("#"+i+"_val_obra").val('0');
              $("#"+i+"_val_obra").prop('readonly',true);
              //$("#"+i+"_obraValor").hide();
              $("#cont_obra").show();
                $("#div_total_obra_"+i).show();
              for(j=1; j<=12; j++ )
             {     $("#obra_"+j).show();
                   $("#a_obra_"+i+"_"+j).show();
                   $("#b_obra_"+i+"_"+j).show();
             }
          }
       }
       else if(opcion=='valor_anual')
       {
            for(i= anno_inicial; i<=anno_final; i++)
          {
            $("#"+i+"_obraValor").show();
              $("#"+i+"_val_obra").prop('readonly',false);
            $("#cont_obra").hide();
             $("#div_total_obra_"+i).hide();
             for(j=1; j<=12; j++ )
             {     $("#obra_"+j).hide();
                   $("#a_obra_"+i+"_"+j).hide();
                   $("#b_obra_"+i+"_"+j).hide();
             }
          }
       }
      }

    }
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
  /*$.datepicker.setDefaults($.datepicker.regional['es']);
    $( "#fechaLiquidacion" ).datepicker({
    showOn: 'button',
    buttonText: 'Selecciona una fecha',
    buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
    buttonImageOnly: true,
    numberOfMonths: 1,
    maxDate: '1m',
    minDate: '0d',
    });
  */
  //Lanzador de datepicker para fecha de contrato a todo costo
  $.datepicker.setDefaults($.datepicker.regional['es']);
  $( "#fechaInicio, #fechaFin, #fechaInicioObra, #fechaFinObra" ).datepicker({
    showOn: 'button',
    buttonText: 'Selecciona una fecha',
    buttonImage: '<?php echo base_url() , "img/calendario.png" ?>',
    buttonImageOnly: true,
    numberOfMonths: 1,
     maxDate: '<?php echo $codigoGestion["PERIODO_FINAL"]; ?>',
     minDate: '<?php echo $codigoGestion["PERIODO_INICIAL"]; ?>'
  });


  //elimina la información del contrato seleccionado


  //agregar contrato a todo costo
  $("#agregar_contrato_costo").click(function(){
    //Separar años y meses del contrato
    var fecha_inicio = $('#fechaInicio').val();
    var fecha_inicio_separado = fecha_inicio.split("/");
    var fecha_fin = $('#fechaFin').val();
    var fecha_fin_separado = fecha_fin.split("/");
    var contrato=$("#contrato").val();
    var valor_contr = $("#valor").val();

    if (contrato==0 || contrato === ''){
         mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Contrato es Obligatorio </div>';
         document.getElementById("mensaje").innerHTML = mierror;
        }
     else if(fecha_inicio === ''){
           mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Fecha de Inicio es obligatorio</div>';
           document.getElementById("mensaje").innerHTML = mierror;
           }
    else if(fecha_fin ===''){
        mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Fecha Fin es obligatorio</div>';
        document.getElementById("mensaje").innerHTML = mierror;
        }
    else if(valor_contr ===''){
        mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo valor es obligatorio</div>';
        document.getElementById("mensaje").innerHTML = mierror;
        }
   else {
  //Visualiza información de los contratos creados
    $("#contrato_costo").show();
    $("#ContratoCosto").append('<tr  id="costo_'+contrato+'">'+
                '<td><input type="text" readonly="readonly" class="input-small input-disabled" name="contratos_costo[]" id="contrato_costo'+contrato +'" value='+contrato+'></td>'+
                '<td><input type="text" readonly="readonly" "class="input-small input-disabled"  name="fecha_inicio_costo[]" id="fecha_inicio_costo'+contrato +'" value='+fecha_inicio+'></td>'+
                '<td><input type="text" readonly="readonly" "class="input-small input-disabled" name="fecha_fin_costo[]" id="fecha_fin_costo'+contrato +'" value='+fecha_fin+'></td>'+
                '<td><input type="text" readonly="readonly" class="input-small input-disabled"  name="valor_contr_costo[]" id="valor_contr_costo'+contrato +'" value='+valor_contr+'></td>'+
               '<td><input type="button" id="eliminar" class="btn btn-success"  name="Eliminar" value="Eliminar" onclick='+ 'eliminar_cont('+contrato+')'+' ></td>'+
                '</tr>');

    //fechas de reccorrido
    dia_inicio = parseInt(fecha_inicio_separado[0]);
    mes_inicio = parseInt(fecha_inicio_separado[1]);
    anno_inicio = parseInt(fecha_inicio_separado[2]);
    dia_fin = parseInt(fecha_fin_separado[0]);
    mes_fin = parseInt(fecha_fin_separado[1]);
    anno_fin = parseInt(fecha_fin_separado[2]);
     var data1=0;
     var data2=0;
    if((anno_fin - anno_inicio) < 0){
      alert('Ha ingresado una fecha final con un año menor al año inicial');
    }
    if((anno_fin - anno_inicio) == 0){
      if((mes_fin - mes_inicio) == 0){
        if((dia_fin - dia_inicio) < 31){
      //  alert('mismo_mes');
          valor_contrato = $("#valor").val();
          valor_mensual = valor_contrato;
          //retiro el formato
          eliminar_formato(anno_inicio);
          //***/
          previo_total = parseInt($("#totalCosto_"+anno_inicio).val());
          previo_mes = parseInt($("#"+anno_inicio+"_"+mes_inicio+"_costo").val());
          $("#"+anno_inicio+"_"+mes_inicio+"_costo").val(parseInt(valor_mensual) + previo_mes);
          $("#"+anno_inicio+"_"+mes_inicio+"_dataCosto").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.0025));
          var total = parseInt($("#totalCosto_"+anno_inicio).val()) + parseInt(valor_mensual*0.0025);
          $("#totalCosto_"+anno_inicio).val(total);
          var totalContrato= $("#"+anno_inicio+"_TotalContratoCosto").val();
          $("#"+anno_inicio+"_TotalContratoCosto").val(parseInt(parseInt(valor_contrato)+parseInt(totalContrato)));
          //Agrega Formato
          agregar_formato(anno_inicio);
              }
      }
      if((mes_fin - mes_inicio) > 0){
     //   alert('diferente_mes')
        var valor_contrato = $("#valor").val();
        valor_mensual = valor_contrato/((mes_fin - mes_inicio)+1)
         eliminar_formato(anno_inicio);

        for (i = mes_inicio; i <= mes_fin; i++){

          previo_total = parseInt($("#totalCosto_"+anno_inicio).val());
          previo_mes = parseInt($("#"+anno_inicio+"_"+i+"_costo").val());
          $("#"+anno_inicio+"_"+i+"_costo").val(parseInt(valor_mensual) + previo_mes);
          $("#"+anno_inicio+"_"+i+"_dataCosto").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.0025));
          total = parseInt($("#totalCosto_" + anno_inicio).val()) + parseInt(valor_mensual*0.0025);
          $("#totalCosto_"+anno_inicio).val(total);


        }
           var totalContrato= $("#"+anno_inicio+"_TotalContratoCosto").val();
             $("#"+anno_inicio+"_TotalContratoCosto").val(parseInt(parseInt(valor_contrato)+parseInt(totalContrato)));
              agregar_formato(anno_inicio);
        }
      if((mes_fin - mes_inicio) < 0){
          alert('Ha ingresado una fecha final con un mes menor al mes inicial');
      }
    }
    if((anno_fin - anno_inicio) > 0 ){
          var valor_contrato = $("#valor").val();
          var meses_inicio = 0;
          var meses_fin = 0;
          var otros_meses=0;
     //Calculo la cantidad de meses
      for (j = anno_inicio; j <= anno_fin; j++){
        if (j == anno_inicio){//primer año
            for (i = mes_inicio; i <= 12; i++){
                meses_inicio = meses_inicio + 1;
            }
        }
        else if (j == anno_fin){//ultimo año
             for (i = 1; i <= mes_fin; i++){
              meses_fin = meses_fin + 1;
          }
        }
        else{//otros años
           for (i = 1; i <= 12; i++){
              otros_meses = otros_meses+1;
                }
      }
    }
     var cantidad_meses=(parseInt(meses_inicio)+parseInt(meses_fin)+parseInt(otros_meses));
      valor_mensual = valor_contrato/cantidad_meses;
      previo_total = parseInt($("#totalCosto_"+anno_inicio).val());
      //cantidad de años intermedios
      cantidad_annos=otros_meses/12;
      var a=0;
        for (j = anno_inicio; j <= anno_fin; j++) {  a++;
        if (j == anno_inicio){//primer año
               var total_mes=0;
             //  alert('primer_año_'+j);
               eliminar_formato(j);
            for (i = mes_inicio; i <= 12; i++){
                previo_mes = parseInt($("#"+anno_inicio+"_"+i+"_costo").val());
                $("#"+anno_inicio+"_"+i+"_costo").val(parseInt(valor_mensual) + previo_mes);
                $("#"+anno_inicio+"_"+i+"_dataCosto").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.0025));
                total = parseInt($("#totalCosto_" + anno_inicio).val()) + parseInt(valor_mensual*0.0025);
                $("#totalCosto_"+anno_inicio).val(total);
                dato1=$("#"+anno_inicio+"_"+i+"_costo").val();
                total_mes+=parseInt(dato1);
            }
                var totalContrato= $("#"+anno_inicio+"_TotalContratoCosto").val();
                $("#"+anno_inicio+"_TotalContratoCosto").val(parseInt(total_mes));
                  agregar_formato(j);

        }
        else if (j ==anno_fin){//ultimo año
                total_mes=0;
               eliminar_formato(j);
            for (i = 1; i <= mes_fin; i++){
                    previo_mes = parseInt($("#"+anno_fin+"_"+i+"_costo").val());
                    $("#"+anno_fin+"_"+i+"_costo").val(parseInt(valor_mensual) + previo_mes);
                    $("#"+anno_fin+"_"+i+"_dataCosto").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.0025));
                    total = parseInt($("#totalCosto_" + anno_fin).val()) + parseInt(valor_mensual*0.0025);
                    $("#totalCosto_"+anno_fin).val(total);
                     dato1=$("#"+anno_fin+"_"+i+"_costo").val();
                     total_mes+=parseInt(dato1);
          }


              $("#"+anno_fin+"_TotalContratoCosto").val(parseInt(total_mes));
               agregar_formato(j);
        }
              else  if ((j!=anno_inicio) && (j!=anno_fin)){//otros años
                var annos_intermedios = anno_inicio;
                var total_mes=0;
                 eliminar_formato(j);
                for(i =1; i<=12; i++)
                 {
                     previo_mes = parseInt($("#"+j+"_"+i+"_costo").val());
                     $("#"+j+"_"+i+"_costo").val((parseInt(previo_mes))+(parseInt(valor_mensual)));
                     var dat= $("#"+j+"_"+i+"_costo").val();
                     $("#"+j+"_"+i+"_dataCosto").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.0025));
                     total = parseInt($("#totalCosto_" + j).val()) + parseInt(valor_mensual*0.0025);
                     $("#totalCosto_"+j).val(total);
                     dato1=$("#"+j+"_"+i+"_costo").val();
                     total_mes+=parseInt(dato1);
                 }
              var totalContrato= $("#"+j+"_TotalContratoCosto").val();
              $("#"+j+"_TotalContratoCosto").val(parseInt(total_mes));
              agregar_formato(j);
            }
  }
 //alert('esto_es '+a);
    }
    }
});


//Mano de obra
$("#agregar_contrato_obra").click(function(){
    //Separar años y meses del contrato
    var fecha_inicio = $('#fechaInicioObra').val();
    var fecha_inicio_separado = fecha_inicio.split("/");
    var fecha_fin = $('#fechaFinObra').val();
    var fecha_fin_separado = fecha_fin.split("/");
    var contrato=$("#contratoObra").val();
    var valor_contr = $("#valorObra").val();
      if (contrato==0 || contrato === ''){
         mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Contrato es Obligatorio </div>';
         document.getElementById("mensaje").innerHTML = mierror;
        }
     else if(fecha_inicio === ''){
           mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Fecha de Inicio es obligatorio</div>';
           document.getElementById("mensaje").innerHTML = mierror;
           }
    else if(fecha_fin ===''){
        mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Fecha Fin es obligatorio</div>';
        document.getElementById("mensaje").innerHTML = mierror;
        }
    else if(valor_contr ===''){
        mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo valor es obligatorio</div>';
        document.getElementById("mensaje").innerHTML = mierror;
        }

   else {

  //Visualiza información de los contratos creados
    $("#contrato_obra").show();
    $("#ContratoObra").append('<tr  id="obra_'+contrato+'">'+
                '<td><input type="text" readonly="readonly"  class="input-small input-disabled" name="contratos_obra[]" id="contrato_obra'+contrato +'" value='+contrato+'></td>'+
                '<td><input type="text" readonly="readonly" name="fecha_inicio_obra[]" id="fecha_inicio_obra'+contrato +'" value='+fecha_inicio+'></td>'+
                '<td><input type="text" readonly="readonly" name="fecha_fin_obra[]" id="fecha_fin_obra'+contrato +'" value='+fecha_fin+'></td>'+
                '<td><input type="text" readonly="readonly" name="valor_contr_obra[]" id="valor_contr_obra'+contrato +'" value='+valor_contr+'></td>'+
               '<td><input type="button" id="eliminar" class="btn btn-success"  name="Eliminar" value="Eliminar" onclick='+ 'eliminar_cont_obra('+contrato+')'+' ></td>'+
                '</tr>');

    //fechas de reccorrido
    dia_inicio = parseInt(fecha_inicio_separado[0]);
    mes_inicio = parseInt(fecha_inicio_separado[1]);
    anno_inicio = parseInt(fecha_inicio_separado[2]);
    dia_fin = parseInt(fecha_fin_separado[0]);
    mes_fin = parseInt(fecha_fin_separado[1]);
    anno_fin = parseInt(fecha_fin_separado[2]);

    if((anno_fin - anno_inicio) < 0){
      alert('Ha ingresado una fecha final con un año menor al año inicial');
    }
    if((anno_fin - anno_inicio) == 0){
      if((mes_fin - mes_inicio) == 0){
        if((dia_fin - dia_inicio) < 31){
         // alert('mismo_mes');
          eliminar_formato_obra(anno_inicio);
          valor_contrato = $("#valorObra").val();
          valor_mensual = valor_contrato;
          previo_total = parseInt($("#totalObra_"+anno_inicio).val());
          previo_mes = parseInt($("#"+anno_inicio+"_"+mes_inicio+"_obra").val());
          $("#"+anno_inicio+"_"+mes_inicio+"_obra").val(parseInt(valor_mensual) + previo_mes);
          $("#"+anno_inicio+"_"+mes_inicio+"_dataObra").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.01));
          total = parseInt($("#totalObra_"+anno_inicio).val()) + parseInt(valor_mensual*0.01);
          $("#totalObra_"+anno_inicio).val(total);
          var totalContrato= $("#"+anno_inicio+"_TotalContratoObra").val();
          $("#"+anno_inicio+"_TotalContratoObra").val(parseInt(valor_contrato));
          agregar_formato_obra(anno_inicio);
            }
      }
      if((mes_fin - mes_inicio) > 0){
      //  alert('diferente_mes')
        var valor_contrato = $("#valorObra").val();
        valor_mensual = valor_contrato/((mes_fin - mes_inicio)+1)

       eliminar_formato_obra(anno_inicio);
        for (i = mes_inicio; i <= mes_fin; i++){
          //alert(i);
          previo_total = parseInt($("#totalObra_"+anno_inicio).val());
          previo_mes = parseInt($("#"+anno_inicio+"_"+i+"_obra").val());
          $("#"+anno_inicio+"_"+i+"_obra").val(parseInt(valor_mensual) + previo_mes);
          $("#"+anno_inicio+"_"+i+"_dataObra").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.01));
          $("#totalObra_"+anno_inicio).val(parseInt( previo_total+(valor_mensual*0.01)));


        }
          var totalContrato= $("#"+anno_inicio+"_TotalContratoObra").val();
        $("#"+anno_inicio+"_TotalContratoObra").val(parseInt(valor_contrato));
        agregar_formato_obra(anno_inicio);
        }
      if((mes_fin - mes_inicio) < 0){
          alert('Ha ingresado una fecha final con un mes menor al mes inicial');
      }
    }
    if((anno_fin - anno_inicio) > 0 ){
          var valor_contrato = $("#valorObra").val();
          var meses_inicio = 0;
          var meses_fin = 0;
          var otros_meses=0;
      //alert('diferente_año');
     //Calculo la cantidad de meses
      for (j = anno_inicio; j <= anno_fin; j++){
        if (j == anno_inicio){//primer año
            for (i = mes_inicio; i <= 12; i++){
                meses_inicio = meses_inicio + 1;
            }
        }
        else if (j == anno_fin){//ultimo año
             for (i = 1; i <= mes_fin; i++){
              meses_fin = meses_fin + 1;
          }
        }
        else{//otros años
          for (i = 1; i <= 12; i++){
              otros_meses = otros_meses+1;
                }
      }
    }
     var cantidad_meses=(parseInt(meses_inicio)+parseInt(meses_fin)+parseInt(otros_meses));
     valor_mensual = valor_contrato/cantidad_meses;
     previo_total = parseInt($("#totalObra_"+anno_inicio).val());
     //cantidad de años intermedios
     cantidad_annos=otros_meses/12;
     // alert( cantidad_annos);
        for (j = anno_inicio; j <= anno_fin; j++){
        if (j == anno_inicio){//primer año
            var total_mes=0;
            eliminar_formato_obra(j);
            for (i = mes_inicio; i <= 12; i++){
                previo_mes = parseInt($("#"+anno_inicio+"_"+i+"_obra").val());
                $("#"+anno_inicio+"_"+i+"_obra").val(parseInt(valor_mensual) + previo_mes);
                $("#"+anno_inicio+"_"+i+"_dataObra").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.01));
                total = parseInt($("#totalObra_" + anno_inicio).val()) + parseInt(valor_mensual*0.01);
                $("#totalObra_"+anno_inicio).val(total);
                 dato1=$("#"+anno_inicio+"_"+i+"_obra").val();
                 total_mes+=parseInt(dato1);
            }
                var totalContrato= $("#"+anno_inicio+"_TotalContratoObra").val();
                $("#"+anno_inicio+"_TotalContratoObra").val(parseInt(total_mes));
                agregar_formato_obra(j);
            }



           else if (j == anno_fin){//ultimo año
               var total_mes=0;
               eliminar_formato_obra(j);
             for (i = 1; i <= mes_fin; i++){
                    previo_mes = parseInt($("#"+anno_fin+"_"+i+"_obra").val());
                    $("#"+anno_fin+"_"+i+"_obra").val(parseInt(valor_mensual) + previo_mes);
                    $("#"+anno_fin+"_"+i+"_dataObra").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.01));
                    total = parseInt($("#totalObra_" + anno_fin).val()) + parseInt(valor_mensual*0.01);
                    $("#totalCosto_"+anno_fin).val(total);
                    dato1=$("#"+anno_fin+"_"+i+"_obra").val();
                    total_mes+=parseInt(dato1);
          }
                 var totalContrato= $("#"+anno_fin+"_TotalContratoObra").val();
                 $("#"+anno_fin+"_TotalContratoObra").val(parseInt(total_mes));
                 agregar_formato_obra(j);
            }
              else  if ((j!=anno_inicio) && (j!=anno_fin)){//otros años
                var annos_intermedios = anno_inicio;
                var total_mes=0;
                eliminar_formato_obra(j);
                for(i =1; i<=12; i++)
                 {
                     previo_mes = parseInt($("#"+j+"_"+i+"_obra").val());
                     $("#"+j+"_"+i+"_obra").val((parseInt(previo_mes))+(parseInt(valor_mensual)));
                     var dat= $("#"+j+"_"+i+"_obra").val();
                     $("#"+j+"_"+i+"_dataObra").val(parseInt((parseInt(valor_mensual) + previo_mes)*0.01));
                     total = parseInt($("#totalObra_" + j).val()) + parseInt(valor_mensual*0.01);
                     $("#totalObra_"+j).val(total);
                     dato1=$("#"+j+"_"+i+"_obra").val();
                     total_mes+=parseInt(dato1);
                 }
                 var totalContrato= $("#"+j+"_TotalContratoObra").val();
                 $("#"+j+"_TotalContratoObra").val(parseInt(total_mes));
                 agregar_formato_obra(j);
            }
  }
    }
        }
  });
});


     function eliminar_cont_obra(contrato)
            {
            //valores
            var contr_fecha_inicio = $('#fecha_inicio_obra'+ contrato).val();
            var fecha_inicio_sep = contr_fecha_inicio.split("/");
            var contr_fecha_fin = $('#fecha_fin_obra'+contrato).val();
            var fecha_fin_sep = contr_fecha_fin.split("/");
            var valor_contr = $("#valor_contr_obra"+contrato).val();
            //fechas de reccorrido
            var contr_dia_inicio = parseInt(fecha_inicio_sep[0]);
            var contr_mes_inicio = parseInt(fecha_inicio_sep[1]);
            var contr_anno_inicio = parseInt(fecha_inicio_sep[2]);
            var contr_dia_fin = parseInt(fecha_fin_sep[0]);
            var contr_mes_fin = parseInt(fecha_fin_sep[1]);
            var contr_anno_fin = parseInt(fecha_fin_sep[2]);
    //cuando cotrato corresponde al  mismo año y el mismo mes
    if((contr_anno_fin - contr_anno_inicio) == 0){
         //cuando el contrato es del mismo mes
      if((contr_mes_fin - contr_mes_inicio) == 0){
        if((contr_dia_fin - contr_dia_inicio) < 31){
            eliminar_formato_obra(contr_anno_inicio);
          var contr_valor_mensual = valor_contr;
          var contr_previo_total   = parseInt($("#totalObra_"+contr_anno_inicio).val());
          var contr_previo_mes     = parseInt($("#"+contr_anno_inicio+"_"+contr_mes_inicio+"_obra").val());
          var valor_restar         = parseInt((parseInt(contr_valor_mensual))*0.01);
          var contr_datacosto      = parseInt($("#"+contr_anno_inicio + "_" + contr_mes_inicio + "_dataObra").val());
          $("#"+contr_anno_inicio + "_" + contr_mes_inicio+"_dataObra").val(parseInt(parseInt(contr_previo_total)-valor_restar));
          //resto el valor del previo mensual
          $("#"+contr_anno_inicio+"_"+contr_mes_inicio+"_obra").val(parseInt( parseInt(contr_previo_mes) - contr_valor_mensual));
          //resto el valor del previo_total
          $("#totalObra_"+contr_anno_inicio).val(parseInt(parseInt(contr_previo_total) - valor_restar));
          var totalContrato= $("#"+anno_inicio+"_TotalContratoObra").val();
          $("#"+anno_inicio+"_TotalContratoObra").val(parseInt(parseInt(totalContrato)-parseInt(valor_contr)));
          agregar_formato_obra(contr_anno_inicio);
                }
      }
        //cuando el contrato corresponde a varios meses del mismo año
       else if((contr_mes_fin - contr_mes_inicio) > 0){
       // alert('diferente_mes')
        var contr_valor_mensual =valor_contr /((contr_mes_fin - contr_mes_inicio)+1)
        total_datacosto=0;
        eliminar_formato_obra(contr_anno_inicio);
        for (i = contr_mes_inicio; i <= contr_mes_fin; i++){
            //actualizo el costo
           contr_previo_mes = parseInt($("#"+contr_anno_inicio+"_"+i+"_obra").val());
           $("#"+contr_anno_inicio+"_"+i+"_obra").val(parseInt(contr_previo_mes-contr_valor_mensual));
          //actualizo el dato costo de acuerdo al previo mes
           var contr_previo_total = parseInt($("#totalObra_"+contr_anno_inicio).val());
           var contr_previo_mes_act = parseInt($("#"+contr_anno_inicio+"_"+i+"_obra").val());
          //actualizo el campo dataCosto
           $("#"+contr_anno_inicio+"_"+i+"_dataObra").val(parseInt((parseInt(contr_previo_mes_act)*0.01)));
           //actualizo el campo totalcosto
           contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataObra").val();
           total_datacosto+= parseInt(contr_datacosto);
           }
        $("#totalObra_" + contr_anno_inicio).val(total_datacosto);
        var totalContrato= $("#"+anno_inicio+"_TotalContratoObra").val();
        $("#"+anno_inicio+"_TotalContratoObra").val(parseInt(parseInt(totalContrato)-parseInt(valor_contr)));
        agregar_formato_obra(contr_anno_inicio);
            }
     }
    //Cuando el contrato es de varios años
    else if((contr_anno_fin - contr_anno_inicio) > 0 ){
        var meses=calcularmeses(contr_anno_inicio,contr_anno_fin,contr_mes_inicio,contr_mes_fin);
        var cantidad_meses=meses[0]+meses[1]+meses[2];
        var otros_meses=meses[2];
        contr_valor_mensual =valor_contr /cantidad_meses;
        contr_previo_total = parseInt($("#totalObra_"+contr_anno_inicio).val());
        var contr_datacosto=0;
        var total_datacosto=0;
      //cantidad de años intermedios
        var contr_cantidad_annos=otros_meses/12;
     // alert( cantidad_annos);

        for (j = contr_anno_inicio; j <= contr_anno_fin; j++){
        if (j == contr_anno_inicio){//primer año
            var total_mes_cont=0;
            eliminar_formato_obra(j);
            for (i = contr_mes_inicio; i <= 12; i++){
                //obtengo el valor del previo mes
                 contr_previo_mes = parseInt($("#"+contr_anno_inicio+"_"+i+"_obra").val());
                 $("#"+contr_anno_inicio+"_"+i+"_obra").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                 var contr_previo_mes_act = parseInt($("#"+contr_anno_inicio+"_"+i+"_obra").val());
                 //actualizo el campo dataCosto

                 $("#"+contr_anno_inicio+"_"+i+"_dataObra").val(parseInt((parseInt(contr_previo_mes_act)*0.01)));
                 //actualizo el campo totalcosto
                 total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes
                 contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataObra").val();
                 total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));
             }
              $("#totalObra_" + contr_anno_inicio).val(total_datacosto);
              //Actualiza el total del valor del contrato
                 var totalContrato= $("#"+j+"_TotalContratoObra").val();
                 var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                $("#"+j+"_TotalContratoObra").val(parseInt(valor_final_cont));
                agregar_formato_obra(j);
        }
       if ((j!=contr_anno_inicio) && (j!=contr_anno_fin)){//otros años
                var total_mes_cont=0;
                eliminar_formato_obra(j);
                for(i =1; i<=12; i++)
                {
                    contr_previo_mes = parseInt($("#"+j+"_"+i+"_obra").val());
                    $("#"+j+"_"+i+"_obra").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                    contr_previo_mes_act=parseInt($("#"+j+"_"+i+"_obra").val());
                    $("#"+j+"_"+i+"_dataObra").val(parseInt((parseInt(contr_previo_mes_act))*0.01));
                 //actualizo el campo totalcosto
                    contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataObra").val();
                    total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));
                    total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes

                }
                    $("#totalObra_"+j).val(total_datacosto);
                    //Actualiza el total del valor del contrato
                    var totalContrato= $("#"+j+"_TotalContratoObra").val();
                    var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                    $("#"+j+"_TotalContratoObra").val(parseInt(valor_final_cont));
                    agregar_formato_obra(j);

      }
      if (j == contr_anno_fin){//ultimo año
            var total_mes_cont=0;
            eliminar_formato_obra(j);
             for (i = 1; i <= mes_fin; i++){
                    contr_previo_mes = parseInt($("#"+contr_anno_fin+"_"+i+"_obra").val());
                    $("#"+contr_anno_fin+"_"+i+"_obra").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                    contr_previo_mes_act=parseInt($("#"+contr_anno_fin+"_"+i+"_obra").val());
                    $("#"+contr_anno_fin+"_"+i+"_dataObra").val(parseInt((parseInt(contr_previo_mes_act))*0.01));
                    //actualizo el campo totalcosto
                    contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataObra").val();
                    total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));
                    total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes

          }
                    $("#totalObra_"+contr_anno_fin).val(total_datacosto);
                    //Actualiza el total del valor del contrato
                    var totalContrato= $("#"+j+"_TotalContratoObra").val();
                    var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                    $("#"+j+"_TotalContratoObra").val(parseInt(valor_final_cont));
                    agregar_formato_obra(j);

        }

        }
    }
     //elimina la fila con la información del contrato
 $("#obra_"+contrato).remove();
}

    function eliminar_cont(contrato)
            {
            var contr_fecha_inicio = $('#fecha_inicio_costo'+ contrato).val();
            var fecha_inicio_sep = contr_fecha_inicio.split("/");
            var contr_fecha_fin = $('#fecha_fin_costo'+contrato).val();
            var fecha_fin_sep = contr_fecha_fin.split("/");
            var valor_contr = $("#valor_contr_costo"+contrato).val();
           //fechas de reccorrido
            var contr_dia_inicio = parseInt(fecha_inicio_sep[0]);
            var contr_mes_inicio = parseInt(fecha_inicio_sep[1]);
            var contr_anno_inicio = parseInt(fecha_inicio_sep[2]);
            var contr_dia_fin = parseInt(fecha_fin_sep[0]);
            var contr_mes_fin = parseInt(fecha_fin_sep[1]);
            var contr_anno_fin = parseInt(fecha_fin_sep[2]);
    //cuando cotrato corresponde al  mismo año y el mismo mes
    if((contr_anno_fin - contr_anno_inicio) == 0){
         //cuando el contrato es del mismo mes
      if((contr_mes_fin - contr_mes_inicio) == 0){
        if((contr_dia_fin - contr_dia_inicio) < 31){
            eliminar_formato(contr_anno_inicio);
          var contr_valor_mensual = valor_contr;
          var contr_previo_total   = parseInt($("#totalCosto_"+contr_anno_inicio).val());
          var contr_previo_mes     = parseInt($("#"+contr_anno_inicio+"_"+contr_mes_inicio+"_costo").val());
          var valor_restar         = parseInt((parseInt(contr_valor_mensual))*0.0025);
          var contr_datacosto      = parseInt($("#"+contr_anno_inicio + "_" + contr_mes_inicio + "_dataCosto").val());
          $("#"+contr_anno_inicio + "_" + contr_mes_inicio+"_dataCosto").val(parseInt(parseInt(contr_previo_total)-valor_restar));
          //resto el valor del previo mensual
          $("#"+contr_anno_inicio+"_"+contr_mes_inicio+"_costo").val(parseInt( parseInt(contr_previo_mes) - contr_valor_mensual));
          //resto el valor del previo_total
          $("#totalCosto_"+contr_anno_inicio).val(parseInt(parseInt(contr_previo_total) - parseInt(valor_restar)));
           var totalContrato= $("#"+anno_inicio+"_TotalContratoCosto").val();
          $("#"+anno_inicio+"_TotalContratoCosto").val(parseInt(parseInt(totalContrato)-parseInt(valor_contr)));
          agregar_formato(contr_anno_inicio);
        }
      }
        //cuando el contrato corresponde a varios meses del mismo año
       else if((contr_mes_fin - contr_mes_inicio) > 0){

        var contr_valor_mensual =valor_contr /((contr_mes_fin - contr_mes_inicio)+1)
        total_datacosto=0;
        eliminar_formato(contr_anno_inicio);
        for (i = contr_mes_inicio; i <= contr_mes_fin; i++){
            //actualizo el costo
           contr_previo_mes = parseInt($("#"+contr_anno_inicio+"_"+i+"_costo").val());
           $("#"+contr_anno_inicio+"_"+i+"_costo").val(parseInt(contr_previo_mes-contr_valor_mensual));
          //actualizo el dato costo de acuerdo al previo mes
           var contr_previo_total = parseInt($("#totalCosto_"+contr_anno_inicio).val());
           var contr_previo_mes_act = parseInt($("#"+contr_anno_inicio+"_"+i+"_costo").val());
          //actualizo el campo dataCosto
           $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val(parseInt((parseInt(contr_previo_mes_act)*0.0025)));
           //actualizo el campo totalcosto
            contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val();
            total_datacosto+=parseInt(contr_datacosto);
           }
        $("#totalCosto_" + contr_anno_inicio).val(total_datacosto);
          var totalContrato= $("#"+anno_inicio+"_TotalContratoCosto").val();
          $("#"+anno_inicio+"_TotalContratoCosto").val(parseInt(parseInt(totalContrato)-parseInt(valor_contr)));
          agregar_formato(contr_anno_inicio);
        }
     }
    //Cuando el contrato es de varios años
    else if((contr_anno_fin - contr_anno_inicio) > 0 ){
          //Calculo los meses
        var meses=calcularmeses(contr_anno_inicio,contr_anno_fin,contr_mes_inicio,contr_mes_fin);
        var cantidad_meses=meses[0]+meses[1]+meses[2];
        var otros_meses=meses[2];
        contr_valor_mensual =valor_contr /cantidad_meses;
        contr_previo_total = parseInt($("#totalCosto_"+contr_anno_inicio).val());
        var contr_datacosto=0;
        var total_datacosto=0;
      //cantidad de años intermedios
        var contr_cantidad_annos=otros_meses/12;
     // alert( cantidad_annos);

        for (j = contr_anno_inicio; j <= contr_anno_fin; j++){
        if (j == contr_anno_inicio){//primer año
             var mes_cont_costo=0;
             var total_mes_cont=0;
             var totalContrato= 0;

             eliminar_formato(j);
            for (i = contr_mes_inicio; i <= 12; i++){
                //obtengo el valor del previo mes
                 contr_previo_mes = parseInt($("#"+contr_anno_inicio+"_"+i+"_costo").val());
                 $("#"+contr_anno_inicio+"_"+i+"_costo").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                 var contr_previo_mes_act = parseInt($("#"+contr_anno_inicio+"_"+i+"_costo").val());
                 //actualizo el campo dataCosto
                 mes_cont_costo= $("#"+contr_anno_inicio+"_"+i+"_costo").val();
                 total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes
                 $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val(parseInt((parseInt(contr_previo_mes_act)*0.0025)));
                 //actualizo el campo totalcosto
                 contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val();
                 total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));
             }
              $("#totalCosto_" + contr_anno_inicio).val(total_datacosto);
              //Actualiza el total del valor del contrato
                 var totalContrato= $("#"+j+"_TotalContratoCosto").val();
                 var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                $("#"+j+"_TotalContratoCosto").val(parseInt(valor_final_cont));
                agregar_formato(j);

        }
       if ((j!=contr_anno_inicio) && (j!=contr_anno_fin)){//otros años
                var mes_cont_costo=0;
                var total_mes_cont=0;
                eliminar_formato(j);
                for(i =1; i<=12; i++)
                {
                    contr_previo_mes = parseInt($("#"+j+"_"+i+"_costo").val());
                    $("#"+j+"_"+i+"_costo").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                    contr_previo_mes_act=parseInt($("#"+j+"_"+i+"_costo").val());
                    $("#"+j+"_"+i+"_dataCosto").val(parseInt((parseInt(contr_previo_mes_act))*0.0025));
                    total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes
                 //actualizo el campo totalcosto
                    contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val();
                    total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));
                    mes_cont_costo= $("#"+contr_anno_inicio+"_"+i+"_costo").val();
                }
                $("#totalCosto_"+j).val(total_datacosto);
               //Actualiza el total del valor del contrato
                var totalContrato= $("#"+j+"_TotalContratoCosto").val();
                var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                $("#"+j+"_TotalContratoCosto").val(parseInt(valor_final_cont));
                agregar_formato(j);

      }
      if (j == contr_anno_fin){//ultimo año
            var total_mes_cont=0;
            eliminar_formato(j);
             for (i = 1; i <= mes_fin; i++){
                    contr_previo_mes = parseInt($("#"+contr_anno_fin+"_"+i+"_costo").val());
                    $("#"+contr_anno_fin+"_"+i+"_costo").val(parseInt(contr_previo_mes)-parseInt(contr_valor_mensual));
                    contr_previo_mes_act=parseInt($("#"+contr_anno_fin+"_"+i+"_costo").val());
                    $("#"+contr_anno_fin+"_"+i+"_dataCosto").val(parseInt((parseInt(contr_previo_mes_act))*0.0025));
                    total_mes_cont+=parseInt(contr_valor_mensual);//Acumula los valores que se eliminan mes a mes
                    //actualizo el campo totalcosto
                    contr_datacosto=  $("#"+contr_anno_inicio+"_"+i+"_dataCosto").val();
                    total_datacosto=(parseInt(total_datacosto)+ parseInt(contr_datacosto));

          }
           $("#totalCosto_"+contr_anno_fin).val(total_datacosto);
            //Actualiza el total del valor del contrato
                 var totalContrato= $("#"+j+"_TotalContratoCosto").val();
                 var valor_final_cont=parseInt(totalContrato)-parseInt(total_mes_cont);
                 $("#"+j+"_TotalContratoCosto").val(parseInt(valor_final_cont));
                 agregar_formato(j);
        }
        }
    }
//elimina la fila con la información del contrato
        $("#costo_"+contrato).remove();
        }

$(document).ready(function() {

    //Inhabilita los meses que no se fiscalizan para el primer y ultimo año
    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var dia_inicial = parseInt(fecha_inicial_separado[0]);
    var mes_inicial= parseInt(fecha_inicial_separado[1]);
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var dia_final = parseInt(fecha_final_separado[0]);
    var mes_final = parseInt(fecha_final_separado[1]);
    var anno_final = parseInt(fecha_final_separado[2]);
    var meses = calcularmeses(anno_inicial, anno_final, mes_inicial,mes_final);
   //meses corresponde a un arreglo que contiene la información del contrato del primer año, de los años intermedios y del ultimo año si los hay.

    var meses_inicio =meses[0];
    var meses_final =meses[1];

    meses_final=meses_final+1;
    for (j = anno_inicial; j <= anno_final; j++){
         if((anno_final - anno_inicial) == 0){
                 if((mes_final - mes_inicial) == 0)
              {
                     for(i=1;i<=12;i++){
                        if(i!=mes_inicial){
                               $("#"+anno_inicial+"_"+i).prop('readonly', true);
                        }
                    }
                }
               else{
                   for(i=1;i<mes_inicial;i++){
                        $("#"+anno_inicial+"_"+i).prop('readonly', true);
                   }
                   for(i=(mes_final+1);i<=12;i++){
                        $("#"+anno_inicial+"_"+i).prop('readonly', true);
                   }
               }
         }

         else if ((anno_final  - anno_inicial) > 0){

          if (j == anno_inicial){//primer año

             var mes_ini=12-meses_inicio;
              for (i=1; i<=mes_ini; i++){
                   $("#"+anno_inicial+"_"+i).prop('readonly', true);
                    }
          }
          else if (j == anno_final){//ultimo año

                for (i = meses_final; i<=12 ; i++){
                   $("#"+anno_final+"_"+i).prop('readonly', true);
            }
          }

      }}
});

   function calcularmeses(contr_anno_inicio,contr_anno_fin,contr_mes_inicio,contr_mes_fin)
   {
          var meses_inicio = 0;
          var meses_fin = 0;
          var otros_meses = 0;
     for (j = contr_anno_inicio; j <= contr_anno_fin; j++){
          if (j == contr_anno_inicio){//primer año
              for (i = contr_mes_inicio; i <= 12; i++){
                  meses_inicio = meses_inicio + 1;
                    }
          }
          else if (j == contr_anno_fin){//ultimo año
                for (i = 1; i <= contr_mes_fin; i++){
                meses_fin = meses_fin + 1;
            }
          }
          else{//otros años
               for (i = 1; i <= 12; i++){
                otros_meses = otros_meses+1;
                  }
        }
      }
      var meses = new Array(meses_inicio,meses_fin,otros_meses);
      return meses;
   }



</script>
<!-- Fin función datepicker -->

<!-- Función solo números y calculos liquidación -->
<script type="text/javascript" language="javascript" charset="utf-8">

  function soloNumeros(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key);//.toLowerCase();
    letras = '0123456789';//" áéíóúabcdefghijklmnñopqrstuvwxyz";
    especiales = [8, 37, 39, 46];

    tecla_especial = false;
    for(var i in especiales) {
      if(key == especiales[i]) {
        tecla_especial = true;
        break;
      }
    }
    if(letras.indexOf(tecla) == -1 && !tecla_especial)
      return false;
  }


function calcularValor(c) {

    var empleados = c.value.replace(/\./g,'');;
    var identificador = c.id;
    var identificador_for = identificador.split('_');
    var anno_name = identificador_for[0];
    var smlv = document.getElementById(anno_name).value;
    var factorSmlv = smlv/40;
    var factor = empleados * factorSmlv;
    document.getElementById(identificador+'_data').value = factor.toFixed(0);
    var acumulador, total = 0;
    for (var i = 1; i <=12; i++)
    {
      acumulador = document.getElementById(identificador_for[0]+'_'+i+'_data').value;
      total = total + parseInt(acumulador);
      total.toFixed(0);
    }
    total=formato(total);
    document.getElementById('total_'+identificador_for[0]).value = total;
    var data=  document.getElementById(identificador+'_data').value ;
    data=formato(data);
    document.getElementById(identificador+'_data').value=data;
  }




  function agregar_formato(inicio)
  { //alert('agrega formato');
    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var anno_final = parseInt(fecha_final_separado[2]);

    //Agrego Formato para todos los campos
      for(a= anno_inicial; a<=anno_final; a++)

          {    if(a==inicio){
                d3=$("#"+"totalCosto_"+a).val();
                //d4=$("#"+a+"_TotalContratoCosto").val();
                d5=$("#"+a+"_val_costo").val();
              //  alert('agrego_formato'+d3);
              if( d3!=0 || d5!=0){
                d3=formato(d3);
              //  d4=formato(d4);
                d5=formato(d5);
                $("#totalCosto_"+a).val(d3);
              //  $("#"+a+"_TotalContratoCosto").val(d4);
                $("#"+a+"_val_costo").val(d5);
            }
                for(b=1; b<=12; b++ )
                     {
                        var d1=$("#"+a+"_"+b+"_costo").val();
                        var d2=$("#"+a+"_"+b+"_dataCosto").val();
                        if(d1!=0 || d2!=0){
                           // alert('dato'+d1);
                        d1=formato(d1);
                        d2=formato(d2);
//                         alert('año'+a+'mes'+b);
//                        alert('dato'+d1);

                        $("#"+a+"_"+b+"_costo").val(d1);
                        $("#"+a+"_"+b+"_dataCosto").val(d2);
                    }

             }
          }
      }
  }
  function eliminar_formato(inicio)
  {

    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var dia_inicial = parseInt(fecha_inicial_separado[0]);
    var mes_inicial= parseInt(fecha_inicial_separado[1]);
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var dia_final = parseInt(fecha_final_separado[0]);
    var mes_final = parseInt(fecha_final_separado[1]);
    var anno_final = parseInt(fecha_final_separado[2]);

      for(a= anno_inicial; a<=anno_final; a++)
          {   if(a==inicio){
               d3=$("#"+"totalCosto_"+a).val();
           //    d4=$("#"+a+"_TotalContratoCosto").val();
               d5=$("#"+a+"_val_costo").val();
               d3=retira_formato(d3);
              // d4=retira_formato(d4);
               d5=retira_formato(d5);
               $("#totalCosto_"+a).val(parseInt(d3));
           //    $("#"+a+"_TotalContratoCosto").val(parseInt(d4));
               $("#"+a+"_val_costo").val(parseInt(d5));
               for(b=1; b<=12; b++ )
             {
                d1=$("#"+a+"_"+b+"_costo").val();
                d2=$("#"+a+"_"+b+"_dataCosto").val();
                d1=retira_formato(d1);
                d2=retira_formato(d2);
                $("#"+a+"_"+b+"_costo").val(parseInt(d1));
                $("#"+a+"_"+b+"_dataCosto").val(parseInt(d2));
             }
            }
        }
       }

   function agregar_formato_obra(inicio)
  {

    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var dia_inicial = parseInt(fecha_inicial_separado[0]);
    var mes_inicial= parseInt(fecha_inicial_separado[1]);
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var dia_final = parseInt(fecha_final_separado[0]);
    var mes_final = parseInt(fecha_final_separado[1]);
    var anno_final = parseInt(fecha_final_separado[2]);

    //Agrego Formato para todos los campos
      for(a= anno_inicial; a<=anno_final; a++)

          {    if(a==inicio){
                d3=$("#"+"totalObra_"+a).val();
                //d4=$("#"+a+"_TotalContratoCosto").val();
                d5=$("#"+a+"_val_obra").val();

              if( d3!=0  || d5!=0){
                d3=formato(d3);
               // d4=formato(d4);
                d5=formato(d5);

                $("#totalObra_"+a).val(d3);
              //  $("#"+a+"_TotalContratoCosto").val(d4);
                $("#"+a+"_val_obra").val(d5);
            }
                for(b=1; b<=12; b++ )
                     {
                        var d1=$("#"+a+"_"+b+"_obra").val();
                        var d2=$("#"+a+"_"+b+"_dataObra").val();
                        if(d1!=0 || d2!=0){
                           // alert('dato'+d1);
                        d1=formato(d1);
                        d2=formato(d2);
//                         alert('año'+a+'mes'+b);
//                        alert('dato'+d1);

                        $("#"+a+"_"+b+"_obra").val(d1);
                        $("#"+a+"_"+b+"_dataObra").val(d2);
                    }

             }
          }
      }
  }
  function eliminar_formato_obra(inicio)
  {

    var fecha_inicial = "<?php echo $codigoGestion['PERIODO_INICIAL']; ?>";
    var fecha_inicial_separado = fecha_inicial.split("/");
    var fecha_final ="<?php echo $codigoGestion['PERIODO_FINAL'] ?>";
    var fecha_final_separado = fecha_final.split("/");
    var dia_inicial = parseInt(fecha_inicial_separado[0]);
    var mes_inicial= parseInt(fecha_inicial_separado[1]);
    var anno_inicial = parseInt(fecha_inicial_separado[2]);
    var dia_final = parseInt(fecha_final_separado[0]);
    var mes_final = parseInt(fecha_final_separado[1]);
    var anno_final = parseInt(fecha_final_separado[2]);

      for(a= anno_inicial; a<=anno_final; a++)
          {   if(a==inicio){
                //alert('año_inicio_2'+inicio);
               d3=$("#"+"totalObra_"+a).val();
              // d4=$("#"+a+"_TotalContratoObra").val();
               d5=$("#"+a+"_val_obra").val();
           // alert('quito_formato'+d3);
               d3=retira_formato(d3);
             //  d4=retira_formato(d4);
               d5=retira_formato(d5);
               //alert('sin_formato'+d3);
               $("#totalObra_"+a).val(parseInt(d3));
             //  $("#"+a+"_TotalContratoCosto").val(parseInt(d4));
               $("#"+a+"_val_obra").val(parseInt(d5));

               for(b=1; b<=12; b++ )
             {
                d1=$("#"+a+"_"+b+"_obra").val();
                d2=$("#"+a+"_"+b+"_dataObra").val();
                d1=retira_formato(d1);
                d2=retira_formato(d2);
                $("#"+a+"_"+b+"_obra").val(parseInt(d1));
                $("#"+a+"_"+b+"_dataObra").val(parseInt(d2));
             }
            }
        }
          }

  function formato(valor){

       valor = valor.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
       valor = valor.split('').reverse().join('').replace(/^[\.]/, '');
            return valor;
    }


    function  retira_formato(valor){

    var str = valor;
    var res = str.split("");
    res=res.length;
      if(valor!=0){
        for (m=0; m<=res; m++){
         str=str.replace(/[\.]/, '' );
        }
    }

    return str;
}
  </script>
