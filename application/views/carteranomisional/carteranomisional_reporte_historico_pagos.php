<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large" width='500' height= '700'>



<center>
<h2>Reportes Historico Pagos</h2>

<table>
<tr>
<td>Número de Identificación:</td>
<td><input type="text" id="identificacion" style="width : 90%;"></td>
</tr>
<tr>
<td>Tipo de Cartera:</td>
<td><select id="tipo_cartera" style="width : 96%;">
		<?php  
 foreach ($tipos as $lista ) { 
 	?>
<option value="<?= $lista->COD_TIPOCARTERA ?>"><?= $lista->NOMBRE_CARTERA ?></option>
<?php } ?>

	</select>
</td>	
</tr>

<tr>
<td>Identificación Deuda:</td>
<td><input type="text" id="identificacion_deuda" style="width : 90%;">
</td>
 </tr>
</table>
     </center>  

 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 </center>
   </p> 

    
</div>
<br>
<div id="detalle_reporte"></div>

<script type="text/javascript" language="javascript" charset="utf-8">

$('#aceptar').click(function(){
	var url3="<?= base_url('index.php/carteranomisional/reporte_historico_pagos_detalle') ?>/";
	$('#detalle_reporte').load(url3);
});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

