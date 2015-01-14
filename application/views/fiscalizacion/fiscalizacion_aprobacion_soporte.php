<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form" width='420' height= '550'>
 
 <?php     

        echo form_open(current_url()); ?>
 
<center><h2>Aprobación Soporte de Pago</h2></center>
<center>
FECHA&nbsp: &nbsp<?=$fecha?>	  &nbsp HORA&nbsp:  &nbsp<?=$hora?>  	<br>
</center>


<input type="hidden" id="cod_gestion_cobro" name="cod_gestion_cobro" value="<?=$aprobacion['COD_GESTION_COBRO']  ?>" >
<input type="hidden" id="cod_soporte_gestion" name="cod_soporte_gestion" value="<?=$aprobacion['COD_SOPORTE_GESTION']  ?>" >

	<p>   
     <?php
		
         echo form_label('Concepto:<span class="required">*</span>', 'concepto_id');  
              foreach($conceptos as $row) {
                  $select[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
               }
          echo form_dropdown('concepto_id', $select,$aprobacion['COD_CONCEPTO'],'id="concepto_id" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('concepto_id','<div>','</div>');
        

        ?>
  </p>
	
Archivo&nbsp: &nbsp<?=$aprobacion['NOMBRE_ARCHIVO']?> &nbsp&nbsp&nbsp&nbsp
  <a class="button" id="doc" name="doc">
	Ver
</a>
	
<p>
        <?php
         echo form_label('Descripción<span class="required">*</span>', 'descripcion');
           $datadesc = array(
                      'name'        => 'descripcion',
                      'id'          => 'descripcion',
                      'value'       => base64_decode($aprobacion['COMENTARIOS']),
                      'maxlength'   => '200',
                      'required'    => 'required',
                      'rows'        => '3',
                      'disabled'	=> 'disabled'
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion','<div>','</div>');
        ?>
        </span>
        </p>
<center>
<input type='button' name='responder' class='btn btn-success' value='Responder'  id='responder'">
</center>
     <?php echo form_close(); ?>	
    


</div>

 <div id='respuesta'> </div>
 <style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }

 </style>
     <script type="text/javascript" language="javascript" charset="utf-8">
 $('#responder').click(function(){
	var cod_gestion_cobro=$("#cod_gestion_cobro").val();
	var cod_soporte_gestion_cobro=$("#cod_soporte_gestion").val();


	var url4="<?= base_url('index.php/fiscalizacion/respuesta_aprobacion_soporte') ?>/";
	$('#respuesta').load(url4,{cod_gestion_cobro : cod_gestion_cobro,cod_soporte_gestion_cobro: cod_soporte_gestion_cobro });

});
 
 
 $('#aprobacion').dialog({
                autoOpen: true,
                width: 420,
                height: 550,
                modal:true,
                title:'Aprobación Soporte de Pago',
                close: function() {
                    $('#aprobacion *').remove();
                }
            });	
            
             $('#doc').click(function(){

	var url="<?=base_url()?>uploads/fiscalizaciones/<?=$aprobacion['COD_FISCALIZACION']?>/validarsoportespagos/<?=$aprobacion['NOMBRE_ARCHIVO']?>";
	
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