
<div class="center-form-large">


 
<center><h2>Agenda</h2></center> 
Fiscalizador &nbsp:  
 <?php     

        echo form_open(current_url()); ?>
<select name="fiscalizador_id" id="fiscalizador_id">
 <?php  if($idcargo=='3'){
 foreach ($fiscalizadores as $lista ) { 
 	?>
<option value="<?= $lista->IDUSUARIO ?>"><?= $lista->NOMBREUSUARIO ?></option>
<?php } 
}
else
{
?>	

<option value="<?=$this->ion_auth->user()->row()->IDUSUARIO ?>"><?= $this->ion_auth->user()->row()->NOMBREUSUARIO ?></option>
 <?php 	
}?>
</select>
&nbsp&nbspFecha Inicio&nbsp: &nbsp<input type="text" id="fecha_inicio" name="fecha_inicio" maxlength="50" readonly="readonly"  style= 'width : 15%;' title="Fecha Inicio:" class="validate[required]">
&nbsp&nbspFecha Fin &nbsp: &nbsp<input type="text" id="fecha_final" name="fecha_final" maxlength="50" readonly="readonly" style= 'width : 15%;' title="Fecha Fin:" class="validate[required]">
<center><div id="alerta"></div></center>	
<center>
	<input type='button' id='consultar_agenda' name='consultar_agenda' class='btn btn-success' value='Consultar' >
 <?php  echo anchor('', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
</center>
    <?php echo form_close(); ?>	
<div id='agenda'> </div>
</div>


<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script >
$( document ).ready(function() {

//var ciudad = $("#ciudades").val();
//$('#ciudad').val(ciudad);


$(".preload, .load").hide();
$("#tablaq").hide();
	
	$( "#fecha_inicio").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});


       
$( "#fecha_final").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});
	

$('#consultar_agenda').click(function(){
	    if ($("#fecha_inicio").val().length < 1) 
       {
      $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Fecha Inicio Obligatorio<p>');
      $("#fecha_inicio").focus();
       return false;
  }
  
  	    if ($("#fecha_final").val().length < 1) 
       {
      $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Fecha Fin Obligatorio<p>');
      $("#fecha_final").focus();
       return false;
  }
  $("#alerta").hide();
	
	$(".preload, .load").show();
	var fecha_inicio=$("#fecha_inicio").val();
	var fecha_final=$("#fecha_final").val();
	var fiscalizador_id=$("#fiscalizador_id").val();
	var url3="<?= base_url('index.php/fiscalizacion/vista_agenda') ?>/";
	$('#agenda').load(url3,{fecha_inicio : fecha_inicio , fecha_final: fecha_final, fiscalizador_id: fiscalizador_id});


});




});


 function ajaxValidationCallback(status, form, json, options) {
	
}



</script>

<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>