<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Crear Modalidad</h2>


<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

<center>
    <tr>
    <td>&nbsp;</td>
    <td>Modalidad</td>
    <td><?php
         $datacomponente = array(
                      'name'        => 'componente',
                      'id'          => 'componente',
                      'maxlength'   => '40',
                      'required'    => 'required',
                      'style'       => 'width : 70%;',
                      'onkeypress'  => 'escape(this);',
                      'onblur' 		=> 'escape(this);',
                      'onkeyup' 	=> 'escape(this);',
                    );

           echo form_input($datacomponente);
           echo form_error('componente','<div>','</div>');
        ?></td>
   
  	<td>&nbsp;</td>
  </tr>
  
    <tr>
    <div id="alerta"></div>
    </tr>  
  
        <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
     <td>        <?php
           $cod_ac = array(
                      'name'        => 'cod_ac_mod',
                      'id'          => 'cod_ac_mod',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'type'		=> 'hidden',
                      'value'       => $cod_acuerdo
                    );

           echo form_input($cod_ac);
           echo form_error('cod_ac_mod','<div>','</div>');
        ?></td>
    
     </center> 
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

</div>
  <script type="text/javascript">
  
function escape(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta").hide();
		}
  			else{
  	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#componente').val("");
      $("#componente").focus();
      return false;
  				}
}


function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 