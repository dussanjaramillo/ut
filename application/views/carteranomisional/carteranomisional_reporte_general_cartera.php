<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large" width='500' height= '700'>



<center>
<h2>Reportes General Cartera</h2>

<table>

<tr>
	<td>&nbsp;</td>
	<td>Regional:</td>
	<td><select id="regional" style="width : 80%;">
		<option value="1">Cali</option>
		<option value="2">Cundinamarca</option>
		<option value="3">Medellin</option>
	</select></td>
	<td>&nbsp;</td>
	<td>Fecha Inicio:</td>
	<td><input type="text" id="fecha_inicio" style="width : 70%;"></td>
	<td>&nbsp;</td>
	<td>Fecha Fin:</td>
	<td><input type="text" id="fecha_fin" style="width : 70%;"></td>
	<td>&nbsp;</td>
</tr>	

<tr>
	<td>&nbsp;</td>
	<td>Periodos:</td>
	<td><select id="periodo" style="width : 80%;">

			<option value="1">Enero</option>
		<option value="2">Febrero</option>
		<option value="3">Marzo</option>
				
	</select>
	</td>
	<td>&nbsp;</td>
	<td>Tipo de Cartera:</td>
	<td><select id="tipo_cartera" style="width : 76%;">
		<?php  
 foreach ($tipos as $lista ) { 
 	?>
<option value="<?= $lista->COD_TIPOCARTERA ?>"><?= $lista->NOMBRE_CARTERA ?></option>
<?php } ?>

	</select></td>
	<td>&nbsp;</td>
	<td>Tipo de Funcionario:</td>
	<td><select id="tipo_funcionario" style="width : 80%;">
				<option value="1">Funcionario Publico</option>
		<option value="2">Trabajador Oficial</option>
		<option value="3">Pensionado</option>
	</select></td>
	<td>&nbsp;</td>
</tr>	
</table>

</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 </center>
   </p> 

    
</div>
<br>
<div id="detalle_reporte"></div>

<script type="text/javascript" language="javascript" charset="utf-8">

$( "#fecha_inicio").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Inicio',
	buttonImage: 'http://jqueryui.com/resources/demos/datepicker/images/calendar.gif',
	buttonImageOnly: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});


$( "#fecha_fin").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Fin',
	buttonImage: 'http://jqueryui.com/resources/demos/datepicker/images/calendar.gif',
	buttonImageOnly: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});

$('#aceptar').click(function(){
	var url3="<?= base_url('index.php/carteranomisional/reporte_general_cartera_detalle') ?>/";
	$('#detalle_reporte').load(url3);
});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

