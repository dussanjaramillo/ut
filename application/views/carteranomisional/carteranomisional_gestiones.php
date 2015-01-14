<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
switch ($attipoc) {
	case 1:
	$height=300;	
		break;
	case 2:
	$height=300;	
		break;	
	case 3:
	$height=300;	
		break;	
	case 4:
	$height=300;	
		break;	
	case 5:
	$height=300;	
		break;	
	case 6:
	$height=300;	
		break;	
	case 7:
	$height=300;	
		break;	
	case 8:
	$height=500;	
		break;	
	case 9:
	$height=300;	
		break;	
	case 10:
	$height=345;	
		break;	
	case 11:
	$height=380;	
		break;	
	
	default:
	$height=300;	
		break;
}

?>


<div class="center-form" width='500' height= "<?=$height?>"> 

<center><h2>Gestión</h2></center>

<?php 
if($attipoc==8||$attipoc==11){
?>
<center>
<input type='button' name='generacion_refi' class='btn btn-success' value='Generación Refinanciación'  class="btn btn-large  btn-primary" id="generacion_refi" style="width : 340px;">
</center>
<p></p>
<center>
<input type='button' name='generacion_reliq' class='btn btn-success' value='Generación Reliquidación'  class="btn btn-large  btn-primary" id="generacion_reliq" style="width : 340px;">
</center>
<p></p>
<?php }
/* 
<center>
<input type='button' name='respuesta_recurso' class='btn btn-success' value='Respuesta Recurso'  class="btn btn-large  btn-primary" id="respuesta_recurso" style="width : 340px;">
</center>
<p></p>
<center>
<input type='button' name='generacion_noti' class='btn btn-success' value='Generación Notificación'  class="btn btn-large  btn-primary" id="generacion_noti" style="width : 340px;">
</center>
<p></p>
<center>
<input type='button' name='enviar_correo_empleado' class='btn btn-success' value='Enviar Correo Empleado'  class="btn btn-large  btn-primary" id="enviar_correo_empleado" style="width : 340px; heigth : 1px">
</center>
<p></p>
*/ 
if($attipoc==8){
/*
<center>
<input type='button' name='traslado_vivienda' class='btn btn-success' value='Solicitud Traslado Vivienda'  class="btn btn-large  btn-primary" id="traslado_vivienda" style="width : 340px; heigth : 1px">
</center>
<p></p>
<?php */?>
<center>
<input type='button' name='pagos_seguros' class='btn btn-success' value='Registro Pagos Seguros'  class="btn btn-large  btn-primary" id="pagos_seguros" style="width : 340px; heigth : 1px">
</center>
<p></p>

<center>
<input type='button' name='ver_pago_seguro_i' class='btn btn-success' value='Ver Pagos Seguro Incendio'  class="btn btn-large  btn-primary" id="ver_pago_seguro_i" style="width : 340px; heigth : 1px">
</center>
<p></p>

<center>
<input type='button' name='ver_pago_seguro_v' class='btn btn-success' value='Ver Pagos Seguro Vida'  class="btn btn-large  btn-primary" id="ver_pago_seguro_v" style="width : 340px; heigth : 1px">
</center>
<p></p>
<?php } if($attipoc==10){ /*
<center>
<input type='button' name='cobro_beneficiarios' class='btn btn-success' value='Generación Cobro Beneficiarios'  class="btn btn-large  btn-primary" id="cobro_beneficiarios" style="width : 340px; heigth : 1px">
</center>
<p></p>*/?>
<?php } if($attipoc!=8){ ?>
<center>
<input type='button' name='paso_coactivo' class='btn btn-success' value='Paso a Cobro Coactivo'  class="btn btn-large  btn-primary" id="paso_coactivo" style="width : 340px; heigth : 1px">
</center>
<p></p>
<?php } if($attipoc==8){ ?>
<center>
<input type='button' name='paso_judicial' class='btn btn-success' value='Paso a Cobro Judicial'  class="btn btn-large  btn-primary" id="paso_judicial" style="width : 340px; heigth : 1px">
</center>
<p></p>
<?php } ?>
<center>
<input type='button' name='documentos' class='btn btn-success' value='Agregar Documentos'  class="btn btn-large  btn-primary" id="documentos" style="width : 340px; heigth : 1px">
</center>

<p></p>
<input type="hidden" id="id_cnm" name="id_cnm" value="<?=$id_cnm?>" >
<input type="hidden" id="atdoc" name="atdoc" value="<?=$atdoc?>" >
<input type="hidden" id="atarchivos" name="atarchivos" value="<?=$atarchivos?>" >
<input type="hidden" id="tipo_cartera" name="tipo_cartera" value="<?=$attipoc?>" >
</div>





<form id="refinanciacion" action="<?= base_url('index.php/carteranomisional/refinanciacion') ?>" method="post" >
	<input type="hidden" id="id_cnm_refi" name="id_cnm_refi">
		<input type="hidden" id="tipo_cartera_rf" name="tipo_cartera_rf">
</form>

<form id="reliquidacion_form" action="<?= base_url('index.php/carteranomisional/reliquidacion') ?>" method="post" >
	<input type="hidden" id="id_cnm_reli" name="id_cnm_reli">
	<input type="hidden" id="tipo_cartera_rl" name="tipo_cartera_rl">
</form>

<form id="recurso_form" action="<?= base_url('index.php/carteranomisional/recurso') ?>" method="post" >
	
</form>

