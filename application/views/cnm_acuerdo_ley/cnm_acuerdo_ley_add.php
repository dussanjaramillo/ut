<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Crear Acuerdo de Ley</h2>


<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

    <tr>
	<td>&nbsp;</td>
    <td>Acuerdo de Ley</td>
    <td>        <?php
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'maxlength'   => '40',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'onkeypress'  => 'escape(this);',
                      'onblur' 		=> 'escape(this);',
                      'onkeyup' 	=> 'escape(this);',
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Fecha de Inicio de Vigencia</td>
    <td><?php
         $datafecha_ini = array(
                      'name'        => 'fecha_ini',
                      'id'          => 'fecha_ini',
                      'maxlength'   => '128',
                      'readonly'    => 'readonly',
                      'required'    => 'required',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($datafecha_ini);
           echo form_error('fecha_ini','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <div id="alerta"></div>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>Tipo Vinculación</td>
    <td><?php
           
                  $select['1'] = "EMPLEADO PÚBLICO";
				  $select['2'] = "TRABAJADOR OFICIAL";
				  $select['3'] = "PENSIONADO";
				  $select['4'] = "EXFUNCIONARIO";
				  $select['5'] = "EMPRESAS";
				  $select['6'] = "TODOS";
            
          echo form_dropdown('tipo_vinc', $select,'','id="tipo_vinc" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_vinc','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
 	<td>Tipo de Cartera</td>
    <td><?php
		
          foreach($tipocart->result_array as $row) {
              	
                  $selecttipocartera[$row['COD_TIPOCARTERA']] = $row['NOMBRE_CARTERA'];
               }
          echo form_dropdown('tipo_cartera_id', $selecttipocartera,'','id="tipo_cartera_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_cartera_id','<div>','</div>');
        

        ?>
    </td>
  	<td>&nbsp;</td>
  </tr>
  
    <tr>
	<td>&nbsp;</td>
    <td>Monto Máximo ($)</td>
    <td>        <?php
           $datamonto_max = array(
                      'name'        => 'monto_max',
                      'id'          => 'monto_max',
                      'maxlength'   => '11',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'onkeypress'  => 'num(this);',
                      'onblur' 		=> 'num(this);',
                      'onkeyup' 	=> 'num(this);'
                    );

           echo form_input($datamonto_max);
           echo form_error('monto_max','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Plazo Máximo (Años)</td>
    <td><?php
         $dataplazo_max = array(
                      'name'        => 'plazo_max',
                      'id'          => 'plazo_max',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($dataplazo_max);
           echo form_error('plazo_max','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
        <tr>
    <td>&nbsp;</td>
    <td>Plazo Max. Refinanciación (Años)</td>
 <td><?php
         $dataplazo_max_ref = array(
                      'name'        => 'plazo_max_ref',
                      'id'          => 'plazo_max_ref',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($dataplazo_max_ref);
           echo form_error('plazo_max_ref','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  	<td>&nbsp;</td>
  </tr>
  

  
        <tr>
    <td>&nbsp;</td>
    <td>Manejo de Gracia</td>
    <td><?php
            
                  $selectman_gracia['1'] = "NO MANEJA";
				  $selectman_gracia['2'] = "GRACIA DE CAPITAL";
            	  $selectman_gracia['3'] = "GRACIA DE INTERÉS CORRIENTE";
				  $selectman_gracia['4'] = "CAPITALIZABLE";
          echo form_dropdown('man_gracia', $selectman_gracia,'','id="man_gracia" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('man_gracia','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Duración de Gracia</td>
 <td><?php
         $datadur = array(
                      'name'        => 'dur',
                      'id'          => 'dur',
                      'maxlength'   => '128',
                      'style'       => 'width : 20%;'
                    );

           echo form_input($datadur);
           echo form_error('dur','<div>','</div>');
		   
		                  $selectduracion['1'] = "DIAS";
				  $selectduracion['2'] = "MESES";
            	  $selectduracion['3'] = "AÑOS";

          echo form_dropdown('select_duracion', $selectduracion,'','id="select_duracion" style="width : 60%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('select_duracion','<div>','</div>');   
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
    <tr>
    <td>&nbsp;</td>
    <td>Incremento Cuota</td>
    <td><?php
           
                  $select_incr_cuota['1'] = "NO MANEJA";
				  $select_incr_cuota['2'] = "ANUAL";
            
          echo form_dropdown('incr_cuota', $select_incr_cuota,'','id="incr_cuota" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('incr_cuota','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Fecha de Incremento</td>
    <td><?php
         $dataperiodo_inc = array(
                      'name'        => 'periodo_inc',
                      'id'          => 'periodo_inc',
                      'readonly'          => 'readonly',
                      'maxlength'   => '128',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($dataperiodo_inc);
           echo form_error('periodo_inc','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
   <td>Porcentaje Incremento</td>
 <td><?php
         $dataporcent_inc = array(
                      'name'        => 'porcent_inc',
                      'id'          => 'porcent_inc',
                      'maxlength'   => '128',
                      'style'       => 'width : 80%;'
                    );

           echo form_input($dataporcent_inc);
           echo form_error('porcent_inc','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Abono con Cesantias</td>
    <td><?php
           
                  $select_abono_ces['1'] = "NO MANEJA";
				  $select_abono_ces['2'] = "ANUAL";
            
          echo form_dropdown('abono_ces', $select_abono_ces,'','id="abono_ces" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('abono_ces','<div>','</div>');
        ?></td>
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
       	$('#nombre').val("");
      $("#nombre").focus();
      return false;
  				}
}

 function num(c){
  c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  // x = c.value;
    // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    var num = c.value.replace(/\./g,'');
  if(!isNaN(num))
  {
   num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
   num = num.split('').reverse().join('').replace(/^[\.]/,'');
   c.value = num;
  }
 }

$('#porcent_inc').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_inc').val();
	
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


$('#porcent_abono_ces').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_abono_ces').val();
	
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


$('#porcent_abono_prim').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porcent_abono_prim').val();
	
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


$('#monto_max').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#monto_max').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==8 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==10)|| (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || (keynum==44 && keynum2!=103) || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
  
$('#plazo_max').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo_max').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==2 && (keynum != 8&&keynum != 0))  )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
}); 

$('#plazo_max_ref').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#plazo_max_ref').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==2 && (keynum != 8&&keynum != 0))  )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
}); 


$('#dur').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#dur').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==3 && (keynum != 8&&keynum != 0))  )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
}); 


$('#periodo_inc').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#periodo_inc').val();
	
	for(var i=0;i<info.length;i++){
		if(info[i]==","){
			keynum2=103;
			i=info.length;
		}
	}
	
if((info.length==3 && (keynum != 8&&keynum != 0))  )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});  
  
  
$( "#fecha_ini").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	
});

$( "#periodo_inc").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	
});


$('#cancelar').click(function(){
        	window.history.back()
       });  

  
function validar_porcentaje (campo) {
	
}

function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 