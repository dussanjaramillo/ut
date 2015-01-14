<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Agregar Gestión</h2>
        
        <p>
        <?php
         echo form_label('Tipo Gestión<span class="required">*</span>', 'tipogestion');
           $dataid = array(
                      'name'        => 'tipogestion',
                      'id'          => 'tipogestion',
                      'value'       => set_value('tipogestion'),
                      'maxlength'   => '100',
                      'class'       => 'span4',
                      'required'    => 'required'
                    );

           echo form_input($dataid);
           echo form_error('tipogestion','<div>','</div>');
        ?>
        </p>
        <p>
        <?php
         echo form_label('Tipo Proceso<span class="required">*</span>', 't_proceso');                               
              foreach($procesos as $row) {
                  $options[$row->COD_TIPO_PROCESO] = $row->TIPO_PROCESO;
               }
          echo form_dropdown('t_proceso', $options,set_value('t_proceso'),'id="t_proceso" class="chosen span4"');
         
           echo form_error('t_proceso','<div>','</div>');
        ?>
        </p>
         <br><br>
        <p>
                <?php  echo anchor('gestion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   