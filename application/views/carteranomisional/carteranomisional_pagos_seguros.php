<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">



<center>
<h2>Registro Pagos Seguros</h2>
<table>
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td>Tipo de Cartera</td>
    <td><select id="input" style="width : 91%;"></select></td>
    <td>&nbsp;</td>
    <td>Identificación Cobro&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 85%;"></td>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
  	<td>&nbsp;</td>
    <td>Identificación Deudor</td>
    <td><input type="text" id="input" style="width : 85%;"></td>
    <td>&nbsp;</td>
    <td>Nombre Deudor</td>
    <td><input type="text" id="input" style="width : 85%;"></td>
    <td>&nbsp;</td>
  </tr>
 
  	    <tr>
  	<td>&nbsp;</td>
    <td>Saldo Deuda</td>
    <td><input type="text" id="input" style="width : 85%;"></td>
    <td>&nbsp;</td>
    <td>Registro Pago</td>
    <td><select id="input" style="width : 91%;"></select></td>
    <td>&nbsp;</td>
  </tr>
 
 
        <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>
   
     	    <tr>
  	<td>&nbsp;</td>
    <td>Periodo Pago</td>
    <td><select id="input" style="width : 91%;"></select></td>
    <td>&nbsp;</td>
    <td>Valor del Pago</td>
    <td><input type="text" id="input" style="width : 85%;"></td>
    <td>&nbsp;</td>
  </tr>

 
  </table>



</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >

 </center>
   </p> 

    
</div>


<script type="text/javascript" language="javascript" charset="utf-8">


            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>
