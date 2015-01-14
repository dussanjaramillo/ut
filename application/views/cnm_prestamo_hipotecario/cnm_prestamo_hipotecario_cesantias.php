<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<center>
<div class="center-form" width='500' height= '700'>



 <?php     

        echo form_open_multipart('cnm_prestamo_hipotecario/subir_archivo_cesantias'); ?>
        <?php echo $custom_error; ?>
<h2>Cargue de Cesantias</h2>



  <p> 
        <?php
         echo form_label('Adjuntar:<span class="required">*</span>', 'userfile');
           $dataAdjuntar = array(
                      'name'        => 'userfile[]',
                      'id'          => 'userfile',
                      'maxlength'   => '128',
                      'style'		=> 'text-align:right',
                      'type' 		=> 'file',
                      'required'    => 'required',
                      'multiple'    => '',
                    );

           echo form_upload($dataAdjuntar);
           echo form_error('userfile','<div>','</div>');
        ?>
</p> 
<input type="hidden" id="vista_flag" name="vista_flag" value='1' >        

      
<p>
 <?php  echo anchor('carteranomisional', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
 <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Enviar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Enviar',
                       'class' => 'btn btn-success',
						
                       );

                echo form_button($data);    
                ?>
    </p> 
     <?php echo form_close(); ?>	
    
</div>
</center>

<script type="text/javascript" language="javascript" charset="utf-8">

            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

