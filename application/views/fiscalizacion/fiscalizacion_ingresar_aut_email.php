<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>



<div class="center-form" width='500' height= '700'>

 <?php     

        echo form_open_multipart(current_url()); ?>
        <?php echo $custom_error; ?>
<center><h3>Ingresar Autorización Notificación Email</h3></center>


<p>
        <?php
         echo form_label('Autoriza notificaciones:<span class="required">*</span>', 'autoriza_id');
           $dataAutoriza = array(
                      'name'        => 'autoriza_id',
                      'id'          => 'autoriza_id',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'type'		=> 'checkbox',	
                      'value'       => 'accept',
    					'checked'     => TRUE,
                    );
echo form_checkbox($dataAutoriza);
           echo form_error('autoriza_id','<div>','</div>');
        ?>
</p>


<p>
        <?php
         echo form_label('Contacto:<span class="required">*</span>', 'contacto_id');
           $dataContacto = array(
                      'name'        => 'contacto_id',
                      'id'          => 'contacto_id',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'required'	=> 'required'
                    );

           echo form_input($dataContacto);
           echo form_error('contacto_id','<div>','</div>');
        ?>
</p>    
        <div id="alerta"></div>	

<p>
        <?php
         echo form_label('Email Autorizado:<span class="required">*</span>', 'email_id');
           $dataEmail = array(
                      'name'        => 'email_id',
                      'id'          => 'email_id',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'required'	=> 'required'
                    );

           echo form_input($dataEmail);
           echo form_error('email_id','<div>','</div>');
        ?>
</p> 
<div id="alerta"></div>	

<input type="hidden" id="vista_flag" name="vista_flag" value="<?=$nit  ?>" >
<input type="hidden" id="cod_fisc" name="cod_fisc" value="<?=$cod_fisc  ?>" >
<input type="hidden" id="nit" name="nit" value="<?=$nit  ?>" >
<input type="hidden" id="cod_concepto" name="cod_concepto" value="<?=$cod_concepto  ?>" >

<p>
        <?php
         echo form_label('Fecha:<span class="required">*</span>', 'fecha_id');
           $dataFecha = array(
                      'name'        => 'fecha_id',
                      'id'          => 'fecha_id',
                      'maxlength'   => '128',
                      'readonly'    => 'readonly',
                      'style'		=> 'text-align:right',
                      'value'		=> $fecha
                    );

           echo form_input($dataFecha);
           echo form_error('fecha_id','<div>','</div>');
        ?>
</p>    
  <p> 
        <?php
         echo form_label('Documento:<span class="required">*</span>', 'userfile');
           $dataAdjuntar = array(
                      'name'        => 'userfile',
                      'id'          => 'userfile',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'type' 		=> 'file',
                      'required'    => 'required',
                    );

           echo form_upload($dataAdjuntar);
           echo form_error('userfile','<div>','</div>');
        ?>
</p> 
        <p>

        </span>
        </p>

      
<p>
	     	 <input type='button' name='cancelar' class='btn btn-default' value='Cancelar'  id='cancelar' >

 <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit_button',
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

  $('#contacto_id').change(function(){

 	    if ($('#contacto_id').val().indexOf('%') != -1 || $('#contacto_id').val().indexOf('<') != -1) 
       { 	
       	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido "%|<"<p>');
       	$('#contacto_id').val("");
      $("#contacto_id").focus();
       return false;

  }
  else{
  	$("#alerta").hide();
  }

});

 $('#ingresar_autorizacion_email').dialog({
                autoOpen: true,
                width: 500,
                height: 700,
                modal:true,
                title:'Ingresar Autorización Notificación Email',
                close: function() {
                    $('#ingresar_autorizacion_email *').remove();
                }
            });	
            
            $('#cancelar').click(function(){
        	$('#ingresar_autorizacion_email').dialog('close');
       });
       
       
        $('#userfile').change(function(){
documento = document.getElementById("userfile").value;
var extension = documento.split(".");
 	    if( extension[extension.length-1]!='pdf' )
		{
$('#userfile').val("");
{alert("formato no valido, solo se puede adjuntar un documento en formato pdf");}
  }
}); 
       
       function validar_email(valor)
	{
		// creamos nuestra regla con expresiones regulares.
		var filter = /[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		// utilizamos test para comprobar si el parametro valor cumple la regla
		if(filter.test(valor))
			return true;
		else
			return false;
	}
	// cuando presionamos el boton verificar
	
       $('#submit_button').click(function(){
       	  $("#alerta").show();
        	if(validar_email($("#email_id").val()))
		{
		
		}else
		{
			      $('#alerta').html('<p class="text-error">Campo Email Invalido<p>');
      $("#email_id").focus();
       return false;
		}
         $("#alerta").hide();
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
