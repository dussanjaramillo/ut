<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">

  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <?php echo form_hidden('id', $result->COD_CIUU) ?>
  <h2>Editar CIIU</h2>
  <p>
    <?php
    echo form_label('División<span class="required">*</span>', 'division');
    $data = array(
        'name' => 'division',
        'id' => 'division',
        'value' => $result->DIVISION,
        'maxlength' => '12'
    );

    echo form_input($data);
    echo form_error('division', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Grupo<span class="required">*</span>', 'grupo');
    $data = array(
        'name' => 'grupo',
        'id' => 'grupo',
        'value' => $result->GRUPO,
        'maxlength' => '12'
    );

    echo form_input($data);
    echo form_error('grupo', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('CLASE<span class="required">*</span>', 'clase');
    $data = array(
        'name' => 'clase',
        'id' => 'clase',
        'value' => $result->CLASE,
        'maxlength' => '12'
    );

    echo form_input($data);
    echo form_error('clase', '<div>', '</div>');
    ?>
  </p>
  <p>
    <?php
    echo form_label('Descripción<span class="required">*</span>', 'descripcion');
    $data = array(
        'name' => 'descripcion',
        'id' => 'descripcion',
        'value' => $result->DESCRIPCION,
        'maxlength' => '250'
    );

    echo form_textarea($data);
    echo form_error('descripcion', '<div>', '</div>');
    ?>
  </p>
  <p>     
    <?php echo anchor('ciiu', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
    <?php echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"'); ?>
  </p>
<?php echo form_close(); ?>
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
              location.href = '<?php echo base_url() . "index.php/aplicaciones/delete/" . $result->IDAPLICACION; ?>';

            },
            Cancelar: function() {
              $(this).dialog("close");
            }
          }
        });
      });
    });
  </script>

  <div id="dialog-confirm" title="¿Eliminar la aplicación?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la aplicación "<?php echo $result->NOMBREAPLICACION; ?>"?</p>
  </div>