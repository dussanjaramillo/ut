<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<?php if (isset($error)) : ?>
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <?php echo $error['text']; ?>
</div>
<?php endif; ?>
<div class="controls controls-row">
  <div class="span2"><strong>Concepto del pago <span class="required">*</span></strong></div>
  <div class="span3">
    <select name="tipo_carne" id="tipo_carne" class="validate[required]">
    	<option value="">Seleccione el tipo de carné...</option>
      <option value="APRENDIZ">Aprendiz</option>
      <option value="BENEFICIARIO_SERVICIO_MEDICO">Beneficiario del servicio medico</option>
      <option value="CONTRATISTA">Contratista</option>
      <option value="PENSIONADO">Pensionado</option>
    </select>
  </div>
</div>