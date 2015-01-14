<div class="preload"></div>
<img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="text-center">
	<h2><?php echo $title ?></h2>
  <h4 class="titulo"><?php echo $stitle ?></h4>
</div>
<?php
if (isset($message)) {
  echo $message;
}

$attributes = array('id' => 'myform');
echo form_open("regionales/guardar", $attributes);
if(isset($regional) and !empty($regional)) :
	$data = array(
			'name' => 'editar',
			'value' => 'true',
			'type' => 'hidden',
	);

	echo form_input($data);
endif;
?>
<div class="center-form-large table-bordered">
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CÓDIGO DE LA REGIONAL</strong></div>
    <div class="span4"><input type="text" id="regional" name="codigo"<?php echo (isset($regional))?"readonly":'class="validate[required, custom[onlyNumber], minSize[1], maxSize[15]]"'; ?> value="<?php
    	echo (isset($regional))?$regional['COD_REGIONAL']:"" ?>" /> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>NOMBRE DE LA REGIONAL</strong></div>
    <div class="span4"><input type="text" name="nombre" value="<?php
    	echo (isset($regional))?$regional['NOMBRE_REGIONAL']:"" ?>" class="validate[required, custom[onlyLetterSp]]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>TELÉFONO</strong></div>
    <div class="span4"><input type="text" name="telefono" value="<?php
    	echo (isset($regional))?$regional['TELEFONO_REGIONAL']:"" ?>" class="validate[required, custom[phone]]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DIRECCIÓN</strong></div>
    <div class="span4"><input type="text" name="direccion" value="<?php
    	echo (isset($regional))?$regional['DIRECCION_REGIONAL']:"" ?>" class="validate[required, custom[Direccion]]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CELULAR</strong></div>
    <div class="span4"><input type="text" name="celular" value="<?php
    	echo (isset($regional))?$regional['CELULAR_REGIONAL']:"" ?>" class="validate[custom[phone]]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>EMAIL DE LA REGIONAL</strong></div>
    <div class="span4"><input type="text" name="email" value="<?php
    	echo (isset($regional))?$regional['EMAIL_REGIONAL']:"" ?>" class="validate[required, custom[email]]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CÓDIGO SIIF DE LA REGIONAL</strong></div>
    <div class="span4"><input type="text" name="cod_siif" value="<?php
    	echo (isset($regional))?$regional['COD_SIIF']:"" ?>" class="validate[required]" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DEPARTAMENTO</strong></div>
    <div class="span4">
    	<select id="departamento" name="departamento">
      	<option value="">Seleccione un departamento...</option>
        <?php
					foreach ($departamentos as $depto) :
						?>
				<option value="<?php echo $depto['COD_DEPARTAMENTO'] ?>"<?php
        	echo (isset($regional) and $regional['COD_DEPARTAMENTO']==$depto['COD_DEPARTAMENTO'])?'selected="selected"':'' ?>><?php echo $depto['NOM_DEPARTAMENTO'] ?></option>
            <?php
					endforeach;
				?>
      </select>
    </div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>CIUDAD</strong></div>
    <div class="span4">
    	<select id="ciudad" name="ciudad">
      	<option value="">Seleccione una ciudad...</option>
        <?php
				if(isset($ciudades)) :
					foreach ($ciudades as $ciudad) :
						?>
				<option value="<?php echo $ciudad['CODMUNICIPIO'] ?>"<?php echo ($regional['COD_CIUDAD']==$ciudad['CODMUNICIPIO'])?'selected="selected"':'' ?>><?php echo $ciudad['NOMBREMUNICIPIO'] ?></option>
            <?php
					endforeach;
				endif;
				?>
      </select>
    </div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>DIRECTOR</strong></div>
    <div class="span4"><input type="text" name="director" value="<?php
    	echo (isset($regional))?$regional['CEDULA_DIRECTOR']:"" ?>" class="validate[required, custom[onlyNumber], minSize[4], maxSize[20]]" id="director" /> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>COORDINADOR COBRO COACTIVO</strong></div>
    <div class="span4"><input type="text" name="coordinador" value="<?php
    	echo (isset($regional))?$regional['CEDULA_COORDINADOR']:"" ?>" class="validate[required, custom[onlyNumber], minSize[4], maxSize[20]]" id="coordinador" /> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>COORDINADOR DE RELACIONES CORPORATIVAS</strong></div>
    <div class="span4"><input type="text" name="coordinadorr" value="<?php
    	echo (isset($regional))?$regional['CEDULA_COORDINADOR_RELACIONES']:"" ?>" class="validate[required, custom[onlyNumber], minSize[4], maxSize[20]]" id="coordinadorr" /> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
    <div class="span4"><strong>SECRETARIO COBRO COACTIVO</strong></div>
    <div class="span4"><input type="text" name="secretario" value="<?php
    	echo (isset($regional))?$regional['CEDULA_SECRETARIO']:"" ?>" class="validate[required, custom[onlyNumber], minSize[4], maxSize[20]]" id="secretario" /> <img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></div>
  </div>
  <div class="control-group" style="overflow: hidden">
  	<div class="span4">
			<?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'guardar regional',
          'type' => 'submit',
          'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar regional',
          'class' => 'btn btn-success addpagobtn'
      );
  
      echo form_button($data);
      ?>
		</div>
    <div class="span4" style="overflow:hidden">
		<?php
      echo anchor('regionales/index', '&nbsp;<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning btn-lg"');
    ?>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".preload, .load, #preloadmini").hide();
		
		$("#director, #coordinador, #coordinadorr, #secretario").autocomplete({
      source: "<?php echo base_url("index.php/regionales/usuarios") ?>",
      minLength: 4,
      search: function( event, ui ) {
        $(this).siblings("#preloadmini").show();
      },
      response: function( event, ui ) {
        $(this).siblings("#preloadmini").hide();
      },
			select: function( event, ui ) {
				if(ui.item) {
					$(this).val(ui.item.idusuario);
				}
        console.log( ui.item ?
          "Selected: " + ui.item.idusuario + " :: " + ui.item.nombre :
          "Nothing selected, input was " + this.value );
      }
    });
		$("#departamento").change(function() {
			if ($(this).val() != "") {
				$('#myform').validationEngine('hideAll');
        jQuery.ajax({
          url: '<?php echo base_url("index.php/regionales/ciudades") ?>',
          type: 'post',
          data: {
            depto: $("#departamento option:selected").val()
          },
					beforeSend: function(){
						$(".preload, .load").show();
					}
        }).done(
					function(data) {
						if (data == null || data == "") {
							alert("El DEPARTAMENTO no tiene ciudades asociadas");
							$("#submit-button").attr("disabled", true);
						}
						else {
							var options = '<option value="">Por favor seleccione la ciudad...</option>';
							$.each(data, function(i, item) {
								options += '<option value="' + item.CODMUNICIPIO + '">' + item.NOMBREMUNICIPIO + '</option>' + "\n";
							});
							$("#ciudad").addClass("validate[required]").html(options).show();
							$("#submit-button").attr("disabled", false);
						}
					}
        ).always(function() {
					$(".preload, .load").hide();
				});
      }
		});
		
		$("#regional").on({
			"keyup": function() {
				verificar($(this).val());
			},
			'onkeypress': function() {
				verificar(id);
			},
			'onblur': function() {
				verificar(id);
			},
			'onkeyup': function() {
				verificar(id);
			},
			'change': function() {
				verificar(id);
			}
		});
	});
	
	var httpR;
	
	function verificar(id) {
		if (id != "") {
				$('#myform').validationEngine('hideAll');
        jQuery.ajax({
          url: '<?php echo base_url("index.php/regionales/regional") ?>',
          type: 'post',
          data: {
            id: id
          },
					beforeSend: function(data2){
						if(httpR) { httpR.abort(); }
						httpR = data2;
						$("#regional").siblings("#preloadmini").show();
					}
        }).done(
					function(data) {
						if (data == true) {
							$("#submit-button").attr("disabled", false);
						}
						else if(data == false) {
							alert("El código ingresado ya existe, por favor ingrese uno diferente");
							$("#submit-button").attr("disabled", true);
						}
					}
        ).always(function() {
					$("#regional").siblings("#preloadmini").hide();
				});
      }
	}
	
	function ajaxValidationCallback() {}
</script>