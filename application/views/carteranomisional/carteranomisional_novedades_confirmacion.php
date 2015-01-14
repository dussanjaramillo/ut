<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '400'>



<center>
<h2>Confirmación</h2>

<p>   

Desea confirmar la generacion de Novedades para el periodo seleccionado?
<br><br>
NOTA: La información generada incluira los cobros registrados hasta el día 
           de ayer.
<br>
      
<p>
 <center>	 
 <input type='button' name='si' class='btn btn-success' value='SI'  id='si' >
 <input type='button' name='no' class='btn btn-success' value='NO'  id='no' >
 </center>
   </p> 

    
</div>


<script type="text/javascript" language="javascript" charset="utf-8">

$('#confirm').dialog({
                autoOpen: true,
                width: 500,
                height: 400,
                modal:true,
                title:'Confirmación',
                close: function() {
                    $('#confirm *').remove();
                }
            });	
            
 $('#si').click(function(){
 	
	var url2="<?= base_url('index.php/carteranomisional/novedad_correcta') ?>/";
	$('#novedad_correcta').load(url2);
	$('#confirm').dialog('close');
});

 $('#no').click(function(){
$('#confirm').dialog('close');
});
           
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    
 </style>