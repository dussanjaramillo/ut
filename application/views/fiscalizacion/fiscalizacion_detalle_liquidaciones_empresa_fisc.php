<div class="center-form-large">
<h1>Detalle Liquidaciones Empresa</h1>

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

<input type="hidden" id="cod_nit" name="cod_nit" value=<?=$empresa['CODEMPRESA']?>>
 

<CENTER>
	
<br>
Periodo Inicio&nbsp&nbsp &nbsp<input type="text" id="fecha_inicio" name="fecha_inicio" maxlength="50" readonly="readonly"  style="width : 120px; heigth : 1px" title="Fecha Inicio:" class="validate[required]">
&nbspConcepto &nbsp&nbsp  

<select name="concepto_id" id="concepto_id" style="width : 200px; heigth : 1px">
 <option value="99">Todos</option>
 <?php  
 foreach ($conceptos as $lista ) { 
 	?>
<option value="<?= $lista->COD_CPTO_FISCALIZACION ?>"><?= $lista->NOMBRE_CONCEPTO ?></option>
<?php } 
?>	


</select>
<br>

&nbsp&nbspPeriodo Fin &nbsp&nbsp &nbsp<input type="text" id="fecha_final" name="fecha_final" maxlength="50" readonly="readonly" style="width : 120px; heigth : 1px" title="Fecha Fin:" class="validate[required]">

&nbsp&nbspTipo &nbsp&nbsp &nbsp&nbsp&nbsp &nbsp&nbsp

<select name="tipo_id" id="tipo_id" style="width : 200px; heigth : 1px">

<option value="99">Todos</option>
<option value="0">Liquidación</option>
<option value="1">Resolución</option>

</select>	
	<br>
	
 <?php     

        echo form_open(current_url()); ?>	
	<input type='button' name='consultar_liquidacion' class='btn btn-success' value='Consultar'  id='consultar_liquidacion'>
	 <?php  echo anchor('conempresasprocobro', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
		</CENTER>
<br>
    <?php echo form_close(); ?>
<p></p>
</div>



 <div id='detalle_liquidacion'> </div>

 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
 
<script >
$(".preload, .load").hide();

$('#cancelar').click(function(){
        	window.history.back()
       });

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


$('#consultar_liquidacion').click(function(){
	
	var	cod_nit = $("#cod_nit").val();
	var	concepto_id = $("#concepto_id").val();
	var	tipo_id = $("#tipo_id").val();
	var	fecha_inicio = $("#fecha_inicio").val();
	var	fecha_final = $("#fecha_final").val();
		var urlliquidacion="<?= base_url('index.php/fiscalizacion/dataliquidacion') ?>/";
	$('#detalle_liquidacion').load(urlliquidacion,{concepto_id : concepto_id, tipo_id: tipo_id, fecha_inicio: fecha_inicio, fecha_final:fecha_final, cod_nit: cod_nit });
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