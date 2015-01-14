<h1>Agenda</h1>

<br>
<table id="gestionagenda">
 <thead>
    <tr>
	<th>FECHAHORA</th>
	<th>DIA</th>	
     <th>FECHA</th>
     <th>HORA INICIO</th>
     <th>HORA FIN</th>
     <th>NOMBRE EMPRESA</th>
     <th>DIRECCIÓN</th>
     <th>TELEFONO</th>
   </tr>
 </thead>
 <tbody>
<?php $total = 0;
foreach ($agenda->result_array as $data) { $total += 1; ?> 
	<tr>
<?php

$fechaexplode=explode("/", $data['FECHA_VISITA']);
$horaexplode=explode(":", $data['HORA_INICIO']);
$fechacontacto=$fechaexplode[2].$fechaexplode[1].$fechaexplode[0].$horaexplode[0].$horaexplode[1];

$fechadia=$fechaexplode[2]."-".$fechaexplode[1]."-".$fechaexplode[0];
$dias = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
$fecha = $dias[date('N', strtotime($fechadia))];

?> 
<td><?=$fechacontacto ?></td>
<td><?=$fecha  ?></td>	
<td><?=$data['FECHA_VISITA']  ?></td>	
<td><?=$data['HORA_INICIO']  ?></td>	
<td><?=$data['HORA_FIN']  ?></td>	
<td><?=$data['NOMBRE_EMPRESA']  ?></td>	
<td><?=$data['DIRECCION']  ?></td>	
<td><?=$data['TELEFONO_FIJO']  ?></td>	

</tr>
		<?php
}
?> 
 </tbody>  
 </table>

  

<script >
$(".preload, .load").hide();


 $('#gestionagenda').dataTable({
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
					  { "bSearchable": false, "bVisible": false },
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
              
                      ],

        
    });
    

        function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 
