<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Nuevo Concepto de Nulidad</h2>
        
        <p>
        <?php
         echo form_label('Nombre Causal<span class="required">*</span>', 'nombreconcepto');
           $dataid = array(
                      'name'        => 'nombreconcepto',
                      'id'          => 'nombreconcepto',
                      'value'       => set_value('nombreconcepto'),
                      'maxlength'   => '100',
                      'required'    => 'required',
                      'class'       => 'span4'
                    );

           echo form_input($dataid);
           echo form_error('nombreconcepto','<div>','</div>');
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $select[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $select,set_value('estado_id'),'id="estado" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
      </spam>
        </p>
        <br>
        <p>
                <?php  echo anchor('conceptos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
        </p>

        <?php echo form_close(); ?>

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
   