<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Registro Cartera Vivienda</h2>

<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

  <tr>
  	<td>&nbsp;</td>
    <td><b>Identificación Empleado:</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;" readonly="readonly" value="<?=$carteras["IDENTIFICACION"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Apellidos Empleado:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="apellidos" style="width : 91%;" readonly="readonly" value="<?=$carteras["APELLIDOS"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Nombres Empleado:</td>
    <td><input type="text" id="nombres" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRES"]?>"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Dirección:</td>
    <td><input type="text" id="direccion" style="width : 91%;" readonly="readonly" value="<?=$carteras["DIRECCION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Teléfono Contacto:</td>
    <td><input type="text" id="telefono" style="width : 91%;" readonly="readonly" value="<?=$carteras["TELEFONO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td><b>Correo Electrónico:</td>
    <td><input type="text" id="correo" style="width : 91%;" readonly="readonly" value="<?=$carteras["CORREO_ELECTRONICO"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Regional:</td>
    <td><input type="text" id="regional" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_REGIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  


</table>

<table width="780" border="0">
	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr>
	   	   <tr>
    <td>&nbsp;</td>	
    <td><b>Tipo de Acuerdo:</td>
 	<td><input type="text" id="tipo_acuerdo" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_ACUERDO"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  	<td>&nbsp;</td>
  </tr>
           <tr>
    <td>&nbsp;</td>	
    <td><b>Tipo de Cartera:</td>
     <td><input type="text" id="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_CARTERA"]?>"></td>
	<td>&nbsp;</td>
    <td><b>Tipo de Modalidad:</td>
    	<?php if(!empty($carteras["NOMBRE_MODALIDAD"]))
  {?>
  	<td><input type="text" id="tipo_modalidad" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_MODALIDAD"]?>"></td>
<?php	
  }else{?> 
  	<td><input type="text" id="tipo_modalidad2" style="width : 91%;" readonly="readonly"></td>
<?php	
  }?> 
   <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>	
    <td><b>Identificación Deuda:</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$carteras["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Fecha de Solicitud:</td>
    <td><input type="text" id="fecha_solicitud" name="fecha_solicitud" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_SOLICITUD"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
      <tr>
    <td>&nbsp;</td>	
    <td><b>Fecha de Escritura</td>
    <td><input type="text" id="fecha_escritura" name="fecha_escritura" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_ESCRITURA"]?>"></td>
  	    <td>&nbsp;</td>
    <td><b>Número de Escritura</td>
    <td><input type="text" id="numero_escritura" name="numero_escritura" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUMERO_ESCRITURA"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
    <tr>
    <td>&nbsp;</td>	
    <td><b>Estado:</td>
    <td><input type="text" id="tipo_estado_id" name="tipo_estado_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["DESC_EST_CARTERA"]?>"></td>
  	<td>&nbsp;</td>
    <td><b>Fecha Activación:</td>
    <td><input type="text" id="fecha_activacion" name="fecha_activacion" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_ACTIVACION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Deuda:</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  	<td>&nbsp;</td>
  </tr>
  
        <tr>
    <td>&nbsp;</td>	
    <td><b>Plazo:</td>
    <td><input type="text" id="plazo" name="plazo" style="width : 15%;" readonly="readonly" value="<?=$carteras["PLAZO_CUOTAS"]?>">
    <input type="text" id="plazo_id" name="plazo_id" style="width : 70%;" readonly="readonly" value="<?=$carteras["NOMBRE_PLAZO"]?>"></td>

    <td>&nbsp;</td>
    <td><b>Forma de Pago:</td>
        <td><input type="text" id="forma_pago_id" name="forma_pago_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["FORMA_PAGO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
                    <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa de Interés Corriente:</td>
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
    <td><b>Tasa de Interés de Mora:</td>
        
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
    
  
      <tr>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">
    <input type="text" id="ea_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.A.V.<br>
    <input type="text" id="namv_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.M.V.<input type="text" id="ndv_corr" style="width : 10%;" readonly="readonly" disabled="disabled">%N.D.V.	
    </td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td>
	<input type="text" id="ea_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="naav_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.A.V.<br>
    <input type="text" id="namv_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.A.M.V.<input type="text" id="ndv_mora" style="width : 10%;" readonly="readonly" disabled="disabled">%N.D.V.	
    </td>
  	<td rowspan="2">&nbsp;</td>
  </tr>
 	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr> 
	 	
	 	          <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa de Seguro</td>
<?php 
         	if(!empty($carterasini['CALCULO_SEGURO'])){
         	if($carterasini['CALCULO_SEGURO']==1){   ?>
  	<td><input type="text" id="tipo_tasa_seg" name="tipo_tasa_seg" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_SEGURO"]?>">
    &nbsp;<input type="text" id="valor_tasa_seg" name="valor_tasa_seg" style="width : 13%;" readonly="readonly" value="<?=$carteras["VALOR_T_SEGURO"]?>">%
  	&nbsp;<input type="text" id="tasa_esp_seg" name="tasa_esp_seg" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_SEGURO"]?>"></td>
  	<?php }else{  ?>
	<td><input type="text" id="tipo_tasa_seg" name="tipo_tasa_seg" style="width : 35%;" readonly="readonly" value="<?=$carteras["NOMBRE_CALCULO_COMP_SEGURO"]?>">
    <input type="text" id="valor_tasa_seg" name="valor_tasa_seg" style="width : 13%;" readonly="readonly">
  	&nbsp;<input type="text" id="tasa_esp_seg" name="tasa_esp_seg" style="width : 28%;" readonly="readonly" value="<?=$carteras["NOMBRE_TASAINTERES_SEGURO"]?>"></td>	
  		<?php } } else{ ?>
	<td><input type="text" id="tipo_tasa_seg" name="tipo_tasa_seg" style="width : 35%;" readonly="readonly">
    <input type="text" id="valor_tasa_seg" name="valor_tasa_seg" style="width : 13%;" readonly="readonly">  
    &nbsp;<input type="text" id="tasa_esp_seg" name="tasa_esp_seg" style="width : 28%;" readonly="readonly"</td>	
  
  <?php } ?>
    <td>&nbsp;</td>
    <td><b>Incremento de Cuota</td>
    <td><input type="text" id="select_incremento" name="select_incremento" style="width : 30%;" readonly="readonly" value="<?=$carteras["NOMBRE_PERIODOLIQUIDACION"]?>">
   <input type="text" id="fecha_incremento" name="fecha_incremento" style="width : 50%;" readonly="readonly" value="<?=$carteras["FECHA_INCREMENTO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>

      <tr>
    <td>&nbsp;</td>	
    <td><b>Identificacion Garantia</td>
    <td><input type="text" id="id_garantia" name="id_garantia" style="width : 91%;" readonly="readonly" value="<?=$carteras["ID_GARANTIA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Descripcion Garantía</td>
    <td><input type="text" id="desc_garantia" name="desc_garantia" style="width : 91%;" readonly="readonly" value="<?=$carteras["DESC_GARANTIA"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
        <tr>
    <td>&nbsp;</td>	
    <td><b>Número de Poliza</td>
    <td><input type="text" id="n_poliza" name="n_poliza" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUM_POLIZA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Aseguradora</td>
    <td><input type="text" id="aseguradora" name="aseguradora" style="width : 91%;" readonly="readonly" value="<?=$carteras["ASEGURADORA"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  	
  <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  

<tr><td>&nbsp;</td>	</tr>
         <tr>
    <td>&nbsp;</td>	
    <td><b>Salario</td>
    <td><input type="text" id="salario" name="salario" style="width : 91%;" maxlength="10" readonly="readonly" value="<?=number_format($carteras["SALARIO"], 0, ',', '.')?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
    <td>&nbsp;</td>
    <td><b>Factor Tipo</td>
    <td><input type="text" id="factor_tipo" name="factor_tipo" style="width : 91%;" readonly="readonly" value="<?=str_replace(".", ",", $carteras["FACTOR_TIPO"]) ?>"></td>
    <td>&nbsp;</td>
  </tr> 
           <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa IPC</td>
    <td><input type="text" id="tasa_ipc" name="tasa_ipc" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $carteras["TASA_IPC"]) ?>"></td>
    <td>&nbsp;</td>
    <td><b>Tasa Interés Cesantias</td>
    <td><input type="text" id="tasa_cesantia" name="tasa_cesantia" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $carteras["TASA_INTERES_CESANTIAS"]) ?>"></td>
    <td>&nbsp;</td>
  </tr>
  
           <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Cuota Aprobada</td>
    <td><input type="text" id="valor_cuota_aprob" name="valor_cuota_aprob" maxlength="10" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_CUOTA_APROBADA"], 0, ',', '.')?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
    <td>&nbsp;</td>
    <td><b>Gradiente</td>
    <td><input type="text" id="gradiente" name="gradiente" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $carteras["GRADIENTE"]) ?>"></td>
    <td>&nbsp;</td>
  </tr>
             <tr>
    <td>&nbsp;</td>	
    <td><b>Fecha Retiro</td>
    <td><input type="text" id="fecha_retiro" name="fecha_retiro" style="width : 91%;" required="required" readonly="readonly" value="<?=$carteras["FECHA_RETIRO"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr><td>&nbsp;</td>	</tr> 
   <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  
</table>
<table>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td><b>Identificación Codeudor:&nbsp&nbsp</td>
    <td><input type="text" id="id_codeudor" name="id_codeudor" style="width : 91%;" readonly="readonly" value="<?=$carteras["ID_CODEUDOR"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Nombre Codeudor:&nbsp&nbsp</td>
    <td><input type="text" id="nombre_codeudor" name="nombre_codeudor" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_CODEUDOR"]?>"></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
        
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
	var urlconsultarcnm="<?= base_url('index.php/carteranomisional/visualizar_pagos_hipotecario') ?>/";
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