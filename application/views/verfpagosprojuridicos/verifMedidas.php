<!--Vista donde se registra si el proceso tiene medidas cautelares-->
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?> 
<div class="center-form-large">
	<?php echo $custom_error; ?>  
    <h4 style="text-align: center;">Verificación Medidas Cautelares</h4> 
<div class="controls controls-row">

          <div class="span4">
            <?php
						echo form_label('Identificación', 'NIT_EMPRESA');
						$data = array('name'        => 'NIT_EMPRESA',
													'id'          => 'NIT_EMPRESA',
													'value'       => $auto->NIT_EMPRESA,
													'class'      => 'span3',
													'readonly'   => 'readonly'
												);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Ejecutado', 'RAZON_SOCIAL');
						$data = array('name'        => 'RAZON_SOCIAL',
													'id'          => 'RAZON_SOCIAL',
													'value'       => $auto->RAZON_SOCIAL,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span4">
            <?php
						echo form_label('Concepto', 'concepto');
						$data = array('name'        => 'concepto',
													'id'          => 'concepto',
													'value'       => $auto->NOMBRE_CONCEPTO,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Instancia', 'instancia');
						$data = array('name'        => 'instancia',
													'id'          => 'instancia',
													'value'       => 'Cobro Coactivo',
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
					?>
          </div>
        </div>
        <div class="controls controls-row">
          <div class="span4">
            <?php
						echo form_label('Representante Legal', 'representante_legal');
						$data = array('name'        => 'representante_legal',
													'id'          => 'representante_legal',
													'value'       => $auto->REPRESENTANTE_LEGAL,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
          <div class="span4">
            <?php
						echo form_label('Teléfono', 'telefono');
						$data = array('name'        => 'telefono',
													'id'          => 'telefono',
													'value'       => $auto->TELEFONO_FIJO,
													'class'       => 'span3',
													'readonly'    => 'readonly'
										);
						echo form_input($data);
						?>
          </div>
        </div>

<div style="text-align: center;"><br><br>

    <form name="form1" id="form1" method="post" action="<?= base_url("index.php/verfpagosprojuridicos/medidasCautelares") ?>" onsubmit="return validar()">
    <input type="hidden" name="cod_proceso" id="cod_proceso" value="<?php echo $cod_proceso; ?>"> 
        <span style="text-align: center;">Existen Medidas Cautelares ?.</span>
    <br>
    <br>
     <?php
                    $data = array('name' => 'medidas', 'id' =>'siexisten', 'value' => '1', 'checked' => FALSE, 'style' => 'margin-left:0px',);
                    echo form_radio($data);
                    ?>
                   Si             

                    <?php
                    $data = array('name' => 'medidas', 'id' => 'noexisten', 'value' => '2', 'checked' => FALSE, 'style' => 'margin-left:50px');
                    echo form_radio($data);
                    ?>
                    No 
                    <br><br><br>
                    <input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-success">
      </form>
</div>
</div>
<script>
function validar(){
  
    alert('hola');
}
</script>
