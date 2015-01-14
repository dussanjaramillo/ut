<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Registro Cartera Responsabilidad Bienes</h2>

 <?php     
        echo form_open(current_url()); ?>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

  <tr>
  	<td>&nbsp;</td>
    <td>Identificación Empleado</td>
    <td><input type="text" id="cedula" name="cedula" value="<?=$datos_c["NUM_IDENTIFICACION"]?>" readonly="readonly" style="width : 91%;" required="required"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" id="consultar" style="width : 91%;" value="Consultar" class='btn btn-success'></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Apellidos Empleado&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="apellidos" style="width : 91%;" readonly="readonly" required="required"></td>
    <td>&nbsp;</td>
    <td>Nombres Empleado</td>
    <td><input type="text" id="nombres" style="width : 91%;" readonly="readonly" required="required"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Dirección</td>
    <td><input type="text" id="direccion" style="width : 91%;" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Teléfono Contacto</td>
    <td><input type="text" id="telefono" style="width : 91%;" readonly="readonly"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>Correo Electrónico</td>
    <td><input type="text" id="correo" style="width : 91%;" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Tipo de Funcionario</td>
    <td><input type="text" id="tipo_funcionario" style="width : 91%;" readonly="readonly"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>	
    <td>Dependencia</td>
    <td><input type="text" id="dependencia" style="width : 91%;" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Regional</td>
    <td><input type="text" id="regional" style="width : 91%;" readonly="readonly"></td>
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
    <td>Tipo de Acuerdo</td>
 <?php
 if(!empty($acuerdo_prestamo)){
 ?>	
 <td>
    	  	<?php

          foreach($acuerdos->result_array as $row) {
              	
                  $selecttipoacuerdo[$row['COD_ACUERDOLEY']] = $row['NOMBRE_ACUERDO'];
               }
          echo form_dropdown('tipo_acuerdo_id', $selecttipoacuerdo,'','id="tipo_acuerdo_id" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_acuerdo_id','<div>','</div>');
        

        ?>
    	
    	</td>
	
 <?php
 }else{
 ?>
<td><input type="text" id="tipo_acuerdo_id" name="tipo_acuerdo_id" style="width : 91%;" readonly="readonly" disabled="disabled"></td>
 <?php
 }
 ?> 
  	<td>&nbsp;</td>
  </tr>
  
        
 	       <tr>
    <td>&nbsp;</td>	
    <td>Codigo Bien</td>
    <td><input type="text" id="id_bien" name="id_bien" style="width : 91%;" value="<?=$datos_c["CODIGO_BIEN"]?>" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Descripción Bien</td>
    <td><input type="text" id="desc_bien" name="desc_bien" style="width : 91%;" readonly="readonly" value="<?=$datos_c["DESCRIPCION_ELEMENTO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
   	       <tr>
    <td>&nbsp;</td>	
    <td>Placa Inventario</td>
    <td><input type="text" id="placa_inv" name="placa_inv" style="width : 91%;" value="<?=$datos_c["PLACA_INVENATARIO"]?>" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Regional Bien</td>
    <td><input type="text" id="regional_bien" name="regional_bien" style="width : 91%;" readonly="readonly" value="<?=$datos_c["COD_REGIONAL"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
     	       <tr>
    <td>&nbsp;</td>	
    <td>Motivo Dado de Baja</td>
    <td><input type="text" id="motivo_baja" name="motivo_baja" style="width : 91%;" value="<?=$datos_c["MOTIVO_BAJA"]?>" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Fecha Dado de Baja</td>
    <td><input type="text" id="fecha_baja" name="fecha_baja" style="width : 91%;" readonly="readonly" value="<?=$datos_c["FECHA_BAJA"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
     	       <tr>
    <td>&nbsp;</td>	
    <td>Valor Compra</td>
    <td><input type="text" id="valor_compra" name="valor_compra" style="width : 91%;" value="<?=$datos_c["VALOR_COMPRA"]?>" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Valor Depreciación</td>
    <td><input type="text" id="valor_dep" name="valor_dep" style="width : 91%;" readonly="readonly" value="<?=$datos_c["VALOR_DEPRECIACION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
       	       <tr>
    <td>&nbsp;</td>	
    <td>Valor por Depreciar</td>
    <td><input type="text" id="valor_x_depreciar" name="valor_x_depreciar" style="width : 91%;" value="<?=$datos_c["VALOR_X_DEPRECIAR"]?>" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Valor Deducible</td>
    <td><input type="text" id="valor_deducible" name="valor_deducible" style="width : 91%;" readonly="readonly" value="<?=$datos_c["VALOR_DEDUCIBLE"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>	
    <td>Identificacion Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" disabled="disabled"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

          
        <tr>
    <td>&nbsp;</td>	
    <td>Fecha de Resolucion</td>
    <td><input type="text" id="fecha_resolucion" name="fecha_resolucion" style="width : 84%;" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Número Resolucion</td>
    <td><input type="text" id="numero_resolucion" name="numero_resolucion" style="width : 91%;"></td>
  	<td>&nbsp;</td>
  </tr> 
      <tr>
    <td>&nbsp;</td>	
    <td>Estado</td>
    <td><?php

          foreach($estado->result_array as $row) {
              	
                  $selecttipoestado[$row['COD_EST_CARTERA']] = $row['DESC_EST_CARTERA'];
               }
          echo form_dropdown('tipo_estado_id', $selecttipoestado,'2','id="tipo_estado_id" readonly="readonly" disabled="disabled" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_estado_id','<div>','</div>');
        

        ?></td>
    <td>&nbsp;</td>
        <td>Forma de Pago</td>
        <td><?php

          foreach($forma_pago->result_array as $row) {
              	
                  $selectformapago[$row['COD_FORMAPAGO']] = $row['FORMA_PAGO'];
               }
          echo form_dropdown('forma_pago_id', $selectformapago,'','id="forma_pago_id" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('forma_pago_id','<div>','</div>');
        

        ?></td>
        <td>&nbsp;</td>
  </tr>
  
 
          <tr>
    <td>&nbsp;</td>	
    <td>Tasa de Interes Corriente</td>
    <td>
<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_corriente', $selecttipotasa,'','id="combo_tipo_tasa_corriente" readonly="readonly" disabled="disabled" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>
    	
    	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 15%;" readonly="readonly" disabled="disabled">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,'','id="aplica_tasa_c" style="width : 30%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
</td>
    <td>&nbsp;</td>
    <td>Tasa de Interes de Mora</td>
    <td>
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

<table>
<tr><td>&nbsp;</td>	</tr>
         <tr>
    <td>&nbsp;</td>	
    <td>Identificacion Codeudor</td>
    <td><input type="text" id="id_codeudor" name="id_codeudor" style="width : 91%;" required="required"></td>
    <td>&nbsp;</td>
    <td>Nombre Codeudor&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="nombre_codeudor" name="nombre_codeudor" style="width : 91%;" required="required"></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
   <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  
</table>

<table width="780">
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>	
    <td><input type="checkbox" id="t_i_c" name="t_i_c" value="accept">&nbspModificar Tasa de Interes Corriente</td>
    <td>&nbsp;</td>	
    <td><input type="checkbox" id="t_i_m" name="t_i_m" value="accept">&nbspModificar Tasa de Interes Mora</td>
    <td>&nbsp;</td>	
  	<td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
</table>

<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit_button',
                       'value' => 'GUARDAR',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> GUARDAR',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
<?php echo form_close(); ?>
<p>
	 

   </p> 

    
</div>

<div id="files"></div>

<script type="text/javascript" language="javascript" charset="utf-8">



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


    $( "#cedula" ).autocomplete({
      source: "<?php echo base_url("index.php/cnm_calamidad/traercedula") ?>",
      minLength: 3
      });


    $('#consultar').click(function(){

var cedula_comp = $("#cedula").val();

	$.getJSON("<?= base_url('index.php/cnm_calamidad/get_datos') ?>/"+$("#cedula").val(),function(data){

$('#apellidos').val(data.APELLIDOS);
$('#nombres').val(data.NOMBRES);
$('#direccion').val(data.DIRECCION);
$('#telefono').val(data.TELEFONO);
$('#correo').val(data.CORREO_ELECTRONICO);
$('#tipo_funcionario').val(data.NOMBRE_TIPOVINCULACION);
$('#dependencia').val(data.DEPENDENCIA);
$('#regional').val(data.NOMBRE_REGIONAL);
});	 


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





$('#id_codeudor').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#id_codeudor').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
    
 $('#id_codeudor').change(function(){
num_acta = document.getElementById("id_codeudor").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#id_codeudor').val("");
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


$('#tipo_acuerdo_id').change(function(){

	$("#tipo_modalidad_id").empty();
	$.getJSON("<?= base_url('index.php/cnm_calamidad/get_tipo_modalidad') ?>/"+$("#tipo_acuerdo_id").val(),function(data){

        $.each(data, function(k,v){
            $("#tipo_modalidad_id").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


}); 

$('#porcent_tasa_c').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_tasa_c').val();

	
if((info.length==4 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
    
 $('#porcent_tasa_c').change(function(){
num_acta = document.getElementById("porcent_tasa_c").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#porcent_tasa_c').val("");
  }
});

$('#porcent_tasa_m').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_tasa_m').val();

	
if((info.length==4 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
    
 $('#porcent_tasa_m').change(function(){
var info=$('#porcent_tasa_m').val()
num_acta = document.getElementById("porcent_tasa_m").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
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
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#valor_deuda').val("");
  }
});     

$('#valor_cuota').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#valor_cuota').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
    

$('#valor_cuota').change(function(){
var info=$('#valor_cuota').val()
num_acta = document.getElementById("valor_cuota").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#valor_cuota').val("");
  }
});    
    
    $('#plazo').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
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

                function ajaxValidationCallback(status, form, json, options) {
	
	
}

</script>

<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla2 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla3 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 

</style> 