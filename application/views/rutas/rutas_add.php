<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nueva Ruta</h2>
  <p><?php
  echo form_label('Banco<span class="required">*</span>', 'idbanco');
  foreach ($bancos as $row) {
    $selectg[$row->IDBANCO] = $row->NOMBREBANCO;
  }
  echo form_dropdown('idbanco', $selectg, '', 'id="idbanco" class="chosen" data-placeholder="seleccione..." ');

  echo form_error('idbanco', '<div>', '</div>');
  ?>
  </p>
  <p><?php
    echo form_label('Origen de Datos<span class="required">*</span>', 'idorigendatos');
    foreach ($origendatos as $row) {
      $selecto[$row->COD_ORIGENDATOS] = $row->NOMBRE_ORIGENDATOS;
    }
    echo form_dropdown('idorigendatos', $selecto, '', 'id="idorigendatos" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('idorigendatos', '<div>', '</div>');
  ?>
  </p>
  <p><?php
    echo form_label('Tipo Cargue/Descargue<span class="required">*</span>', 'idtipoaccion');
    foreach ($tipoaccion as $row) {
      $selectt[$row->IDTIPOACCION] = $row->NOMBRETIPOACCION;
    }
    echo form_dropdown('idtipoaccion', $selectt, '', 'id="idtipoaccion" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('idtipoaccion', '<div>', '</div>');
  ?>
  </p>
  <p><?php
    echo form_label('Tipo Carpeta<span class="required">*</span>', 'idtipocarpeta');
    foreach ($tipocarpeta as $row) {
      $selecti[$row->IDTIPOCARPETA] = $row->NOMBRETIPOCARPETA;
    }
    echo form_dropdown('idtipocarpeta', $selecti, '', 'id="idtipocarpeta" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('idtipocarpeta', '<div>', '</div>');
  ?>
  </p>
  <p>
<?php
echo form_label('Ruta<span class="required">*</span>', 'nombreruta');
$dataruta = array(
    'name' => 'nombreruta',
    'id' => 'nombreruta',
    'placeholder' => 'C:\...\salida',
    'maxlength' => '128'
);

echo form_input($dataruta);
echo form_error('nombreruta', '<div>', '</div>');
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
      $selecte[$row->IDESTADO] = $row->NOMBREESTADO;
    }
    echo form_dropdown('estado_id', $selecte, '', 'id="estado" class="chosen" data-placeholder="seleccione..." ');

    echo form_error('estado_id', '<div>', '</div>');
?>
  </p>
  <p>
<?php echo anchor('rutas', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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

  function fnc(v) {
    alert(v);
  }

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
