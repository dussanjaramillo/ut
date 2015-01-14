<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Consulta Cartera Convenios</h2>

<?php     
        echo form_open(current_url()); ?>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td><b>Identificación del Tercero</td>
    <td><input type="text" id="cod_empresa" name="cod_empresa" style="width : 91%;" required="required" readonly="readonly" value="<?=$carteras["COD_ENTIDAD"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Razón Social&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="razon_social" style="width : 91%;" readonly="readonly" value="<?=$carteras["RAZON_SOCIAL"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Sigla</td>
    <td><input type="text" id="sigla" style="width : 91%;" readonly="readonly" value="<?=$carteras["SIGLA"]?>"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Dirección</td>
    <td><input type="text" id="direccion" style="width : 91%;" readonly="readonly" value="<?=$carteras["DIRECCION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Teléfono Contacto</td>
    <td><input type="text" id="telefono" style="width : 91%;" readonly="readonly" value="<?=$carteras["TELEFONO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td><b>Correo Electrónico</td>
    <td><input type="text" id="correo_electronico" style="width : 91%;" readonly="readonly" value="<?=$carteras["CORREO_ELECTRONICO"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Regional</td>
    <td><input type="text" id="regional" style="width : 91%;" readonly="readonly" value="<?=$carteras["COD_REGIONAL"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
   
   </table>

<table width="780" border="0">
	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr>
	    <tr>
    <td>&nbsp;</td>	
    <td><b>Tipo de Cartera</td>
    <td><input type="text" id="tipo_cartera" name="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_CARTERA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Fecha  Suscripción</td>
    <td><input type="text" id="fecha_suscripcion" name="fecha_suscripcion" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_SUSCRIPCION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>

      <tr>
    <td>&nbsp;</td>	
    <td><b>Identificacion Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$carteras["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Número de Convenio</td>
    <td><input type="text" id="nro_convenio" name="nro_convenio" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUMERO_CONVENIO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
      <tr>
    <td>&nbsp;</td>	
    <td><b>Fecha Acta de Liquidación</td>
    <td><input type="text" id="fecha_acta_liq" name="fecha_acta_liq" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_ACTA_LIQ"]?>"></td>
    <td>&nbsp;</td>
    <td><b>No Acta de Liquidación</td>
    <td><input type="text" id="nro_acta_liq" name="nro_acta_liq" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUMERO_ACTA_LIQ"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>	
    <td><b>Termino del Convenio </td>
    <td><input type="text" id="termino_convenio" name="termino_convenio" style="width : 91%;" readonly="readonly" value="<?=$carteras["TERMINO_CONVENIO"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Fecha Max.Pago Reintegro</td>
    <td><input type="text" id="fecha_max_reint" name="fecha_max_reint" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_MAX_PAGO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Total Convenio </td>
    <td><input type="text" id="valor_convenio" name="valor_convenio" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_TOTAL_CONVENIO"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td><b>Aporte SENA</td>
    <td><input type="text" id="aporte_sena" name="aporte_sena" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["APORTE_SENA"], 0, ',', '.')?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
        <tr>
    <td>&nbsp;</td>	
    <td><b>Valor de Rendimientos</td>
    <td><input type="text" id="valor_rendimientos" name="valor_rendimientos" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_RENDIMIENTOS"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td><b>Valor del Reintegro</td>
    <td><input type="text" id="valor_reintegro" name="valor_reintegro" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_REINTEGRO"], 0, ',', '.')?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
    <tr>
    <td>&nbsp;</td>	
    <td><b>Inicio de Rendimientos</td>
    <td><input type="text" id="rendimientos_ini" name="rendimientos_ini" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_INICIO_CALCULO_R"]?>"></td>
  	<td>&nbsp;</td>
    <td><b>Fin de Rendimientos</td>
    <td><input type="text" id="rendimientos_fin" name="rendimientos_fin" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_FIN_CALCULO_R"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
    
          <tr>
    <td>&nbsp;</td>	
    <td><b>Estado</td>
	<td><input type="text" id="tipo_estado_id" name="tipo_estado_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["DESC_EST_CARTERA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Tasa de Rendimientos</td>
    <td><input type="text" id="tasa_rendimientos" name="tasa_rendimientos" style="width : 91%;" readonly="readonly" required="required" value="<?=$carteras["TASA_RENDIMIENTO"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Deuda:</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td><b>Forma de Pago:</td>
        <td><input type="text" id="forma_pago_id" name="forma_pago_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["FORMA_PAGO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  


        
          <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa de Interes Corriente:</td>
         <td>
         	<?php 
         	if(!empty($carterasini['CALCULO_CORRIENTE'])){
         	if($carterasini['CALCULO_CORRIENTE']==1){   ?>
  <input type="text" id="combo_tipo_tasa_corriente" name="combo_tipo_tasa_corriente" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_CORR"]?>">
  &nbsp;<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 13%;" readonly="readonly" value="<?=$carteras["VALOR_T_CORRIENTE"]?>">%
  &nbsp;<input type="text" id="aplica_tasa_c" name="aplica_tasa_c" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_CORR"]?>"></td>
  	<?php }else{  ?>
  <input type="text" id="combo_tipo_tasa_corriente" name="combo_tipo_tasa_corriente" style="width : 35%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_CORR"]?>">
  		 <input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 13%;" readonly="readonly">
  		 &nbsp;<input type="text" id="aplica_tasa_c" name="aplica_tasa_c" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_CORR"]?>"></td>
  		<?php }}else{  ?>
    <input type="text" id="combo_tipo_tasa_corriente" name="combo_tipo_tasa_corriente" style="width : 35%;" readonly="readonly">
  		 <input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 13%;" readonly="readonly">	
  		 &nbsp;<input type="text" id="aplica_tasa_c" name="aplica_tasa_c" style="width : 28%;" readonly="readonly"></td>	
  		
  		<?php } ?>
  	<td>&nbsp;</td>
    <td><b>Tasa de Interes de Mora:</td>
        
         	<?php 
         	if(!empty($carterasini['CALCULO_MORA'])){ 
         	if($carterasini['CALCULO_MORA']==1){   ?>
  	<td><input type="text" id="combo_tipo_tasa_mora" name="combo_tipo_tasa_mora" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_MORA"]?>">
    &nbsp;<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 13%;" readonly="readonly" value="<?=$carteras["VALOR_T_MORA"]?>">%
  	&nbsp;<input type="text" id="aplica_tasa_m" name="aplica_tasa_m" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_MORA"]?>"></td>
  	<?php }else{  ?>
	<td><input type="text" id="combo_tipo_tasa_mora" name="combo_tipo_tasa_mora" style="width : 35%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_MORA"]?>">
    <input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 13%;" readonly="readonly">
  	&nbsp;<input type="text" id="aplica_tasa_m" name="aplica_tasa_m" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_MORA"]?>"></td>	
  		<?php } } else{ ?>
	<td><input type="text" id="combo_tipo_tasa_mora" name="combo_tipo_tasa_mora" style="width : 35%;" readonly="readonly">
    <input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 13%;" readonly="readonly">  
    &nbsp;<input type="text" id="aplica_tasa_m" name="aplica_tasa_m" style="width : 28%;" readonly="readonly"</td>	
  
  <?php } ?>

   <td>&nbsp;</td>
  </tr>
    
    </tr>
    <tr>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">
    <input type="text" id="ea_corr" name="ea_corr" style="width : 10%;" readonly="readonly">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_corr" name="naav_corr" style="width : 10%;" readonly="readonly">%N.A.A.V.<br>
    <input type="text" id="namv_corr" name="namv_corr" style="width : 10%;" readonly="readonly">%N.A.M.V.<input type="text" id="ndv_corr" name="ndv_corr" style="width : 10%;" readonly="readonly">%N.D.V.	
    </td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td>
	<input type="text" id="ea_mora" name="ea_mora" style="width : 10%;" readonly="readonly">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_mora" name="naav_mora" style="width : 10%;" readonly="readonly">%N.A.A.V.<br>
    <input type="text" id="namv_mora" name="namv_mora" style="width : 10%;" readonly="readonly">%N.A.M.V.<input type="text" id="ndv_mora" name="ndv_mora" style="width : 10%;" readonly="readonly">%N.D.V.	
    </td>
  	<td rowspan="2">&nbsp;</td>
  </tr>
 	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr> 

