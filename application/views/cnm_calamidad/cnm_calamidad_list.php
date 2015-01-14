<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Consulta Cartera Calamidad Doméstica</h2>

<div id="cartera">
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
    <td><b>Fecha de Resolución:</td>
    <td><input type="text" id="fecha_resolucion" name="fecha_resolucion" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_RESOLUCION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Número Resolución:</td>
    <td><input type="text" id="numero_resolucion" name="numero_resolucion" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUM_RESOLUCION"]?>"></td>
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
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  
 

 	 <tr>
	 	<td>&nbsp;</td>	
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
	var urlconsultarcnm="<?= base_url('index.php/carteranomisional/visualizar_pagos') ?>/";
	$('#consulta_pagos').load(urlconsultarcnm, { id_deuda: id_deuda });

});

 $('#regresar').click(function(){
	$('#consulta').submit();
});


 
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
