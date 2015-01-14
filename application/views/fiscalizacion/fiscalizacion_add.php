<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form">
 
 <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
<h3>Agregar Fiscalización</h3>




<p>
        <?php
         echo form_label('Periodo Inicio<span class="required">*</span>', 'periodo_inicio');
           $dataPeriodoInicio = array(
                      'name'        => 'periodo_inicio',
                      'id'          => 'periodo_inicio',
                      'maxlength'   => '128',
                      'required'   => 'required',
                       'readonly'   => 'readonly',
                       'value'   => $inicio,
                      'style'		=> 'text-align:right'
                    );

           echo form_input($dataPeriodoInicio);
           echo form_error('periodo_inicio','<div>','</div>');
        ?>
</p>
<p>        
        <?php
         echo form_label('Periodo Fin<span class="required">*</span>', 'periodo_fin');
           $dataPeriodoFin = array(
                      'name'        => 'periodo_fin',
                      'id'          => 'periodo_fin',
                      'maxlength'   => '128',
                      'readonly'   => 'readonly',
                      'required'   => 'required',
                      'value'   => $fin,
                      'style'		=> 'text-align:right'
                    );

           echo form_input($dataPeriodoFin);
           echo form_error('periodo_fin','<div>','</div>');
        ?>
 </p>       
<p>   
     <?php
		
         echo form_label('Concepto:<span class="required">*</span>', 'concepto_id');  
              foreach($conceptos as $row) {
                  $select[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
               }
          echo form_dropdown('concepto_id', $select,'','id="concepto_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('concepto_id','<div>','</div>');
        

        ?>
  </p>  
  
      <input type="hidden" id="cod_asign" name="cod_asign" value="<?=$cod_asign?>" >    
       <input type="hidden" id="nit" name="nit" value="<?=$nit?>" > 
      <input type="hidden" id="vista_flag" name="vista_flag" value="<?=$cod_asign  ?>" >
<p>
	<center>
 <?php  echo anchor('fiscalizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
 <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Crear',
                       'class' => 'btn btn-success',
                       );

                echo form_button($data);    
                ?>
                </center>
    </p> 
     <?php echo form_close(); ?>	
    


</div>

<script type="text/javascript" language="javascript" charset="utf-8">
$(".preload, .load").hide();
$(function(){

   window.location.hash="no";
   window.location.hash="Again-No" //chrome
   window.onhashchange=function(){window.location.hash="no";}
	

	var fecha_actual = new Date();
	var fecha_per_fin= "-5y1m-"+fecha_actual.getDate()+"d";
	var dia_per_fin= "-"+fecha_actual.getDate();
	var fecha_per_ini= "-1m-"+(fecha_actual.getDate()-1)+"d";
	var dia_per_ini= "-5y-0m-"+(fecha_actual.getDate()-1)+"d";
$("#periodo_inicio").datepicker({
	
	showOn: 'button',
	buttonText: 'Periodo Inicio',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	maxDate: fecha_per_ini,
    minDate: dia_per_ini,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	onClose: function(selectedDate) {
	fecha = selectedDate.split('/');
	diafin=fechafin(fecha);
	if(selectedDate!=""){
$("#periodo_inicio").val('01'+'/'+fecha[1]+'/'+fecha[2]);	
$("#periodo_fin").datepicker("option", "minDate", diafin);
}
      }
		
});

$( "#periodo_fin").datepicker({
	
	showOn: 'button',
	buttonText: 'Periodo Fin',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	maxDate: dia_per_fin,
      minDate: fecha_per_fin,
		changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	onClose: function(selectedDate) {
	fecha2 = selectedDate.split('/');
	diafin2=fechafin(fecha2);
		if(selectedDate!=""){
$("#periodo_fin").val(diafin2);	
$("#periodo_inicio").datepicker("option", "maxDate", '01'+'/'+fecha2[1]+'/'+fecha2[2]);
}
      }
	
});
	
});

function fechafin(fecha){
	
if(fecha[1]=='01'||fecha[1]=='03'||fecha[1]=='05'||fecha[1]=='07'||fecha[1]=='08'||fecha[1]=='10'||fecha[1]=='12')
{
return('31'+'/'+fecha[1]+'/'+fecha[2]); 		
}
else if(fecha[1]=='02')
{return('28'+'/'+fecha[1]+'/'+fecha[2]); 	}
else{return('30'+'/'+fecha[1]+'/'+fecha[2]); }	
}

function ajaxValidationCallback(status, form, json, options) {
	
}
</script>
 
   