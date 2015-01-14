<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='450' height= '450'>
 
 <?php     

        echo form_open(current_url()); ?>
 
<center><h2>Detalle Correo</h2></center>
<p>
FECHA ENVIO: &nbsp<?=$email['FECHA_ENVIO']?>
</p>	
<p>
PARA: &nbsp<?=$email['DIRECCION_DESTINO']?>
</p>	
<p>
CC: &nbsp<?=$email['CC']?>
</p>	
<p>
CCO: &nbsp<?=$email['CCO']?>
</p>	
<p>
ASUNTO: &nbsp<?=$email['ASUNTO']?>
</p>	
<p>
ADJUNTO: &nbsp<?=$email['NOMBRE_ADJUNTO']?>
<?php if(!empty($email['NOMBRE_ADJUNTO'])){ ?>

  <a class="button" id="doc" name="doc">
	Ver
</a>
<?php } ?>
</p>	
<p>
MENSAJE: &nbsp<?=$email['MENSAJE']?>
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
 $('#ver_correo').dialog({
                autoOpen: true,
                width: 450,
                height: 450,
                modal:true,
                title:'Detalle Correo Electronico',
                close: function() {
                    $('#ver_correo *').remove();
                }
            });	
         
$('#doc').click(function(){

	var url="<?=base_url()?>uploads/fiscalizaciones/<?=$email['COD_INTERNO_PROCESO']?>/docemail/<?=$email['NOMBRE_ADJUNTO']?>";
	
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

   