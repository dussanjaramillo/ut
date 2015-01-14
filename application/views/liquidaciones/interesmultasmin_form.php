<?php
/**
* Formulario para el calculo de intereses por Resolución de Multas del Ministerio de Trabajo
*
* @package    Cartera
* @subpackage Views
* @author     jdussan
* @location     application/views/interesmultasmin_form.php
* @last-modified  14/11/2014
* @copyright
*/
if(!defined('BASEPATH') ) exit('No direct script access allowed');

if(isset($message))
{
	echo $message;
}

$attributos_formulario = array('class' => 'form-inline', 'id' => 'multas');
$atributos_fechaLiquidacion = array ('name' => 'fechaLiquidacion', 'id' => 'fechaLiquidacion', 'class'  => 'input-small uneditable-input', 'value' => $fecha, 'required' => 'required', 'readonly' => 'readonly' );
$atributos_boton = array('id' =>'calcularInteres', 'name' => 'calcularInteres', 'class' => 'btn btn-success', 'content' => '<i class="icon-ok icon-white"></i>  Aceptar', 'type' => 'button');
$atributos_codigoMulta =  array('id' => 'codigoMulta' , 'name' => 'codigoMulta', 'value' => $codigoMulta, 'type' => 'hidden' );
$atributos_codigoFiscalizacion = array('id' => 'codigoFiscalizacion', 'name' => 'codigoFiscalizacion', 'value' => $codigoFiscalizacion, 'type' => 'hidden');
$atributos_nit = array('id' => 'nit', 'name' => 'nit', 'value' => $empresa['CODEMPRESA'], 'type' => 'hidden');
$atributos_valorMulta =  array('id' => 'valorMulta' , 'name' => 'valorMulta', 'value' => $empresa['VALOR'], 'type' => 'hidden' );
$atributos_fechaElaboracion =  array('id' => 'fechaElaboracion' , 'name' => 'fechaElaboracion', 'value' => $empresa['FECHA_CREACION'], 'type' => 'hidden' );
$atributos_fechaEjecutoria =  array('id' => 'fechaEjecutoria' , 'name' => 'fechaEjecutoria', 'value' => $empresa['FECHA_EJECUTORIA'], 'type' => 'hidden' );
?>
<!-- Estilos personalizados -->
<style type="text/css">
.ui-widget-overlay{z-index: 10000;}
.ui-dialog{z-index: 15000;}
#cabecera table{width: 100%; height: auto; margin: 10px;}
#cabecera td{padding: 5px; text-align: left; vertical-align: middle;}
#informe{display:none; width:90%; margin-left:auto; margin-right:auto;}
#loader{display:none; text-align:center;}
#vistaPrevia_contenedor, #cabecera_base{display:none;}
.dialogo
{
    margin: 0;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 20px;
    color: #333333;
    background-color: #ffffff;
}
</style>
<!-- Fin Estilos personalizados -->
<form id="form2" name="form2" target = "_blank"   method="post" action="<?php echo site_url('/liquidaciones/pdf') ?>">
 	<textarea id="html" name="html" style="width: 100%;height: 300px; display:none"></textarea>
 	<input type="hidden" name="tipo" id="tipo" value="3">
 	<input type="hidden" name="nombre" id="nombre">
 	<input type="hidden" name="titulo" id="titulo" value="Liquidación Multa Ministerio">
 </form>

