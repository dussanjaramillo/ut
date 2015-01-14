<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>



<div class="center-form" width='460' height= '460'>

 <?php     

        echo form_open_multipart(current_url()); ?>

<center><h3>Agregar Beneficiarios</h3></center>

<p>
        <?php
         echo form_label('Identificación<span class="required">*</span>', 'identificacion');
           $dataid= array(
                      'name'        => 'identificacion_ben',
                      'id'          => 'identificacion_ben',
                      'maxlength'   => '30',
                      'required'    => 'required',
                      'onkeypress'  => 'escape(this);',
                      'onblur'      => 'escape(this);',
                      'onkeyup' 	=> 'escape(this);'

                    );

          echo form_input($dataid);
           echo form_error('identificacion','<div>','</div>');
        ?>
        </p>
<div id="alerta1"></div>	
<p>
        <?php
         echo form_label('Nombres<span class="required">*</span>', 'nombres');
           $datanombres = array(
                      'name'        => 'nombres',
                      'id'          => 'nombres',
                      'maxlength'   => '35',
                      'required'    => 'required',
                      'rows'        => '3',
                      'onkeypress'  => 'escape2(this);',
                      'onblur'      => 'escape2(this);',
                      'onkeyup' 	=> 'escape2(this);'

                    );

          echo form_input($datanombres);
           echo form_error('nombres','<div>','</div>');
        ?>
        </p>
<div id="alerta2"></div>	

<p>
        <?php
         echo form_label('Apellidos<span class="required">*</span>', 'apellidos');
           $dataapell = array(
                      'name'        => 'apellidos',
                      'id'          => 'apellidos',
                      'maxlength'   => '35',
                      'required'    => 'required',
                      'rows'        => '3',
                      'onkeypress'  => 'escape3(this);',
                      'onblur'      => 'escape3(this);',
                      'onkeyup' 	=> 'escape3(this);'

                    );

          echo form_input($dataapell);
           echo form_error('apellidos','<div>','</div>');
        ?>
        </p>
    
<div id="alerta3"></div>	
<p>
        <?php
         echo form_label('Parentesco<span class="required">*</span>', 'parentesco');
           $dataparent = array(
                      'name'        => 'parentesco',
                      'id'          => 'parentesco',
                      'maxlength'   => '35',
                      'required'    => 'required',
                      'rows'        => '3',
                      'onkeypress'  => 'escape4(this);',
                      'onblur'      => 'escape4(this);',
                      'onkeyup' 	=> 'escape4(this);'

                    );

          echo form_input($dataparent);
           echo form_error('parentesco','<div>','</div>');
        ?>
        </p>
<div id="alerta2"></div>	 
        <div id="alerta"></div>	

<input type="hidden" id="vista_2" name="vista_2" value="1" >
    <input type="hidden" id="id_cartera_form" name="id_cartera_form" value="<?=$id_cartera ?>">
    <input type="hidden" id="tipo_cartera" name="tipo_cartera" value="<?=$tipo_cartera?>" >
      
<p><center>
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
    </center>
     <?php echo form_close(); ?>	
    
</div>
<script type="text/javascript" language="javascript" charset="utf-8">

  function escape(c){
  	 	    if( !(/[/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta1").hide();
		}
  			else{
  	$("#alerta1").show();
       	$('#alerta1').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#identificacion_ben').val("");
      $("#identificacion_ben").focus();
      return false;
  				}
}
  function escape2(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta2").hide();
		}
  			else{
  	$("#alerta2").show();
       	$('#alerta2').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#nombres').val("");
      $("#nombres").focus();
      return false;
  				}
}
  function escape3(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta3").hide();
		}
  			else{
  	$("#alerta3").show();
       	$('#alerta3').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#apellidos').val("");
      $("#apellidos").focus();
      return false;
  				}
}
  function escape4(c){
  	 	    if( !(/[/\*\,\.\;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(c.value)) )
		{
			$("#alerta4").hide();
		}
  			else{
  	$("#alerta4").show();
       	$('#alerta4').html('<p class="text-warning"><i class="fa fa-warning"></i> Caracter Invalido <p>');
       	$('#parentesco').val("");
      $("#parentesco").focus();
      return false;
  				}
}


 $('#agregar_beneficiario').dialog({
                autoOpen: true,
                width: 460,
                height: 460,
                modal:true,
                title:'Agregar Beneficiario',
                close: function() {
                    $('#agregar_beneficiario *').remove();
                }
            });	
            
            $('#cancelar').click(function(){
        	$('#agregar_beneficiario').dialog('close');
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
