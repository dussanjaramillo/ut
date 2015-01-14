<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



<center>
<h2>Generación Novedades Cartera No Misional</h2>

<p>   

PERIODO A GENERAR<select name="periodo_id" id="periodo_id">

<option value="0">ENERO 2014</option>
<option value="1">FEBRERO 2014</option>
<option value="2">MARZO 2014</option>


</select>
<br>
COMENTARIOS
<BR>
<textarea rows="5" cols="100"></textarea>

    </p>


</center>  
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='ENVIAR'  id='aceptar' >
 <input type='button' name='cancelar' class='btn btn-success' value='CANCELAR'  id='cancelar' >
 </center>
   </p> 

    
</div>

<div id='confirm'> </div>
<div id='novedad_correcta'> </div>
<script type="text/javascript" language="javascript" charset="utf-8">


            
 $('#aceptar').click(function(){
	var url2="<?= base_url('index.php/carteranomisional/novedades_confirmacion') ?>/";
	$('#confirm').load(url2);
});

 $('#cancelar').click(function(){
history.back();
});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

