<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



 <?php     

        echo form_open_multipart(current_url()); ?>
        <?php echo $custom_error; ?>
<h2>Validar Soportes de Pago</h2>


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
         echo form_label('Hora:<span class="required">*</span>', 'hora_id');
           $dataHora = array(
                      'name'        => 'hora_id',
                      'id'          => 'hora_id',
                      'maxlength'   => '128',
                      'disabled'    => 'disabled',
                      'style'		=> 'text-align:right',
                      'value'		=> $hora
                    );

           echo form_input($dataHora);
           echo form_error('hora_id','<div>','</div>');
        ?>
</p>    

<input type="hidden" id="cod_fisc" name="cod_fisc" value="<?=$cod_fisc  ?>" >
<input type="hidden" id="nit_1" name="nit" value="<?=$nit  ?>" >

<p>   
     <?php
		
         echo form_label('Concepto:<span class="required">*</span>', 'concepto_id');  
              foreach($conceptos as $row) {
                  $select[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
               }
          echo form_dropdown('concepto_id', $select,$cod_concepto,'id="concepto_id" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('concepto_id','<div>','</div>');
        

        ?>
  </p>
  <p> 
        <?php
         echo form_label('Adjuntar:<span class="required">*</span>', 'userfile');
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
                      'maxlength'   => '200',
                      'required'    => 'required',
                      'rows'        => '3',
                      'onkeypress' 	=> "escape(this);",
                      'onblur' 		=> "escape(this);",
                      'onkeyup' 	=> "escape(this);"
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion','<div>','</div>');
        ?>
        </span>
        </p>
<div id="alerta"></div>	
      
<p>
 <?php  echo anchor('fiscalizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
 <input type="hidden" id="vista_flag" name="vista_flag" value="1" >   
     <?php echo form_close(); ?>	
    
</div>


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

 $('#descripcion').change(function(){

 	    if ($('#descripcion').val().indexOf('%') != -1 || $('#descripcion').val().indexOf('<') != -1) 
       { 	
       	$("#alerta").show();
       	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido "%|<"<p>');
       	$('#descripcion').val("");
      $("#descripcion").focus();
       return false;

  }
  else{
  	$("#alerta").hide();
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

                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>