</table>

<table>
<tr><td>&nbsp;</td>	</tr>
<b>Documentos:

    <?php	if($lista_adjuntos!=""){
    		
	foreach ($lista_adjuntos as $key => $value) {
		?>
 <tr>		
&nbsp<?=$value?>
<a href=<?=base_url()?>uploads/carteranomisional/<?=$carteras["COD_CARTERA_NOMISIONAL"]?>/documentoscartera/<?=$value?> class="button">
  Ver
</a> 	
 	
 </tr>			
			<?php }
		}?>


  <tr><td>&nbsp;</td>	</tr> 
        
</table>    
<p>
	 

<?php echo form_close(); ?>
</p>
    
</div>

 <p>
 <center>	 
 <input type='button' name='pagos' class='btn btn-success' value='Visualizar Pagos'  id='pagos' >
 <input type='button' name='regresar' class='btn btn-success' value='Regresar'  id='regresar' >
 
 </center>
   </p> 
   
<div id='consulta_pagos'> </div> 

  <form id="consulta" action="<?= base_url('index.php/carteranomisional/consulta') ?>">

</form>


<script type="text/javascript" language="javascript" charset="utf-8">

 $('#pagos').click(function(){
 	 var id_deuda = $("#id_deuda").val();
	var urlconsultarcnm="<?= base_url('index.php/carteranomisional/visualizar_pagos_ecollect') ?>/";
	$('#consulta_pagos').load(urlconsultarcnm, { id_deuda: id_deuda });

});

 $('#regresar').click(function(){
	$('#consulta').submit();
});

                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>
<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla2 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla3 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 

</style> 