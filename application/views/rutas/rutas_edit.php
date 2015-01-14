<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">

  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <?php echo form_hidden('id', $result->COD_RUTA_ARCHIVO) ?>
  <h2>Editar Ruta</h2>
  <p>
<?php
echo form_label('Banco<span class="required">*</span>', 'idbanco');
foreach ($bancos as $row) {
  $selectg[$row->IDBANCO] = $row->NOMBREBANCO;
}
echo form_dropdown('idbanco', $selectg, $result->COD_BANCO, 'id="idbanco"  disabled="disabled" class="chosen"');

echo form_error('idbanco', '<div>', '</div>');
?>
  </p>
  <p><?php
    echo form_label('Origen de Datos<span class="required">*</span>', 'idorigendatos');
    foreach ($origendatos as $row) {
      $selecto[$row->COD_ORIGENDATOS] = $row->NOMBRE_ORIGENDATOS;
    }
    echo form_dropdown('idorigendatos', $selecto, $result->COD_ORIGEN_DATOS, 'id="idorigendatos"  disabled="disabled" class="chosen"');

    echo form_error('idorigendatos', '<div>', '</div>');
?>
  </p>
  <p><?php
    echo form_label('Tipo Cargue/Descargue<span class="required">*</span>', 'idtipoaccion');
    foreach ($tipoaccion as $row) {
      $selectt[$row->IDTIPOACCION] = $row->NOMBRETIPOACCION;
    }
    echo form_dropdown('idtipoaccion', $selectt, $result->COD_TIPO_ACCION, 'id="idtipoaccion"  disabled="disabled" class="chosen"');

    echo form_error('idtipoaccion', '<div>', '</div>');
?>
  </p>
  <p><?php
    echo form_label('Tipo Carpeta<span class="required">*</span>', 'idtipocarpeta');
    foreach ($tipocarpeta as $row) {
      $selecti[$row->IDTIPOCARPETA] = $row->NOMBRETIPOCARPETA;
    }
    echo form_dropdown('idtipocarpeta', $selecti, $result->COD_TIPO_CARPETA, 'id="idtipocarpeta"  disabled="disabled" class="chosen"');

    echo form_error('idtipocarpeta', '<div>', '</div>');
?>
  </p>
  <p>
<?php
echo form_label('Ruta<span class="required">*</span>', 'nombreruta');
$data = array(
    'name' => 'nombreruta',
    'id' => 'nombreruta',
    'value' => $result->RUTA,
    'maxlength' => '128'
);

echo form_input($data);
echo form_error('nombreruta', '<div>', '</div>');
?>
  </p>

  <p>
<?php
echo form_label('Estado<span class="required">*</span>', 'estado_id');
foreach ($estados as $row) {
  $optionse[$row->IDESTADO] = $row->NOMBREESTADO;
}
echo form_dropdown('estado_id', $optionse, $result->COD_ESTADO, 'id="estado" class="chosen"');

echo form_error('estado_id', '<div>', '</div>');
?>
  </p> 

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
  <?php
  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('rutas/delete')) {
    echo anchor(base_url().'index.php/rutas/delete/'.$result->COD_RUTA_ARCHIVO, '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
  }
  ?>

</p>

<?php echo form_close(); ?>

<script type="text/javascript">
  //style selects
  var config = {
    '.chosen': {}
  }
  for (var selector in config) {
    $(selector).chosen(config[selector]);
  }

</script>

<script>
  $(function() {
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#borrar").click(function(evento) {
      evento.preventDefault();
      var link = $(this).attr('href');
      $("#dialog:ui-dialog").dialog("destroy");

      $("#dialog-confirm").dialog({
        resizable: false,
        height: 180,
        modal: true,
        buttons: {
          "Confirmar": function() {
            location.href = '<?php echo base_url() . "index.php/rutas/delete/" . $result->IDRUTAARCHIVO; ?>';

          },
          Cancelar: function() {
            $(this).dialog("close");
          }
        }
      });
    });
  });
</script>

<div id="dialog-confirm" title="¿Eliminar la Ruta?" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la ruta "<?php echo $result->RUTA; ?>"?</p>
</div>