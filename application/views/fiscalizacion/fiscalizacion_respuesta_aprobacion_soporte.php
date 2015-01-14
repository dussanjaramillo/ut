<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='420' height= '550'>
 
 <?php     

        echo form_open(current_url()); ?>
 
<center><h2>Respuesta Aprobación Soporte de Pago</h2></center>
<center>
FECHA&nbsp: &nbsp<?=$fecha?>	 &nbsp &nbsp &nbsp HORA&nbsp:  &nbsp<?=$hora?>  	<br>
</center>

<input type="hidden" id="cod_gestion_cobro" name="cod_gestion_cobro" value="<?=$cod_gestion  ?>" >
<input type="hidden" id="cod_soporte_gestion" name="cod_soporte_gestion" value="<?=$cod_soporte_gestion  ?>" >
<input type="hidden" id="fecha" name="fecha" value="<?=$fecha  ?>" >
<input type="hidden" id="hora" name="hora" value="<?=$hora  ?>" >
<p>   
     <?php
		
         echo form_label('RESPUESTA:<span class="required">*</span>', 'respuesta_id');  
              foreach($respuestas as $row) {
                  $select[$row->COD_RESPUESTA] = $row->NOMBRE_GESTION;
               }
          echo form_dropdown('respuesta_id', $select,'','id="respuesta_id"  class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('respuesta_id','<div>','</div>');
        

        ?>
  </p>	
  <p>
        
        <?php
         echo form_label('Descripción<span class="required">*</span>', 'descripcion_resp_sop');
           $datadesc = array(
                      'name'        => 'descripcion_resp_sop',
                      'id'          => 'descripcion_resp_sop',
                      'value'       => set_value('descripcion_resp_sop'),
                      'maxlength'   => '200',
                      'required'    => 'required',
                      'rows'        => '3',
                      'onkeypress' 	=> "escape(this);",
                      'onblur' 		=> "escape(this);",
                      'onkeyup' 	=> "escape(this);"
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion_resp_sop','<div>','</div>');
        ?>
        </span>
        </p>
        <div id="alerta"></div>	
        <center>
   <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar',
                       'class' => 'btn btn-success',
						
                       );

                echo form_button($data);    
                ?>     
</center>
     <?php echo form_close(); ?>	
    


</div>

 <div id='guardarapr'> </div>

<script type="text/javascript" language="javascript" charset="utf-8">

 function escape(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta").hide();
		}
  			else{
  	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#descripcion').val("");
      $("#descripcion").focus();
      return false;
  				}
}
 
  $('#descripcion_resp_sop').change(function(){

 	    if ($('#descripcion_resp_sop').val().indexOf('%') != -1 || $('#descripcion_resp_sop').val().indexOf('<') != -1) 
       { 	
       	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido "%|<"<p>');
       	$('#descripcion_resp_sop').val("");
      $("#descripcion_resp_sop").focus();
       return false;

  }
  else{
  	$("#alerta").hide();
  }

});

 $('#respuesta').dialog({
                autoOpen: true,
                width: 420,
                height: 550,
                modal:true,
                title:'Respuesta Aprobación Soporte de Pago',
                close: function() {
                    $('#respuesta *').remove();
                }
            });	
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
            

  </script>

   