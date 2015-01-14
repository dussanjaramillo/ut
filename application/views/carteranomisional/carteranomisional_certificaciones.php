<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form" width='500' height= '700'>



<center>
<h2>Certificaciones Cartera No Misional</h2>
</center>  

<table>
	<tr>
<td><center>CERTIFICACIÓN:</center></td>
<td><select name="tipo_certificacion_id" id="tipo_certificacion_id">
<option value="1">CERTIFICADO DE ESTADO DE CUENTA A LA FECHA</option>
<option value="2">CERTIFICADO DE ESTADO DE CUENTA ANUAL</option>
</select></td>
</tr>
	<tr>
<td>TIPO CARTERA:</td>
<td><select name="cartera_id" id="cartera_id">
 <?php  
 foreach ($tipos as $lista ) { 
 	?>
<option value="<?= $lista->COD_TIPOCARTERA ?>"><?= $lista->NOMBRE_CARTERA ?></option>
<?php } ?>

</select></td>
</tr>
    
<tr>     
<td>IDENTIFICACIÓN:</td>
<td><input type="text" id="identificacion" style="width : 95%;">  
</td>
</tr>
<tr>     
<td>FECHA CERTIFICACIÓN:</td>
<td><input type="text" id="fecha" style="width : 85%;">  
</td>
</tr>
<tr>     
<td>ACLARACIONES:</td>
<td><textarea rows="5" cols="100"></textarea> 
</td>
</tr>
</table>
      
<p>
 <center>	 
 <input type='button' name='aceptar' class='btn btn-success' value='Generar'  id='aceptar' >
 </center>
   </p> 

    
</div>
<br>
<div id='certificacion'> </div>

 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 
<script type="text/javascript" language="javascript" charset="utf-8">

$( "#fecha").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha Certificacion',
	buttonImage: 'http://jqueryui.com/resources/demos/datepicker/images/calendar.gif',
	buttonImageOnly: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});


$('#cartera_id').change(function(){
	$(".preload, .load").show();
	$("#tipo_certificacion_id").empty();
	$.getJSON("<?= base_url('index.php/carteranomisional/get_tipo_certificacion') ?>/"+$("#cartera_id").val(),function(data){
        console.log(JSON.stringify(data));
        $.each(data, function(k,v){
            $("#tipo_certificacion_id").append("<option value=\""+k+"\">"+v+"</option>");
        });
        $(".preload, .load").hide();
    });


});

$('#aceptar').click(function(){
	
		var certificacion= $("#tipo_certificacion_id").val();
		var cartera= $("#cartera_id").val();

	var urlcartera="<?= base_url('index.php/carteranomisional/tipo_certificacion') ?>/";
	$('#certificacion').load(urlcartera,{cartera : cartera, certificacion: certificacion });
	
});

 $(".preload, .load").hide();        
            
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