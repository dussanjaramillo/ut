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
    <input type="text" name="concepto_pago" id="concepto_pago" class="validate[required]" />
  </div>
</div>