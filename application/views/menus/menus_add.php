<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nuevo elemento del menú</h2>
  <p>
    <?php
    echo form_label('Nombre<span class="required">*</span>', 'nombre');
    $data = array(
        'name' => 'nombre',
        'id' => 'nombre',
        'value' => set_value('nombre'),
        'maxlength' => '30',
        'required' => 'required'
    );

    echo form_input($data);
    echo form_error('nombre', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Método<span class="required">*</span>', 'url');
    $data = array(
        'name' => 'url',
        'id' => 'url',
        'value' => set_value('url'),
        'maxlength' => '60',
        'required' => 'required'
    );

    echo form_input($data);
    echo form_error('url', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Módulo<span class="required">*</span>', 'modulo_id');
    foreach ($modulos as $row) {
      $select[$row->IDMODULO] = $row->NOMBREMODULO;
    }
    echo form_dropdown('modulo_id', $select, '', 'id="modulo" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('modulo_id', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Estado<span class="required">*</span>', 'estado_id');
    foreach ($estados as $row) {
      $select2[$row->IDESTADO] = $row->NOMBREESTADO;
    }
    echo form_dropdown('estado_id', $select2, '', 'id="estado" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('estado_id', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('&Iacute;cono<span class="required">*</span>', 'icono');
    ?> <select name="icono" id="icono" class="chosen" data-placeholder="seleccione..."> <?php
      foreach ($iconos as $row) {
        echo '<option value="' . $row->NOMBREICONO . '" class="fa fa-' . $row->NOMBREICONO . ' fa-lg"> ' . $row->TRADUCCION . '</option>';
      }
      ?> </select> <?php
    echo form_error('icono', '<div>', '</div>');
    ?>

  </p>
  <p>
    <?php
    echo form_label('M&eacute;todo en men&uacute;<span class="required">*</span>', 'icono');
    ?>
    Si 
    <?php
    $data = array(
        'name' => 'en_menu',
        'id' => 'en_menu',
        'value' => '1',
        'maxlength' => '1',
        'required' => 'required',
        'checked' => 'checked'
    );

    echo form_radio($data);
    ?>
     No 
    <?php
    $data = array(
        'name' => 'en_menu',
        'id' => 'en_menu',
        'value' => '0',
        'maxlength' => '1',
        'required' => 'required'
    );

    echo form_radio($data);
    echo form_error('icono', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php echo anchor('menus', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
    '.chosen': {disable_search_threshold: 10}
  }
  for (var selector in config) {
    $(selector).chosen(config[selector]);
  }

</script>
