<?php
/**
* Formulario para el calculo de intereses por Resolución de Multas del Ministerio de Trabajo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/interesmultasmin_form.php
* @last-modified  08/06/2014
* @copyright	
*/ 
if(!defined('BASEPATH') ) exit('No direct script access allowed');
if(isset($message)){
	echo $message;
}
//print_r($empresa);
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: left; vertical-align: middle;}
#informe{display:none; width:90%; margin-left:auto; margin-right:auto;}
#loader{display:none; text-align:center;}
#vistaPrevia_base, #cabecera_base{display:none;}
</style>
<!-- Fin Estilos personalizados -->
<!-- Cargue de cabeceras -->
<div class="center-form-xlarge" id="cabecera">
	<?php
		$attributos_formulario = array('class' => 'form-inline', 'id' => 'multas');
		echo form_open('', $attributos_formulario);
	?>
		<h2 class="text-center">Intereses Resolución Multas Ministerio del Trabajo</h2>
		<br><br>
		<table>
			<tr>
				<td><strong>Regional:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_REGIONAL']; ?></span></td>
				<td><strong>Estado del Proceso:</strong><br><span class="muted"><?php echo $empresa['TIPOGESTION']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><strong>Nit:</strong><br><span class="muted"><?php echo $empresa['CODEMPRESA']; ?></span></td>
				<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_EMPRESA']; ?></span></td>
				<td><strong>Fecha Ejecutoria:</strong><br><span class="muted"><?php echo $empresa['FECHA_EJECUTORIA']; ?></span></td>
			</tr>
			<tr>
				<td><strong>Número de Resolución:</strong><br><span class="muted"><?php echo $empresa['NRO_RESOLUCION']; ?></span></td>
				<td><strong>Valor Capital:</strong><br><span class="muted"><?php echo "$".number_format($empresa['VALOR'], 2, '.', ','); ?></span></td>
				<td><strong>Fecha de Registro:</strong><br><span class="muted"><?php echo $empresa['FECHA_CREACION']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td>
					<?php echo form_label('<b>Fecha de Liquidación:</b>','fechaLiquidacion'); ?>
					<?php 
						$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'class'  => 'input-small uneditable-input', 'value' => $fecha, 'required' => 'required', 'readonly' => 'readonly' );
						echo form_input($atributos_fechaLiquidacion);
					?>
				</td>
				<td>
					<?php 
    						$atributos_boton = array('id' =>'calcularInteres', 'name' => 'calcularInteres', 'class' => 'btn btn-success', 'content' => '<i class="icon-ok icon-white"></i>  Aceptar', 'type' => 'button');
						echo form_button($atributos_boton) ; 
					?>
				</td>
				<td></td>
			</tr>
		</table>
		<?php
			$atributos_codigoMulta =  array('id' => 'codigoMulta' , 'name' => 'codigoMulta', 'value' => $codigoMulta, 'type' => 'hidden' );
			echo form_input($atributos_codigoMulta);
			$atributos_valorMulta =  array('id' => 'valorMulta' , 'name' => 'valorMulta', 'value' => $empresa['VALOR'], 'type' => 'hidden' );
			echo form_input($atributos_valorMulta);
			$atributos_fechaElaboracion =  array('id' => 'fechaElaboracion' , 'name' => 'fechaElaboracion', 'value' => $empresa['FECHA_CREACION'], 'type' => 'hidden' );
			echo form_input($atributos_fechaElaboracion);
			$atributos_fechaEjecutoria =  array('id' => 'fechaEjecutoria' , 'name' => 'fechaEjecutoria', 'value' => $empresa['FECHA_EJECUTORIA'], 'type' => 'hidden' );
			echo form_input($atributos_fechaEjecutoria);
			echo form_close();
		?>
</div>
<!-- Fin cargue de Cabeceras -->
<br><br>
<!-- Loader -->
<div id="loader">
	<i class="fa fa-refresh fa-spin fa-3x"></i>
