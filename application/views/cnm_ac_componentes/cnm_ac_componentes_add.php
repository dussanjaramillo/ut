<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Crear Componentes</h2>

<center>
	<b>
	Acuerdo de Ley:&nbsp&nbsp<?=$infoac['NOMBRE_ACUERDO']?>&nbsp&nbsp&nbsp&nbsp&nbspTipo de Cartera:&nbsp&nbsp<?=$infoac['NOMBRE_CARTERA']?>
	</b>
</center>

<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>


    <tr>
    <td>&nbsp;</td>
    <td>Componente</td>
    <td><?php
    	
           
                   foreach($comp_esp->result_array as $row) {
              	
                  $selectcompesp[$row['COD_COMPONENTE_ESP']] = $row['NOMBRE_COMP_ESP'];
               }
          echo form_dropdown('comp_esp_id', $selectcompesp,'','id="comp_esp_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('comp_esp_id','<div>','</div>');

        ?></td>
    <td>&nbsp;</td>
    <td>Aplica a</td>
    <td><?php
            
                 foreach($comp_aplic->result_array as $row) {
              	
                  $selectaplica[$row['COD_APLICA_COMPONENTE']] = $row['NOMBRE_APLICA_COMP'];
               }
          echo form_dropdown('comp_aplica_id', $selectaplica,'','id="comp_aplica_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('comp_aplica_id','<div>','</div>');
           ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Formula de Interés</td>
    <td><?php
           
                 foreach($f_interes->result_array as $row) {
              	
                  $select_f_interes[$row['COD_FORMULA_INT']] = $row['NOMBRE_FORMULA_INT'];
               }
          echo form_dropdown('f_interes_id', $select_f_interes,'','id="f_interes_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('f_interes_id','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Calculo</td>
    <td><?php
            
                  foreach($calculo->result_array as $row) {
              	
                  $select_calculo[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('calculo_id', $select_calculo,'','id="calculo_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('calculo_id','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Tipo de Tasa</td>
    <td><?php
		
          foreach($tipo_tasa->result_array as $row) {
              	
                  $selecttipocartera[$row['COD_TIPO_TASAINTERES']] = $row['NOMBRE_TASAINTERES'];
               }
          echo form_dropdown('tipo_tasa_id', $selecttipocartera,'','id="tipo_tasa_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_tasa_id','<div>','</div>');
        

        ?>
   </td>
    <td>&nbsp;</td>
    <td>Valor Tasa(%)</td>
 <td><input type="text" name="valor" id="valor" style= 'width : 80%;'/>
</td>
  	<td>&nbsp;</td>
  </tr>
  
  
        <tr>
    <td>&nbsp;</td>
    <td>Puntos Adicionales(%)</td>
<td>        <?php
           $datapuntos_add = array(
                      'name'        => 'puntos_add',
                      'id'          => 'puntos_add',
                      'maxlength'   => '128',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($datapuntos_add);
           echo form_error('puntos_add','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

  </tr>
  
  <tr>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
        <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>        <?php
           $cod_ac = array(
                      'name'        => 'cod_ac_comp',
                      'id'          => 'cod_ac_comp',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'type'		=> 'hidden',
                      'value'       => $componente
                    );

           echo form_input($cod_ac);
           echo form_error('cod_acuerdo_c','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
        
  
  </table>

        
	 <?php  echo anchor('cnm_acuerdo_ley', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
               
 <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                <input type="hidden" id="vista_flag" name="vista_flag" value="1" >
        </p>

        <?php echo form_close(); ?>
</center>
<input type="hidden" id="cod_ac_comp" name="cod_ac_comp" value="<?=$componente?>">
</div>
  <script type="text/javascript">
  
$('#calculo_id').change(function(){

	$("#tipo_tasa_id").empty();
	$.getJSON("<?= base_url('index.php/cnm_ac_componentes/get_tipo_tasa') ?>/"+$("#calculo_id").val(),function(data){
        console.log(JSON.stringify(data));
        $.each(data, function(k,v){
            $("#tipo_tasa_id").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


});  
  
//  $(document).ready(function(){
  	
//$("#porc_ref").numeric(","); 
//});

$('#puntos_add').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#puntos_add').val();
	
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



$('#valor').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#valor').val();
	
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

  
$('#plazo_min').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo_min').val();
	
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


$('#plazo_max').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo_max').val();
	
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



$('#cancelar').click(function(){
        	window.history.back()
       });  

  
function validar_porcentaje (campo) {
	
}

function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 