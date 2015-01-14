<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nueva Tasa de pago</h2>
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
echo form_label('Valor tasa<span class="required">*</span>', 'valortasa');
$data = array(
    'name' => 'valortasa',
    'id' => 'valortasa',
    'value' => '',
    'maxlength' => '10',
    'class' => 'input-mini'
);

echo form_input($data);
echo '<span class="add-on">%</span>';
echo form_error('valortasa', '<div>', '</div>');
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
<?php echo anchor('tasaspago', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
