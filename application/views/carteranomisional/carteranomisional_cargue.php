<?php 
if (isset($message)){
    echo $message;
   }
?>
 
<h1>Cargue de Archivos de Cartera No Misional</h1>



<center>
<input type='button' name='excedentes_servicio_medico' class='btn btn-success' value='Cargue para Excedentes de Servicios Médicos'  class="btn btn-large  btn-primary" id="excedentes_servicio_medico" style="width : 340px; heigth : 100px">
</center>
<p></p><p></p>
<center>
<input type='button' name='cuotas_partes' class='btn btn-success' value='Cargue Cuotas Partes Pensional'  class="btn btn-large  btn-primary" id="cuotas_partes" style="width : 340px;">
</center>
<p></p><p></p>
<center>
<input type='button' name='activos_dados_baja' class='btn btn-success' value='Cargue Activos Dados de Baja'  class="btn btn-large  btn-primary" id="activos_dados_baja" style="width : 340px;">
</center>
<p></p><p></p>


  <form id="cargue_medico" action="<?= base_url('index.php/cnm_servicio_medico/manage') ?>" method="post" >
</form>
  <form id="cargue_cuotas" action="<?= base_url('index.php/cnm_cuota_parte/manage') ?>" method="post" >
</form>
  <form id="cargue_activos" action="<?= base_url('index.php/cnm_responsabilidad_bienes/manage') ?>" method="post" >
</form>



<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<script type="text/javascript" language="javascript" charset="utf-8">
  $(".preload, .load").hide();

  	$('#excedentes_servicio_medico').click(function(){
  		
 $('#cargue_medico').submit();
 
       });
       
         	$('#cuotas_partes').click(function(){
  		
 $('#cargue_cuotas').submit();
 
       });
       
         	$('#activos_dados_baja').click(function(){
  		
 $('#cargue_activos').submit();
 
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

<a name="abajo"></a>
