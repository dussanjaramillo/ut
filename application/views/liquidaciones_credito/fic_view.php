<?php 
/**
* Formulario para la captura de información para el calculo de liquidaciones por aportes del Fondo de Formación Profesional de la Industria de la Construcción FIC en los procesos administrativos
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/fic_view.php
* @last-modified  06/06/2014
* @copyright	
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed'); 
if(isset($message))
{
  echo $message;
}
//print_r($codigoGestion);
?>
<!-- Estilos personalizados -->
<style type="text/css"> 
#cabecera table, #datos_generales table{width: 100%; height: auto; margin: 10px;}
#cabecera td, #datos_generales td{padding: 5px; text-align: left; vertical-align: middle;}
#datos_generales{border:1px solid #CFCFCF;}
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
        <td><b>Razón Social : </b><?php echo $codigoGestion['NOMBRE_EMPRESA'] ?></td>
        <td><b>NIT : </b><?php echo $codigoGestion['NIT_EMPRESA'] ?></td>
        <td><b>Regional : </b><?php echo $codigoGestion['NOMBRE_REGIONAL'] ?></td>
      </tr>
      <tr>
        <td><b>Periodo Inicial : </b><?php echo $codigoGestion['PERIODO_INICIAL'] ?></td>
        <td><b>Periodo Fin : </b><?php echo $codigoGestion['PERIODO_FINAL'] ?></td>
        <td>
          <?php 
            echo form_label('<b>Fecha de Liquidación :&nbsp;</b>','fechaLiquidacion');  
            $atributos_fechaLiquidacion = array ('type' => 'date', 'name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'value' => $fecha, 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
            echo form_input($atributos_fechaLiquidacion);
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
            $atributos_valorInversion = array ('type' => 'number', 'name' => 'valorInversion', 'id' => 'valorInversion', 'value' => set_value('valorInversion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_valorInversion);
            echo '<span class="add-on">.00</span>';
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php 
            echo form_label('<b>Gastos de Financiación :&nbsp;</b>','gastosFinanciacion');  
            echo '<span class="add-on">$</span>';
            $atributos_gastosFinanciacion = array ('type' => 'number', 'name' => 'gastosFinanciacion', 'id' => 'gastosFinanciacion', 'value' => set_value('gastosFinanciacion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_gastosFinanciacion);
            echo ' <span class="add-on">.00</span>';  
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php 
            echo form_label('<b>Impuestos Legales :&nbsp;</b>','impuestosLegales');  
            echo '<span class="add-on">$</span>';
            $atributos_impuestosLegales = array ('type' => 'number', 'name' => 'impuestosLegales', 'id' => 'impuestosLegales', 'value' => set_value('impuestosLegales','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_impuestosLegales);
            echo '<span class="add-on">.00</span>';
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
            $atributos_valorLote = array ('type' => 'number', 'name' => 'valorLote', 'id' => 'valorLote', 'value' => set_value('valorLote','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_valorLote);
            echo '<span class="add-on">.00</span>';
          ?>
          </div>
        </td>
        <td>
          <div class="input-prepend input-append">
          <?php 
            echo form_label('<b>Indenmización a Terceros :&nbsp;</b>','indenmizacion');  
            echo '<span class="add-on">$</span>';
            $atributos_indenmizacion = array ('type' => 'number', 'name' => 'indenmizacion', 'id' => 'indenmizacion', 'value' => set_value('indenmizacion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
            echo form_input($atributos_indenmizacion);
            echo '<span class="add-on">.00</span>';
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
      <!--Inicio de la Liquidación Normativa -->
      <div class="tab-pane active" id="normativa">
        <h3>Número de Trabajadores</h3>
        <p>Salario Mínimo Legal Vigente:  <?php echo $smlv; ?></p>
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
              for($i = 1; $i <= 12; $i++):
                echo '<td>';
                $atributos_data = array ('type' => 'number', 'name' => $year.'_'.$i, 'id' => $year.'_'.$i, 'value' => set_value($i,'0'), 'class' => 'input-small', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);', 'onchange' => 'calcularValor(this);');
                echo form_input($atributos_data);
                echo '</td>';
              endfor;
            ?>
          </tr>
          <tr>
            <td></td>
            <?php
              for($i = 1; $i <= 12; $i++):
                echo '<td><div class="input-prepend input-append"><span class="add-on">$</span>';
                $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_data', 'id' => $year.'_'.$i.'_data', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                echo form_input($atributos_data);
                echo '<span class="add-on">.00</span></td>';
              endfor;
            ?>
            <td>
              <div class="input-prepend input-append"><span class="add-on">$</span>
              <?php
                $atributos_data = array ('type' => 'text', 'name' => 'total_'.$year, 'id' => 'total_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                echo form_input($atributos_data);
              ?>
              <span class="add-on">.00</span>
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
              <table class="table">
                <tr>
                  <td><label for="contrato"><b>Contrato:</b></label></td>
                  <td>
                    <?php 
                    $atributos_contrato = array ('type' => 'number', 'name' => 'contrato', 'id' => 'contrato', 'value' => set_value('contrato','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
                    echo form_input($atributos_contrato);
                    ?>
                  </td>
                  <td><label for="fechaInicio">Inicio:</label></td>
                  <td>
                    <?php
                    $atributos_fechaInicio = array ('type' => 'text', 'name' => 'fechaInicio', 'id' => 'fechaInicio', 'value' => set_value('fechaInicio'), 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
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
                    <div class="input-prepend input-append">
                      <?php
                      echo '<span class="add-on">$</span>';
                      $atributos_valor = array ('type' => 'number', 'name' => 'valor', 'id' => 'valor', 'value' => set_value('valor'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this)');
                      echo form_input($atributos_valor);
                      echo '<span class="add-on">.00</span>';
                      ?>
                    </div>
                  </td>
                  <td><a href="#" class="btn btn-success"><i class="icon-white icon-plus"></i> Agregar Contrato</a></td>
                </tr>
              </table>
              <br><br>
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
                  for($i = 1; $i <= 12; $i++):
                    echo '<td><div class="input-prepend input-append"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_costo', 'id' => $year.'_'.$i.'_costo', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '<span class="add-on">.00</span></td>';
                  endfor;
                ?>
              </tr>
              <tr>
                <td></td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td><div class="input-prepend input-append"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_dataCosto', 'id' => $year.'_'.$i.'_dataCosto', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '<span class="add-on">.00</span></td>';
                  endfor;
                ?>
                <td>
                  <?php
                    $atributos_data = array ('type' => 'text', 'name' => 'totalCosto_'.$year, 'id' => 'totalCosto_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                  ?>
                </td>
              </tr>
              <?php
              endfor;
              ?>
            </table>
          </div>
          <!--Fin Liquidación Presuntiva a todo costo -->
          <div class="tab-pane" id="obra">
            <h3>Contrato Mano de Obra</h3>
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
                    <div class="input-prepend input-append">
                      <?php
                      echo '<span class="add-on">$</span>';
                      $atributos_valorObra = array ('type' => 'number', 'name' => 'valorObra', 'id' => 'valorObra', 'value' => set_value('valorObra'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15', 'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this)');
                      echo form_input($atributos_valorObra);
                      echo '<span class="add-on">.00</span>';
                      ?>
                    </div>
                  </td>
                  <td><a href="#" class="btn btn-success"><i class="icon-white icon-plus"></i> Agregar Contrato</a></td>
                </tr>
              </table>
              <br><br>
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
                  for($i = 1; $i <= 12; $i++):
                    echo '<td><div class="input-prepend input-append"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_obra', 'id' => $year.'_'.$i.'_obra', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '<span class="add-on">.00</span></td>';
                  endfor;
                ?>
              </tr>
              <tr>
                <td></td>
                <?php
                  for($i = 1; $i <= 12; $i++):
                    echo '<td><div class="input-prepend input-append"><span class="add-on">$</span>';
                    $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_dataObra', 'id' => $year.'_'.$i.'_dataObra', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                    echo '<span class="add-on">.00</span></td>';
                  endfor;
                ?>
                <td>
                  <?php
                    $atributos_data = array ('type' => 'text', 'name' => 'totalObra_'.$year, 'id' => 'totalObra_'.$year, 'value' => set_value('total','0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                    echo form_input($atributos_data);
                  ?>
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
  </div>
  <!--Fin de valores específicos para la liquidación -->
</div>
<?php 
  echo form_close(); 
?>  
 
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

  //Lanzador de datepicker para fecha de contrato a todo costo
  $.datepicker.setDefaults($.datepicker.regional['es']);
  $( "#fechaInicio, #fechaFin, #fechaInicioObra, #fechaFinObra" ).datepicker({
    showOn: 'button',
    buttonText: 'Selecciona una fecha',
    buttonImage: '<?php echo base_url() , "img/calendario.png" ?>',
    buttonImageOnly: true,
    numberOfMonths: 1
  });

});
  //Verificador máximos caracteres para datos generales
  /*var max_chars = 15; //cantidad de caracteres máximos
  
  $('#valorInversion, #gastosFinanciacion, #impuestosLegales, #valorLote, #indenmización').keydown(function() 
  {
        var chars = $(this).val().length; //cantidad de caracteres digitados
    
    if (chars > max_chars) //verificar la cantidad de caracteres
      { 
          var data = $(this).val().substring(0, max_chars);
          $('#alertas').html('<p class="text-warning"><i class="fa fa-warning"></i> Los valores no pueden exceder los 15 digitos<p>');
          $(this).val(data);
        }
    else
    { 
      var data = $(this).val().substring(0, max_chars);
      $('#alertas').html('');
          $(this).val(data);
    }
    });*/
