
<h1>Comunicación Presentarse Ante el SENA</h1>

<CENTER>
	 <strong>
NIT&nbsp: &nbsp<?=$empresa['CODEMPRESA']?>	 &nbsp &nbsp &nbsp RAZÓN SOCIAL&nbsp:  &nbsp<?=$empresa['NOMBRE_EMPRESA']?>  	<br>
 	 </strong>
</CENTER>
<p></p><p></p><p></p>



<center>

 <div>	
<input type="hidden" id="cod_fisc" name="cod_fisc" value=<?=$cod_fisc?>> 	
<input type="hidden" id="nit" name="nit" value=<?=$nit?>> 


<form>

FECHA&nbsp: &nbsp<input type="text" id="fecha_comunicacion" name="fecha_comunicacion" maxlength="50" readonly="readonly" style="text-align:right " title="Fecha:"> 
&nbspHORA INICIO&nbsp: &nbsp<select name="hora_inicio" id="hora_inicio" title="Hora Inicio:" ></select>
&nbspHORA FIN&nbsp: &nbsp<select name="hora_fin" id="hora_fin" title="Hora Fin:" ></select>
<div id="alerta"></div>	
</form>


</center>

 <center>
 <input type='button' name='crear' class='btn btn-success' value='Crear Comunicación'  id='crear' >
 </center>
 <div id='ver_com_ofi'> </div>
 
 
 <form id="retorno_fisc" action="<?= base_url('index.php/fiscalizacion/datatable') ?>" method="post" >
    <input type="hidden" id="cod_asignacion_fisc" name="cod_asignacion_fisc" value=<?=$cod_asignacion_fisc?>> 
    <input type="hidden" id="cod_nit" name="cod_nit" value=<?=$nit?>>
</form> 
 
<div id="comunicacion_pr_sena"></div>	 
 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 
 
 
<script >
$( document ).ready(function() {
$(".preload, .load").hide();

$(function(){


  obj = document.forms[0].hora_inicio;
  for (i=7; i<18; i++) {
    h = (i<10) ? '0'+i : i; 
    opt = document.createElement('option');
    opt.innerHTML = h + ':00';
    obj.appendChild(opt)
    opt = document.createElement('option');
    opt.innerHTML = h + ':30';
    obj.appendChild(opt)
  }
  obj2 = document.forms[0].hora_fin;
  
  for (j=7; j<18; j++) {
    h = (j<10) ? '0'+j : j; 
    opt2 = document.createElement('option');
    opt2.innerHTML = h + ':30';
    obj2.appendChild(opt2)
    j++;
  	h = (j<10) ? '0'+j : j; 
    opt2 = document.createElement('option');
    opt2.innerHTML = h + ':00';
    obj2.appendChild(opt2)
    j--;
  }

	
$( "#fecha_comunicacion").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	numberOfMonths: 1,
	minDate: "0",
	dateFormat: 'dd/mm/yy',
	timeFormat: 'hh:mm:00',
	
	
		
});

	
});


    $('#crear').click(function(){
        if ($("#fecha_comunicacion").val().length < 1) 
       {
      $('#alerta').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Fecha Obligatorio<p>');
      $("#fecha_comunicacion").focus();
       return false;
  }
    $("#alerta").hide();

    	$(".preload, .load").show();
    var	nit = $("#nit").val();
    var	cod_fisc = $("#cod_fisc").val();
    var	fecha_visita = $("#fecha_comunicacion").val();
    var	hora_inicio = $("#hora_inicio").val();
    var	hora_fin = $("#hora_fin").val();
    var url = "<?php echo base_url('index.php/fiscalizacion/comunicacion_presentarse_sena') ?>";
        $('#comunicacion_pr_sena').load(url, {cod_fisc: cod_fisc, nit: nit, fecha_visita: fecha_visita, hora_inicio: hora_inicio, hora_fin: hora_fin });

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