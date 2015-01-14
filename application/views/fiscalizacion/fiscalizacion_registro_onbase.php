<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>



<div class="center-form" width='460' height= '460'>

 <?php     

        echo form_open_multipart(current_url()); ?>
        <?php echo $custom_error; ?>
<center><h3>Registro Onbase</h3></center>





<p> 
        <?php
         echo form_label('Fecha:<span class="required">*</span>', 'fecha_id');
           $dataFecha = array(
                      'name'        => 'fecha_id',
                      'id'          => 'fecha_id',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                    'required'	=> 'required',
                    'readonly'	=> 'readonly'
                    
					);

           echo form_input($dataFecha);
           echo form_error('fecha_id','<div>','</div>');
        ?>
</p>    

<p>
<p>
        <?php
         echo form_label('Número Registro:<span class="required">*</span>', 'registro_id');
           $dataReg = array(
                      'name'        => 'registro_id',
                      'id'          => 'registro_id',
                      'maxlength'   => '20',
                      'style'		=> 'text-align:right',
					  'required'	=> 'required',
                      'onkeypress' 	=> "escape(this);",
                      'onblur' 		=> "escape(this);",
                      'onkeyup' 	=> "escape(this);"
                    );

           echo form_input($dataReg);
           echo form_error('registro_id','<div>','</div>');
        ?>

        </p>

<input type="hidden" id="vista_flag" name="vista_flag" value="<?=$cod_gestion  ?>" >




      
<p>
	     	 <input type='button' name='cancelar' class='btn btn-default' value='Cancelar'  id='cancelar' >

 <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Enviar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar',
                       'class' => 'btn btn-success',
						
                       );

                echo form_button($data);    
                ?>
    </p> 
     <?php echo form_close(); ?>	
    
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
function escape(c){
  	 	    if( !(/[A-z\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta").hide();
		}
  			else{
  	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#registro_id').val("");
      $("#registro_id").focus();
      return false;
  				}
}
 $('#registro_onbase_div').dialog({
                autoOpen: true,
                width: 460,
                height: 460,
                modal:true,
                title:'Registro OnBase',
                close: function() {
                    $('#registro_onbase_div *').remove();
                }
            });	

$( "#fecha_id").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	maxDate: "0",
	dateFormat: 'dd/mm/yy'
		
});
	
            
            $('#cancelar').click(function(){
        	$('#registro_onbase_div').dialog('close');
       });

                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>


<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    
 </style>
