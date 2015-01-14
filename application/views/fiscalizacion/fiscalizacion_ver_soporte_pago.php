<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='450' height= '250'>
 
 <?php     

        echo form_open(current_url()); ?>
 
	
 
 
<center><h3>Soporte Pago</h3></center>

<p>
DOCUMENTO: &nbsp<?=$informe['NOMBRE_ARCHIVO']?>
  <a class="button" id="doc" name="doc">
	Ver
</a>
</p>	




     <?php echo form_close(); ?>	
    


</div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }

 </style>


<script type="text/javascript" language="javascript" charset="utf-8">
$(".preload, .load").hide();

 $('#ver_sop_pago').dialog({
                autoOpen: true,
                width: 450,
                height: 250,
                modal:true,
                title:'Ver Soporte Pago',
                close: function() {
                    $('#ver_sop_pago *').remove();
                }
            });	
            
            
            $('#doc').click(function(){

	var url="<?=base_url()?>uploads/fiscalizaciones/<?=$informe['COD_FISCALIZACION_EMPRESA']?>/validarsoportespagos/<?=$informe['NOMBRE_ARCHIVO']?>";
	
	redirect_by_post(url, true);
	});
         
                function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }   
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

   