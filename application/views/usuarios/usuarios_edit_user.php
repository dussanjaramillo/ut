<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
if (isset($message)){
    echo $message;
   }
?>

<div class="center-form-large">
<?php     

echo form_open(current_url()); ?>
<?php echo form_hidden('id',$result->IDUSUARIO) ?>
<?php echo $custom_error; ?>
<h2>Editar sus datos</h2>
  <div class="controls controls-row">

<div class="span2">
<?php
 echo form_label('Alias<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => $result->NOMBREUSUARIO,
              'maxlength'   => '50',
              'required'    => 'required',
              'class'       => 'span2'

            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</div>
<div class="span3">
<?php
 echo form_label('Email<span class="required">*</span>', 'url');
   $data = array(
              'name'        => 'email',
              'id'          => 'email',
              'value'       => $result->EMAIL,
              'maxlength'   => '150',
              'required'    => 'required',
              'class'       => 'span3'
            );

   echo form_email($data);
   echo form_error('email','<div>','</div>');
?>
</div>
<div class="span2">
<?php
 echo form_label('Cédula<span class="required">*</span>', 'cedula');
   $data = array(
              'name'        => 'cedula',
              'id'          => 'cedula',
              'value'       => $result->IDUSUARIO,
							'minlength'   => '6',
              'maxlength'   => '12',
              'required'    => 'required',
              'class'      => 'span2',
							'readonly'      => 'readonly'
            );

   echo form_input($data);
   echo form_error('cedula','<div>','</div>');
?>
</div>
</div>
  <div class="controls controls-row">

<div class="span3">
<?php
 echo form_label('Nombres<span class="required">*</span>', 'nombres');
   $data = array(
              'name'        => 'nombres',
              'id'          => 'nombres',
              'value'       => $result->NOMBRES,
              'maxlength'   => '50',
              'required'    => 'required',
              'class'       => 'span3'
            );

   echo form_input($data);
   echo form_error('nombres','<div>','</div>');
?>
</div>
<div class="span3">
<?php
 echo form_label('Apellidos<span class="required">*</span>', 'apellidos');
   $data = array(
              'name'        => 'apellidos',
              'id'          => 'apellidos',
              'value'       => $result->APELLIDOS,
              'maxlength'   => '100',
              'required'    => 'required',
              'class'       => 'span3'

            );

   echo form_input($data);
   echo form_error('apellidos','<div>','</div>');
?>
</div>
<div class="span2">

</div>
</div>
<div class="controls controls-row">
<div class="span2">

</div>

<div class="span2">

</div>
<div class="span2">

</div>
<div class="controls controls-row"></div>
<div class="controls controls-row">
<p class="pull-right">
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
</div>
<?php echo form_close(); ?>
  
</div>
