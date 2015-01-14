<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



 <?php     

        echo form_open_multipart('cnm_responsabilidad_bienes/subir_archivo'); ?>
        <?php echo $custom_error; ?>
<h2>Cargue Activos Dados de Baja</h2>



<p>   
     <?php
		
         echo form_label('Tipo de Cartera:<span class="required">*</span>', 'cartera_id');  
              foreach($tipos as $row) {
                  $select[$row->COD_TIPOCARTERA] = $row->NOMBRE_CARTERA;
               }
          echo form_dropdown('cartera_id', $select,'','id="cartera_id" readonly="readonly" disabled="disabled" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('cartera_id','<div>','</div>');
        

        ?>
  </p>
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


<script type="text/javascript" language="javascript" charset="utf-8">

            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

