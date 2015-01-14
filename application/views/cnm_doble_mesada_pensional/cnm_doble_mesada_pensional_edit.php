<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Doble Mesada Pensional</h2>

  
 <?php     
        echo form_open(current_url(),array('onsubmit'=>'return confirmSubmit();')); ?>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

  <tr>
  	<td>&nbsp;</td>
    <td>Identificación Pensionado:</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;" required="required" readonly="readonly" value="<?=$detalleCart["COD_EMPLEADO"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" id="consultar" style="width : 91%;" value="Consultar" class='btn btn-success'></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Apellidos Empleado&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="apellidos" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["APELLIDOS"]?>"></td>
    <td>&nbsp;</td>
    <td>Nombres Empleado</td>
    <td><input type="text" id="nombres" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["NOMBRES"]?>"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Dirección</td>
    <td><input type="text" id="direccion" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["DIRECCION"]?>"></td>
    <td>&nbsp;</td>
    <td>Teléfono Contacto</td>
    <td><input type="text" id="telefono" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["TELEFONO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>Correo Electrónico</td>
    <td><input type="text" id="correo" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["CORREO_ELECTRONICO"]?>"></td>
    <td>&nbsp;</td>
    <td>Regional</td>
    <td><input type="text" id="regional" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["NOMBRE_REGIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>

</table>

<table width="780" border="0">
	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr>
	    <tr>
    <td>&nbsp;</td>	
    <td>Tipo de Cartera</td>
   <td><input type="text" id="tipo_cartera" name="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$tipoCartera["NOMBRE_CARTERA"]?>"><input type="hidden" id="cod_tipo_cartera" name="cod_tipo_cartera" value="<?=$tipoCartera["COD_TIPOCARTERA"]?>"></td>
      <td>&nbsp;</td>
    <td>Fecha Inicial de Pensión</td>
    <td><input type="text" id="fecha_pension" name="fecha_pension" style="width : 84%;" readonly="readonly" value="<?=$detalleCart["FECHA_INI_PENSION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>

      <tr>
    <td>&nbsp;</td>	
    <td>Identificacion de la Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
    <td>Valor Doble Mesada</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" required="required" onchange ="presubmit();" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=number_format($detalleCart["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td>&nbsp;</td>	
    <td>Fecha de Resolución</td>
    <td><input type="text" id="fecha_resolucion" name="fecha_resolucion" style="width : 84%;" readonly="readonly" value="<?=$detalleCart["FECHA_RESOLUCION"]?>"></td>
    <td>&nbsp;</td>
    <td>Número Resolución</td>
    <td><input type="text" id="numero_resolucion" name="numero_resolucion" style="width : 91%;" value="<?=$detalleCart["NUMERO_RESOLUCION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
<tr>
    <td>&nbsp;</td>	
    <td>Estado</td>
    <td><?php

          foreach($estado->result_array as $row) {
              	
                  $selecttipoestado[$row['COD_EST_CARTERA']] = $row['DESC_EST_CARTERA'];
               }
          echo form_dropdown('tipo_estado_id', $selecttipoestado,$detalleCart["COD_ESTADO"],'id="tipo_estado_id" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_estado_id','<div>','</div>');
        

        ?></td>
    <td>&nbsp;</td>
    <td>Fecha Activación</td>
    <td><input type="text" id="fecha_activacion" name="fecha_activacion" style="width : 84%;" readonly="readonly" value="<?=$detalleCart["FECHA_ACTIVACION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
                <tr>
    <td>&nbsp;</td>	
    <td>Plazo</td>
    <td><input type="text" id="plazo" name="plazo" onchange ="presubmit();" style="width : 15%;" required="required" value="<?=$detalleCart["PLAZO_CUOTAS"]?>">
    	
    	<?php

          foreach($duracion->result_array as $row) {
              	
                  $selectplazo[$row['COD_DURACION_GRACIA']] = $row['NOMBRE_DURACCION'];
               }
          echo form_dropdown('plazo_id', $selectplazo,$detalleCart["COD_PLAZO"],'id="plazo_id" onchange ="presubmit();" style="width : 75%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('plazo_id','<div>','</div>');
        

        ?>
        </td>
    <td>&nbsp;</td>
    <td>Forma de Pago</td>
        <td><?php

          foreach($forma_pago->result_array as $row) {
              	
                  $selectformapago[$row['COD_FORMAPAGO']] = $row['FORMA_PAGO'];
               }
          echo form_dropdown('forma_pago_id', $selectformapago,$detalleCart["COD_FORMAPAGO"],'id="forma_pago_id" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('forma_pago_id','<div>','</div>');
        

        ?></td>
    

  	<td>&nbsp;</td>
  </tr>
  
                   <tr>
    <td>&nbsp;</td>	
    <td>Tasa de Interés Corriente</td>

    <td>    <?php if($detalleCart["MOD_TASA_CORRIENTE"]=='S')
    {?>
    	
    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_corriente', $selecttipotasa,$detalleCart["CALCULO_CORRIENTE"],'id="combo_tipo_tasa_corriente" onchange ="presubmit();" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>    
    
                    <?php if($detalleCart["CALCULO_CORRIENTE"]=='1')
    {?>
    	    	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 15%;" onchange ="presubmit();" value="<?=$detalleCart["VALOR_T_CORRIENTE"]?>">%
    	<?php

          foreach($tipotasaespC->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,$detalleCart["TIPO_T_F_CORRIENTE"],'id="aplica_tasa_c" style="width : 30%;" onchange ="presubmit();" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
    	<?php 
    	}else{
    	?>
    	
    	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" onchange ="presubmit();" style="width : 15%;">%
    	<?php

          foreach($tipotasaespV->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,$detalleCart["TIPO_T_V_CORRIENTE"],'id="aplica_tasa_c" onchange ="presubmit();" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
        <?php } ?>
    
    
    
  <?php }  else{?>
  	
  	    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_corriente', $selecttipotasa,'','id="combo_tipo_tasa_corriente" onchange ="presubmit();" style="width : 34%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>   
  	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 15%;" readonly="readonly" onchange ="presubmit();" disabled="disabled">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,'','id="aplica_tasa_c" style="width : 30%;" readonly="readonly" onchange ="presubmit();" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
  	
  	<?php } ?>
    
</td> 
    <td>&nbsp;</td>
    <td>Tasa de Interés de Mora</td>


    <td>    <?php if($detalleCart["MOD_TASA_MORA"]=='S')
    {?>
    	
    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_mora', $selecttipotasa,$detalleCart["CALCULO_MORA"],'id="combo_tipo_tasa_mora" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_mora','<div>','</div>');
        

        ?>
    
                    <?php if($detalleCart["CALCULO_MORA"]=='1')
    {?>
    	    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;" value="<?=$detalleCart["VALOR_T_MORA"]?>">%
    	<?php

          foreach($tipotasaespC->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_m', $selecttasamora,$detalleCart["TIPO_T_F_MORA"],'id="aplica_tasa_m" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_m','<div>','</div>');
        

        ?>
    	<?php 
    	}else{
    	?>
    	
    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;">%
    	<?php

          foreach($tipotasaespV->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_m', $selecttasamora,$detalleCart["TIPO_T_V_MORA"],'id="aplica_tasa_m" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_m','<div>','</div>');
        

        ?>
        <?php } ?>
    
    
    
  <?php }  else{?>
  	
  	    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_mora', $selecttipotasa,'','id="combo_tipo_tasa_mora" style="width : 34%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_mora','<div>','</div>');
        

        ?>

    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;" readonly="readonly" disabled="disabled">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_m', $selecttasamora,'','id="aplica_tasa_m" style="width : 30%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_m','<div>','</div>');
        

        ?>
  	
  	<?php } ?>
    
</td>

  	<td>&nbsp;</td>
  </tr>
        <tr>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">
    <input type="text" id="ea_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.A.V.<br>
    <input type="text" id="namv_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.M.V.<input type="text" id="ndv_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.D.V.	
    </td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td>
	<input type="text" id="ea_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.A.V.<br>
    <input type="text" id="namv_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.M.V.<input type="text" id="ndv_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.D.V.	
    </td>
  	<td rowspan="2">&nbsp;</td>
  </tr>
 	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr> 

</table>

<table width="780">
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>	
    <?php if($detalleCart["MOD_TASA_CORRIENTE"]=='S')
    {?>
    <td><input type="checkbox" id="t_i_c" name="t_i_c" value="accept" checked="checked">&nbspModificar Tasa de Interés Corriente</td>	
	<?php }
else{?>
    <td><input type="checkbox" id="t_i_c" name="t_i_c" value="accept">&nbspModificar Tasa de Interés Corriente</td>
    <?php }?>
    
    <td>&nbsp;</td>	
        <?php if($detalleCart["MOD_TASA_MORA"]=='S')
    {?>
       <td><input type="checkbox" id="t_i_m" name="t_i_m" value="accept" checked="checked">&nbspModificar Tasa de Interés Mora</td>
       <?php }
else{?>
       <td><input type="checkbox" id="t_i_m" name="t_i_m" value="accept">&nbspModificar Tasa de Interés Mora</td>
       <?php }?>

    <td>&nbsp;</td>	
  	<td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
</table>

<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
                <?php 
                $data = array(
                       'name' => 'button',
                       'disabled' => 'disabled',
                       'id' => 'submit-button',
                       'value' => 'GUARDAR',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> GUARDAR',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                 <input type='button' name='proyeccion' class='btn btn-success' value='IMPRIMIR PLAN AMORTIZACIÓN'  id='proyeccion' >

<?php echo form_close(); ?>
<p>
	 

   </p> 

    
</div>

<div id="files"></div>
<div id="proyeccion_tabla"></div>
<script type="text/javascript" language="javascript" charset="utf-8">

$('#proyeccion').click(function(){

var fecha_activacion=$('#fecha_activacion').val();

var medio_pago=$('#forma_pago_id').val();

var hipotecario=false;

var reliquidacion=false;
var refinanciacion=false;

var datos={ VALOR_DEUDA : $('#valor_deuda').val(), 
			CALCULO_CORRIENTE : $('#combo_tipo_tasa_corriente').val(),  
			TIPO_T_V_CORRIENTE : $('#aplica_tasa_c').val(), 
			TIPO_T_F_CORRIENTE : $('#aplica_tasa_c').val(), 
			COD_FORMAPAGO : $('#forma_pago_id').val(),  
			COD_PLAZO : $('#plazo_id').val(), 
			PLAZO_CUOTAS : $('#plazo').val(),  
			VALOR_CUOTA : $('#valor_cuota_aprob').val(),  
			VALOR_T_CORRIENTE : $('#porcent_tasa_c').val(), 
			SALARIO : $('#salario').val(),  
			TASA_INTERES_CESANTIAS : $('#tasa_cesantia').val(),  
			VALOR_CUOTA_APROBADA : $('#valor_cuota_aprob').val(), 
			FECHA_RETIRO : $('#fecha_retiro').val(),  
			GRADIENTE : $('#gradiente').val(), 
			TASA_IPC : $('#tasa_ipc').val(), 
			FACTOR_TIPO : $('#factor_tipo').val(),
			RESPUESTA : '1'
		
	 };

console.log (datos);

       var url = "<?php echo base_url('index.php/cnm_liquidacioncuotas/CnmCalcularCuotasVistaJson') ?>";
        $.post(url, {datos: datos, fecha_activacion: fecha_activacion, medio_pago: medio_pago, hipotecario: hipotecario, reliquidacion: reliquidacion, refinanciacion: refinanciacion })
        	.done(function(data)
        { 
        if(data.error==false)
        {
        	var url2 = "<?php echo base_url('index.php/carteranomisional/visualizar_proyeccion') ?>";
        	$('#proyeccion_tabla').load(url2, {datos: datos, fecha_activacion: fecha_activacion, medio_pago: medio_pago, hipotecario: hipotecario, reliquidacion: reliquidacion, refinanciacion: refinanciacion });
			$('#submit-button').attr("disabled",false);
        }	       
        else
        {
        	alert(data.mensaje+ "\n" +"Cuota Sugerida:"+ data.cuota_sugerida );
        	$('#submit-button').attr("disabled",true);
        }	       
        	       })
        .fail(function(data)
        {
        alert("no se puede liquidar, error de base de datos");
	    });



});
function presubmit(){
$('#submit-button').attr("disabled",true);

 }
function confirmSubmit() {
  if (confirm("Esta seguro de editar la Cartera?")) {
     return true;
  }
  return false;
}

function num(c){
  c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  // x = c.value;
    // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    var num = c.value.replace(/\./g,'');
  if(!isNaN(num))
  {
   num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
   num = num.split('').reverse().join('').replace(/^[\.]/,'');
   c.value = num;
  }
 }
 
   function escape(c){
  	 	    if( !(/[/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta").hide();
		}
  			else{
  	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#nombre_codeudor').val("");
      $("#nombre_codeudor").focus();
      return false;
  				}
}


  $( "#cedula" ).autocomplete({
      source: "<?php echo base_url("index.php/cnm_doble_mesada_pensional/traercedula") ?>",
      minLength: 3
      });


    $('#consultar').click(function(){

var cedula_comp = $("#cedula").val();

	$.getJSON("<?= base_url('index.php/cnm_doble_mesada_pensional/get_datos') ?>/"+$("#cedula").val(),function(data){

$('#apellidos').val(data.APELLIDOS);
$('#nombres').val(data.NOMBRES);
$('#direccion').val(data.DIRECCION);
$('#telefono').val(data.TELEFONO);
$('#correo').val(data.CORREO_ELECTRONICO);
$('#regional').val(data.NOMBRE_REGIONAL);
});	 


});	  

$('#t_i_c').change(function(){
	var chek = document.getElementById("t_i_c").checked;
	if(chek==true)
	{
		$('#combo_tipo_tasa_corriente').attr("disabled",false);     	
		$('#combo_tipo_tasa_corriente').attr("readonly",false);  
		$('#porcent_tasa_c').attr("disabled",false);     	
		$('#porcent_tasa_c').attr("readonly",false);     	
		$('#aplica_tasa_c').attr("disabled",false);     	
		$('#aplica_tasa_c').attr("readonly",false);     	   	
   }
    else{
    	$('#combo_tipo_tasa_corriente').attr("disabled",true);     	
    	$('#combo_tipo_tasa_corriente').attr("readonly",true);
    	$('#porcent_tasa_c').attr("disabled",true);     	
    	$('#porcent_tasa_c').attr("readonly",true);
    	$('#aplica_tasa_c').attr("disabled",true);     	
    	$('#aplica_tasa_c').attr("readonly",true);
    }
       });
       
       
       $('#t_i_m').change(function(){
	var chek = document.getElementById("t_i_m").checked;

	if(chek==true)
	{
		$('#combo_tipo_tasa_mora').attr("disabled",false);     	
		$('#combo_tipo_tasa_mora').attr("readonly",false);  
		$('#porcent_tasa_m').attr("disabled",false);     	
		$('#porcent_tasa_m').attr("readonly",false);     	
		$('#aplica_tasa_m').attr("disabled",false);     	
		$('#aplica_tasa_m').attr("readonly",false);     	   	
   }
    else{
    	$('#combo_tipo_tasa_mora').attr("disabled",true);     	
    	$('#combo_tipo_tasa_mora').attr("readonly",true);
    	$('#porcent_tasa_m').attr("disabled",true);     	
    	$('#porcent_tasa_m').attr("readonly",true);
    	$('#aplica_tasa_m').attr("disabled",true);     	
    	$('#aplica_tasa_m').attr("readonly",true);
    }
       });
            
            
     $('#combo_tipo_tasa_corriente').change(function(){

	$("#aplica_tasa_c").empty();
	$.getJSON("<?= base_url('index.php/cnm_ac_componentes/get_tipo_tasa') ?>/"+$("#combo_tipo_tasa_corriente").val(),function(data){
        console.log(JSON.stringify(data));
        $.each(data, function(k,v){
            $("#aplica_tasa_c").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


});  

$('#combo_tipo_tasa_mora').change(function(){

	$("#aplica_tasa_m").empty();
	$.getJSON("<?= base_url('index.php/cnm_ac_componentes/get_tipo_tasa') ?>/"+$("#combo_tipo_tasa_mora").val(),function(data){
        console.log(JSON.stringify(data));
        $.each(data, function(k,v){
            $("#aplica_tasa_m").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


});       
            
            $( "#fecha_pension").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Pensión',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	
});

$( "#fecha_resolucion").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Resolucion',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	
});

$( "#fecha_activacion").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Activación',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	
});



$('#porcent_tasa_c').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_tasa_c').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==5 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==4)|| (keynum==44 && info.length==0)
|| ((keynum!=44&&keynum!=8&&keynum!=0&&keynum!=13&&keynum!=0&&keynum!=82) && info.length==2 && keynum2!=103) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

 $('#porcent_tasa_c').change(function(){
num_acta = document.getElementById("porcent_tasa_c").value;
 	    if( !(/[A-z-\/\*\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#porcent_tasa_c').val("");
  }
});

$('#porcent_tasa_m').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_tasa_m').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==5 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==4)|| (keynum==44 && info.length==0)
|| ((keynum!=44&&keynum!=8&&keynum!=0&&keynum!=13&&keynum!=0&&keynum!=82) && info.length==2 && keynum2!=103) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

   
 $('#porcent_tasa_m').change(function(){
var info=$('#porcent_tasa_m').val()
num_acta = document.getElementById("porcent_tasa_m").value;
 	    if( !(/[A-z-\/\*\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#porcent_tasa_m').val("");
  }
});     


$('#numero_resolucion').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#numero_resolucion').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

 $('#numero_resolucion').change(function(){
var info=$('#numero_resolucion').val()
num_acta = document.getElementById("numero_resolucion").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#numero_resolucion').val("");
  }
});     


$('#valor_deuda').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#valor_deuda').val();
	

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

$('#valor_deuda').change(function(){
var info=$('#valor_deuda').val()
num_acta = document.getElementById("valor_deuda").value;
 	    if( !(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#valor_deuda').val("");
  }
});     

 $('#plazo').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==3 && (keynum != 8&&keynum != 0))  )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
}); 

$('#plazo').change(function(){
var info=$('#plazo').val()
num_acta = document.getElementById("plazo").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#plazo').val("");
  }
});      

$('#cedula').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#cedula').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
    
 $('#cedula').change(function(){
num_acta = document.getElementById("cedula").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#cedula').val("");
  }
});

                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla2 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla3 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla4 { border: 1px solid #000;} 

</style> 