<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<h2>Nuevo módulo</h2>
<p>
<?php
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => set_value('nombre'),
              'maxlength'   => '30',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Controlador<span class="required">*</span>', 'url');
   $data = array(
              'name'        => 'url',
              'id'          => 'url',
              'value'       => set_value('url'),
              'maxlength'   => '30',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('url','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Aplicación<span class="required">*</span>', 'aplicacion_id');  
      foreach($aplicaciones as $row) {
          $select[$row->IDAPLICACION] = $row->NOMBREAPLICACION;
       }
  echo form_dropdown('aplicacion_id', $select,'','id="aplicacion" class="chosen" data-placeholder="seleccione..." ');
 
   echo form_error('aplicacion_id','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');  
      foreach($estados as $row) {
          $select2[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $select2,'','id="estado" class="chosen" data-placeholder="seleccione..." ');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<p>


<?php
 echo form_label('Ícono<span class="required">*</span>', 'icono');  
     ?> <select name="icono" id="icono" class="chosen" data-placeholder="seleccione..."> <?php
      foreach($iconos as $row) {
      echo '<option value="'.$row->NOMBREICONO.'" class="fa fa-'.$row->NOMBREICONO.' fa-lg"> '.$row->TRADUCCION.'</option>';
       }
     ?> </select> <?php 
   echo form_error('icono','<div>','</div>');
?>

</p>
<p>
        <?php  echo anchor('modulos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
    var config = {
      '.chosen'           : {disable_search_threshold: 10}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>
