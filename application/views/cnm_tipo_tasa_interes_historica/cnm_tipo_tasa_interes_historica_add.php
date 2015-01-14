<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Nuevo Tipo de Tasa de Interés Histórica</h2>

        <p>
        <?php
         echo form_label('Nombre Tipo de Tasa<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => set_value('nombre'),
                      'maxlength'   => '40',
                      'required'    => 'required',
                      'onkeypress'  => 'escape(this);',
                      'onblur' 		=> 'escape(this);',
                      'onkeyup' 	=> 'escape(this);',

                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
        ?>
        </p>

       
    <div id="alerta"></div>
    
        <p>   
     <?php
		
         echo form_label('Tipo Tasa:<span class="required">*</span>', 'tipo_tasa_id');  
              //var_dump($formaPago);
			  //die();
              foreach($tipo_tasa->result_array as $row) {
              	
                  $selectformap[$row['COD_TIPO_TASAINTERES']] = $row['NOMBRE_TASAINTERES'];
               }
          echo form_dropdown('tipo_tasa_id', $selectformap,'','id="tipo_tasa_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_tasa_id','<div>','</div>');
        

        ?>
  </p>  
        <p>
                <?php  echo anchor('cnm_tipo_tasa_interes_historic', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
       	$('#nombre').val("");
      $("#nombre").focus();
      return false;
  				}
}
  
$('#cancelar').click(function(){
        	window.history.back()
       });  
  
function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 