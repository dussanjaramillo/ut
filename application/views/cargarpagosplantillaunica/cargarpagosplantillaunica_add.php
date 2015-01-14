<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
        <?php     

       echo form_open_multipart(current_url()); 
       ?>
        <?php //echo $custom_error; ?>
        <h2>Cargar archivo de pagos planilla única</h2>
   
        <p>
        <?php
         echo form_label('Cargar archivo (Archivo ZIP que contenga archivos de texto) <span class="required">*</span>', 'archivo');
        ?>
         <input type="file" name="archivo" id="archivo" size="20" />
        </p>

     

        <p>
                <?php  echo anchor('cargarpagosplantillaunica', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Cargar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Cargar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
            <?php echo form_error('archivo','<div>','</div>'); ?>    
        </p>

        <?php echo form_close(); ?>

<textarea  class="input-block-level" rows="8">


</textarea>
</div>





  <script type="text/javascript">
  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

  



  </script>
   