<!-- Cargue de cabeceras -->
<div class="center-form-xlarge" id="cabecera">
	<?php
		echo form_open('', $attributos_formulario);
	?>
		<h2 class="text-center">Intereses Resolución Multas Ministerio del Trabajo</h2>
		<br><br>
		<table>
			<tr>
				<td width="34%"><strong>Regional:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_REGIONAL']; ?></span></td>
				<td width="33%"><strong>Estado del Proceso:</strong><br><span class="muted"><?php echo $empresa['TIPOGESTION']; ?></span></td>
				<td width="33%"><strong>Multa N°:</strong><br><span class="muted"><?php echo $codigoMulta; ?></span></td>
			</tr>
			<tr>
				<td width="34%"><strong>Nit:</strong><br><span class="muted"><?php echo $empresa['CODEMPRESA']; ?></span></td>
				<td width="33%"><strong>Razón Social:</strong><br><span class="muted"><?php echo $empresa['RAZON_SOCIAL']; ?></span></td>
				<td width="33%"><strong>Fecha Ejecutoria:</strong><br><span class="muted"><?php echo $empresa['FECHA_EJECUTORIA']; ?></span></td>
			</tr>
			<tr>
				<td width="34%"><strong>Número de Resolución:</strong><br><span class="muted"><?php echo $empresa['NRO_RESOLUCION']; ?></span></td>
				<td width="33%"><strong>Valor Capital:</strong><br><span class="muted"><?php echo "$".number_format($empresa['VALOR'], 2, '.', ','); ?></span></td>
				<td width="33%"><strong>Fecha de Registro:</strong><br><span class="muted"><?php echo $empresa['FECHA_CREACION']; ?></span></td>
			</tr>
			<tr>
				<td width="34%">
					<?php echo form_label('<b>Fecha de Liquidación:</b>','fechaLiquidacion'); ?>
					<?php
						echo form_input($atributos_fechaLiquidacion);
					?>
				</td>
				<td width="33%">
					<?php
						echo form_button($atributos_boton) ;
					?>
				</td>
				<td width="33%"><a id="cancelar" class="btn btn-warning" href="<?php echo site_url(); ?>/multasministerio/"><i class="fa fa-minus-circle"></i> Cancelar</a></td>
			</tr>
			<?php
				if(isset($previa)):
				echo  '<tr id = "mensajePrevia"><td colspan = "3" class = "text-left text-warning"><i class="fa fa-exclamation-triangle"></i> <strong>Existe una liquidación previa para esta Multa. Proceda con precaución</strong></td></tr>';
				endif;
			?>
		</table>
		<?php

			echo form_input($atributos_codigoMulta);
			echo form_input($atributos_codigoFiscalizacion);
			echo form_input($atributos_nit);
			echo form_input($atributos_valorMulta);
			echo form_input($atributos_fechaElaboracion);
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
		<h3 class="text-center">Intereses Resolución Multas Ministerio del Trabajo</h3>
		<br><br>
		<table style="border: 1px solid; width:100%; padding:5px; margin-bottom:15px;">
			<tr>
				<td><strong>Regional:</strong><br><span class="muted"><?php echo $empresa['NOMBRE_REGIONAL']; ?></span></td>
				<td><strong>Estado del Proceso:</strong><br><span class="muted"><?php echo $empresa['TIPOGESTION']; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><strong>Nit:</strong><br><span class="muted"><?php echo $empresa['CODEMPRESA']; ?></span></td>
				<td><strong>Razón Social:</strong><br><span class="muted"><?php echo $empresa['RAZON_SOCIAL']; ?></span></td>
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
<div class="center-form-xlarge" id="informe" title="Informe">
</div>
<div id = "vistaPrevia_contenedor" title = "Resumen Liquidación Multas Ministerio">
	<div id = "vistaPrevia_titulo">
		<h3 class = "text-center">Intereses Resolución Multas Ministerio del Trabajo</h3>
		<br><br>
		<table class = "table table-bordered">
			<tr>
				<td><strong>Regional:</strong><br><?php echo $empresa['NOMBRE_REGIONAL']; ?></td>
				<td colspan = "2"><strong>Estado del Proceso:</strong><br><?php echo $empresa['TIPOGESTION']; ?></td>
			</tr>
			<tr>
				<td><strong>Nit:</strong><br><?php echo $empresa['CODEMPRESA']; ?></td>
				<td><strong>Razón Social:</strong><br><?php echo $empresa['RAZON_SOCIAL']; ?></td>
				<td><strong>Fecha Ejecutoria:</strong><br><?php echo $empresa['FECHA_EJECUTORIA']; ?></td>
			</tr>
			<tr>
				<td><strong>Número de Resolución:</strong><br><?php echo $empresa['NRO_RESOLUCION']; ?></td>
				<td><strong>Valor Capital:</strong><br><?php echo "$".number_format($empresa['VALOR'], 2, '.', ','); ?></td>
				<td><strong>Fecha de Registro:</strong><br><?php echo $empresa['FECHA_CREACION']; ?></td>
			</tr>
			<tr>
				<td><strong>Fecha de Liquidación:</strong></td>
				<td id = "fechaLiq" colspan = "2"></td>
			</tr>
		</table>
	</div>
	<div id="vistaPrevia_base" style="background-color:#EEE"></div>
