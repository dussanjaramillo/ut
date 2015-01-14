<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php if (isset($error)) : ?>
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $error['text']; ?>
</div>
<?php endif; ?>
<div class="controls controls-row">
  <div class="span2"><strong>Año de la orden <span class="required">*</span></strong></div>
  <div class="span3">
    <select name="anio" id="anio" class="validate[required]">
			<option value="">Seleccione el año de la orden...</option>
      <?php for($x=date("Y"); $x>=1980; --$x) : ?>
      <option value="<?php echo $x ?>"><?php echo $x ?></option>
      <?php endfor; ?>
    </select>
  </div>
  <div class="span2"><strong>Número de la orden de viaje:</strong> <span class="required">*</span></div>
  <div class="span3">
  	<input type="text" name="numero_orden_viaje" id="numero_orden_viaje" class="validate[required]" />
  </div>
</div>