<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">

  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <?php echo form_hidden('id', $result->COD_PLAZO_ACUERDO_PAGO) ?>
  <h2>Editar plazo de acuerdo de pago</h2>
  <p>
<?php
echo form_label('Plazo<span class="required">*</span>', 'plazo');
$data = array(
    'name' => 'plazo',
    'id' => 'plazo',
    'value' => $result->PLAZO,
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
        'value' => $result->UNIDAD,
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

echo form_dropdown('periodicidad', $options, $result->PERIODICIDAD, 'id="periodicidad" class="chosen" data-placeholder="seleccione..." ');

echo form_error('periodicidad', '<div>', '</div>');
?>
  </p>
  <p>
<?php
echo form_label('Max. cuotas<span class="required">*</span>', 'maxcuotas');
$data = array(
    'name' => 'maxcuotas',
    'id' => 'maxcuotas',
    'value' => $result->MAX_CUOTAS,
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
echo form_dropdown('concepto', $selecc, $result->COD_CONCEPTO, 'id="concepto" class="chosen" readOnly="readOnly"');

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

echo form_dropdown('instancia', $optioni, $result->INSTANCIA, 'id="instancia" class="chosen" placeholder="seleccione..." ');

echo form_error('instancia', '<div>', '</div>');
?>
  </p>

  <p>
<?php
echo form_label('Estado<span class="required">*</span>', 'estado_id');
foreach ($estados as $row) {
  $options[$row->IDESTADO] = $row->NOMBREESTADO;
}
echo form_dropdown('estado_id', $options, $result->COD_ESTADO, 'id="estado" class="chosen"');

echo form_error('estado_id', '<div>', '</div>');
?>
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
    <?php
    if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('plazoacuerdospago/delete')) {
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
              location.href = '<?php echo base_url() . "index.php/plazoacuerdospago/delete/" . $result->COD_PLAZO_ACUERDO_PAGO; ?>';

            },
            Cancelar: function() {
              $(this).dialog("close");
            }
          }
        });
      });
    });
  </script>

  <div id="dialog-confirm" title="¿Eliminar el plazo de acuerdo de pago?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el plazo de acuerdo de pago "<?php echo $result->PLAZO; ?> <?php echo $result->UNIDAD; ?>"?</p>
  </div>