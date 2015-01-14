
<?php
if(isset($message))
{
echo $message;	
}
?>
<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



<center>
<h2>Registro Cartera No Misional</h2>

<p>   

<select name="cartera_id" id="cartera_id">
 <?php  
 foreach ($tipos as $lista ) { 
 	?>
<option value="<?= $lista->COD_TIPOCARTERA ?>"><?= $lista->NOMBRE_CARTERA ?></option>
<?php } ?>

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

<form id="calamidad" action="<?= base_url('index.php/cnm_calamidad/add') ?>" method="post" >
	<input type="hidden" id="cod_cartera_calamidad" name="cod_cartera_calamidad" >
</form>
<form id="convenio" action="<?= base_url('index.php/cnm_convenio/add') ?>" method="post" >
		<input type="hidden" id="cod_cartera_convenio" name="cod_cartera_convenio" >
	
</form>
<form id="educacion" action="<?= base_url('index.php/cnm_educacion/add') ?>" method="post" >
	<input type="hidden" id="cod_educativo_sancion" name="cod_educativo_sancion" >
</form>

<form id="educacion_prestamo" action="<?= base_url('index.php/cnm_educacion/add_prestamo') ?>" method="post" >
	<input type="hidden" id="cod_prestamo_educativo" name="cod_prestamo_educativo" >
</form>

<form id="servicio_medico" action="<?= base_url('index.php/cnm_servicio_medico/add') ?>" method="post" >
	<input type="hidden" id="cod_servicio_medico" name="cod_servicio_medico" >
</form>

<form id="responsabilidad_bienes" action="<?= base_url('index.php/cnm_responsabilidad_bienes/add') ?>" method="post" >
	<input type="hidden" id="cod_activos" name="cod_activos" >
</form>

<form id="responsabilidad_fondos" action="<?= base_url('index.php/cnm_responsabilidad_fondos/add') ?>" method="post" >
		<input type="hidden" id="cod_cartera_resp_fondos" name="cod_cartera_resp_fondos" >
</form>

<form id="prestamo_hipotecario" action="<?= base_url('index.php/cnm_prestamo_hipotecario/add') ?>" method="post" >
	<input type="hidden" id="cod_prestamo_hip" name="cod_prestamo_hip" >
</form>

<form id="cuota_parte" action="<?= base_url('index.php/cnm_cuota_parte/add') ?>" method="post" >
	
</form>

<form id="doble_mesada_pensional" action="<?= base_url('index.php/cnm_doble_mesada_pensional/add') ?>" method="post" >
	<input type="hidden" id="cod_doble_mesada" name="cod_doble_mesada" >
</form>

<form id="prestamo_ahorro" action="<?= base_url('index.php/cnm_prestamo_ahorro/add') ?>" method="post" >
	<input type="hidden" id="cod_prestamo_ahorro" name="cod_prestamo_ahorro" >
</form>

<form id="otras_carteras" action="<?= base_url('index.php/cnm_otras_carteras/add') ?>" method="post" >
	<input type="hidden" id="cod_otras_carteras" name="cod_otras_carteras" >
</form>
<div id='exist'> </div>
<script type="text/javascript" language="javascript" charset="utf-8">

$('#cartera_id').change(function(){
if($("#proceso_id").val()==2&&$("#cartera_id").val()<12)
{$('#exist').show();
	var cod_cartera = $("#cartera_id").val();

	var urlcrearcnm="<?= base_url('index.php/carteranomisional/consultacarteraexist') ?>/";
	$('#exist').load(urlcrearcnm,{cod_cartera : cod_cartera});
}
else{
	$('#exist').hide();
}
});
$('#proceso_id').change(function(){
if($("#proceso_id").val()==2&&$("#cartera_id").val()<12)
{$('#exist').show();
	var cod_cartera = $("#cartera_id").val();

	var urlcrearcnm="<?= base_url('index.php/carteranomisional/consultacarteraexist') ?>/";
	$('#exist').load(urlcrearcnm,{cod_cartera : cod_cartera});
}
else{
	$('#exist').hide();
}
});
            
 $('#aceptar').click(function(){

switch($("#cartera_id").val())
{	
	case '1':
	$('#cod_cartera_calamidad').val($("#cartera_id").val());

	 $('#calamidad').submit();
	break;
	
	case '2':
	$('#cod_cartera_convenio').val($("#cartera_id").val());
	$('#convenio').submit();
	break;
	
	case '3':
	$('#cod_educativo_sancion').val($("#cartera_id").val());
	$('#educacion').submit();
	break;
	
	case '4':
	$('#cod_prestamo_educativo').val($("#cartera_id").val());
	$('#educacion_prestamo').submit();
	break;
	
	case '5':
	$('#cod_servicio_medico').val($("#cartera_id").val());
	$('#servicio_medico').submit();
	break;
	
	case '6':
	$('#cod_activos').val($("#cartera_id").val());
	$('#responsabilidad_bienes').submit();
	break;
	
	case '7':
	$('#cod_cartera_resp_fondos').val($("#cartera_id").val());
	$('#responsabilidad_fondos').submit();
	break;
	
	case '8':
	$('#cod_prestamo_hip').val($("#cartera_id").val());
	$('#prestamo_hipotecario').submit();
	break;
	
	case '9':
	$('#cuota_parte').submit();
	break;
	
	case '10':
	$('#cod_doble_mesada').val($("#cartera_id").val());
	$('#doble_mesada_pensional').submit();
	break;
	
	case '11':
	$('#cod_prestamo_ahorro').val($("#cartera_id").val());
	$('#prestamo_ahorro').submit();
	break;
	
	
	default:
	$('#cod_otras_carteras').val($("#cartera_id").val());
	$('#otras_carteras').submit();
	break;
}

});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

