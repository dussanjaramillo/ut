<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
  <?php
  $attributes = array('class' => 'form-inline');
  echo form_open(current_url(), $attributes);
  ?>
  <?php echo $custom_error; ?>
  <h2>Nuevo plazo de acuerdo de pago</h2>
  <p>
    <?php
    echo form_label('Plazo<span class="required">*</span>', 'plazo');
    $data = array(
        'name' => 'plazo',
        'id' => 'plazo',
        'value' => '',
        'maxlength' => '10',
        'class' => 'input-mini'
    );

    echo form_input($data);
    echo form_error('plazo', '<div>', '</div>');
    ?>

    <?php
    echo form_label('Unidad<span class="required">*</span>', 'unidad');
    $data = array(
        'name' => 'unidad',
        'id' => 'unidad',
        'value' => 'Meses',
        'maxlength' => '10',
        'readOnly' => 'readOnly',
        'class' => 'input-mini'
    );

    echo form_input($data);
    echo form_error('unidad', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Periodidad<span class="required">*</span>', 'periodicidad');
    $options = array(
        'Mensual' => 'Mensual',
        'Bimestral' => 'Bimestral',
        'Trimestral' => 'Trimestral',
        'Semestral' => 'Semestral',
        'Anual' => 'Anual',
    );

    echo form_dropdown('periodicidad', $options, '', 'id="periodicidad" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('periodicidad', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Max. cuotas<span class="required">*</span>', 'maxcuotas');
    $data = array(
        'name' => 'maxcuotas',
        'id' => 'maxcuotas',
        'value' => '',
        'maxlength' => '10'
    );

    echo form_input($data);
    echo form_error('maxcuotas', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Concepto<span class="required">*</span>', 'concepto');
    foreach ($conceptos as $row) {
      $selecc[$row->COD_CPTO_FISCALIZACION] = $row->NOMBRE_CONCEPTO;
    }
    echo form_dropdown('concepto', $selecc, '', 'id="concepto" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('concepto', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Instancia<span class="required">*</span>', 'instancia');
    $optioni = array(
        'Persuasivo' => 'Persuasivo',
        'Coactivo' => 'Coactivo',
    );

    echo form_dropdown('instancia', $optioni, '', 'id="instancia" class="chosen" placeholder="seleccione..." ');

    echo form_error('instancia', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Fecha Creacion<span class="required">*</span>', 'fechacreacion');
    $dataFecha = array(
        'name' => 'fechacreacion',
        'id' => 'fechacreacion',
        'value' => date("d/m/Y"),
        'maxlength' => '128',
        'readonly' => 'readonly'
    );

    echo form_input($dataFecha);
    echo form_error('fechacreacion', '<div>', '</div>');

    echo form_hidden('fechacreaciond', date("d/m/Y"));
    ?>

  </p>
  <p>
    <?php
    echo form_label('Estado<span class="required">*</span>', 'estado_id');
    foreach ($estados as $row) {
      $select[$row->IDESTADO] = $row->NOMBREESTADO;
    }
    echo form_dropdown('estado_id', $select, '', 'id="estado" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('estado_id', '<div>', '</div>');
    ?>
    </span>
  </p>
  <p>
    <?php echo anchor('plazoacuerdospago', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
    if (!state.id)
      return state.text; // optgroup
    return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
  }
  $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) {
      return m;
    }
  });


  $(document).ready(function() {
    $(".chosen").select2();

  });





</script>
