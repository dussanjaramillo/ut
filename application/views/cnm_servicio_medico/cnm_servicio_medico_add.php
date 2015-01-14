<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Asignación de Cartera de Servicio Médico</h2>

  
<form>
<table width="780" class="tabla1">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td>Número de Orden</td>
    <td><input type="text" id="numero_orden" name="numero_orden" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Identificacion Empleado</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
   
</table>
<div id="alerta"></div>


<input type="hidden" name="cod_servicio_medico" id="cod_servicio_medico" value="<?=$cod_servicio_medico?>"> 

</form>      
<p>

 <input type='button' name='buscar' class='btn btn-success' value='Buscar'  id='buscar'>

   </p> 


</div>


    <div id='asignacion'> </div>

<script type="text/javascript" language="javascript" charset="utf-8">


           $( "#cedula" ).autocomplete({
      source: "<?php echo base_url("index.php/cnm_servicio_medico/traercedula") ?>",
      minLength: 3
      });

$('#buscar').click(function(){
	
	    if ($("#cedula").val().length < 1 && $("#numero_orden").val().length < 1) 
       {
       	$("#alerta").show();
      $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Identificación Obligatorio<p>');
      $("#cedula").focus();
       return false;
  }
else{  
if($("#cedula").val().length < 1)
	{
	$.getJSON("<?= base_url('index.php/cnm_servicio_medico/verificacion_orden') ?>/"+$("#numero_orden").val(),function(data){
if(data.EXIST==0)
{ 	$("#alerta").show();
	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Número Orden no existe ingrese Número de Orden Valido<p>');
         $("#numero_orden").focus();
       return false;
}
else
{
	$("#alerta").hide();
	var cedula = $("#cedula").val();
	var orden = $("#numero_activo").val();
	var cod_activos2 = $("#cod_activos").val();
		var urlcrearcnm="<?= base_url('index.php/cnm_servicio_medico/asignacion_res') ?>/";
	$('#asignacion').load(urlcrearcnm,{cedula : cedula, orden: orden , cod_activos2: cod_activos2});
}

});	 

}

if($("#numero_orden").val().length < 1)
	{
		
	$.getJSON("<?= base_url('index.php/cnm_servicio_medico/verificacion_cedula') ?>/"+$("#cedula").val(),function(data){
if(data.EXIST==0)
{ 	$("#alerta").show();
	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Identificación Empleado no existe ingrese Identificación Valida<p>');
        $("#cedula").focus();
       return false;
}
else
{
	$("#alerta").hide();
	
	var cedula = $("#cedula").val();
	var orden = $("#numero_orden").val();
	var cod_servicio_medico2 = $("#cod_servicio_medico").val();
		var urlcrearcnm="<?= base_url('index.php/cnm_servicio_medico/asignacion_res') ?>/";
	$('#asignacion').load(urlcrearcnm,{cedula : cedula, orden: orden, cod_servicio_medico2:cod_servicio_medico2});


}

});	 
}
}

});
           
            
                function ajaxValidationCallback(status, form, json, options){
	
}
</script>


<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
</style> 