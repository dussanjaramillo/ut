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
  <div class="span2"><strong>Operador: <span class="required">*</span></strong></div>
  <div class="span3">
    <select name="operador" id="operador" class="validate[required]">
      <option value="" selected="selected">Seleccionar el operador...</option>
      <?php if(!empty($operador)) : ?>
        <?php foreach($operador as $opera) : ?>
      <option value="<?php echo $opera['COD_TIPO_OPERADOR'] ?>"><?php echo $opera['NOMBRE_OPERADOR'] ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $("#fecha_periodo").datepicker({
      dateFormat: "yy-mm",
			minDate: "-5y",
      changeMonth: true,
			changeYear: true
    });
	});
</script>