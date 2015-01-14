<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nuevo Estado</h2>
  <p><?php
  echo form_label('Tipo Gesti&oacute;n<span class="required">*</span>', 'tipogestion');
  foreach ($idgestion as $row) {
    $selectg[$row->COD_GESTION] = $row->TIPOGESTION;
  }
  echo form_dropdown('tipogestion', $selectg, '', 'id="tipogestion" class="chosen" data-placeholder="seleccione..." ');

  echo form_error('tipogestion', '<div>', '</div>');
  ?>
  </p>
  <p>
<?php
echo form_label('Nombre Respuesta<span class="required">*</span>', 'nombrerespuesta');
$dataid = array(
    'name' => 'nombrerespuesta',
    'id' => 'nombrerespuesta',
    'value' => set_value('nombrerespuesta'),
    'maxlength' => '128'
);

echo form_input($dataid);
echo form_error('nombrerespuesta', '<div>', '</div>');
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
?>

  </p>
  <p><?php
    echo form_label('Estado<span class="required">*</span>', 'estado_id');
    foreach ($estados as $row) {
      $select[$row->IDESTADO] = $row->NOMBREESTADO;
    }
    echo form_dropdown('estado_id', $select, '', 'id="estado" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('estado_id', '<div>', '</div>');
?>
  </p>
  <!--<p>
<?php
/*echo form_label('Descripción<span class="required">*</span>', 'descripcion');
$datadesc = array(
    'name' => 'descripcion',
    'id' => 'descripcion',
    'value' => set_value('descripcion'),
    'maxlength' => '200',
    'required' => 'required',
    'rows' => '3'
);

echo form_textarea($datadesc);
echo form_error('descripcion', '<div>', '</div>');*/
?>
  </p>-->
  <p>
<?php echo anchor('respuestagestion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
