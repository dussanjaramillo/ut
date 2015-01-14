<div class="text-center">
	<h2>Detalle de la regional</h2>
</div>
<div class="center-form-large table-bordered">
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CÓDIGO DE LA REGIONAL</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['COD_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>NOMBRE DE LA REGIONAL</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['NOMBRE_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>TELÉFONO</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['TELEFONO_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DIRECCIÓN</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['DIRECCION_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CELULAR</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['CELULAR_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>EMAIL DE LA REGIONAL</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['EMAIL_REGIONAL']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CÓDIGO SIIF DE LA REGIONAL</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['COD_SIIF']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DEPARTAMENTO</strong></div>
    <div class="span4"><?php
		foreach ($departamentos as $depto) :
			if(isset($regional) and $regional['COD_DEPARTAMENTO']==$depto['COD_DEPARTAMENTO']) :
				echo $depto['NOM_DEPARTAMENTO'];
			endif;
		endforeach;
		?>
    </div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CIUDAD</strong></div>
    <div class="span4"><?php
		if(isset($ciudades)) :
			foreach ($ciudades as $ciudad) :
				if($regional['COD_CIUDAD']==$ciudad['CODMUNICIPIO']) :
					echo $ciudad['NOMBREMUNICIPIO'];
				endif;
			endforeach;
		endif;
		?>
    </div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DIRECTOR</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['CEDULA_DIRECTOR']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>COORDINADOR</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['CEDULA_COORDINADOR']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>COORDINADOR DE RELACIONES CORPORATIVAS</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['CEDULA_COORDINADOR_RELACIONES']:"" ?></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>SECRETARIO</strong></div>
    <div class="span4"><?php echo (isset($regional))?$regional['CEDULA_SECRETARIO']:"" ?></div>
  </div>
	<?php
	  echo anchor('regionales/index', '&nbsp;<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-default btn-lg"');
	?>
</div>
	