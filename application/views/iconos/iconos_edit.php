<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
<?php echo form_open(current_url()); ?>
<?php echo $custom_error; ?> <?php echo form_hidden('id',$result->IDICONO) ?>
<h2>Editar Ícono</h2>
<p>
  <?php
	echo form_label('Nombre <span class="required">*</span>', 'nombre');
	$data = array(
						'name'			=> 'nombre',
						'id'				=> 'nombre',
						'value'			=> $result->NOMBREICONO,
						'maxlength'	=> '128'
					);
	echo form_input($data);
	echo form_error('nombre','<div>','</div>');
	?>
</p>
<p>
  <?php
	echo form_label('Traducción <span class="required">*</span>', 'traduccion');
	$data = array(
						'name'			=> 'traduccion',
						'id'				=> 'traduccion',
						'value'			=> $result->TRADUCCION,
						'maxlength'	=> '128',
						'class'			=> 'span3'
					);
	echo form_input($data);
	echo form_error('url','<div>','</div>');
?>
</p>
*Nombres de íconos aceptados en <?php echo anchor('http://fontawesome.io/icons/','<br>fontawesome.io V4.1','target="_blank"'); ?>
<p>
  <?php echo anchor('iconos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
<div id="dialog-confirm" title="¿Eliminar la aplicación?" style="display:none;">
  <p>
  	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    ¿Confirma que desea eliminar el ícono "<?php echo mb_strtoupper($result->NOMBREICONO); ?>"?
  </p>
</div>
<script type="text/javascript">
//style selects
var config = {'.chosen' : {}}

$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$("#borrar").click(function(evento) { 
		evento.preventDefault();
		var link = $(this).attr('href');
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		$( "#dialog-confirm" ).dialog({
			resizable: false,
			height:180,
			modal: true,
			buttons: {
				"Confirmar": function() {
					location.href='<?php echo base_url()."index.php/iconos/delete/".$result->IDICONO; ?>';
				},
				Cancelar: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
});
</script>