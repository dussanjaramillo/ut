<?php 
/**
* Formulario para la captura de la información necesaria para el calculo de liquidaciones por aportes del Fondo de Industria y Comercio (FIC) en los procesos administrativos
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/fic_view.php
* @last-modified  11/05/2014
*/
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); 
if (isset($message))
{
  echo $message;
}
print_r($codigoGestion);
?>
<div class="center-form-xlarge">
  <?php 
    $atributos_form = array('method' => 'post', 'id' => 'fic', 'class' => 'form-inline');
    echo form_open('liquidaciones/loadFormFic', $atributos_form);
  ?>
  <h2 class="text-center">Liquidación Fondo de Industria y Comercio (FIC)</h2>
  <br><br>
  <div id="cabecera" class="row-fluid">
    <span class="span1">&nbsp;</span>
    <span class="span2"><b>Razón Social : </b><?php echo $codigoGestion['NOMBRE_EMPRESA'] ?></span>
    <span class="span2"><b>NIT : </b><?php echo $codigoGestion['NIT_EMPRESA'] ?></span>
    <span class="span2"><b>Regional : </b><?php echo $codigoGestion['NOMBRE_REGIONAL'] ?></span>
    <span class="span2"><b>Periodo Inicial : </b><?php echo $codigoGestion['PERIODO_INICIAL'] ?></span>
    <span class="span2"><b>Periodo Fin : </b><?php echo $codigoGestion['PERIODO_FINAL'] ?></span>
    <span class="span1">&nbsp;</span>
    <span class="span12">&nbsp;</span>
    <span class="span4 offset4">
      <?php 
        echo form_label('<b>Fecha de Liquidación :&nbsp;</b>','fechaLiquidacion');  
        $atributos_fechaLiquidacion = array ('type' => 'date', 'name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'value' => $fecha, 'class' => 'input-small uneditable-input', 'readonly' => 'readonly');
        echo form_input($atributos_fechaLiquidacion);
      ?>
    </span>
    <span class="span4">&nbsp;</span>
  </div>
  <div id="datosGenerales" class="row-fluid" style="border:1px solid #CFCFCF">
    <span class="span12">&nbsp;</span>
    <span class="span3">
      <div class="input-prepend input-append">
      <?php 
        echo form_label('<b>Valor Inversión :&nbsp;</b>','valorInversion');  
        echo '<span class="add-on">$</span>';
        $atributos_valorInversion = array ('type' => 'number', 'name' => 'valorInversion', 'id' => 'valorInversion', 'value' => set_value('valorInversion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        echo form_input($atributos_valorInversion);
        echo '<span class="add-on">.00</span>';
      ?>
      </div>
    </span>
    <span class="span4">
      <div class="input-prepend input-append">
      <?php 
        echo form_label('<b>Gastos de Financiación :&nbsp;</b>','gastosFinanciacion');  
        echo '<span class="add-on">$</span>';
        $atributos_gastosFinanciacion = array ('type' => 'number', 'name' => 'gastosFinanciacion', 'id' => 'gastosFinanciacion', 'value' => set_value('gastosFinanciacion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        echo form_input($atributos_gastosFinanciacion);
        echo ' <span class="add-on">.00</span>';  
      ?>
      </div>
    </span>
    <span class="span4">
      <div class="input-prepend input-append">
      <?php 
        echo form_label('<b>Impuestos Legales :&nbsp;</b>','impuestosLegales');  
        echo '<span class="add-on">$</span>';
        $atributos_impuestosLegales = array ('type' => 'number', 'name' => 'impuestosLegales', 'id' => 'impuestosLegales', 'value' => set_value('impuestosLegales','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        echo form_input($atributos_impuestosLegales);
        echo ' <span class="add-on">.00</span>';
      ?>
      </div>
    </span>
    <span class="span12">&nbsp;</span>
    <span class="span3">
      <div class="input-prepend input-append">
      <?php 
        echo form_label('<b>Valor del Lote :&nbsp;</b>','valorLote');  
        echo '<span class="add-on">$</span>';
        $atributos_valorLote = array ('type' => 'number', 'name' => 'valorLote', 'id' => 'valorLote', 'value' => set_value('valorLote','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        echo form_input($atributos_valorLote);
        echo ' <span class="add-on">.00</span>';
      ?>
      </div>
    </span>
    <span class="span4">
      <div class="input-prepend input-append">
      <?php 
        echo form_label('<b>Indenmización a Terceros :&nbsp;</b>','indenmizacion');  
        echo '<span class="add-on">$</span>';
        $atributos_indenmizacion = array ('type' => 'number', 'name' => 'indenmizacion', 'id' => 'indenmizacion', 'value' => set_value('indenmizacion','0'), 'class' => 'input-small', 'required' => 'required', 'maxlenght' => '15',  'onkeypress' => 'num(this);', 'onblur' => 'num(this);' , 'onkeyup' => 'num(this);');
        echo form_input($atributos_indenmizacion);
        echo ' <span class="add-on">.00</span>';
      ?>
      </div>
    </span>
    <span class="span12" id="alertas">&nbsp;</span>
  </div>
  <br><br>
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
                echo '<td>';
                $atributos_data = array ('type' => 'text', 'name' => $year.'_'.$i.'_data', 'id' => $year.'_'.$i.'_data', 'value' => set_value($i,'0'), 'class' => 'input-small input-disabled', 'readonly' => 'readonly');
                echo form_input($atributos_data);
                echo '</td>';
              endfor;
            ?>
            <td>
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
      <div class="tab-pane" id="presuntiva">
        <div class="tabbable">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#costo" data-toggle="tab">Contrato a Todo Costo</a></li>
            <li><a href="#obra" data-toggle="tab">Contrato Mano de Obra</a></li> 
          </ul>
        </div> 
        <div class="tab-content">
          <div class="tab-pane active" id="costo">
            <p>Costo</p>
          </div>
          <div class="tab-pane" id="obra">
            <p>Costo</p>
          </div>
        </div> 
      </div>
    </div>
    </div>
  </div>
</div>
<?php 
  echo form_close(); 
?>  
 


<script type="text/javascript">
$(function() {
  //Array para dar formato en español al datepicker
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
  //Lanzador de datepicker para fecha de liquidación
  $.datepicker.setDefaults($.datepicker.regional['es']);
    $( "#fechaLiquidacion" ).datepicker({
    showOn: 'button',
    buttonText: 'Selecciona una fecha',
    buttonImage: '<?php echo base_url() , "img/calendario.png" ?>',
    buttonImageOnly: true,
    numberOfMonths: 1,
    maxDate: '30d',
    minDate: '0d',
    });
  });
  
  //Verificador máximos caracteres para datos generales
  var max_chars = 15; //cantidad de caracteres máximos
  
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
    });
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

<!-- Función solo números -->
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
    alert(c.Id);
  }
</script>
<!-- Fin Función solo números -->