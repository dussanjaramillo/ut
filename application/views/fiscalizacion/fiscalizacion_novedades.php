<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='500' height= '550'> 

<center><h2>Novedades</h2></center>

<input type="hidden" id="cod_fiscalizacion_1" name="cod_fiscalizacion" value="<?=$cod_fisc  ?>" >
<input type="hidden" id="nit_1" name="nit" value="<?=$nit  ?>" >
<input type="hidden" id="cod_concepto_1" name="cod_concepto" value="<?=$cod_concepto  ?>" >

<center>
<input type='button' name='nueva_gestion' class='btn btn-success' value='Ingresar Gestión'  class="btn btn-large  btn-primary" id="nueva_gestion" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<center>
<input type='button' name='llamada_empresa' class='btn btn-success' value='Realizar Llamada'  class="btn btn-large  btn-primary" id="llamada_empresa" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<center>
<input type='button' name='notificacion_personal' class='btn btn-success' value='Comunicación Personal'  class="btn btn-large  btn-primary" id="notificacion_personal" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<center>
<input type='button' name='validar_soporte_pagos_1' class='btn btn-success' value='Validar Soportes de Pago Ingresar'  class="btn btn-large  btn-primary" id="validar_soporte_pagos" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php if($visitas_programadas['VISITAS']<4&&$visitas_programadas['VISITAS']==$informes_realizados['INFORMES']){ ?>
<center>
<input type='button' name='programar_visita' class='btn btn-success' value='Programar Visita <?=$visitas_programadas['VISITAS']+1?>'  class="btn btn-large  btn-primary" id="programar_visita" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php } 
if($visitas_presencia['VISITAS']<3&&$visitas_presencia['VISITAS']==$informe_presencia['INFORMES']&&$visitas_programadas['VISITAS']==$informes_realizados['INFORMES']){ ?>
<center>
<input type='button' name='com_presentarse_sena' class='btn btn-success' value='Comunicación Presentarse ante el SENA'  class="btn btn-large  btn-primary" id="com_presentarse_sena" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php } ?>


<?php 


if($count_aprobacion_envio_email>0){?>
	
	<input type="hidden" id="email" name="email" value="<?=$email_aprobado?>" >
<center>
<input type='button' name='enviar_correo_empresario' class='btn btn-success' value='Enviar Correo Empresario'  class="btn btn-large  btn-primary" id="enviar_correo_empresario" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p
<?php 

 	} ?>
<center>
<input type='button' name='autorizacion_notificacion_email' class='btn btn-success' value='Ingresar Autorización Notificación Email'  class="btn btn-large  btn-primary" id="autorizacion_notificacion_email" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p
<?php  
if($liquidacion['LIQUIDACION']==0||($liquidacion['LIQUIDACION']>0&&$liquidaciondet['LIQUIDACION']=="N")){ ?>
	
	<center>
<input type='button' name='liquidacion' class='btn btn-success' value='Liquidación'  class="btn btn-large  btn-primary" id="liquidacion" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php } 
if(($liquidacion['LIQUIDACION']==1&&$liquidaciondet['LIQUIDACION']=="N")){ 
?>
	<center>
<input type='button' name='liquidacion_en_firme' class='btn btn-success' value='Liquidación en Firme'  class="btn btn-large  btn-primary" id="liquidacion_en_firme" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>

<?php 
} 
if($informes_realizados['INFORMES']>0&&$liquidaciondet['LIQUIDACION']=="S"){ ?>
<center>
<input type='button' name='acta_via_gubernativa' class='btn btn-success' value='Generar Acta Proceso Via Administrativa'  class="btn btn-large  btn-primary" id="acta_via_gubernativa" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php } 
if((empty($liquidaciondet['FECHA_RESOLUCION'])&&$this->session->userdata['id_coordinador_relaciones']==$this->ion_auth->user()->row()->IDUSUARIO)||$this->ion_auth->is_admin()){ ?>
<center>
<input type='button' name='anulacion' class='btn btn-warning' value='Anulación'  class="btn btn-large  btn-primary" id="anulacion" style="width : 340px; heigth : 1px">
</center>
<p></p><p></p>
<?php 
} 
?>
</div>

  <form id="generar_doc_comunicacion_pr" action="<?= base_url('index.php/fiscalizacion/consulta_comunicacion_presentarse_sena') ?>" method="post" >
    <input type="hidden" id="cod_fisca_presentarse_sena" name="cod_fisca_presentarse_sena" >
    <input type="hidden" id="nit_presentarse_sena" name="nit_presentarse_sena" >
</form>

  <form id="acta_via_guber" action="<?= base_url('index.php/fiscalizacion/agregar_documentos_acta_via_gubernativa') ?>" method="post" >
    <input type="hidden" id="cod_fisca_acta_via_guber" name="cod_fisca_acta_via_guber" >
    <input type="hidden" id="nit_acta_via_guber" name="nit_acta_via_guber" >
</form>

  <form id="validar_sop" action="<?= base_url('index.php/fiscalizacion/validar_sop') ?>" method="post" >
    <input type="hidden" id="cod_fisc" name="cod_fisc" value="<?=$cod_fisc  ?>">
    <input type="hidden" id="nit" name="nit" value="<?=$nit  ?>">
    <input type="hidden" id="cod_concepto" name="cod_concepto" value="<?=$cod_concepto  ?>" >