</div>
<!-- Fin listado de interes -->
<br><br>
<!-- LIstado de Controles -->
<div id="controles" class="controls controls-row" style="display:none;">
	<table width="100%">
		<tr>
			<td width="25%" class="text-center"><a class="btn btn-default" href="<?php echo site_url(); ?>/multasministerio/"><i class="fa fa-arrow-left"></i> Volver a Multas</a></td>
			<td width="25%" class="text-center"><a class="btn btn-info"  id="vistaPrevia"><i class="fa fa-eye"></i>  Vista previa</a></td>
			<td width="25%" class="text-center"><a class="btn btn-info" onClick="javascript:pdf();"><i class="fa fa-print"></i>  Imprimir</a></td>
			<td width="25%" class="text-center"><a class="btn btn-success" href="comprobanteAlterno/<?php echo $empresa['COD_MULTAMINISTERIO']?>"><i class="fa fa-bank"></i>  Comprobante</a></td>
		</tr>
	</table>
</div>
<!-- LIstado de Controles -->
<!-- Función datepicker /post / modal -->
<script type="text/javascript" language="javascript" charset="utf-8">
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
	$('').datepicker({
		showOn: 'button',
		buttonText: 'Seleccione una fecha',
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
				codigoFiscalizacion: $('#codigoFiscalizacion').val(),
				nit: $('#nit').val(),
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
				$('#fechaLiq').html($('#fechaLiquidacion').val());
				$('#calcularInteres').attr("disabled", "disabled");
				$('#calcularInteres').css("display", "none");
				$('#cancelar').attr("disabled", "disabled");
				$('#cancelar').css("display", "none");
				$('#mensajePrevia').css("display", "none");
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
    			// $( "#reporteInteres, #intereses" ).attr( "style", "border: 1px solid; width:100%; padding:5px; margin-bottom:15px;" )
    			$( "#vistaPrevia_contenedor" ).dialog({
                    dialogClass: 'dialogo',
                    width: 1100,
      				height: 500,
     			 	modal: true
    			});
  		});
  	});
});
 </script>
<!-- Fin función DatePicker /post / modal -->
 <!--Función paginador -->
<script type="text/javascript" language="javascript" charset="utf-8">
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
        	ant.className = 'btn btn-default'; //con eso le asigno un estilo
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
        	sig.className = 'btn btn-default';
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

function base64_encode(data)
{
	var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
	var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	ac = 0,
	enc = '',
	tmp_arr = [];
	if (!data)
	{
		return data;
	}
	do
	{
		o1 = data.charCodeAt(i++);
		o2 = data.charCodeAt(i++);
		o3 = data.charCodeAt(i++);
		bits = o1 << 16 | o2 << 8 | o3;
		h1 = bits >> 18 & 0x3f;
		h2 = bits >> 12 & 0x3f;
		h3 = bits >> 6 & 0x3f;
		h4 = bits & 0x3f;
		tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	}
	while (i < data.length);
	enc = tmp_arr.join('');
	var r = data.length % 3;
	return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}


function pdf()
{
	var informacion = document.getElementById("vistaPrevia_contenedor").innerHTML;
	document.getElementById("nombre").value ='liquidacionMultaMinisterio_<?php echo $codigoMulta; ?>';
	document.getElementById("html").value = base64_encode(informacion);
	$("#form2").submit();

}

</script>
<!-- Fin función paginador -->

<!--
/* End of file interesmultasmin_form.php */
-->