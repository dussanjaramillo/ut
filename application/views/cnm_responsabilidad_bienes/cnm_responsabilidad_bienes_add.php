<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Asignación de Responsabilidad de Bienes</h2>

  
<form>

<table width="780" class="tabla1">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td>Identificacion Activo</td>
    <td><input type="text" id="numero_activo" name="numero_activo" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Identificacion Empleado</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
  
</table>
<div id="alerta"></div>	 
<input type="hidden" name="cod_activos" id="cod_activos" value="<?=$cod_activos?>"> 

</form>      
 
 <input type='button' name='buscar' class='btn btn-success' value='Buscar'  id='buscar'>

<div id="bienes"></div>
</div>




    <div id='asignacion'> </div>

<script type="text/javascript" language="javascript" charset="utf-8">

 $( "#cedula" ).autocomplete({
      source: "<?php echo base_url("index.php/cnm_responsabilidad_bienes/traercedula") ?>",
      minLength: 3
      });

$('#buscar').click(function(){

    
    if ($("#cedula").val().length < 1 && $("#numero_activo").val().length < 1) 
       {
       	$("#alerta").show();
      $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Identificación Obligatorio<p>');
      $("#cedula").focus();
       return false;
  }
else{  
if($("#cedula").val().length < 1)
	{
	$.getJSON("<?= base_url('index.php/cnm_responsabilidad_bienes/verificacion_activo') ?>/"+$("#numero_activo").val(),function(data){
if(data.EXIST==0)
{ 	$("#alerta").show();
	$('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Identificación Activo no existe ingrese Identificación Valida<p>');
         $("#numero_activo").focus();
       return false;
}
else
{
	$("#alerta").hide();
	var cedula = $("#cedula").val();
	var orden = $("#numero_activo").val();
	var cod_activos2 = $("#cod_activos").val();
		var urlcrearcnm="<?= base_url('index.php/cnm_responsabilidad_bienes/asignacion_res') ?>/";
	$('#asignacion').load(urlcrearcnm,{cedula : cedula, orden: orden , cod_activos2: cod_activos2});
}

});	 

}

if($("#numero_activo").val().length < 1)
	{
		
	$.getJSON("<?= base_url('index.php/cnm_responsabilidad_bienes/verificacion_cedula') ?>/"+$("#cedula").val(),function(data){
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
	var orden = $("#numero_activo").val();
	var cod_activos2 = $("#cod_activos").val();
		var urlcrearcnm="<?= base_url('index.php/cnm_responsabilidad_bienes/asignacion_res') ?>/";
	$('#asignacion').load(urlcrearcnm,{cedula : cedula, orden: orden , cod_activos2: cod_activos2});
}

});	 
}
}

});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>


<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
</style> 