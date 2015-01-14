<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large" style="overflow: hidden">
  <?php echo form_open(current_url()); ?>
  <?php echo $custom_error; ?>
  <h2>Nuevo usuario</h2>
  <div class="controls controls-row">
    <div class="span2">
      <?php
			echo form_label('Alias <span class="required">*</span>', 'nombre');
			$data = array('name'        => 'nombre',
										'id'          => 'nombre',
										'value'       => set_value('nombre'),
										'maxlength'   => '50',
										'required'    => 'required',
										'class'       => 'span2'
							);
			echo form_input($data);
			echo form_error('nombre','<div>','</div>');
			?>
    </div>
    <div class="span3">
      <?php
			echo form_label('Email empresarial y de acceso<span class="required">*</span>', 'url');
			$data = array('name'        => 'email',
										'id'          => 'email',
										'value'       => set_value('email'),
										'maxlength'   => '150',
										'required'    => 'required',
										'class'       => 'span3'
							);
			echo form_email($data);
			echo form_error('email','<div>','</div>');
			?>
    </div>
    <div class="span2">
      <?php
			echo form_label('Cédula<span class="required">*</span>', 'cedula');
			$data = array('name'        => 'cedula',
										'id'          => 'cedula',
										'value'       => set_value('cedula'),
										'minlength'   => '6',
										'maxlength'   => '12',
										'required'    => 'required',
										'class'      => 'span2'
							);
			echo form_input($data);
			echo form_error('cedula','<div>','</div>');
			?>
    </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
			echo form_label('Nombres<span class="required">*</span>', 'nombres');
			$data = array('name'        => 'nombres',
										'id'          => 'nombres',
										'value'       => set_value('nombres'),
										'maxlength'   => '50',
										'required'    => 'required',
										'class'       => 'span3'
							);
			echo form_input($data);
			echo form_error('nombres','<div>','</div>');
			?>
    </div>
    <div class="span3">
      <?php
			echo form_label('Apellidos<span class="required">*</span>', 'apellidos');
			$data = array('name'        => 'apellidos',
										'id'          => 'apellidos',
										'value'       => set_value('apellidos'),
										'maxlength'   => '100',
										'required'    => 'required',
										'class'       => 'span3'
							);
			echo form_input($data);
			echo form_error('apellidos','<div>','</div>');
			?>
    </div>
    <div class="span2"> </div>
  </div>
  <div class="controls controls-row">
    <div class="span3">
      <?php
			echo form_label('Regional<span class="required">*</span>', 'regional_id');                               
			foreach($regionales as $row) {
				$selectx[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
			}
			echo form_dropdown('regional_id', $selectx,'','id="regional" class="chosen span3" placeholder="seleccione..." ');
			echo form_error('regional_id','<div>','</div>');
			?>
    </div>
    <div class="span2">
      <?php
			echo form_label('Cargo<span class="required">*</span>', 'cargo_id');                               
			foreach($cargos as $row) {
				$select1[$row->IDCARGO] = $row->NOMBRECARGO;
			}
			echo form_dropdown('cargo_id', $select1,'','id="cargo" class="chosen span2" placeholder="seleccione..." ');
			echo form_error('cargo_id','<div>','</div>');
			?>
    </div>
    <div class="span3">
      <?php
			echo form_label('Grupo<span class="required">*</span>', 'grupo_id');                               
			foreach($grupos as $row) {
				$select[$row->IDGRUPO] = $row->NOMBREGRUPO;
			}
			echo form_dropdown('grupo_id', $select,'','id="grupo" class="chosen span3" placeholder="seleccione..." ');
			echo form_error('grupo_id','<div>','</div>');
			?>
    </div>
  </div>
  <br>
  <div class="controls controls-row">
    <div class="span3">
      <?php
			echo form_label('¿El usuario es fiscalizador?<span class="required">*</span>', 'fiscalizador');
			echo form_label('No&nbsp;&nbsp;', '', array('class'=>'pull-left'));
			$data = array('name'      => 'fiscalizador',
										'id'        => 'fiscalizador',
										'value'     => 'N',
										'required'	=> 'required',
										'checked'		=> TRUE,
										'class'			=> 'pull-left'
							);
			echo form_radio($data);
			echo form_label('&nbsp;&nbsp;Si&nbsp;&nbsp;', '', array('class'=>'pull-left'));
			$data = array('name'			=> 'fiscalizador',
										'id'				=> 'fiscalizador',
										'value'			=> 'S',
										'required'	=> 'required',
										'checked'		=> FALSE,
										'class'			=> 'pull-left'
							);
			echo form_radio($data);
			echo form_error('fiscalizador','<div>','</div>');
			?>
    </div>
    <div class="span2">
      <?php
			echo form_label('Contraseña<span class="required">* **</span>', 'password');
			$data = array('name'        => 'password',
										'id'          => 'password',
										'value'       => set_value('password'),
										'minlength'   => '6',
										'maxlength'   => '128',
										'required'    => 'required',
										'class'       => 'span2'
							);
			echo form_password($data);
			echo form_error('password','<div>','</div>');
			?>
    </div>
    <div class="span2">
      <?php
			echo form_label('Repita su contraseña<span class="required">* **</span>', 'repassword');
			$data = array('name'        => 'repassword',
										'id'          => 'repassword',
										'value'       => set_value('repassword'),
										'minlength'   => '6',
										'maxlength'   => '128',
										'required'    => 'required',
										'class'       => 'span2'
							);
			echo form_password($data);
			echo form_error('repassword','<div>','</div>');
			?>
    </div>
    <div class="span2"> </div>
  </div>
  <div class="controls controls-row">
  	<div class="span3">
			<?php
			echo form_label('Dirección <span class="required">*</span>', 'direccion');
      $data = array('name'        => 'direccion',
										'id'          => 'direccion',
										'value'       => set_value('direccion'),
										'maxlength'   => '100',
										'class'       => 'span3',
										'required'    => 'required'
							);
      
      echo form_input($data);
      echo form_error('direccion','<div>','</div>');
      ?>
		</div>
    <div class="span2">
			<?php
      echo form_label('Teléfono', 'telefono');
      $data = array('name'			=> 'telefono',
										'id'				=> 'telefono',
										'value'			=> set_value('telefono'),
										'maxlength'	=> '12',
										'class'			=> 'span2'
							);
      echo form_input($data);
      echo form_error('telefono','<div>','</div>');
      ?>
    </div>
    <div class="span2">
			<?php
      echo form_label('Celular <span class="required">*</span>', 'celular');
      $data = array('name'			=> 'celular',
										'id'				=> 'celular',
										'value'			=> set_value('celular'),
										'maxlength'	=> '12',
										'class'			=> 'span2',
										'required'	=> 'required'
							);
      
      echo form_input($data);
      echo form_error('celular','<div>','</div>');
      ?>
    </div>
  </div>
  <div class="controls controls-row">
  	<div class="span3">
			<?php
      echo form_label('Email Personal <span class="required">*</span>', 'emailp');
      $data = array('name'        => 'emailp',
										'id'          => 'emailp',
										'value'       => set_value('emailp'),
										'maxlength'   => '50',
										'class'       => 'span3',
										'placeholder' => 'ejemplo@sudominio.com',
										'required'    => 'required'
							);
      echo form_input($data);
      echo form_error('emailp','<div>','</div>');
      ?>
    </div>
  </div>
  <div class="controls controls-row">
    <p class="pull-right">
      <?php  echo anchor('usuarios', '<i class="fa fa-minus-circle fa-lg"></i> Cancelar', 'class="btn btn-warning"'); ?>
      <?php 
			$data = array('name' => 'button',
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
  <div class="span8"> <span class="required">**</span> La contraseña debe tener al menos 6 caracteres.
    <div class="alert alert-warning">Por favor revise el número de documento y verifique que está correcto; este no podrá ser editado despues de guardar los datos del usuario.</div>
  </div>
</div>
<script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script> 
<script>
$(function() {
$( "#radio" ).buttonset();
});
</script>