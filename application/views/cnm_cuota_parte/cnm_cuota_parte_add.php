<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Cartera Cuotas Partes</h2>

  
<form>
<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td>Código Entidad</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Identificación Entidad&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Razón Social&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Sigla</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td>Dirección</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Teléfono Contacto</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>Correo Electrónico</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Regional</td>
    <td><select id="input" style="width : 98%;"></select></td>
  	<td>&nbsp;</td>
  </tr>
   
   </table>

<table width="780" border="0">
	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr>
	    <tr>
    <td>&nbsp;</td>	
    <td>Tipo de Cartera</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Plazo</td>
    <td><input type="text" id="input" style="width : 15%;"><select id="input" style="width : 77%;"></select></td>
    <td>&nbsp;</td>
  </tr>

      <tr>
    <td>&nbsp;</td>	
    <td>Identificacion Deuda</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Periodo a Reportar</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
  </tr>
     
          <tr>
    <td>&nbsp;</td>	
    <td>Estado</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Fecha Activación</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
  	<td>&nbsp;</td>
  </tr>
  
        <tr>
    <td>&nbsp;</td>	
    <td>Fecha de Resolución</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Número Resolución</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
  	<td>&nbsp;</td>
  </tr>
  
        

              <tr>
    <td>&nbsp;</td>	
    <td>Tasa de Interes Corriente</td>
    <td><select id="input" style="width : 38%;"></select><input type="text" id="input" style="width : 15%;">%<select id="input" style="width : 35%;"></select></td>
    <td>&nbsp;</td>
    <td>Tasa de Interes de Mora</td>
    <td><select id="input" style="width : 38%;"></select><input type="text" id="input" style="width : 15%;">%<select id="input" style="width : 35%;"></select></td>
  	<td>&nbsp;</td>
  </tr>
    
    </tr>
    <tr>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">
    <input type="text" id="input" style="width : 10%;">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="input" style="width : 10%;">%N.A.A.V.<br>
    <input type="text" id="input" style="width : 10%;">%N.A.M.V.<input type="text" id="input" style="width : 10%;">%N.D.V.	
    </td>
    <td rowspan="2">&nbsp;</td>
    <td rowspan="2">&nbsp;</td>
    <td>
	<input type="text" id="input" style="width : 10%;">%E.A.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" id="input" style="width : 10%;">%N.A.A.V.<br>
    <input type="text" id="input" style="width : 10%;">%N.A.M.V.<input type="text" id="input" style="width : 10%;">%N.D.V.	
    </td>
  	<td rowspan="2">&nbsp;</td>
  </tr>
 	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr> 

</table>
<br>
<b>Pensionados a Reportar
<br>

<table width="780">
	<table border="1">
<tr>
     <th>Identificación Pensionado</th>    
     <th>Nombre Pensionado</th>
     <th>Fecha Inicial</th>
     <th>Fecha Final</th>
     <th>Cuota Parte Actual</th>     
    </tr>
  <tr>
    <td>&nbsp;</td>	
	<td>&nbsp;</td>	
	<td>&nbsp;</td>	
	<td>&nbsp;</td>	
    <td>&nbsp;</td>
  </tr> 
  
  <tr>
    <td>&nbsp;</td>	
	<td>&nbsp;</td>	
	<td>&nbsp;</td>	
	<td>&nbsp;</td>	
    <td>&nbsp;</td>
  </tr> 

  </table>
  <br>
    <tr>
    <td><input type="button" value="Adicionar" id="input" style="width : 91%;"></td>	
  	<td>&nbsp;</td>	
  	<td>&nbsp;</td>	
  	<td>Total Deuda</td>	
  	<td><input type="text" id="input"></td>	
  	</tr> 
  	<br>
  	 <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  
</table>
<br>
<table>
	<?php echo form_open_multipart(current_url()); ?>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td><input type="checkbox" id="input">&nbspAnexar Archivos</td>
    <td>
    	<?php
         echo form_label('Subir Archivos<span class="required"></span>', 'userfile');
           $dataAdjuntar = array(
                      'name'        => 'userfile',
                      'id'          => 'userfile',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'type' 		=> 'file',
                      'required'    => 'required',
                    );

           echo form_upload($dataAdjuntar);
           echo form_error('userfile','<div>','</div>');
        ?>
</td>
    <td>&nbsp;</td>
    <td></td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr> 
  
  <tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td>&nbsp;</td>
    <td><input type="button" value="Cargar" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" value="Eliminar" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <?php echo form_close(); ?>
     <tr>
  	<td colspan="7"><hr width=100% align="center" style="background-color: black;  height: 1px;" ></td>

  </tr>  
</table>

<table width="780">
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td><input type="checkbox" id="input">&nbspModificar Plazo</td>
    <td>&nbsp;</td>
    <td><input type="checkbox" id="input">&nbspModificar Tasa de Interes Corriente</td>
    <td>&nbsp;</td>
    <td><input type="checkbox" id="input">&nbspModificar Tasa de Interes Mora</td>
  	<td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
</table>





</form>      
<p>
	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='ac eptar' >

   </p> 

    
</div>



<script type="text/javascript" language="javascript" charset="utf-8">

           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla2 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
table.tabla3 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 

</style> 