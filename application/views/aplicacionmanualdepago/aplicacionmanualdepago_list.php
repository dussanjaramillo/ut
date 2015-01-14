<?php 
if (isset($message)){
	echo $message;
}
?>
<div class="center-form">
	<div class="text-center"><h2><?php echo $title ?></h2><h4 class="titulo"><?php echo $stitle ?></h4></div>
	<?php $attributes = array('class' => 'form-inline myform'); echo form_open("aplicacionmanualdepago/seleccionar_form", $attributes);?>
		<?php
			$data = array(
				'name'        => 'opcion',
				'id'          => 'cargar_extractos',
				'value'       => 'cargar_extractos',
				'checked'     => FALSE,
				'class'       => 'validate[required]'
			);
			echo form_radio($data);
		?> <label for="extractos">Cargar Extractos</label><br>
		<?php
			$data = array(
				'name'        => 'opcion',
				'id'          => 'aplicar_pagos_manual',
				'value'       => 'AplicarPagos_manual',
				'checked'     => FALSE,
				'class'       => 'validate[required]'
			);
			echo form_radio($data);
		?> <label for="aplicar">Aplicar Pagos Manualmente</label><br><br>
		<?php 
			$data = array(
				   'name' => 'button',
				   'id' => 'submit-button',
				   'value' => 'seleccionar',
				   'type' => 'submit',
				   'content' => '<i class="fa fa-arrow-circle-right"></i> Aceptar',
				   'class' => 'btn btn-default'
				   );

			echo form_button($data);    
		?>
	<?php echo form_close();?>
</div>