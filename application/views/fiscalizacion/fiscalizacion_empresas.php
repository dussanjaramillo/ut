<?php 
if (isset($message)){
    echo $message;
   }
?>
 

<br>
<table id="tablaotrasempresas">
 <thead>
    <tr>

     <th>Nit</th>
     <th>Razón Social</th>
     <th>Asignado a</th>
     <th>Fecha Asignación</th>
     <th>Fiscalización</th>
    </tr>
  </thead>
 <tbody>
<?php $total = 0;
foreach ($infoempresa->result_array as $dataempresa) {$total += 1;?> 
	<tr>


<td><?=$dataempresa['CODEMPRESA']  ?></td>	
<td><?=$dataempresa['NOMBRE_EMPRESA']  ?></td>	
<td><?=$dataempresa['NOMBREUSUARIO']  ?></td>	
<td><?=$dataempresa['FECHA_ASIGNACION']  ?></td>	
<td><input type="radio" class='at2' id="nit_asig_fisc2" name="nit_asig_fisc2" class="btn btn-success" at2 ="<?=$dataempresa['CODEMPRESA']  ?>" at4 ="<?=$dataempresa['COD_ASIGNACIONFISCALIZACION']  ?>" ></td>	

		
</tr>
		<?php
}
?> 
 </tbody>  
 </table>

<br>




<script type="text/javascript" language="javascript" charset="utf-8">

$(document).ready(function(){  
	
  $('.at2').click(function(){
  	$(".preload, .load").show();
var nit_asign_emp = $(this).attr('at2');
 $('#cod_nit').val(nit_asign_emp);
var cod_asign_fisc = $(this).attr('at4');
 $('#cod_asignacion_fisc').val(cod_asign_fisc); 
   $('#f2').submit();
});	
datatable();  
});	
$(".preload, .load").hide();
//generación de la tabla mediante json
function datatable () {
$('#tablaotrasempresas').dataTable({

		"bJQueryUI": true,
        "bServerSide" : false,
        "bProcessing" : true,
        "bSort" : true,
        "bPaginate" : true,
        "iDisplayStart" : 0,
        "iDisplayLength" : 10,
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
                      
                      ],
        
    });
}
    
  

    function ajaxValidationCallback(status, form, json, options) {
	
}
</script>