<h2>Registro de empresas</h2>
<div class="center-form-large">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <div id="resultado"></div>
  <div class="controls controls-row">
    <h2>Datos de usuario</h2>
    <div class="span2">
      <?php
      echo form_label('Cédula o Nit<span class="required">*</span>', 'cedula');
      $data = array(
          'name' => 'cedula',
          'id' => 'cedula',
          'value' => set_value('cedula'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span2'
      );

      echo form_input($data);
      echo form_error('cedula', '<div>', '</div>');
      ?>
    </div>
    <div class="span3">
      <?php
      echo form_label('Email<span class="required">*</span>', 'email');
      $data = array(
          'name' => 'email',
          'id' => 'email',
          'value' => set_value('email'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span3'
      );

      echo form_email($data);
      echo form_error('email', '<div>', '</div>');
      ?>
    </div>
    <!--<div class="span2">
      <?php
      echo form_label('Cargo<span class="required">*</span>', 'cargo_id');
      foreach ($cargos as $row) {
        $select[$row->IDCARGO] = $row->DESCRIPCIONCARGO;
      }
      echo form_dropdown('cargo_id', $select, '', 'id="cargo" class="chosen span2" placeholder="seleccione..." ');


      echo form_error('cargo_id', '<div>', '</div>');
      ?>
    </div>-->
  </div>
  <div class="controls controls-row">

    <div class="span3">
      <?php
      echo form_label('Nombres<span class="required">*</span>', 'nombres');
      $data = array(
          'name' => 'nombres',
          'id' => 'nombres',
          'value' => set_value('nombres'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span3'
      );

      echo form_input($data);
      echo form_error('nombres', '<div>', '</div>');
      ?>
    </div>
    <div class="span3">
      <?php
      echo form_label('Apellidos<span class="required">*</span>', 'apellidos');
      $data = array(
          'name' => 'apellidos',
          'id' => 'apellidos',
          'value' => set_value('apellidos'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span3'
      );

      echo form_input($data);
      echo form_error('apellidos', '<div>', '</div>');
      ?>
    </div>
    <div class="span2">
      <?php
      echo form_label('Alias / Apodo<span class="required">*</span>', 'nombre');
      $data = array(
          'name' => 'nombre',
          'id' => 'nombre',
          'value' => set_value('nombre'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span2'
      );

      echo form_input($data);
      echo form_error('nombre', '<div>', '</div>');
      ?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span2">
      <?php
      echo form_label('Contraseña<span class="required">*</span>', 'password');
      $data = array(
          'name' => 'password',
          'id' => 'password',
          'value' => set_value('password'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span2'
      );

      echo form_password($data);
      echo form_error('password', '<div>', '</div>');
      ?>
    </div>

    <div class="span2">
      <?php
      echo form_label('Repita su contraseña<span class="required">*</span>', 'repassword');
      $data = array(
          'name' => 'repassword',
          'id' => 'repassword',
          'value' => set_value('repassword'),
          'maxlength' => '128',
          'required' => 'required',
          'class' => 'span2'
      );

      echo form_password($data);
      echo form_error('repassword', '<div>', '</div>');
      ?>

    </div>
    <div class="span2">
      <?php
      ?>
    </div>
  </div>

  <div class="controls controls-row">
    <p class="pull-right">
      <?php echo anchor('/', '<i class="fa fa-minus-circle fa-lg"></i> Cancelar', 'class="btn"'); ?>
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
  </div>
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

  $(document).ready(function() {
    var consulta;
    //hacemos focus
    $("#cedula").focus();
    //comprobamos si se pulsa una tecla
    $("#cedula").on("keydown", function(event) {
      var numero = event.which;
      //obtenemos el texto introducido en el campo
      consulta = $("#cedula").val();
      if (consulta.length > 1) {
        if (numero >= 48 && numero <= 57) {
          //hace la búsqueda
          $("#resultado").html('<img src="<?php echo base_url("img") ?>/throbber.gif" width="24" height="24" />');
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/inicio/id_check",
            data: "c=" + consulta,
            dataType: "html",
            error: function() {
              alert("error petición ajax");
            },
            success: function(data) {
              $("#resultado").html(data);
              //n();
            }
          });
        }
      }
    });
  });
</script>