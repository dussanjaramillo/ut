<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Agregar Tasa Interés Histórica</h2>
        
        <p>
        <?php
         echo form_label('Tipo Tasa Histórico <span class="required">*</span>', 'tasahistorico');
           $datatt = array(
                      'name'        => 'tasahistorico',
                      'id'          => 'tasahistorico',
                      'value'       => set_value('tasahistorico'),
                      'maxlength'   => '128'
                    );

           echo form_input($datatt);
           echo form_error('tasahistorico','<div>','</div>');
        ?>
        </p>
         <p>
        <?php
         echo form_label('Tipo de Tasa <span class="required">*</span>', 'tipotasa');
         foreach($estados as $row) {
                  $tipot[$row->IDESTADO] = $row->NOMBREESTADO;
               }

          echo form_dropdown('tipotasa', $tipot,'','id="tipotasa" class="chosen" data-placeholder="seleccione..." ');
                         
          echo form_error('tipotasa','<div>','</div>');
                          
        ?>
        </p>
                
        <p>
                <?php  echo anchor('intereshistorico', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   