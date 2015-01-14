<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form">
 <center>
 	       <?php     
        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Nuevo Tipo de Tasa de Interés</h2>

        <p>
        <?php
         echo form_label('Nombre Tipo de Tasa<span class="required">*</span>', 'nombre');
           $datanombre = array(
                      'name'        => 'nombre',
                      'id'          => 'nombre',
                      'value'       => set_value('nombre'),
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'readonly'    => 'readonly',
                      'value'		=> $nombre
                    );

           echo form_input($datanombre);
           echo form_error('nombre','<div>','</div>');
        ?>
        </p>

        <p>
        <?php
         echo form_label('Tipo<span class="required"></span>', 'tipo_id');  
            
                  $select['1'] = "EFECTIVA";
				  $select['2'] = "NOMINAL";
            
          echo form_dropdown('tipo_id', $select,'','id="tipo_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_id','<div>','</div>');
        ?>
        </span>
        </p>
 
          <p>   
     <?php
		
         echo form_label('Tipo de Periodo que la Tasa Remunera:<span class="required">*</span>', 'tipo_periodo_id');  
              //var_dump($formaPago);
			  //die();
              foreach($tipoPeriodo->result_array as $row) {
              	
                  $selecttipoperiodo[$row['COD_TIPOPERIODOTASA']] = $row['NOMBRE_TIPOPERIODO'];
               }
          echo form_dropdown('tipo_periodo_id', $selecttipoperiodo,'','id="tipo_periodo_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_periodo_id','<div>','</div>');
        

        ?>
  </p>   
 <input type="hidden" id="periodo_id" name="periodo_id" value="1" >
     <?php
		/*
         echo form_label('Periodo de Liquidación de Intereses:<span class="required">*</span>', 'periodo_liq_id');  
              //var_dump($formaPago);
			  //die();
              foreach($periodoLiq->result_array as $row) {
              	
                  $selectperiodo[$row['COD_PERIODOLIQUIDACION']] = $row['NOMBRE_PERIODOLIQUIDACION'];
               }
          echo form_dropdown('periodo_liq_id', $selectperiodo,'','id="periodo_liq_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('periodo_liq_id','<div>','</div>');
        

        */?>
   
        
        <p>   
     <?php
		
         echo form_label('Forma de Pago de Intereses:<span class="required">*</span>', 'forma_pago_id');  
              //var_dump($formaPago);
			  //die();
              foreach($formaPago->result_array as $row) {
              	
                  $selectformap[$row['COD_FORMA_PAGO_INTERESES']] = $row['NOMBRE_FORMAPAGO'];
               }
          echo form_dropdown('forma_pago_id', $selectformap,'','id="forma_pago_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('forma_pago_id','<div>','</div>');
        

        ?>
  </p>  
        <p>
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
  	$('#tipo_periodo_id').attr("readonly",true); 
	$('#tipo_periodo_id').attr("disabled",true);
$('#tipo_id').change(function(){
var nombresep=$("#nombre").val().split('.');
if($("#tipo_id").val()==1)
{	$('#periodo_id').val(1);
	$("#tipo_periodo_id").val(1)
	var nombreanidado = "E.A."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	$('#tipo_periodo_id').attr("readonly",true); 
	$('#tipo_periodo_id').attr("disabled",true);  

}
else{
	var nombreanidado = "N."+nombresep[1]+"."+nombresep[2]+".";	
	$('#nombre').val(nombreanidado);
	$('#tipo_periodo_id').attr("readonly",false);
	$('#tipo_periodo_id').attr("disabled",false);    	
}
       });  
       
       
       $('#tipo_periodo_id').change(function(){
var nombresep=$("#nombre").val().split('.');
if($("#tipo_id").val()==2){
	$('#periodo_id').val($('#tipo_periodo_id').val());
switch ($("#tipo_periodo_id").val())
{
	case '1':
	var nombreanidado = nombresep[0]+".A"+"."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	break;
	
	case '2':
	var nombreanidado = nombresep[0]+".S"+"."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	break;
	case '3':
	var nombreanidado = nombresep[0]+".T"+"."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	break;
	case '4':
	var nombreanidado = nombresep[0]+".M"+"."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	break;
	
	default :		
	var nombreanidado = nombresep[0]+".D"+"."+nombresep[2]+".";
	$('#nombre').val(nombreanidado);
	break;
}
}

       }); 
       
       
$('#forma_pago_id').change(function(){
var nombresep=$("#nombre").val().split('.');
if($("#forma_pago_id").val()==1)
{
	var nombreanidado = nombresep[0]+"."+nombresep[1]+".V.";
	$('#nombre').val(nombreanidado);

}
else{
	var nombreanidado = nombresep[0]+"."+nombresep[1]+".A.";	
	$('#nombre').val(nombreanidado);	
}
       });   
  
$('#cancelar').click(function(){
        	window.history.back()
       });  
  
function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 