</div>
<!-- Fin Loader -->
<!-- Cabecera Reporte -->
<div class="center-form-xlarge" id="cabecera_base">
		<h3 class="text-center">Intereses Resolución Multas Ministerio del Trabajo</h2>
		<br><br>
		<table style="border: 1px solid; width:100%; padding:5px; margin-bottom:15px;">
			<tr>
				<td><strong>Regional:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_REGIONAL']; ?></span></td>
				<td><strong>Estado del Proceso:</strong><br><span class="muted"><?php echo $empresa['TIPOGESTION']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><strong>Nit:</strong><br><span class="muted"><?php echo $empresa['CODEMPRESA']; ?></span></td>
				<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_EMPRESA']; ?></span></td>
				<td><strong>Fecha Ejecutoria:</strong><br><span class="muted"><?php echo $empresa['FECHA_EJECUTORIA']; ?></span></td>
			</tr>
			<tr>
				<td><strong>Número de Resolución:</strong><br><span class="muted"><?php echo $empresa['NRO_RESOLUCION']; ?></span></td>
				<td><strong>Valor Capital:</strong><br><span class="muted"><?php echo "$".number_format($empresa['VALOR'], 2, '.', ','); ?></span></td>
				<td><strong>Fecha de Registro:</strong><br><span class="muted"><?php echo $empresa['FECHA_CREACION']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><strong>Fecha de Liquidación:</strong></td>
				<td></td>
				<td></td>
			</tr>
		</table>
</div>
<!-- Fin Cabecera Reporte -->
<!-- Listado de interes -->
<div id="informe" title="Informe">
</div>
<div id="vistaPrevia_base" title="Resumen Liquidación Multas Ministerio"></div>
<!-- Fin listado de interes -->
<br><br>
<!-- LIstado de Controles -->
<div id="controles" class="controls controls-row" style="display:none;">
	<span class="span2"></span>
	<span class="span2"><a class="btn btn-success"  id="vistaPrevia"><i class="icon-eye-open icon-white"></i>  Vista previa</a></span>
	<span class="span1"></span>
	<span class="span2"><a class="btn btn-success" onClick="javascript:printdiv('cabecera_base','vistaPrevia_base');"><i class="icon-print icon-white"></i>  Imprimir</a></span>
	<span class="span1"></span>
	<span class="span2"><a class="btn btn-success" href="comprobante/<?php echo $empresa['COD_MULTAMINISTERIO']?>"><i class="icon-file icon-white"></i>  Comprobante</a></span>
	<span class="span2"></span>
