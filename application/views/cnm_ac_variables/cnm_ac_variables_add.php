<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Crear Variable</h2>


<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

<center>
    <tr>
    <td>&nbsp;</td>
    <td>Variable</td>
    <td><?php
    	
           
                   foreach($var_esp->result_array as $row) {
              	
                  $selectvaresp[$row['COD_VARIABLE_ESP']] = $row['NOMBRE_VARIABLE_ESP'];
               }
          echo form_dropdown('var_esp_id', $selectvaresp,'','id="var_esp_id" style="width : 43%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('var_esp_id','<div>','</div>');

        ?></td>
   
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Minimo</td>
   <td><?php
         $datamaximo = array(
                      'name'        => 'minimo',
                      'id'          => 'minimo',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 40%;'
                    );

           echo form_input($datamaximo);
           echo form_error('minimo','<div>','</div>');
        ?></td>
   
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Maximo</td>
     <td><?php
         $dataminimo = array(
                      'name'        => 'maximo',
                      'id'          => 'maximo',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 40%;'
                    );

           echo form_input($dataminimo);
           echo form_error('maximo','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
        <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>        <?php
           $cod_ac = array(
                      'name'        => 'cod_ac_var',
                      'id'          => 'cod_ac_var',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'type'		=> 'hidden',
                      'value'       => $cod_Comp
                    );

           echo form_input($cod_ac);
           echo form_error('cod_ac_var','<div>','</div>');
        ?></td>
    
     </center> 
    	<td>&nbsp;</td>
  </tr>
      
  
  
  
        
  
  </table>

        
	<input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >

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
<input type="hidden" id="cod_acuerdo_comp" name="cod_acuerdo_comp" value="<?=$cod_Comp?>">
</div>
  <script type="text/javascript">
  

//$(document).ready(function(){
  	
//$("#porc_ref").numeric(","); 
//});


$('#minimo').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#minimo').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==10)|| (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});

$('#maximo').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#maximo').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==10)|| (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
  



$('#cancelar').click(function(){
        	window.history.back()
       });  

  


function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 