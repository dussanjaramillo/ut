<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">

  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <?php echo form_hidden('id', $result->COD_TIPO_CERTIFICADO) ?>
  <h2>Editar Tipo Certificado</h2>
  <p>
<?php
echo form_label('Nombre<span class="required">*</span>', 'nombre');
$datanombre = array(
    'name' => 'nombre',
    'id' => 'nombre',
    'value' => $result->NOMBRE_CERTIFICADO,
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
    'value' => $result->DESCRIPCION,
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
    echo form_dropdown('estado_id', $select, $result->ESTADO, 'id="estado" class="chosen span3" data-placeholder="seleccione..." ');

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
    <?php
    if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipocertificados/delete')) {
      echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
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
              location.href = '<?php echo base_url() . "index.php/tipocertificados/delete/" . $result->COD_TIPO_CERTIFICADO; ?>';

            },
            Cancelar: function() {
              $(this).dialog("close");
            }
          }
        });
      });
    });
  </script>

  <div id="dialog-confirm" title="¿Eliminar el banco?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el tipo de certificado? "<?php echo $result->NOMBRE_CERTIFICADO; ?>"?</p>
  </div>