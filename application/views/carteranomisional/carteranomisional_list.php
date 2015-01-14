<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php
if(isset($message))
{
echo $message;	
}

?>

<center>
<h2>Consulta Cartera No Misional</h2>

  
<form>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td><b>IDENTIFICACION:</td>
    <td><input type="text" id="identificacion" name="identificacion" style="width : 200px;"></td>
    <td>&nbsp;</td>
    <td><b>TIPO DE CARTERA:&nbsp</td>
    <td>
<select name="cartera_id" id="cartera_id">
 <?php  
 foreach ($tipos as $detallea ) { 
 	?>
<option value="<?= $detallea->COD_TIPOCARTERA ?>"><?= $detallea->NOMBRE_CARTERA ?></option>
<?php } ?>

</select>
    	
    	</td>
    	
    <td>&nbsp;</td>
  </tr>
<tr>
  	<td>&nbsp;</td>
    <td><b>IDENTIFICADOR DEUDA:</td>
    <td><input type="text" id="deuda" name="deuda" style="width : 200px;"></td>
    <td>&nbsp;</td>
    <td><b>ESTADO:&nbsp</td>
    <td>
<select name="estado_id" id="estado_id">
 <?php  
 foreach ($estados as $detalleest ) { 
 	?>
<option value="<?= $detalleest->COD_EST_CARTERA ?>"><?= $detalleest->DESC_EST_CARTERA ?></option>
<?php } ?>

</select>
    	
    	</td>
    <td>&nbsp;</td>
  </tr>  
   
  
 
</table>
</form>

<div id="alerta"></div>	 

<br /><p>
 <center>	 
 <input type='button' name='consultar' class='btn btn-success' value='Consultar'  id='consultar' >
 </center>
   </p> 

<form id="calamidad" action="<?= base_url('index.php/cnm_calamidad/lista') ?>" method="post" >
	<input type="hidden" id="cod_cartera_calamidad" name="cod_cartera_calamidad" >
	<input type="hidden" id="cedula" name="cedula" >
</form>
<form id="convenio" action="<?= base_url('index.php/cnm_convenio/lista') ?>" method="post" >
	<input type="hidden" id="cod_cartera_convenio" name="cod_cartera_convenio" >
	<input type="hidden" id="nit" name="nit" >
</form>
<form id="educacion" action="<?= base_url('index.php/cnm_educacion/lista') ?>" method="post" >
	
</form>

<form id="educacion_prestamo" action="<?= base_url('index.php/cnm_educacion/detalle_prestamo') ?>" method="post" >
	
</form>

<form id="servicio_medico" action="<?= base_url('index.php/cnm_servicio_medico/detalle') ?>" method="post" >
	
</form>

<form id="responsabilidad_bienes" action="<?= base_url('index.php/cnm_responsabilidad_bienes/detalle') ?>" method="post" >
	
</form>

<form id="responsabilidad_fondos" action="<?= base_url('index.php/cnm_responsabilidad_fondos/detalle') ?>" method="post" >
	
</form>

<form id="prestamo_hipotecario" action="<?= base_url('index.php/cnm_prestamo_hipotecario/detalle') ?>" method="post" >
	
</form>

<form id="cuota_parte" action="<?= base_url('index.php/cnm_cuota_parte/detalle') ?>" method="post" >
	
</form>

<form id="doble_mesada_pensional" action="<?= base_url('index.php/cnm_doble_mesada_pensional/detalle') ?>" method="post" >
	
</form>

<form id="prestamo_ahorro" action="<?= base_url('index.php/cnm_prestamo_ahorro/detalle') ?>" method="post" >
	
</form>

<form id="otras_carteras" action="<?= base_url('index.php/cnm_otras_carteras/detalle') ?>" method="post" >
	
</form>

<div id='consulta'></div>
<div id="resultado"></div>	
<script type="text/javascript" language="javascript" charset="utf-8">


 $('#consultar').click(function(){


switch($("#cartera_id").val())
{	
	case '1':
	
	var estado=$("#estado_id").val();
	var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();
	var urlconsultarcnm="<?= base_url('index.php/cnm_calamidad/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda });
	
	break;
	
	case '2':
	var estado=$("#estado_id").val();	
	var cartera_id=$("#cartera_id").val();
	var nit= $("#identificacion").val();
	var id_deuda=$("#deuda").val();
	var urlconsultarcnm="<?= base_url('index.php/cnm_convenio/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , nit : nit, estado : estado, id_deuda : id_deuda });
	
	break;
	
	case '3':
	var estado=$("#estado_id").val();	
	var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_educacion/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda });
	break;
	
	case '4':
	var estado=$("#estado_id").val();	
	var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_educacion/lista_prestamo') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda });
	break;
	
	case '5':
	var estado=$("#estado_id").val();	
		var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_servicio_medico/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda });

	break;
	
	case '6':
	var estado=$("#estado_id").val();	
		var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_responsabilidad_bienes/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda });

	break;
	
	case '7':
	var estado=$("#estado_id").val();	
	var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_responsabilidad_fondos/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});
	break;
	
	case '8':
	var estado=$("#estado_id").val();	
	var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_prestamo_hipotecario/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});
	

	break;
	
	case '9':
	var estado=$("#estado_id").val();
var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_cuota_parte/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});

	break;
	
	case '10':
	var estado=$("#estado_id").val();
		var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_doble_mesada_pensional/lista') ?>/";
	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});	
	break;
	
	case '11':
	var estado=$("#estado_id").val();
		var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_prestamo_ahorro/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});	
	break;
	
	
	default:
	var estado=$("#estado_id").val();
			var cartera_id=$("#cartera_id").val();
	var cedula= $("#identificacion").val();
	var id_deuda=$("#deuda").val();	
	var urlconsultarcnm="<?= base_url('index.php/cnm_otras_carteras/lista') ?>/";

	$('#consulta').load(urlconsultarcnm,{cartera_id : cartera_id , cedula : cedula, estado : estado, id_deuda : id_deuda});	

	break;
}

});


            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

