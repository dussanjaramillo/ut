<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div id="ajax_load" class="ajax_load" style="display: none">
<div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>

<h2>Actualizacion Acto administrativo</h2>
<div class="alert alert-success">
<button class="close" data-dismiss="alert" type="button">×</button>
El acto administrativo de la nulidad se actualizo con exito.
</div>

<script>
$(function(){
    setTimeout('redireccionar()', 3000);
});

function redireccionar(){
    window.location = '<?php echo base_url()?>index.php/nulidadanaliza/listNulidadesNotiCorFisicoActoAdmin';
}
</script>
   