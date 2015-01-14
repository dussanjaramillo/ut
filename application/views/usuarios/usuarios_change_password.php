<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
if (isset($message)){
    echo $message;
   }
?>

<div class="center-form">
<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<h2>Editar su contraseña</h2>
<p>
<?php
 echo form_label('Contraseña actual<span class="required">*</span>', 'old');
   $data = array(
              'name'        => 'old',
              'id'          => 'old',
              'value'       => set_value('old'),
              'maxlength'   => '128',
              'required'    => 'required'
            );

   echo form_password($data);
   echo form_error('old','<div>','</div>');
?>
</p>
<p>
  <?php
 echo form_label('Nueva contraseña<span class="required">* **</span>', 'password');
   $data = array(
              'name'        => 'password',
              'id'          => 'password',
              'value'       => set_value('password'),
              'maxlength'   => '128',
              'required'    => 'required'

            );

   echo form_password($data);
   echo form_error('password','<div>','</div>');
?>
</p>
<p>
  <?php
 echo form_label('Repita su contraseña nueva<span class="required">* **</span>', 'repassword');
   $data = array(
              'name'        => 'repassword',
              'id'          => 'repassword',
              'value'       => set_value('repassword'),
              'maxlength'   => '128',
              'required'    => 'required'

            );

   echo form_password($data);
   echo form_error('repassword','<div>','</div>');
?>
</p>
<p>
        <?php  echo anchor('inicio', '<i class="fa fa-minus-circle fa-lg"></i> Cancelar', 'class="btn btn-warning"'); ?>
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
<span class="required">**</span> La contraseña debe tener al menos 6 caracteres.
</div>
<?php echo form_close(); ?>
  
</div>
