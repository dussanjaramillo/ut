<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '400'>



<center>
<h2>Generación de Novedades</h2>

<p>   
Se ha terminado correctamente el proceso de generación de novedades para el periodo dado:   Nov 2013
</p>
 <center>	 
 <input type='button' name='salir' class='btn btn-success' value='ACEPTAR'  id='salir' >
 </center>

    
</div>

<form id="novedades_form" action="<?= base_url('index.php/carteranomisional/novedades') ?>" method="post" >
	
</form>

<script type="text/javascript" language="javascript" charset="utf-8">

$('#novedad_correcta').dialog({
                autoOpen: true,
                width: 500,
                height: 400,
                modal:true,
                title:'Confirmación',
                close: function() {
                    $('#novedad_correcta *').remove();
                }
            });	
            


 $('#salir').click(function(){
 	$('#novedades_form').submit();
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