</div>
<!-- LIstado de Controles -->
<!-- Función datepicker /post / modal -->
<script>
  $(function() {
	//Array para dar formato en español
	$.datepicker.regional['es'] = 
	{
		closeText: 'Cerrar', 
		prevText: 'Previo', 
		nextText: 'Próximo',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		monthStatus: 'Ver otro mes', yearStatus: 'Ver otro año',
		dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		dateFormat: 'dd/mm/yy', firstDay: 0, 
		 initStatus: 'Selecciona la fecha', isRTL: false
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$( "#fechaLiquidacion" ).datepicker({
		showOn: 'button',
		buttonText: 'Selecciona una fecha',
		buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
		buttonImageOnly: true,
		numberOfMonths: 1,
		maxDate: '29d',
		minDate: '0d'
   	 });

	$('#calcularInteres').click(function()
	{
		if ($('#fechaLiquidacion').val() == "")
		{
			alert('Se ha producido un error de ejecución: Debe indicar una fecha de liquidación')
		}
		else
		{
			$('#loader').fadeIn('slow');	
			$.post('<?php echo site_url();?>/liquidaciones/calcularInteresMultasMinisterio',
			{
				codigoMulta: $('#codigoMulta').val(),
				valorMulta: $('#valorMulta').val(),
				fechaElaboracion: $('#fechaElaboracion').val(),
				fechaEjecutoria: $('#fechaEjecutoria').val(),
				fechaLiquidacion: $('#fechaLiquidacion').val()				
			}
			)
			.done(function(data)
			{	
				$('#loader').fadeOut('fast');
				$('#vistaPrevia_base').html(data);
				$('#informe').html(data);
				$('#calcularInteres').attr("disabled", "disabled");	
				$('#informe').css("display","block");
				var p = new Paginador(
    					document.getElementById('paginador'),
    					document.getElementById('intereses'),
						6
				);
				p.Mostrar();
				$('#controles').css("display","block");	
			})
			.fail(function(e)
			{
				alert('Ha ocurrido un error de ejecución: No fue posible enviar la información');
				$('#loader').fadeOut('fast');
			});
		}
	});

	$('#vistaPrevia').click(function()
	{
		$(function() 
		{
    			$( "#vistaPrevia_base" ).dialog({
      				width: 800,
      				height: 500,
     			 	modal: true
    			});
  		});
  	});
});
 </script>
<!-- Fin función DatePicker /post / modal -->
 <!--Función paginador -->
<script type="text/javascript">
Paginador = function(divPaginador, tabla, tamPagina)
{
	this.miDiv = divPaginador; //un DIV donde irán controles de paginación
    	this.tabla = tabla;           //la tabla a paginar
    	this.tamPagina = tamPagina; //el tamaño de la página (filas por página)
    	this.pagActual = 1;         //asumiendo que se parte en página 1
    	this.paginas = Math.floor((this.tabla.rows.length - 1) / this.tamPagina); //¿?
 
    	this.SetPagina = function(num)
    	{
        		if (num < 0 || num > this.paginas)
            	return;
 
        		this.pagActual = num;
        		var min = 1 + (this.pagActual - 1) * this.tamPagina;
        		var max = min + this.tamPagina - 1;
 
        		for(var i = 1; i < this.tabla.rows.length; i++)
        		{
            		if (i < min || i > max)
                		this.tabla.rows[i].style.display = 'none';
           		 else
                		this.tabla.rows[i].style.display = '';
        		}
        	this.miDiv.firstChild.rows[0].cells[1].innerHTML = this.pagActual;
	}
 
	this.Mostrar = function()
	{
        	//Crear la tabla
        	var tblPaginador = document.createElement('table');
 
        	//Agregar una fila a la tabla
        	var fil = tblPaginador.insertRow(tblPaginador.rows.length);
 
        	//Ahora, agregar las celdas que serán los controles
        	var ant = fil.insertCell(fil.cells.length);
        	ant.innerHTML = 'Anterior';
        	ant.className = 'btn btn-inverse'; //con eso le asigno un estilo
        	var self = this;
        	ant.onclick = function()
        	{
            	if (self.pagActual == 1)
            	{
            		return;	
            	}	
                	
            	self.SetPagina(self.pagActual - 1);
        	}
 
        	var num = fil.insertCell(fil.cells.length);
        	num.innerHTML = ''; //en rigor, aún no se el número de la página
        	num.className = 'badge';

	var pag = fil.insertCell(fil.cells.length);
         	pag.innerHTML ='  de  ';
        

         	var total = fil.insertCell(fil.cells.length);
         	total.innerHTML =(self.paginas+1);
         	total.className = 'badge';
 
        	var sig = fil.insertCell(fil.cells.length);
        	sig.innerHTML = 'Siguiente';
        	sig.className = 'btn btn-inverse';
        	sig.onclick = function()
        	{
            	if (self.pagActual == self.paginas)
                	{
            		return;	
            	}
            	self.SetPagina(self.pagActual + 1);
        	}
 
       	 //Como ya tengo mi tabla, puedo agregarla al DIV de los controles
        	this.miDiv.appendChild(tblPaginador);
 
        	//Control de paginas
        	if (this.tabla.rows.length - 1 > this.paginas * this.tamPagina)
            	this.paginas = this.paginas + 1;
 
        	this.SetPagina(this.pagActual);
    	}
}

function printdiv(id_cabecera, id_cuerpo)
{
	var tittle = document.getElementById(id_cabecera);
	var content = document.getElementById(id_cuerpo);
	var printscreen = window.open('','','left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
	printscreen.document.write(tittle.innerHTML);
	printscreen.document.write(content.innerHTML);
	printscreen.document.close();

	var css = printscreen.document.createElement("link");
	css.setAttribute("href", "<?php echo base_url()?>/css/bootstrap.css");
	css.setAttribute("rel", "stylesheet");
	css.setAttribute("type", "text/css");

	var css1 = printscreen.document.createElement("link");
	css1.setAttribute("href", "<?php echo base_url()?>/css/style.css");
	css1.setAttribute("rel", "stylesheet");
	css1.setAttribute("type", "text/css");

	printscreen.document.head.appendChild(css);
	printscreen.document.head.appendChild(css1);


	printscreen.focus();
	printscreen.print();
	printscreen.close();
}
</script>
<!-- Fin función paginador -->

<!--
/* End of file liquidaciones.php */
-->