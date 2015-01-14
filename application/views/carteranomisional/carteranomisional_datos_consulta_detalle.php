<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>




<table id="tablaq">
 <thead>
    <tr>

     <th>SELECCIONAR</th>
  <th>IDENTIFICACION</th>
     <th>NOMBRE TERCERO</th>
     <th>TELEFONO</th>
      <th>DIRECCION</th>
     <th>REGIONAL</th>
     <th>IDENTIFICACION DEUDA</th>
      <th>ESTADO</th>
     <th>ASIGNADO</th>
     <th>GESTIONAR</th>

    </tr>
  </thead>
 <tbody>
<tr>
	<td><center><input type="radio" class='atcnm'  id="cod_cnm" name="cod_cnm" class="btn btn-success"  atcnm ="1" ></center></td>
	<td>1030617030</td>
	<td>Juan Pablo</td>
	<td>2222222</td>
	<td>carrera 38 sur 70i-22</td>
	<td>Cali</td>
	<td>cv - 132</td>
	<td>ACTIVO</td>
	<td>Pepe Perez</td>
	<td>
	<center><a class="btn btn-small atgestion" title="Gestionar" atgestion="2" ><i class="icon-edit"></i></a></center>	
	</td>
</tr>

 </tbody>  
 </table>

<p>
 <center>	 
 <input type='button' name='detalle' class='btn btn-success' value='Detalle'  id='detalle' >
 </center>
   </p> 

<div id="gestion"></div>
<script type="text/javascript" language="javascript" charset="utf-8">

 $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
    "fnInfoCallback": null,
            },

          	"bLengthChange": false,
        	"aoColumns": [ 


                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      ]

        
    });
    
    $('#detalle').click(function(){

switch($("#cartera_id").val())
{
	case '1':
	 $('#calamidad').submit();
	break;
	
	case '2':
	$('#convenio').submit();
	break;
	
	case '3':
	$('#educacion').submit();
	break;
	
	case '4':
	$('#educacion_prestamo').submit();
	break;
	
	case '5':
	$('#servicio_medico').submit();
	break;
	
	case '6':
	$('#responsabilidad_bienes').submit();
	break;
	
	case '7':
	$('#responsabilidad_fondos').submit();
	break;
	
	case '8':
	$('#prestamo_hipotecario').submit();
	break;
	
	case '9':
	$('#cuota_parte').submit();
	break;
	
	case '10':
	$('#doble_mesada_pensional').submit();
	break;
	
	case '11':
	$('#prestamo_ahorro').submit();
	break;
	
	
	default:
	$('#otras_carteras').submit();
	break;
}

});
            
            $('.atgestion').click(function(){

	var atgestion=$(this).attr('atgestion') ;

	var url2="<?= base_url('index.php/carteranomisional/gestiones')?>";
	$('#gestion').load(url2,{atgestion : atgestion });

});
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

