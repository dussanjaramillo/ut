<div class="center-form-large">
<h1>Detalle Empresa Fiscalizar</h1>

<CENTER>

<table>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td><b>NIT:</td>
    <td><?=$empresa['CODEMPRESA']?></td>
    <td>&nbsp;</td>	
    <td><b>RAZÓN SOCIAL:</td>
    <td><?=$empresa['NOMBRE_EMPRESA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
     <tr>
    <td><b>TELÉFONO FIJO:</td>
    <td><?=$empresa['TELEFONO_FIJO']?></td>
    <td>&nbsp;</td>	
    <td><b>REPRESENTANTE LEGAL:&nbsp;&nbsp;</td>
    <td><?=$empresa['REPRESENTANTE_LEGAL']?></td>
    <td>&nbsp;</td>
  </tr> 
   <tr><td>&nbsp;</td>	</tr> 
  <tr>
    <td><b>TELÉFONO CELULAR:</td>
    <td><?=$empresa['TELEFONO_CELULAR']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>DIRECCIÓN:</td>
    <td><?=$empresa['DIRECCION']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
      
       <tr>
    <td><b>E-MAIL:</td>
    <td><?=$empresa['CORREOELECTRONICO']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>ACTIVIDAD ECONÓMICA:</td>
    <td><?=$empresa['ACTIVIDADECONOMICA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <tr>
    <td><b>NÚMERO EMPLEADOS:</td>
    <td><?=$empresa['NUM_EMPLEADOS']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr>    
</table>	

</CENTER>



 <form id="detalle_liquidacion" action="<?= base_url('index.php/fiscalizacion/detalle_liquidaciones') ?>" method="post" >

    <input type="hidden" id="cod_nit" name="cod_nit" value=<?=$empresa['CODEMPRESA']?>>
</form> 
 


<CENTER>
	
Concepto &nbsp:  

<select name="concepto_id" id="concepto_id">
	 <option value="99">Todos</option>
 <?php  
 foreach ($conceptos as $lista ) { 
 	?>
<option value="<?= $lista->COD_CPTO_FISCALIZACION ?>"><?= $lista->NOMBRE_CONCEPTO ?></option>
<?php } 
?>	


</select>	
&nbsp&nbspFecha Inicio&nbsp: &nbsp<input type="text" id="fecha_inicio" name="fecha_inicio" maxlength="50" readonly="readonly"  style="width : 110px; heigth : 1px" title="Fecha Inicio:" class="validate[required]">
&nbsp&nbspFecha Fin &nbsp: &nbsp<input type="text" id="fecha_final" name="fecha_final" maxlength="50" readonly="readonly" style="width : 110px; heigth : 1px" title="Fecha Fin:" class="validate[required]">
	<br>
<div id="alerta1"></div>
<div id="alerta2"></div>	
	<br>
	<input type='button' name='consultar_detalle_empresa' class='btn btn-success' value='Consultar'  id='consultar_detalle_empresa'>

</CENTER>
<br>

<p></p>
</div>


 <div id='detalle_pagos'> </div>


 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 
<script >
$(".preload, .load").hide();

$( "#fecha_inicio").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});

$( "#fecha_final").datepicker({
	
	showOn: 'button',
	buttonText: 'Fecha',
	buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	numberOfMonths: 1,
	dateFormat: 'dd/mm/yy'
		
});




$('#consultar_detalle_empresa').click(function(){
	
	
	    	    	    if ($("#fecha_inicio").val().length < 1) 
       {
      $('#alerta1').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Fecha Inicio Obligatorio<p>');
      $("#fecha_inicio").focus();
            return false;
  }
        	
    	    if ($("#fecha_final").val().length < 1) 
       {
      $('#alerta2').html('<p class="text-warning"><i class="fa fa-warning"></i> Campo Fecha Fin Obligatorio<p>');
      $("#fecha_final").focus();
       return false;
       }
  
  $("#alerta1").hide();
    $("#alerta2").hide();
	
	var	cod_nit = $("#cod_nit").val();
	var	concepto_id = $("#concepto_id").val();
	var	fecha_inicio = $("#fecha_inicio").val();
	var	fecha_final = $("#fecha_final").val();
		var urlpagos="<?= base_url('index.php/fiscalizacion/datapagosempresa') ?>/";
	$('#detalle_pagos').load(urlpagos,{concepto_id : concepto_id, fecha_inicio: fecha_inicio, fecha_final:fecha_final, cod_nit: cod_nit });
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

<a name="abajo2"></a>