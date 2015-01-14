<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php if (isset($error)) : ?>
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $error['text']; ?>
</div>
<?php endif; ?>
<div class="controls controls-row">
  <div class="span2"><strong>Período de aporte <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="fecha_periodo" readonly id="fecha_periodo" class="validate[required]" />
  </div>
  <div class="span2"><strong>Número de licencía del contrato de la obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="nro_contrato" id="nro_contrato" class="validate[required]" />
  </div>
  
  <div class="span2"><strong>Tipo FIC Presuntivo: <span class="required">*</span></strong></div>
  <div class="span3">
    <select name="tipo_fic" id="tipo_fic" class="validate[required]">
    	<option value="">Seleccione el tipo...</option>
      <option value="CONTRATO">POR CONTRATO</option>
      <option value="ANUAL">ANUAL</option>
    </select>
  </div>
  
  <div class="span2"><strong>Número de trabajadores: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="nro_trabajadores" id="nro_trabajadores" class="validate[required, custom[integer], min[1]]" />
  </div>
  <div class="span2"><strong>Número de trabajadores a adicionar:</strong></div>
  <div class="span3">
    <input type="text" name="nro_trabajadores_adicion" id="nro_trabajadores_adicion" class="validate[custom[integer], min[1]]" />
  </div>
  
  <div class="span2"><strong>Nombre de la obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="nombre_obra" id="nombre_obra" class="validate[required]" />
  </div>
  <div class="span2"><strong>Ciudad de la obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="ciudad_obra" id="ciudad_obra" class="validate[required]" />
  </div>
  
  <div class="span2"><strong>Fecha inicio de la obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="fecha_inicio" readonly id="fecha_inicio" class="validate[required]" />
  </div>
  <div class="span2"><strong>Fecha terminación de la obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="fecha_fin" readonly id="fecha_fin" class="validate[required]" />
  </div>
  
  <div class="span2"><strong>Valor total Obra Todo Costo: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="valor_todo_costo" id="valor_todo_costo" class="validate[required, custom[integer], min[1]]" />
  </div>
  <div class="span2"><strong>Valor total Mano de Obra: <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="valor_mano_obra" id="valor_mano_obra" class="validate[required, custom[integer], min[1]]" />
  </div>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $("#fecha_inicio").datepicker({
      defaultDate: "-1w",
      dateFormat: "yy-mm-dd",
      changeMonth: true,
      maxDate: "0",
      minDate: "-5y",
      changeYear: true,
      onClose: function(selectedDate) {
        $("#fecha_fin").datepicker("option", "minDate", selectedDate);
				$("#fecha_periodo").datepicker("option", "minDate", selectedDate);
      }
    });
    $("#fecha_fin").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
      onClose: function(selectedDate) {
        $("#fecha_inicio").datepicker("option", "maxDate", selectedDate);
				$("#fecha_periodo").datepicker("option", "maxDate", selectedDate);
      }
    });
		$("#fecha_periodo").datepicker({
      dateFormat: "yy-mm-dd",
			defaultDate: "-5y",
      changeMonth: true,
			changeYear: true,
    });
	});
</script>