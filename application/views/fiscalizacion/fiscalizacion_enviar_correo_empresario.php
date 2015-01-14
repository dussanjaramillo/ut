<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='500' height= '700'>
	
   <form id="retorno_fisc" action="<?= base_url('index.php/fiscalizacion/datatable') ?>" method="post" >
    <input type="hidden" id="cod_asignacion_fisc" name="cod_asignacion_fisc" value=<?=$cod_asignacion_fisc?>> 
    <input type="hidden" id="cod_nit" name="cod_nit" value=<?=$nit?>>
</form> 
 

    

 <center><h2>Enviar Correo Electrónico</h2></center>
 <?php     

        echo form_open_multipart(base_url('index.php/fiscalizacion/enviar_email_dest')); ?>
                <?php echo $custom_error; ?>
 <input type="hidden" id="cod_fisc" name="cod_fisc" value="<?=$cod_fisc  ?>" >
<input type="hidden" id="nit" name="nit" value="<?=$nit  ?>" >
<input type="hidden" id="cod_asignacion_fisc2" name="cod_asignacion_fisc2" value=<?=$cod_asignacion_fisc?>> 
  <p>
  	        <?php

           $cod_f = array(
                      'name'        => 'cod_fisc',
                      'id'          => 'cod_fisc',
                      'maxlength'   => '50',
                      'value'   => $cod_fisc,
                      'type' => 'hidden',
                      
                    );

           echo form_input($cod_f);

        ?>
        
                <?php

           $cod_n = array(
                      'name'        => 'nit',
                      'id'          => 'nit',
                      'maxlength'   => '50',
                      'value'   => $nit,
                      'type' => 'hidden',
                    );

           echo form_input($cod_n);

        ?>
  	
        <?php

           $dataasunto = array(
                      'name'        => 'para',
                      'id'          => 'para',
                      'maxlength'   => '50',
                      'required'    => 'required',
                      'value' => $email,
                      'type' => 'hidden',
       );

           echo form_input($dataasunto);
           echo form_error('para','<div>','</div>');
		   
        ?>
<div id="alerta"></div>	
<div id="alerta4"></div>	
        <?php
         echo form_label('CC:', 'cc');
           $datacc = array(
                      'name'        => 'cc',
                      'id'          => 'cc',
                      'maxlength'   => '50'
                    );

           echo form_input($datacc);
           echo form_error('cc','<div>','</div>');
        ?>

        <?php
         echo form_label('CCO:', 'cco');
           $datacco = array(
                      'name'        => 'cco',
                      'id'          => 'cco',
                      'maxlength'   => '50'
                    );

           echo form_input($datacco);
           echo form_error('cco','<div>','</div>');
        ?>

        <?php
         echo form_label('Asunto:<span class="required">*</span>', 'asunto');
           $dataasunto = array(
                      'name'        => 'asunto',
                      'id'          => 'asunto',
                      'maxlength'   => '200',
                      'required'    => 'required',
                    );

           echo form_input($dataasunto);
           echo form_error('asunto','<div>','</div>');
        ?>
<div id="alerta2"></div>	

<p>
        <?php
         
        ?> Adjunto  <?php
        				$dataAutoriza = array(
                      'name'        => 'adjunto_id',
                      'id'          => 'adjunto_id',
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
        <?php
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
        
        <?php
         echo form_label('Descripción<span class="required">*</span>', 'descripcion');
           $datadesc = array(
                      'name'        => 'descripcion',
                      'id'          => 'descripcion',
                      'value'       => set_value('descripcion'),
                      'maxlength'   => '2000',
                      'required'    => 'required',
                      'rows'        => '3'
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion','<div>','</div>');
        ?>
        </span>
        </p>
<div id="alerta3"></div>	
     	 <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >
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
     <?php echo form_close(); ?>	

</div>

 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 

<script>
$(".preload, .load").hide();


$('#enviar_correo_emp').dialog({
                autoOpen: true,
                width: 500,
                height: 700,
                modal:true,
                title:'Enviar Correo Electronico',
                close: function() {
                    $('#enviar_correo_emp *').remove();
                }
            });	

$('#cancelar').click(function(){
        	$('#enviar_correo_emp').dialog('close');
       });

$('#adjunto_id').change(function(){
	var chek = document.getElementById("adjunto_id").checked;
	if(chek==true)
	{
		$('#userfile').show();   
		$('#userfile').attr("required",true);     	
   }
    else{
    	$('#userfile').hide();
    	$('#userfile').attr("required",false);
    }
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
  	
function validateEmail(email) {

		var filter = /[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		// utilizamos test para comprobar si el parametro valor cumple la regla
		if(filter.test(email))
			return true;
		else
			return false;
  }
  
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    
 </style>
 
 <style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>