/*
var nextinput = 0;
var deleinput = 1;
$(document).ready(function(){  
    //Funcion para adicionar una o varias filas
    $( "#add" ).click(function() {
        $('#save').show();
        nextinput++;
         campo = '<div id="table'+nextinput+'">' 
                +'<b>Numero de Contrato </b><input type="text" size="20" style="width:103px" id="contrato' + nextinput + '"  />'                
                +'<b>Fecha Inicio </b><input type="text" size="20" style="width:103px" readonly="readonly" id="fechainicio' + nextinput + '" />'                
                +'<b>Fecha Fin </b><input type="text" size="20" style="width:103px" readonly="readonly" id="fechafin' + nextinput + '"  />'
                +'<b>Valor </b><input type="text" size="20" style="width:103px" id="valor' + nextinput + '"  />'
                +'<input type="hidden" style="width:20px" id="total_anio' + nextinput + '"  />'
                +'<input type="hidden" style="width:20px" id="total_mes' + nextinput + '"  />'
                +'<input type="hidden" style="width:20px" id="total_day' + nextinput + '"  />'
                +'<button class="btn btn-success" style="width:40px; height:20px; align:top" title="Incluir" value="'+nextinput+'" id="incluir'+nextinput+'" onclick="javascript:valida(\''+nextinput+'\');"><i class="fa fa-plus-square"></i></button>'
                +'<button class="btn btn-success" style="width:40px; height:20px; align:top" title="Eliminar" value="'+nextinput+'" id="delete'+nextinput+'" onclick="javascript:rowDelete(\''+nextinput+'\');"><i class="fa fa-minus-square"></i></button>'
                +'</div>'
            $("#campos").append(campo);
            $("#gItcant").val(nextinput);
            
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
                  $("#fechainicio"+nextinput).datepicker({
                    showOn: 'button',
                    buttonText: 'Selecciona una fecha',
                    buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
                    buttonImageOnly: true,
                    numberOfMonths: 1
                    //maxDate: '29d',
                    //minDate: '0d'
                  });
                  $("#fechafin"+nextinput).datepicker({
                    showOn: 'button',
                    buttonText: 'Selecciona una fecha',
                    buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
                    buttonImageOnly: true,
                    numberOfMonths: 1
                    //maxDate: '29d',
                    //minDate: '0d'
                  });
    });                   
    
});     
//funcion para cambiar color del boton normativa
$("#normativa").click(function() {
    $("#normativa").attr('class','btn btn-warning'); 
    $("#presuntiva").attr('class','btn btn-success');
    $("#obra").attr('class','btn btn-success');
    $("#costo").attr('class','btn btn-success');
    $("#tableInfo").show();
    $("#secundarios").hide();
});

//funcion para cambiar color del boton presuntiva
$("#presuntiva").click(function() {
    $("#presuntiva").attr('class','btn btn-warning');
    $("#normativa").attr('class','btn btn-success'); 
    $("#secundarios").show();
    $("#tableInfo").hide();
});

//funcion para cambiar color del boton costo
$("#costo").click(function() {
    $("#costo").attr('class','btn btn-warning');
    $("#obra").attr('class','btn btn-success');
});

//funcion para cambiar color del boton obra
$("#obra").click(function() {
    $("#obra").attr('class','btn btn-warning');
    $("#costo").attr('class','btn btn-success');

});

//funcion para seleccionar y eliminar la fila seleccionada
function rowDelete(xVal){   
    var deliter = $("#gItcant").val();    
    var k=1;
    if (deliter <= 1){
            $('#error').html('<font color="red"><b>Por favor Insertar al menos 1 fila</b></font>');
  }else{
            nextinput--;
            $("#table"+xVal).remove(); 
            var k=1;
            var total = deliter - deleinput;
            for(var i=1;i<=(parseInt(deliter) + 1);i++){
                if($('#table'+i).attr('id')){
                    $("#table"+i).attr('id','table'+(k));
                    $("#contrato"+i).attr('id','contrato'+(k));
                    $("#fechainicio"+i).attr('id','fechainicio'+(k));
                    $("#valor"+i).attr('id','valor'+(k));
                    $("#fechafin"+i).attr('id','fechafin'+(k));
                    $("#delete"+i).attr('id','delete'+(k));
                    $("#delete"+k).removeAttr('onclick');
                    $("#delete"+k).attr('onclick','javascript:rowDelete("'+k+'")');                    
                    k++
                }
            }
            $("#gItcant").val(total); 
        }              
}

//Funcion para validar las fechas
function valida(xVal){ 
    
    var cantidad = $("#gItcant").val(); 
    var inicio = $('#fechainicio'+xVal).val();
    var fin = $('#fechafin'+xVal).val();  
    var contrato = $("#contrato"+xVal).val();
    var valor = $("#valor"+xVal).val();
    
    if (inicio == ''){
        $('#error').html('<font color="red"><b>Por favor Insertar fecha Inicio</b></font>');
        return false;
    }else if (fin == ''){
        $('#error').html('<font color="red"><b>Por favor Insertar fecha Fin</b></font>');
        return false;
    }else{ 
        //split para obtener los años    
        var anoini = parseInt(inicio.split("/")[2]);
        var anofin = parseInt(fin.split("/")[2]);
        
            //split para obtener los meses
            var mesini = parseInt(inicio.split("/")[1]);
            var mesfin = parseInt(fin.split("/")[1]);

                //split para obtener los dias
                var diaini = parseInt(inicio.split("/")[0]);
                var diafin = parseInt(fin.split("/")[0]);
                //Nuevos datos tipo Date
                var past_date= new Date(anoini, mesini, diaini);
                var future_date = new Date(anofin, mesfin, diafin);                                      

var total_days = 0;
var total_months = 0;
var total_years = 0;

 var b = 0;
if(past_date.getMonth() == 2){
  if((future_date.getYear() % 4 == 0 && future_date.getYear() % 100 != 0) || future_date.getYear() % 400 == 0){
    b = 29;
  } else {
    b = 28;
  }
 } else if(past_date.getMonth() <= 7){
  if(past_date.getMonth() == 0){
    b = 31;
  } else if(past_date.getMonth() % 2 == 0){
    b = 30;
  } else {
    b = 31;
  }
} else if(past_date.getMonth() > 7){
   if(past_date.getMonth() % 2 == 0){
    b = 31;
  }else{
    b = 30;
  }
}


if(past_date > future_date){
     $('#error').html("<font color='red'><b>La fecha de inicio ha de ser anterior a la fecha Actual</b></font>");
     return false;
}else{

  if(past_date.getMonth() <= future_date.getMonth()){
    total_years = future_date.getYear() - past_date.getYear();
    if(past_date.getDate() <= future_date.getDate()){
       total_months = future_date.getMonth() - past_date.getMonth();
      total_days = future_date.getDate() - past_date.getDate();
    }else{
      if(future_date.getMonth() == past_date.getMonth()){
        total_years = total_years - 1;
       }
      total_months = (future_date.getMonth() - past_date.getMonth() - 1 + 12) % 12;
      total_days = b - (past_date.getDate() - future_date.getDate());
    }
  }else{
    total_years = future_date.getYear() - past_date.getYear() - 1;
     if(past_date.getDate() > future_date.getDate()){
      total_months = future_date.getMonth() - past_date.getMonth() - 1 + 12;
      total_days = b - (past_date.getDate() - future_date.getDate());
    }else{
       total_months = future_date.getMonth() - past_date.getMonth() + 12;
      total_days = future_date.getDate() - past_date.getDate();
    }
  }
  $('#total_anio'+xVal).val(total_years);
  $('#total_mes'+xVal).val(total_months);
  $('#total_day'+xVal).val(total_days);
      if (contrato == '' || valor == ''){
          $('#error').html("<font color='red'><b>No se encuentran Datos</b></font>");
      }else{    
          $(".ajax_load").show("slow");
          $.ajax({
                type: "POST",    
                url: "valorgrilla",
                data: {valor:valor, cantidad:cantidad, anofin:anofin, total_anio:total_years, total_mes:total_months,
                total_day:total_days, mesini:mesini, mesfin:mesfin, fechainicio:inicio, fechafinal:fin},
                success: function(data) {
                    $("#matrizFic").html(data);
                    $(".ajax_load").hide("slow");
                }
            });
      } 
    }
  }
}*/
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

  function num(c) {
    c.value=c.value.replace(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  }

  function calcularValor(c) {
    var empleados = c.value;
    var identificador = c.id;
    var factorSmlv = '<?php echo $smlv/40; ?>';
    var factor = empleados * factorSmlv;
    document.getElementById(identificador+'_data').value = factor;
    var identificador_for = identificador.split('_');
    var acumulador, total = 0;
    for (var i = 1; i <=12; i++)
    {
      acumulador = document.getElementById(identificador_for[0]+'_'+i+'_data').value;
      total = total + parseInt(acumulador);
      
    }
    document.getElementById('total_'+identificador_for[0]).value = total;
  }
</script>
<!-- Fin Función solo números -->