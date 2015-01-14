<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<center><h3>PRESTAMO EDUCATIVO</h3></center>
</br>

<table id="tablaq">
 <thead>
    <tr>

     <th></th>
  <th>IDENTIFICACION</th>
     <th>NOMBRE</th>
     <th>TELEFONO</th>
      <th>DIRECCION</th>
     <th>REGIONAL</th>
     <th>ID. DEUDA</th>
      <th>ESTADO</th>
     <th>GESTIÓN</th>

    </tr>
  </thead>
 <tbody>

	<?php
	$total = 0;
	foreach ($carteras->result_array as $data) { $total += 1; ?> 
	<tr>
<td><center><input type="radio" class='atcnm'  id="cod_cnm" name="cod_cnm" class="btn btn-success"  atcnm ="<?=$data['COD_CARTERA_NOMISIONAL']?>"></center></td>
<td><?=$data['IDENTIFICACION'] ?></td>
<td><?=$data['NOMBRES']." ".$data['APELLIDOS']?></td>	
<td><?=$data['TELEFONO']  ?></td>	
<td><?=$data['DIRECCION']  ?></td>	
<td><?=$data['NOMBRE_REGIONAL'] ?></td>
<td><?=$data['COD_CARTERA_NOMISIONAL']  ?></td>	
<td><?=$data['DESC_EST_CARTERA'] ?></td>	


<td>
<?php if($data['COD_ESTADO']==1||$data['COD_ESTADO']==3){ ?>
	<center><a class="btn btn-small ateditar" title="Editar" ateditar="<?=$data['COD_CARTERA_NOMISIONAL']?>"><i class="icon-edit"></i></a></center>	
<?php }elseif($data['COD_ESTADO']==2){ ?>	
	<center><a class="btn btn-small atgestion" title="Gestionar" atgestion="<?=$data['COD_CARTERA_NOMISIONAL']?>" atdoc="<?=$data['IDENTIFICACION']?>" atarchivos="<?=$data['ADJUNTOS']?>" attipoc="<?=$data['COD_TIPOCARTERA']?>"><i class="icon-edit"></i></a></center>	
<?php } elseif($data['COD_ESTADO']==4||$data['COD_ESTADO']==5){ ?>	
	<center><a class="btn btn-small" title="Inactivo" disabled="disabled" ><i class="icon-edit"></i></a></center>	
<?php } else{ ?>	
	<center><a class="btn btn-small atcoactivo" title="Reenvio Coactivo" atcoactivo="<?=$data['COD_CARTERA_NOMISIONAL']?>" attipo="<?=$data['COD_TIPOCARTERA']?>"><i class="icon-edit"></i></a></center>	
<?php }?>	
</td>	
	</tr>
	<?php } ?>




 </tbody>  
 </table>

<p>
 <center>	 
 <input type='button' name='detalle' class='btn btn-success' value='Detalle'  id='detalle' >
 </center>
   </p> 
<form id="detalle_cartera" action="<?= base_url('index.php/cnm_educacion/detalle_prestamo') ?>" method="post" >
    <input type="hidden" id="id_cartera" name="id_cartera">
</form>
<form id="edicion_cartera" action="<?= base_url('index.php/cnm_educacion/edit_prestamo') ?>" method="post" >
    <input type="hidden" id="id_cartera_edit" name="id_cartera_edit">
</form>
<div id="gestion"></div>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function(){
	$('.atgestion').click(function() {
		var atgestion=$(this).attr('atgestion');
		var atdoc=$(this).attr('atdoc');
		var atarchivos=$(this).attr('atarchivos');
		var attipoc=$(this).attr('attipoc');
		var url2="<?= base_url('index.php/carteranomisional/gestiones')?>";
		$('#gestion').load(url2,{atgestion : atgestion, atdoc : atdoc , atarchivos : atarchivos, attipoc: attipoc });
	});
	
		$('.ateditar').click(function() {
		var ateditar=$(this).attr('ateditar');
		$('#id_cartera_edit').val(ateditar);
		$('#edicion_cartera').submit();
	});
	
	
	$('.atcnm').click(function(){
		var cod_cartera = $(this).attr('atcnm');
		$('#id_cartera').val(cod_cartera);
	});
	
	$('#detalle').click(function(){
    	if ($("#id_cartera").val().length < 1){
    		$("#alerta3").show();
    		$('#alerta3').html('<p class="text-warning"><i class="fa fa-warning"></i> Seleccione Un Registro Para Consultar el Detalle<p>');
    		$('.atcnm').focus();
    		return false;
    	}
    	$("#alerta3").hide();
    	$('#detalle_cartera').submit();
    });
    
    datatable();
});

function datatable () {
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

		'iTotalRecords' : <?php echo $total ?>,
		'iTotalDisplayRecords' : <?php echo $total ?>,
		'aLengthMenu' : [10,50],
		'sEcho' : 1,
		"aaSorting": [[ 0, "desc" ]],
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

                      ]

        
    });
   }
    
  
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

