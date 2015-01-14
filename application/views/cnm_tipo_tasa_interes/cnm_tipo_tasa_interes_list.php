<h1>Tipo Tasa de Interes No Misional</h1>
<?php
if(isset($message))
{
echo $message;	
}
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id Tipo Tasa</th>
     <th>Eliminar</th>
     <th>Nombre Tipo de Tasa</th>
     <th>Tipo</th>
     <th>Tipo de Periodo que la Tasa Remunera</th>
     <th>Forma de Pago de Intereses</th>
     <th>Fecha Creación</th>

   </tr>
 </thead>
 <tbody>
 <?php
$total = 0;
foreach ($tipos->result_array as $data) {
	$total += 1;?> 
	<tr>

<td><?=$data["COD_TIPO_TASAINTERES"]?></td>
<td><a class="btn btn-small at2" title="Eliminar" at2="<?=$data["COD_TIPO_TASAINTERES"]?>" at4="<?=$data["NOMBRE_TASAINTERES"]?>"><i class="icon-remove"></i></a></td>	
<td><?=$data["NOMBRE_TASAINTERES"]?></td>	
<?php  
if($data["COD_TIPOTASAINTERES"]==1)
{
	$tipo="EFECTIVA";
	}
else{$tipo="NOMINAL";}
?>
<td><?=$tipo?></td>	

<td><?=$data["NOMBRE_TIPOPERIODO"]?></td>

<td><?=$data["NOMBRE_FORMAPAGO"]?></td>	
	
<td><?=$data["FECHA_CREACION"]?></td>		
</tr>
		<?php
}
?> 	
 </tbody>     
</table>
<br><br>

<center>
		
		<?php
      echo anchor('', '<i class="icon-remove"></i> Cancelar', 'class="btn"');          
      echo anchor(base_url().'index.php/cnm_tipo_tasa_interes/add/','<i class="icon-star"></i> Crear','class="btn btn-success"');
?>
</center>

<div id="editar"></div>

<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
$('#tablaq').dataTable({
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
					  ]

        
    });
});    

$('#cancelar').click(function(){
        	window.history.back()
       });  
       
       
    $('.at2').click(function(){
	var cod_tipo_tasa=$(this).attr('at2');
	var nombre_tasa=$(this).attr('at4');
        var url = "<?php echo base_url('index.php/cnm_tipo_tasa_interes/delete') ?>";
        var confirmar = confirm("Desea eliminar el tipo de tasa de interes?");
          if(confirmar == true){
          	
        $.post(url, {cod_tipo_tasa: cod_tipo_tasa})
        	.done(function(data)
        {
        	alert("Se ha eliminado el Tipo de Tasa de Interés "+nombre_tasa);
        	window.location.reload();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar el Tipo De Tasa de Interés");
        	
        	});
        
        }
    });
    

    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
