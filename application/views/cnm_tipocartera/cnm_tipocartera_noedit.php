<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='450' height= '280'>

 <center>
   <h3>Edición Tipo de Cartera</h3>
   ¡Esta Cartera no puede editarse debido a que existen creditos creados por este Concepto.!   
</center>
</div>
  <script type="text/javascript">
  $('#editar').dialog({
                autoOpen: true,
                width: 450,
                height: 280,
                modal:true,
                title:'Editar Tipo Cartera',
                close: function() {
                    $('#editar *').remove();
                }
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