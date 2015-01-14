<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<script>
    function FnVolver(){
        history.back(2);
    } 
    
    $( document ).ready(function() {
        setTimeout("FnVolver()", 3000);
    });
</script>   

<div id="ajax_load" class="ajax_load" style="display: none">
<div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div class="center-form-xlarge">
	<?php echo $custom_error; ?>
  <div class="text-center">
    <h2>Actualizacion auto</h2>
  </div>
  <div class="alert alert-success">
  <button class="close" data-dismiss="alert" type="button">×</button>
  La informacion se actualizo con exito.
  </div>
</div>
   