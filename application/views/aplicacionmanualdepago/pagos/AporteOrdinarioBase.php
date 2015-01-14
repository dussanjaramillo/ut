<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="controls controls-row">
  <div class="span2"><strong>Período de aporte <span class="required">*</span></strong></div>
  <div class="span3">
    <input type="text" name="fecha_periodo" readonly id="fecha_periodo" class="validate[required]" />
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