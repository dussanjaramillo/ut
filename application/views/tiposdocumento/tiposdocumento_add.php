<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<h2>Nuevo tipo de documento</h2>
<p>
<?php
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => set_value('nombre'),
              'maxlength'   => '128',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>

<p>
        <?php  echo anchor('tiposdedocumento', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
