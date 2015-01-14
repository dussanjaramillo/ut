<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form">
<?php echo form_open(current_url()); ?> <?php echo $custom_error; ?> <?php echo form_hidden('id', $result->IDMENU) ?>
<h2>Editar elemento del menú</h2>
<p>
  <?php
	echo form_label('Nombre <span class="required">*</span>', 'nombre');
	$data = array(
		'name' => 'nombre',
		'id' => 'nombre',
		'value' => $result->NOMBREMENU,
		'maxlength' => '30'
	);
	echo form_input($data);
	echo form_error('nombre', '<div>', '</div>');
	?>
</p>
<p>
  <?php
	echo form_label('Método <span class="required">*</span>', 'url');
	$data = array(
		'name' => 'url',
		'id' => 'url',
		'value' => $result->URL,
		'maxlength' => '60',
		'class' => 'span3'
	);
	echo form_input($data);
	echo form_error('url', '<div>', '</div>');
	?>
</p>
<p>
  <?php
	echo form_label('Módulo <span class="required">*</span>', 'modulo_id');
	foreach ($modulos as $row) {
		$options[$row->IDMODULO] = $row->NOMBREMODULO;
		if($result->IDMODULO == $row->IDMODULO) : $nombre_modulo = $row->NOMBREMODULO; endif;
	}
	echo form_dropdown('modulo_id', $options, $result->IDMODULO, 'id="modulo" class="chosen"');
	echo form_error('modulo_id', '<div>', '</div>');
	?>
</p>
<p>
  <?php
	echo form_label('Estado <span class="required">*</span>', 'estado_id');
	foreach ($estados as $row) {
		$options2[$row->IDESTADO] = $row->NOMBREESTADO;
	}
	echo form_dropdown('estado_id', $options2, $result->IDESTADO, 'id="estado" class="chosen"');
	echo form_error('estado_id', '<div>', '</div>');
	?>
</p>
<p>
<?php echo form_label('&Iacute;cono <span class="required">*</span>', 'icono'); ?>
<select name="icono" id="icono" class="chosen" data-placeholder="seleccione...">
  <?php
  foreach ($iconos as $row) {
		if ($row->NOMBREICONO == $result->ICONOMENU) {
			echo $selected = 'selected';
		} else {
			$selected = '';
		}
		echo	'<option value="' . $row->NOMBREICONO . '" class="fa fa-' . $row->NOMBREICONO . ' fa-lg" ' . $selected . ' > ' . 
					$row->TRADUCCION . '</option>';
	}
	?>
</select>
<?php echo form_error('icono', '<div>', '</div>'); ?>
</p>
<p>
  <p>
    <?php echo form_label('M&eacute;todo en men&uacute; <span class="required">*</span>', 'icono'); ?> Si
    <?php
      $data = array(
				'name' => 'en_menu',
				'id' => 'en_menu',
				'value' => '1',
				'maxlength' => '1',
				'required' => 'required'
      );
      if($result->IN_MENU == 1) :
        $data['checked'] = "checked";
      endif;
      echo form_radio($data);
		?> No
    <?php
      $data = array(
				'name' => 'en_menu',
				'id' => 'en_menu',
				'value' => '0',
				'maxlength' => '1',
				'required' => 'required'
      );
      if($result->IN_MENU == 0) :
        $data['checked'] = "checked";
      endif;
      echo form_radio($data);
      echo form_error('icono', '<div>', '</div>');
      ?>
  </p>
</p>
<p>
	<?php echo anchor('menus', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
<div id="dialog-confirm" title="¿Eliminar el elemento del menú?" style="display:none;">
  <p>
  	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    ¿Confirma que desea eliminar el elemento del menú "<?php echo $nombre_modulo; ?>"?
	</p>
</div>
<script type="text/javascript">
//style selects
var config = {'.chosen': {disable_search_threshold: 10}}

for (var selector in config) {
	$(selector).chosen(config[selector]);
}

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
					location.href = '<?php echo base_url() . "index.php/menus/delete/" . $result->IDMENU; ?>';
				},
				Cancelar: function() {
					$(this).dialog("close");
				}
			}
		});
	});
});
</script>
