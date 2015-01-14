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
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["NOMBRE_ACUERDO"]
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
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["FECHA_INICIO"]
                    );

           echo form_input($datafecha_ini);
           echo form_error('fecha_ini','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
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
            
          echo form_dropdown('tipo_vinc', $select,$acuerdo["TIPO_VINCULACION"],'id="tipo_vinc" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_vinc','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Tipo Cuota</td>
    <td><?php
            
                  $selecttip['1'] = "FIJA";
				  $selecttip['2'] = "VARIABLE";
            
          echo form_dropdown('tipo_cuota', $selecttip,$acuerdo["TIPO_CUOTA"],'id="tipo_cuota" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_cuota','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Tipo de Cartera</td>
    <td><?php
		
          foreach($tipocart->result_array as $row) {
              	
                  $selecttipocartera[$row['COD_TIPOCARTERA']] = $row['NOMBRE_CARTERA'];
               }
          echo form_dropdown('tipo_cartera_id', $selecttipocartera,$acuerdo["COD_TIPOCARTERA"],'id="tipo_cartera_id" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_cartera_id','<div>','</div>');
        

        ?>
   </td>
    <td>&nbsp;</td>
    <td>Porc. de Refinanciación(%)</td>
 <td><input type="text" name="porc_ref" id="porc_ref" style= 'width : 80%;' required ='required' value= "<?=$acuerdo["PORCE_ABONO_CESANT"]?>"/>
</td>
  	<td>&nbsp;</td>
  </tr>
  
  
        <tr>
    <td>&nbsp;</td>
    <td>Plazo Max. Refinanciación</td>
 <td><?php
         $dataplazo_max_ref = array(
                      'name'        => 'plazo_max_ref',
                      'id'          => 'plazo_max_ref',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["PLAZO_MAX_REFINANC"]
                    );

           echo form_input($dataplazo_max_ref);
           echo form_error('plazo_max_ref','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Forma Aplicación Pago</td>
<td><?php
            
                  $selectforma_ap_pago['1'] = "CREDITO";
				  $selectforma_ap_pago['2'] = "PORCENTUAL";
            
          echo form_dropdown('forma_ap_pago', $selectforma_ap_pago,$acuerdo["FORMA_APLI_PAGO"],'id="forma_ap_pago" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('forma_ap_pago','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
  <tr>
	<td>&nbsp;</td>
    <td>Monto Máximo ($)</td>
    <td>        <?php
           $datamonto_max = array(
                      'name'        => 'monto_max',
                      'id'          => 'monto_max',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["MONTO_MAXIMO"]
                    );

           echo form_input($datamonto_max);
           echo form_error('monto_max','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Plazo Máximo</td>
    <td><?php
         $dataplazo_max = array(
                      'name'        => 'plazo_max',
                      'id'          => 'plazo_max',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["PLAZO_MAXIMO"]
                    );

           echo form_input($dataplazo_max);
           echo form_error('plazo_max','<div>','</div>');
        ?></td>
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
          echo form_dropdown('man_gracia', $selectman_gracia,$acuerdo["MANEJO_GRACIA"],'id="man_gracia" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('man_gracia','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Duración de Gracia</td>
 <td><?php
         $datadur = array(
                      'name'        => 'dur',
                      'id'          => 'dur',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 20%;',
                      'value'		=> $acuerdo["DURACION_GRACIA"]
                    );

           echo form_input($datadur);
           echo form_error('dur','<div>','</div>');
		   
		                  $selectduracion['1'] = "DIAS";
				  $selectduracion['2'] = "MESES";
            	  $selectduracion['3'] = "AÑOS";

          echo form_dropdown('select_duracion', $selectduracion,$acuerdo["DURACION_GRACIA"],'id="select_duracion" style="width : 60%;" class="chosen" data-placeholder="seleccione..." ');
         
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
				  $select_incr_cuota['3'] = "SEMESTRAL";
            
          echo form_dropdown('incr_cuota', $select_incr_cuota,$acuerdo["INCREMENTO_CUOTA"],'id="incr_cuota" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('incr_cuota','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Periodo de Incremento(mes)</td>
    <td><?php
         $dataperiodo_inc = array(
                      'name'        => 'periodo_inc',
                      'id'          => 'periodo_inc',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["PERIODO_INCREMENTO"]
                    );

           echo form_input($dataperiodo_inc);
           echo form_error('periodo_inc','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Abono con Cesantias</td>
    <td><?php
           
                  $select_abono_ces['1'] = "NO MANEJA";
				  $select_abono_ces['2'] = "ANUAL";
				  $select_abono_ces['3'] = "SEMESTRAL";
            
          echo form_dropdown('abono_ces', $select_abono_ces,$acuerdo["ABONO_CESANTIAS"],'id="abono_ces" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('abono_ces','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Porcentaje a Abonar</td>
    <td><?php
         $dataporcent_abono_ces = array(
                      'name'        => 'porcent_abono_ces',
                      'id'          => 'porcent_abono_ces',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["PORCE_ABONO_CESANT"]
                    );

           echo form_input($dataporcent_abono_ces);
           echo form_error('porcent_abono_ces','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>
    <td>Abono con Primas</td>
    <td><?php
           
                  $select_abono_prim['1'] = "NO MANEJA";
				  $select_abono_prim['2'] = "ANUAL";
				  $select_abono_prim['3'] = "SEMESTRAL";
            
          echo form_dropdown('abono_prim', $select_abono_prim,$acuerdo["ABONO_PRIMAS"],'id="abono_prim" style="width : 86%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('abono_prim','<div>','</div>');
        ?></td>
    <td>&nbsp;</td>
    <td>Porcentaje a Abonar</td>
    <td><?php
         $dataporcent_abono_prim = array(
                      'name'        => 'porcent_abono_prim',
                      'id'          => 'porcent_abono_prim',
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'style'       => 'width : 80%;',
                      'value'		=> $acuerdo["PORCEN_ABONA_PRIMA"]
                    );

           echo form_input($dataporcent_abono_prim);
           echo form_error('porcent_abono_prim','<div>','</div>');
        ?></td>
  	<td>&nbsp;</td>
  </tr>
  
  </table>

        



                <?php  echo anchor('cnm_tipocartera', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
  
//  $(document).ready(function(){
  	
//$("#porc_ref").numeric(","); 
//});
$('#porc_ref').keypress(function(e){
	
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#porc_ref').val();
	
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
	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && keynum2!=103 && info.length==10)|| (keynum==44 && info.length==0) )
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
	
if((info.length==3 && (keynum != 8&&keynum != 0))  )
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
	
if((info.length==3 && (keynum != 8&&keynum != 0))  )
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
	buttonImage: 'http://jqueryui.com/resources/demos/datepicker/images/calendar.gif',
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