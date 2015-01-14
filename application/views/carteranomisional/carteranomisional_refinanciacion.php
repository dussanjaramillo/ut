<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">



<center>
<h2>Refinanciación de Credito</h2>

 <?php     
        echo form_open(current_url(),array('onsubmit'=>'return confirmSubmit();')); ?>
<table width="780">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td><b>Identificación</td>
    <td><input type="text" id="cedula" name="cedula" style="width : 91%;" required="required" readonly="readonly" value="<?=$detalleCart["COD_EMPLEADO"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Tipo de Cartera</td>
	<td><input type="text" id="tipo_cartera" name="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$tipoCartera["NOMBRE_CARTERA"]?>"><input type="hidden" id="cod_tipo_cartera" name="cod_tipo_cartera" value="<?=$tipoCartera["COD_TIPOCARTERA"]?>"></td>
    <td>&nbsp;</td>
  </tr>
     <tr>
	<td>&nbsp;</td>
    <td><b>Apellidos Empleado&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="apellidos" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["APELLIDOS"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Nombres Empleado</td>
    <td><input type="text" id="nombres" style="width : 91%;" readonly="readonly" required="required" value="<?=$detalleCart["NOMBRES"]?>"></td>
    <td>&nbsp;</td>
  </tr>
 
    <tr>
  	<td>&nbsp;</td>
    <td><b>Tipo de Acuerdo</td>
 <?php
 if(!empty($acuerdo_prestamo)){
 ?>	
 <td>
    	  	<?php

          foreach($acuerdos->result_array as $row) {
              	
                  $selecttipoacuerdo[$row['COD_ACUERDOLEY']] = $row['NOMBRE_ACUERDO'];
               }
          echo form_dropdown('tipo_acuerdo_id', $selecttipoacuerdo,$detalleCart["COD_TIPO_ACUERDO"],'id="tipo_acuerdo_id" readonly="readonly" disabled="disabled" style="width : 97%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipo_acuerdo_id','<div>','</div>');
        

        ?>
    	
    	</td>
	
 <?php
 }else{
 ?>
<td><input type="text" id="tipo_acuerdo_id" name="tipo_acuerdo_id" style="width : 91%;" readonly="readonly" disabled="disabled"></td>
 <?php
 }
 ?>  
    <td>&nbsp;</td>
    <td><b>Valor Deuda</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=number_format($detalleCart["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td>&nbsp;</td>
    <td><b>Saldo Deuda</td>
    <td><input type="text" id="saldo_deuda" name="saldo_deuda" style="width : 91%;" readonly="readonly" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=number_format($detalleCart["SALDO_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td><b>Fecha Saldo</td>
    <td><input type="text" id="saldo_deuda" name="saldo_deuda" style="width : 91%;" readonly="readonly" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=date('d/m/Y');?>"></td>
    <td>&nbsp;</td>
  </tr>
  <?php if($tipoCartera["COD_TIPOCARTERA"]==8) {?>
     <tr>
  	<td>&nbsp;</td>
    <td><b>Valor Cuota Actual</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly"onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=number_format($detalleCart["VALOR_CUOTA_APROBADA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php }  ?>
  
       <tr>
  	<td>&nbsp;</td>
    <td><b>Número de Cuotas Pendientes</td>
    <td><input type="text" id="cuotas_pend" name="cuotas_pend" style="width : 91%;" readonly="readonly"onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=$detalleCart["PLAZO_CUOTAS"]-$cuotas_restantes["COUNT(ID_DEUDA_E)"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Intereses No Pagos</td>
    <td><input type="text" id="int_no_pago" name="int_no_pago" style="width : 91%;" readonly="readonly"onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" value="<?=number_format($int_no_pago, 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
  </tr>
        <tr>
  	<td>&nbsp;</td>
    <td><b>Plazo</td>
    <td><input type="text" id="plazo" name="plazo" readonly="readonly" style="width : 15%;" required="required" value="<?=$detalleCart["PLAZO_CUOTAS"]?>">
    	
    	<?php

          foreach($duracion->result_array as $row) {
              	
                  $selectplazo[$row['COD_DURACION_GRACIA']] = $row['NOMBRE_DURACCION'];
               }
          echo form_dropdown('plazo_id', $selectplazo,$detalleCart["COD_PLAZO"],'id="plazo_id" readonly="readonly" disabled="disabled" style="width : 75%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('plazo_id','<div>','</div>');
        

        ?>
        </td>
    <td>&nbsp;</td>
	<td><b>Identificación Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  
  
                  <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa de Interés Corriente</td>

    <td>    <?php if($detalleCart["MOD_TASA_CORRIENTE"]=='S')
    {?>
    	
    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_corriente', $selecttipotasa,$detalleCart["CALCULO_CORRIENTE"],'id="combo_tipo_tasa_corriente" readonly="readonly" disabled="disabled" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>    
    
                    <?php if($detalleCart["CALCULO_CORRIENTE"]=='1')
    {?>
    	    	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" readonly="readonly" style="width : 15%;" required="required" value="<?=$detalleCart["VALOR_T_CORRIENTE"]?>">%
    	<?php

          foreach($tipotasaespC->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,$detalleCart["TIPO_T_F_CORRIENTE"],'id="aplica_tasa_c" style="width : 30%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
    	<?php 
    	}else{
    	?>
    	
    	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 15%;">%
    	<?php

          foreach($tipotasaespV->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,$detalleCart["TIPO_T_V_CORRIENTE"],'id="aplica_tasa_c" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
        <?php } ?>
    
    
    
  <?php }  else{?>
  	
  	    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_corriente', $selecttipotasa,'','id="combo_tipo_tasa_corriente" style="width : 34%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>   
  	<input type="text" id="porcent_tasa_c" name="porcent_tasa_c" style="width : 15%;" readonly="readonly" disabled="disabled">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_c', $selecttasacorriente,'','id="aplica_tasa_c" style="width : 30%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_c','<div>','</div>');
        

        ?>
  	
  	<?php } ?>
    
</td> 
    <td>&nbsp;</td>
    <td><b>Tasa de Interés de Mora</td>


    <td>    <?php if($detalleCart["MOD_TASA_MORA"]=='S')
    {?>
    	
    	<?php

          foreach($tipotasa->result_array as $row) {
      			  
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_mora', $selecttipotasa,$detalleCart["CALCULO_MORA"],'id="combo_tipo_tasa_mora" readonly="readonly" disabled="disabled" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_mora','<div>','</div>');
        

        ?>
    
                    <?php if($detalleCart["CALCULO_MORA"]=='1')
    {?>    
    	    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;" readonly="readonly" disabled="disabled" value="<?=$detalleCart["VALOR_T_MORA"]?>">%
    	<?php
		foreach($tipotasaespC->result_array as $row) {
        $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
        }
        echo form_dropdown('aplica_tasa_m', $selecttasamora,$detalleCart["TIPO_T_F_MORA"],'id="aplica_tasa_m" readonly="readonly" disabled="disabled" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
        echo form_error('aplica_tasa_m','<div>','</div>');
          
        ?>
    	<?php 
    	}else{
    	?>
    	
    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;">%
    	<?php

          foreach($tipotasaespV->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_m', $selecttasamora,$detalleCart["TIPO_T_V_MORA"],'id="aplica_tasa_m" readonly="readonly" disabled="disabled" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_m','<div>','</div>');
        

        ?>
        <?php } ?>
    
    
    
  <?php }  else{?>
  	
  	    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('combo_tipo_tasa_mora', $selecttipotasa,'','id="combo_tipo_tasa_mora" style="width : 34%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('combo_tipo_tasa_mora','<div>','</div>');
        

        ?>

    	<input type="text" id="porcent_tasa_m" name="porcent_tasa_m" style="width : 15%;" readonly="readonly" disabled="disabled">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('aplica_tasa_m', $selecttasamora,'','id="aplica_tasa_m" style="width : 30%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('aplica_tasa_m','<div>','</div>');
        

        ?>
  	
  	<?php } ?>
    
</td>

<tr>
    <td>&nbsp;</td>	
    <td><b>Forma de Pago:</td>
    <td><input type="text" id="forma_pago_id" name="forma_pago_id" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["FORMA_PAGO"]?>"><input type="hidden" id="cod_forma_pago" name="cod_forma_pago" value="<?=$detalleCart["COD_FORMAPAGO"]?>"></td>
  	<td>&nbsp;</td>
    <td><b>Fecha Activación:</td>
    <td><input type="text" id="fecha_activacion" name="fecha_activacion" style="width : 91%;" readonly="readonly" value="<?=$detalleCart["FECHA_ACTIVACION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  	<td>&nbsp;</td>
  </tr>
  <?php if($tipoCartera["COD_TIPOCARTERA"]==8) {?>  
  <tr><td>&nbsp;</td>	</tr>
         <tr>
    <td>&nbsp;</td>	
    <td><b>Salario</td>
    <td><input type="text" id="salario" name="salario" style="width : 91%;" maxlength="10" readonly="readonly" value="<?=number_format($detalleCart["SALARIO"], 0, ',', '.')?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
    <td>&nbsp;</td>
    <td><b>Factor Tipo</td>
    <td><input type="text" id="factor_tipo" name="factor_tipo" style="width : 91%;" readonly="readonly" value="<?=str_replace(".", ",", $detalleCart["FACTOR_TIPO"]) ?>"></td>
    <td>&nbsp;</td>
  </tr> 
           <tr>
    <td>&nbsp;</td>	
    <td><b>Tasa IPC</td>
    <td><input type="text" id="tasa_ipc" name="tasa_ipc" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $detalleCart["TASA_IPC"]) ?>"></td>
    <td>&nbsp;</td>
    <td><b>Tasa Interés Cesantias</td>
    <td><input type="text" id="tasa_cesantia" name="tasa_cesantia" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $detalleCart["TASA_INTERES_CESANTIAS"]) ?>"></td>
    <td>&nbsp;</td>
  </tr>
  
           <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Cuota Aprobada</td>
    <td><input type="text" id="valor_cuota_aprob" name="valor_cuota_aprob" maxlength="10" style="width : 91%;" readonly="readonly" value="<?=number_format($detalleCart["VALOR_CUOTA_APROBADA"], 0, ',', '.')?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
    <td>&nbsp;</td>
    <td><b>Gradiente</td>
    <td><input type="text" id="gradiente" name="gradiente" style="width : 91%;" maxlength="6" readonly="readonly" value="<?=str_replace(".", ",", $detalleCart["GRADIENTE"]) ?>"></td>
    <td>&nbsp;</td>
  </tr>
             <tr>
    <td>&nbsp;</td>	
    <td><b>Fecha Retiro</td>
    <td><input type="text" id="fecha_retiro" name="fecha_retiro" style="width : 91%;" required="required" readonly="readonly" value="<?=$detalleCart["FECHA_RETIRO"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <?php } ?>
  <tr><td>&nbsp;</td>	</tr> 
  

        <tr>
  <td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>
  </tr>
  
  
      <tr>
  	<td>&nbsp;</td>
    <td>Motivo</td>
    <td><?php
            
                 foreach($motivo->result_array as $row) {
              	
                  $selectmotivo[$row['COD_MOTIVO']] = $row['NOMBRE_MOTIVO'];
               }
          echo form_dropdown('motivo_id', $selectmotivo,'','id="motivo_id" style="width : 100%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('motivo_id','<div>','</div>');
           ?></td>    
	<td>&nbsp;</td>
    <td>Nueva Cuota</td>
    <td><input type="text" id="nueva_cuota" name="nueva_cuota" maxlength="10" required="required" onchange ="presubmit();" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>

  
      <tr>
  	<td>&nbsp;</td>
    <td>Plazo</td>
    <td><input type="text" id="refin_plazo" name="refin_plazo" style="width : 15%;" maxlength="10" required="required">
    	
    	<?php

          foreach($duracion->result_array as $row) {
              	
                  $selectplazo[$row['COD_DURACION_GRACIA']] = $row['NOMBRE_DURACCION'];
               }
          echo form_dropdown('refin_plazo_id', $selectplazo,'','id="refin_plazo_id" style="width : 75%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('refin_plazo_id','<div>','</div>');
        

        ?>
        </td>
    <td>&nbsp;</td> 
    <td>Fecha Refinanciación</td>
    <td><input type="text" id="fecha_refin" name="fecha_refin" style="width : 80%;" readonly="readonly"></td>
    <td>&nbsp;</td> 
  </tr>


          <tr>
    <td>&nbsp;</td>	
    <td>Tasa de Interés Corriente</td>
    <td>
<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('reliq_combo_tipo_tasa_corriente', $selecttipotasa,'','id="reliq_combo_tipo_tasa_corriente" style="width : 34%;" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('reliq_combo_tipo_tasa_corriente','<div>','</div>');
        

        ?>
    	
    	<input type="text" id="reliq_porcent_tasa_c" name="reliq_porcent_tasa_c" style="width : 15%;" required="required">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasacorriente[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('reliq_aplica_tasa_c', $selecttasacorriente,'','id="reliq_aplica_tasa_c" style="width : 30%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('reliq_aplica_tasa_c','<div>','</div>');
        

        ?>
</td>
    <td>&nbsp;</td>
    <td>Tasa de Interés de Mora</td>
    <td>
    	<?php

          foreach($tipotasa->result_array as $row) {
              	
                  $selecttipotasa[$row['COD_CALCULO_COMPONENTE']] = $row['NOMBRE_CALCULO_COMP'];
               }
          echo form_dropdown('reliq_combo_tipo_tasa_mora', $selecttipotasa,'','id="reliq_combo_tipo_tasa_mora" required="required" style="width : 34%;" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('reliq_combo_tipo_tasa_mora','<div>','</div>');
        

        ?>

    	<input type="text" id="reliq_porcent_tasa_m" name="reliq_porcent_tasa_m" style="width : 15%;" required="required">%
    	<?php

          foreach($tipotasaesp->result_array as $row) {
              	
                  $selecttasamora[$row['COD_TASA']] = $row['NOMBRE_TASA'];
               }
          echo form_dropdown('reliq_aplica_tasa_m', $selecttasamora,'','id="reliq_aplica_tasa_m" style="width : 30%;" required="required" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('reliq_aplica_tasa_m','<div>','</div>');
        

        ?>
    	
    	
    	</td>
  	<td>&nbsp;</td>
  </tr>
  
  <tr>
  	<td>&nbsp;</td>
  	</tr>
  </table>



</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1'>        
<input type="hidden" id="id_cnm" name="id_cnm" value="<?=$id_cnm?>">
     <center> 
<p>

                <?php 
                $data = array(
                       'name' => 'button',
                       'disabled' => 'disabled',
                       'id' => 'submit-button',
                       'value' => 'Generar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Generar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
  <input type='button' name='proyeccion' class='btn btn-success' value='IMPRIMIR PLAN AMORTIZACIÓN'  id='proyeccion' >
                
<?php  echo anchor('', '<i class="icon-remove"></i> CANCELAR', 'class="btn"'); ?>
<?php echo form_close(); ?>  
   </p> 

    </center>
</div>

<div id="proyeccion_tabla"></div>
<script type="text/javascript" language="javascript" charset="utf-8">
function presubmit(){
$('#submit-button').attr("disabled",true);

 }

$('#proyeccion').click(function(){

var fecha_activacion=$('#fecha_activacion').val();

var medio_pago=$('#cod_forma_pago').val();
if($('#cod_tipo_cartera').val()==8){
var hipotecario=true;
}
else{
var hipotecario=false;
}
var reliquidacion=false;
var refinanciacion=true;

var datos={ VALOR_DEUDA : $('#saldo_deuda').val(), 
			CALCULO_CORRIENTE : '1',  
			TIPO_T_V_CORRIENTE : $('#reliq_aplica_tasa_c').val(), 
			TIPO_T_F_CORRIENTE : $('#reliq_aplica_tasa_c').val(), 
			COD_FORMAPAGO : $('#cod_forma_pago').val(),  
			COD_PLAZO : $('#plazo_id').val(), 
			PLAZO_CUOTAS : $('#plazo').val(),  
			COD_CARTERA : $('#id_deuda').val(), 
			VALOR_CUOTA : $('#valor_cuota_aprob').val(),  
			
			VALOR_T_CORRIENTE : $('#reliq_porcent_tasa_c').val(), 
			
			SALARIO : $('#salario').val(),  
			TASA_INTERES_CESANTIAS : $('#tasa_cesantia').val(),  
			VALOR_CUOTA_APROBADA : $('#nueva_cuota').val(), 
			FECHA_RETIRO : $('#fecha_retiro').val(),  
			GRADIENTE : $('#gradiente').val(), 
			TASA_IPC : $('#tasa_ipc').val(), 
			FACTOR_TIPO : $('#factor_tipo').val(),
			RESPUESTA : '1'
		
	 };

console.log (datos);

       var url = "<?php echo base_url('index.php/cnm_liquidacioncuotas/CnmCalcularCuotasVistaJson') ?>";
        $.post(url, {datos: datos, fecha_activacion: fecha_activacion, medio_pago: medio_pago, hipotecario: hipotecario, reliquidacion: reliquidacion, refinanciacion: refinanciacion })
        	.done(function(data)
        { 
        if(data.error==false)
        {
        	var url2 = "<?php echo base_url('index.php/carteranomisional/visualizar_proyeccion') ?>";
        	$('#proyeccion_tabla').load(url2, {datos: datos, fecha_activacion: fecha_activacion, medio_pago: medio_pago, hipotecario: hipotecario, reliquidacion: reliquidacion, refinanciacion: refinanciacion });
			$('#submit-button').attr("disabled",false);
        }	       
        else
        {
        	alert(data.mensaje+ "\n" +"Cuota Sugerida:"+ data.cuota_sugerida );
        	$('#submit-button').attr("disabled",true);
        }	       
        	       })
        .fail(function(data)
        {
        alert("no se puede liquidar, error de base de datos");
	    });



});

$('#reliq_combo_tipo_tasa_mora').change(function(){

	$("#reliq_aplica_tasa_m").empty();
	$.getJSON("<?= base_url('index.php/cnm_ac_componentes/get_tipo_tasa') ?>/"+$("#reliq_combo_tipo_tasa_mora").val(),function(data){
        console.log(JSON.stringify(data));
        $.each(data, function(k,v){
         	$("#reliq_aplica_tasa_m").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


}); 

function confirmSubmit() {
  if (confirm("Esta seguro de refinanciar la Cartera?")) {
     return true;
  }
  return false;
}

$('#refin_plazo').keypress(function(e){
	
	var keynum = window.event ? window.event.keyCode : e.which;
	keynum2="";
	var info=$('#refin_plazo').val();

	
if((info.length==10 && (keynum != 8&&keynum != 0)) || (keynum==44 && info.length==0) )
	return false;
		
if ((keynum>47 && keynum<58) || keynum == 8 || keynum == 0 || keynum == 13 || keynum == 82 ) 
return true;
return /\d/.test(String.fromCharCode(keynum));
});
             $('#refin_plazo').change(function(){
num_acta = document.getElementById("refin_plazo").value;
 	    if( !(/[A-z-\/\*\,\.\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/.test(num_acta)) )
		{}
  else{
$('#refin_plazo').val("");
  }
});    

$( "#fecha_refin").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Refinanciación',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	changeMonth: true,
	changeYear: true,
	dateFormat: 'dd/mm/yy',
	maxDate: "-1",
      minDate: "-5y",
});

function num(c){
  c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
  // x = c.value;
    // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    var num = c.value.replace(/\./g,'');
  if(!isNaN(num))
  {
   num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
   num = num.split('').reverse().join('').replace(/^[\.]/,'');
   c.value = num;
  }
 }
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>


