<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Actualizar Tasa UVT</h2>
       
        <p>

        <?php
        echo form_label('UVT<span class="required">*</span>', 'tasa');

         echo "<div class='input-prepend'>";
         echo "<span class='add-on'>$</span>";
         $data = array(
                    'name'        => 'tasa',
                    'id'          => 'tasa',
                    'value'       => set_value('tasa'),
                    'maxlength'   => '8',
                    'class'       =>  'input-medium span2',
                    'placeholder' => '####.##',
                    'required'    => 'required'
                  );

         echo form_input($data);
         echo "</div>";
         echo form_error('tasa','<div>','</div>');
        ?>

        </p>

        <p>

        <?php
        echo form_label('SMMLV<span class="required">*</span>', 'salario');

         echo "<div class='input-prepend'>";
         echo "<span class='add-on'>$</span>";
         $data = array(
                    'name'        => 'salario',
                    'id'          => 'salario',
                    'value'       => set_value('salario'),
                    'maxlength'   => '15',
                    'class'       =>  'input-medium span2',
                    'placeholder' => '####.##',
                    'required'    => 'required'
                  );

         echo form_input($data);
         echo "</div>";
         echo form_error('salario','<div>','</div>');
        ?>

        </p>

        <p>

        <?php
        echo form_label('Año de Vigencia<span class="required">*</span>', 'anio');

        
         $data = array(
                    'name'        => 'anio',
                    'id'          => 'anio',
                    'value'       => set_value('anio'),
                    'maxlength'   => '4',
                    'class'       => 'span2',
                    'required'    => 'required'
                    
                  );

         echo form_input($data);
         
         echo form_error('salario','<div>','</div>');
        ?>

        </p>


         

        <p>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   