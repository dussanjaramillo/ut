<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<h2>Nuevo CIIU</h2>
<p>
<?php
 echo form_label('División<span class="required">*</span>', 'division');
   $data = array(
              'name'        => 'division',
              'id'          => 'division',
              'value'       => set_value('division'),
              'maxlength'   => '12',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('division','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Grupo<span class="required">*</span>', 'grupo');
   $data = array(
              'name'        => 'grupo',
              'id'          => 'grupo',
              'value'       => set_value('grupo'),
              'maxlength'   => '12',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('grupo','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Clase<span class="required">*</span>', 'clase');
   $data = array(
              'name'        => 'clase',
              'id'          => 'clase',
              'value'       => set_value('clase'),
              'maxlength'   => '12',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('clase','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Descripción<span class="required">*</span>', 'descripcion');
   $data = array(
              'name'        => 'descripcion',
              'id'          => 'descripcion',
              'value'       => set_value('descripcion'),
              'maxlength'   => '128',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('descripcion','<div>','</div>');
?>
</p>
<p>
        <?php  echo anchor('ciiu', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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

   