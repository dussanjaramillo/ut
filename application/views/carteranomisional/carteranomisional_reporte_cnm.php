<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



<center>
<h2>Reportes Cartera No Misional</h2>

<p>   
	<select name="reporte_id" id="reporte_id">
<option value="1">Reporte General de Cartera</option>
<option value="2">Reporte General de Ingresos</option>
<option value="3">Reporte de Liquidación de Cartera</option>
<option value="4">Informe de Morosos</option>
<option value="5">Histórico de Pagos</option>
</select>
    
  </p>
</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 </center>
   </p> 

    
</div>

<form id="general_cartera" action="<?= base_url('index.php/carteranomisional/reporte_general_cartera') ?>" method="post" >
	
</form>

<form id="general_ingresos" action="<?= base_url('index.php/carteranomisional/reporte_general_ingresos') ?>" method="post" >
	
</form>

<form id="liquidacion_cartera" action="<?= base_url('index.php/carteranomisional/reporte_liquidacion_cartera') ?>" method="post" >
	
</form>

<form id="informe_morosos" action="<?= base_url('index.php/carteranomisional/reporte_informe_morosos') ?>" method="post" >
	
</form>

<form id="historico_pagos" action="<?= base_url('index.php/carteranomisional/reporte_historico_pagos') ?>" method="post" >
	
</form>

<script type="text/javascript" language="javascript" charset="utf-8">



$('#aceptar').click(function(){

switch($("#reporte_id").val())
{
	case '1':
	 $('#general_cartera').submit();
	break;
	
	case '2':
	$('#general_ingresos').submit();
	break;
	
	case '3':
	$('#liquidacion_cartera').submit();
	break;
	
	case '4':
	$('#informe_morosos').submit();
	break;
	
	default:
	$('#historico_pagos').submit();
	break;
}

});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

