<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="center-form">


  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nuevo Tipo Certificado</h2>
  <p>
    <?php
    echo form_label('Nombre<span class="required">*</span>', 'nombre');
    $datanombre = array(
        'name' => 'nombre',
        'id' => 'nombre',
        'value' => set_value('nombre'),
        'maxlength' => '128',
        'class' => 'span3',
        'required' => 'required'
    );

    echo form_input($datanombre);
    echo form_error('nombre', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Descripción<span class="required">*</span>', 'descripcion');
    $data = array(
        'name' => 'descripcion',
        'id' => 'descripcion',
        'value' => set_value('descripcion'),
        'maxlength' => '128',
        'rows' => '3',
        'class' => 'span3',
        'required' => 'required'
    );

    echo form_textarea($data);
    echo form_error('descripcion', '<div>', '</div>');
    ?>

  </p>
  <p>
<?php
echo form_label('Estado<span class="required">*</span>', 'estado_id');
foreach ($estados as $row) {
  $select[$row->IDESTADO] = $row->NOMBREESTADO;
}
echo form_dropdown('estado_id', $select, '', 'id="estado" class="chosen span3" data-placeholder="seleccione..." ');

echo form_error('estado_id', '<div>', '</div>');
?>

  </p>
  <p>
<?php echo anchor('tipocertificados', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
  /*$(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) {
      return m;
    }
  });*/


  $(document).ready(function() {
    //$(".chosen").select2();

  });





</script>

<!--// |:::::Deshabilitar el resize de los text_area -->
<style type="text/css">
  textarea{ resize:none }
</style>
