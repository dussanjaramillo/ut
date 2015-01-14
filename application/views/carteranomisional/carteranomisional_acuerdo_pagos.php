<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">



<center>
<h2>Gestión Acuerdo de Pago</h2>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td>Identificación</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Nombre Deudor&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
  	<td>&nbsp;</td>
    <td>Tipo Funcionario</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Tipo de Cartera&nbsp&nbsp&nbsp</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
  </tr>
 
    <tr>
  	<td>&nbsp;</td>
    <td>Tipo de Acuerdo</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Valor a Cobrar&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td>&nbsp;</td>
    <td>Capital en Mora</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Intereses Corriente&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td>&nbsp;</td>
    <td>Intereses No Pago</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Intereses de Mora&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td>&nbsp;</td>
    <td>Cuota</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Interes Corriente&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
  </tr>
  
      <tr>
  	<td>&nbsp;</td>
    <td>Incremento Cuota</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
    <td>Interes Mora&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
  </tr>
  
      <tr>
  	<td>&nbsp;</td>
    <td>Plazo</td>
    <td><input type="text" id="input" style="width : 12%;"><select id="input" style="width : 80%;"></select></td>
    <td>&nbsp;</td>
    <td>Seguro&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
     <td>&nbsp;</td>
  </tr>
  
        <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 3px;" ></td>

  </tr>
  
        <tr>
  	<td>&nbsp;</td>
    <td>Plazo</td>
    <td><input type="text" id="input" style="width : 12%;"><select id="input" style="width : 80%;"></select></td>
    <td>&nbsp;</td>
    <td>Interes Corriente</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
  </tr>
  
          <tr>
  	<td>&nbsp;</td>
    <td>Cuota Inicial</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
    <td>Interes Mora</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
  </tr>
  
          <tr>
  	<td>&nbsp;</td>
    <td>Motivo Acuerdo</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Seguro</td>
    <td><input type="text" id="input" style="width : 12%;">%<select id="input" style="width : 75%;"></select></td>
    <td>&nbsp;</td>
  </tr>  
          <tr>
  	<td>&nbsp;</td>
    <td>Observaciones</td>
    <td colspan="4">
    <textarea rows="3" style="width: 95%; height: 81px;"></textarea> 	
    	
    </td>
    <td>&nbsp;</td>
  </tr>

          <tr>
  	<td>&nbsp;</td>
    <td colspan="5">
    <input type="radio" id="input">&nbsp Actualizar estado de la deuda original
     <input type="radio" id="input">&nbsp  Cancelar Acuerdo de Pago, reliquidar deuda y pagos.
     <br><input type="radio" id="input">&nbsp Cancelar Acuerdo de pago, liquidar deuda
     </td>
    		
    </td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  	<td>&nbsp;</td>
  	</tr>
  </table>



</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='generar' class='btn btn-success' value='Generar'  id='generar' >
 <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >
 </center>
   </p> 

    
</div>


<script type="text/javascript" language="javascript" charset="utf-8">


            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>
<style type="text/css"> 
table.tabla2 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
</style> 