<form id="notificacion_form" action="<?= base_url('index.php/carteranomisional/gen_notificacion') ?>" method="post" >
	
</form>

<form id="traslado_vivienda_form" action="<?= base_url('index.php/carteranomisional/traslado_vivienda') ?>" method="post" >
	
</form>

<form id="pagos_seguros_form" action="<?= base_url('index.php/carteranomisional/pagos_seguros') ?>" method="post" >
	
</form>

<form id="cobro_beneficiarios_form" action="<?= base_url('index.php/carteranomisional/cobro_beneficiario') ?>" method="post" >
	<input type="hidden" id="id_cnm_cobro" name="id_cnm_cobro">
</form>


<form id="coactivo_form" action="<?= base_url('index.php/carteranomisional/cobro_coactivo') ?>" method="post" >
	<input type="hidden" id="id_cnm_coact" name="id_cnm_coact">
	<input type="hidden" id="atdoc_coact" name="atdoc_coact">
	<input type="hidden" id="archivos_coact" name="archivos_coact">
</form>

<form id="judicial_form" action="<?= base_url('index.php/carteranomisional/cobro_judicial') ?>" method="post" >
	<input type="hidden" id="id_cnm_judicial" name="id_cnm_judicial">
	<input type="hidden" id="atdoc_judicial" name="atdoc_judicial">
	<input type="hidden" id="archivos_judicial" name="archivos_judicial">
</form>

<form id="documentos_form" action="<?= base_url('index.php/carteranomisional/documentos') ?>" method="post" >
	<input type="hidden" id="id_deuda_docs" name="id_deuda_docs">
	<input type="hidden" id="tipo_cartera_docs" name="tipo_cartera_docs">
</form>
<div id="gestion"></div>

<div id='enviar_correo_emp'> </div>

<div id='pago_seguro'> </div>

<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }

 </style>
 
 

  <script type="text/javascript" language="javascript" charset="utf-8">
  $(".preload, .load").hide();
 $('#gestion').dialog({
                autoOpen: true,
                width: 500,
                height: <?=$height?>,
                modal:true,
                title:'Gestión',
                close: function() {
                  $('#gestion *').remove();
                
                
                }
            });	


 $('#ver_pago_seguro_i').click(function(){

var id_cartera=$("#id_cnm").val();
var concepto='12';
var urlpago="<?= base_url('index.php/carteranomisional/ver_pagos_seguros') ?>/";
	$('#pago_seguro').load(urlpago, {id_cartera : id_cartera ,concepto : concepto });
});

$('#ver_pago_seguro_v').click(function(){

var id_cartera=$("#id_cnm").val();
var concepto='13';
var urlpago ="<?= base_url('index.php/carteranomisional/ver_pagos_seguros') ?>/";
	$('#pago_seguro').load(urlpago, {id_cartera : id_cartera ,concepto : concepto });
});
 
 
  $('#generacion_refi').click(function(){
$('#id_cnm_refi').val($('#id_cnm').val());
$('#tipo_cartera_rf').val($('#tipo_cartera').val());
$('#refinanciacion').submit();

}); 	



  $('#generacion_reliq').click(function(){
$('#id_cnm_reli').val($('#id_cnm').val());
$('#tipo_cartera_rl').val($('#tipo_cartera').val());
$('#reliquidacion_form').submit();

}); 

  $('#respuesta_recurso').click(function(){

$('#recurso_form').submit();

}); 


  $('#generacion_noti').click(function(){

$('#notificacion_form').submit();

}); 

$('#enviar_correo_empleado').click(function(){

var urlcorreoempleado="<?= base_url('index.php/carteranomisional/enviar_correo_empleado') ?>/";
	$('#enviar_correo_emp').load(urlcorreoempleado);
});


  $('#traslado_vivienda').click(function(){

$('#traslado_vivienda_form').submit();

}); 

  $('#pagos_seguros').click(function(){

$('#pagos_seguros_form').submit();

}); 

  $('#cobro_beneficiarios').click(function(){
$('#id_cnm_cobro').val($('#id_cnm').val());
$('#cobro_beneficiarios_form').submit();

}); 

  $('#paso_coactivo').click(function(){
  	
  	var cnm_atdoc=$("#atdoc").val();
  	var cnm_coact=$("#id_cnm").val();
  	var atarchivos=$("#atarchivos").val();
  	
		$('#id_cnm_coact').val(cnm_coact);
		$('#atdoc_coact').val(cnm_atdoc);
		$('#archivos_coact').val(atarchivos);
		
          var confirmar = confirm("Desea pasar esta Cartera a Cobro Coactivo?");
          if(confirmar == true){
$('#coactivo_form').submit();
        }
});

  $('#paso_judicial').click(function(){
  	
  	var cnm_atdoc=$("#atdoc").val();
  	var cnm_coact=$("#id_cnm").val();
  	var atarchivos=$("#atarchivos").val();
  	
		$('#id_cnm_judicial').val(cnm_coact);
		$('#atdoc_judicial').val(cnm_atdoc);
		$('#archivos_judicial').val(atarchivos);
		
          var confirmar = confirm("Desea pasar esta Cartera a Cobro Judicial?");
          if(confirmar == true){
$('#judicial_form').submit();
        }
});

  $('#documentos').click(function(){
$('#id_deuda_docs').val($('#id_cnm').val());
$('#tipo_cartera_docs').val($('#tipo_cartera').val());
$('#documentos_form').submit();

}); 	
    function ajaxValidationCallback(status, form, json, options) {
	
}
  </script>
   