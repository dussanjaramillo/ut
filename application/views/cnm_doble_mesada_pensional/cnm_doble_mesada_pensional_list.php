<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Doble Mesada Pensional</h2>

<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>

  <tr>
  	<td>&nbsp;</td>
    <td><b>Identificación Pensionado:</td>
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
   <td><b>Tipo de Cartera:</td>
     <td><input type="text" id="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_CARTERA"]?>"></td>
	   <td>&nbsp;</td>
    <td><b>Fecha Inicial de Pensión:</td>
    <td><input type="text" id="fecha_pension" name="fecha_pension" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_INI_PENSION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>

      <tr>
    <td>&nbsp;</td>	
    <td><b>Identificacion de la Deuda:</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$carteras["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Valor Doble Mesada:</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td>&nbsp;</td>	
    <td><b>Fecha de Resolución:</td>
    <td><input type="text" id="fecha_resolucion" name="fecha_resolucion" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_RESOLUCION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Número Resolución:</td>
    <td><input type="text" id="numero_resolucion" name="numero_resolucion" style="width : 91%;" readonly="readonly" value="<?=$carteras["NUMERO_RESOLUCION"]?>"></td>
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
	 	<td>&nbsp;</td>	
	 	</tr> 

</table>
</br>

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
  </br>
<h3>BENEFICIARIOS</h3>
</br>
</br>

 <table id="tablabeneficiarios" width="500" class="table table-bordered" border="1">
 <thead class="btn-success">
    <tr>
  <th>IDENTIFICACIÓN</th>
  <th>NOMBRES Y APELLIDOS</th>
  <th>PARENTESCO</th>
    </tr>
  </thead>
 <tbody>
<?php
foreach ($beneficiarios->result_array as $data) {?> 
	<tr>

<td style="background-color: #f9f9f9;" border="1"><?=$data['IDENTIFICACION_BENEFICIARIO']  ?></td>
<td style="background-color: #f9f9f9;" border="1"><?=$data['NOMBRES']  ?></td>	
<td style="background-color: #f9f9f9;" border="1"><?=$data['PARENTESCO']  ?></td>	

</tr>
<?php
} ?>

 </tbody>  
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
	var urlconsultarcnm="<?= base_url('index.php/carteranomisional/visualizar_pagos') ?>/";
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
table.tabla4 { border: 1px solid #000;} 

</style> 