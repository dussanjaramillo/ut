<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">



<center>
<h2>Respuesta Recurso</h2>
<table width="780">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  	
  	    <tr>
  	<td>&nbsp;</td>
    <td>Tipo de Cartera</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Identificación&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
  	<td>&nbsp;</td>
    <td>Nombre Deudor</td>
    <td colspan="4"><input type="text" id="input" style="width : 96%;"></td>
    <td>&nbsp;</td>
  </tr>
 
    <tr>
  	<td>&nbsp;</td>
    <td>Dirección</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
    <td>Telefono&nbsp&nbsp&nbsp</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>

    <tr>
  	<td>&nbsp;</td>
    <td>Instancia</td>
    <td><select id="input" style="width : 98%;"></select></td>
    <td>&nbsp;</td>
    <td>Valor Deuda</td>
    <td><input type="text" id="input" style="width : 12%;"><select id="input" style="width : 80%;"></select></td>
    <td>&nbsp;</td>
  </tr>

    </table>
 <table class="tabla2"> 
 	
 	  <tr>
  	<td>&nbsp;</td>
  	</tr>
  	
  	
        <tr>
  	<td>&nbsp;</td>
    <td>Numero Recurso</td>
    <td><input type="text" id="input" style="width : 65%;"></td>
    <td>&nbsp;</td>
    <td>Fecha Instauración</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
  
          <tr>
  	<td>&nbsp;</td>
    <td>Nombre Juez</td>
    <td><input type="text" id="input" style="width : 65%;"></td>
    <td>&nbsp;</td>
    <td>Fecha Respuesta</td>
    <td><input type="text" id="input" style="width : 91%;"></td>
    <td>&nbsp;</td>
  </tr>
  
          <tr>
  	<td>&nbsp;</td>
    <td>Respuesta</td>
    <td colspan="4"><select id="input" style="width : 100%;"></select></td>
    <td>&nbsp;</td>
  </tr>  


          <tr>
  	<td>&nbsp;</td>
    <td>Valor Sancionado</td>
    <td><input type="text" id="input" style="width : 65%;"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>  

  	<?php echo form_open_multipart(current_url()); ?>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td>&nbsp;</td>	
    <td><input type="checkbox" id="input">&nbspAnexar Archivos</td>
    <td>
    	<?php
         echo form_label('<span class="required"></span>', 'userfile');
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
  	<td>&nbsp;</td>
  	</tr>
  </table>



</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Aceptar'  id='aceptar' >
 <input type='button' name='cancelar' class='btn btn-success' value='Cancelar'  id='cancelar' >
 <input type='button' name='imprimir' class='btn btn-success' value='Imprimir'  id='imprimir' >
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
