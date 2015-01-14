<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='450' height= '450'>
 
 <?php     

        echo form_open(current_url()); ?>
 
	<input type="hidden" id="cod_fisc" name="cod_fisc" value="<?=$autorizacion['COD_FISCALIZACION_EMPRESA']  ?>" >	
 
 
<center><h3>Detalle Autorización Notificación</h3></center>
<p>
	<?php 
	if($autorizacion['AUTORIZA']=='S')
	{
			$valautorizacion='Si';
	}
	else {
		$valautorizacion='No';
	}
	?>
AUTORIZA: &nbsp<?=$valautorizacion?>
</p>	
<p>
CONTACTO: &nbsp<?=base64_decode($autorizacion['NOMBRE_CONTACTO'])?>
</p>	
<p>
EMAIL AUTORIZADO: &nbsp<?=$autorizacion['EMAIL_AUTORIZADO']?>
</p>	
<p>
FECHA: &nbsp<?=$autorizacion['FECHA_AUTORIZACION']?>
</p>	
<p>
DOCUMENTO: &nbsp<?=$autorizacion['DOCUMENTO_AUTORIZACION']?>
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
 $('#ver_autorizacion_noti_email').dialog({
                autoOpen: true,
                width: 450,
                height: 450,
                modal:true,
                title:'Ver Autorización Notificación Email',
                close: function() {
                    $('#ver_autorizacion_noti_email *').remove();
                }
            });	
            
$('#doc').click(function(){

	var url="<?=base_url()?>uploads/fiscalizaciones/<?=$autorizacion['COD_FISCALIZACION_EMPRESA']?>/autorizacionenvioemail/<?=$autorizacion['DOCUMENTO_AUTORIZACION']?>";
	
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

   