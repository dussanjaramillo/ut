<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='450' height= '560'>
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h3>Nuevo Rango</h3>
        
        
        <center>
	<b>
	Tipo Tasa Histórico:&nbsp&nbsp<?=$informacion['NOMBRE_TIPOTASA']?>
	</br>
	</br>
	Tipo De Tasa:&nbsp&nbsp<?=$informacion['NOMBRE_TASAINTERES']?>
	</br>
	</br>
	
	</b>
</center>

        
        <p>
        <?php
         echo form_label('Fecha Inicial Vigencia<span class="required">*</span>', 'fecha_inicial');
           $datafechaini = array(
                      'name'        => 'fecha_inicial',
                      'id'          => 'fecha_inicial',
                      'readonly'    => 'readonly',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'value'		=> date('d/m/Y'),
                      
                    );

           echo form_input($datafechaini);
           echo form_error('fecha_inicial','<div>','</div>');
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Fecha Final Vigencia<span class="required">*</span>', 'fecha_final');
           $datafechafin = array(
                      'name'        => 'fecha_final',
                      'id'          => 'fecha_final',
                      'maxlength'   => '128',
                      'readonly'    => 'readonly',
                      'required'    => 'required',
					  'value'		=> date('d/m/Y'),
                    );

           echo form_input($datafechafin);
           echo form_error('fecha_final','<div>','</div>');
        ?>
        </p>

        <p>
        <?php
         echo form_label('Valor Tasa (%)<span class="required">*</span>', 'valor_tasa');
           $datanombre = array(
                      'name'        => 'valor_tasa',
                      'id'          => 'valor_tasa',
                      'maxlength'   => '128',
                      'required'    => 'required',

                    );

           echo form_input($datanombre);
           echo form_error('valor_tasa','<div>','</div>');
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
                
                <input type="hidden" id="cod_tasa_historica" name="cod_tasa_historica" value=<?=$cod_tasa_hist ?> >
                <input type="hidden" id="vista_flag" name="vista_flag" value="1" >
        </p>

        <?php echo form_close(); ?>
</center>
</div>
  <script type="text/javascript">
  
 $('#rangos').dialog({
                autoOpen: true,
                width: 450,
                height: 560,
                modal:true,
                title:'Nuevo Rango',
                close: function() {
                  $('#rangos *').remove();
                
                
                }
            });	  
            
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
$('#valor_tasa').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#valor_tasa').val();
	
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



  	
$( "#fecha_inicial").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	onClose: function(selectedDate) {
        $("#fecha_final").datepicker("option", "minDate", selectedDate);
      }
	
	
		
});

$( "#fecha_final").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	onClose: function(selectedDate) {
        $("#fecha_inicial").datepicker("option", "maxDate", selectedDate);
      }
	
		
});
  
  

  
function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 