</form>

  <form id="liquidacion_form" action="<?= base_url('index.php/liquidaciones/administrar') ?>" method="post" >
    <input type="hidden" id="codigoFiscalizacion" name="codigoFiscalizacion" value="<?=$cod_fisc  ?>">
</form>

  <form id="liquidacion_firme_form" action="<?= base_url('index.php/liquidaciones/getFormSoporteLiquidacion') ?>" method="post" >
    <input type="hidden" id="cod_gestion_liquidacion" name="cod_gestion_liquidacion" value="<?=$cod_fisc  ?>">
</form>


<div id='ingreso_gestion'> </div>
<div id='registrar_llamada'> </div>
<div id='notificacion_personal_form'> </div>
<div id='validar_soporte'> </div>
<div id='programar_visitas'> </div>
<div id='anulacion_div'> </div>
<div id='enviar_correo_emp'> </div>
<div id='ingresar_autorizacion_email'> </div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }

 </style>
 
 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 

  <script type="text/javascript" language="javascript" charset="utf-8">
  $(".preload, .load").hide();
 $('#novedad').dialog({
                autoOpen: true,
                width: 500,
                height: 550,
                modal:true,
                title:'Novedades',
                close: function() {
                  $('#novedad *').remove();
                
                
                }
            });	
  	$('#validar_soporte_pagos').click(function(){

 $('#validar_sop').submit();
});

$('#nueva_gestion').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
var urlllamada="<?= base_url('index.php/fiscalizacion/ingresar_nueva_gestion') ?>/";
	$('#ingreso_gestion').load(urlllamada,{cod_fisc : cod_fisc ,nit : nit });
});


  	$('#programar_visita').click(function(){
  	 $(".preload, .load").show();	
  		
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
 var cod_concepto = $("#cod_concepto_1").val();
var urlprogrvisita="<?= base_url('index.php/fiscalizacion/programar_visita') ?>/";
       
 $.post(urlprogrvisita, {cod_fisc : cod_fisc ,nit : nit ,cod_concepto : cod_concepto },function(data){
        	alert(data);
        	$('#novedad').dialog('close');
        	 $(".preload, .load").hide();

       });

});



$('#com_presentarse_sena').click(function(){
	
	 var cod_fisc_com = $("#cod_fiscalizacion_1").val();
	$('#cod_fisca_presentarse_sena').val(cod_fisc_com);

	var nit_com = $("#nit_1").val();
	$('#nit_presentarse_sena').val(nit_com);
 $('#generar_doc_comunicacion_pr').submit();
 $('#novedad').dialog('close');
});

$('#acta_via_gubernativa').click(function(){
	
	 var cod_fisc_com = $("#cod_fiscalizacion_1").val();
	$('#cod_fisca_acta_via_guber').val(cod_fisc_com);

	var nit_com = $("#nit_1").val();
	$('#nit_acta_via_guber').val(nit_com);
 $('#acta_via_guber').submit();
 $('#novedad').dialog('close');
});

$('#anulacion').click(function(){
 var nit = $("#nit_1").val();	
 var cod_fisc = $("#cod_fiscalizacion_1").val();
var urlanula="<?= base_url('index.php/fiscalizacion/anulacion') ?>/";
	$('#anulacion_div').load(urlanula,{cod_fisc : cod_fisc ,nit : nit});
});

$('#elaborar_comunicacion_of').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
 var cod_concepto = $("#cod_concepto_1").val();
var urlelaborarcomof="<?= base_url('index.php/fiscalizacion/elaborar_comunicacion_oficial') ?>/";
	$('#comunicacion_oficial').load(urlelaborarcomof,{cod_fisc : cod_fisc ,nit : nit ,cod_concepto : cod_concepto });
});


$('#llamada_empresa').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
var urlllamada="<?= base_url('index.php/fiscalizacion/ingresar_llamada') ?>/";
	$('#registrar_llamada').load(urlllamada,{cod_fisc : cod_fisc ,nit : nit });
});

$('#notificacion_personal').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
var urlnotip="<?= base_url('index.php/fiscalizacion/notificacion_personal') ?>/";
	$('#notificacion_personal_form').load(urlnotip,{cod_fisc : cod_fisc ,nit : nit });
});


$('#enviar_correo_empresario').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
 var cod_concepto = $("#cod_concepto_1").val();
 var email = $("#email").val();
 
var urlcorreoempresario="<?= base_url('index.php/fiscalizacion/enviar_correo_empresario') ?>/";
	$('#enviar_correo_emp').load(urlcorreoempresario,{cod_fisc : cod_fisc ,nit : nit ,cod_concepto : cod_concepto, email: email });
});


$('#autorizacion_notificacion_email').click(function(){
 var cod_fisc = $("#cod_fiscalizacion_1").val();
 var nit = $("#nit_1").val();
 var cod_concepto = $("#cod_concepto_1").val();
var urlautorizacion="<?= base_url('index.php/fiscalizacion/ingresar_autorizacion_email') ?>/";
	$('#ingresar_autorizacion_email').load(urlautorizacion,{cod_fisc : cod_fisc ,nit : nit ,cod_concepto : cod_concepto });
});

$('#liquidacion').click(function(){
 $('#liquidacion_form').submit();
});

$('#liquidacion_en_firme').click(function(){
 $('#liquidacion_firme_form').submit();
});



    function ajaxValidationCallback(status, form, json, options) {
	
}
  </script>
 
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>