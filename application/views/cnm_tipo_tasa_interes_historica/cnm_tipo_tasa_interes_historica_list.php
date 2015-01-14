<h1>Tipo Tasa de Interes No Misional Histórico</h1>
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
     <th>Id Tipo Tasa Historica</th>
	    <th>Eliminar</th>
     <th>Rangos de Tasa</th>
     <th>Tipo de Tasa Historica</th>
     <th>Tipo de Tasa</th>


   </tr>
 </thead>
 <tbody>
 <?php
$total = 0;
foreach ($tipos->result_array as $data) {
	$total += 1;?>  
	<tr>

<td><?=$data["COD_TIPO_TASA_HIST"]?></td>
<td><a class="btn btn-small at2" title="Eliminar" at2="<?=$data["COD_TIPO_TASA_HIST"]?>" at4="<?=$data["NOMBRE_TIPOTASA"]?>"><i class="icon-remove"></i></a></td>	
<td><a class="btn btn-small at5" title="Rangos de Tasa" at5="<?=$data["COD_TIPO_TASA_HIST"]?>" at6="<?=$data["NOMBRE_TIPOTASA"]?>"> <i class="icon-random"></i></a></td>	

<td><?=$data["NOMBRE_TIPOTASA"]?></td>	

<td><?=$data["NOMBRE_TASAINTERES"]?></td>	
	
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
      echo anchor(base_url().'index.php/cnm_tipo_tasa_interes_historic/add/','<i class="icon-star"></i> Crear Tasa Histórica','class="btn btn-success"');
?>
</center>

  <form id="rangos" action="<?= base_url('index.php/cnm_rango_tipo_tasa_interes_historica') ?>" method="post" >
    <input type="hidden" id="cod_tasa_hist" name="cod_tasa_hist">

</form>


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

					  ]

        
    });
});    

$('#cancelar').click(function(){
        	window.history.back()
       });  
       
       
            
    $('.at5').click(function(){
	var cod_tipo_tasa_hist=$(this).attr('at5');
$('#cod_tasa_hist').val(cod_tipo_tasa_hist);
	var nombre_tasa_hist=$(this).attr('at6');
		
 $('#rangos').submit();


    });
       
       
    $('.at2').click(function(){
	var cod_tipo_tasa=$(this).attr('at2');

	var nombre_tasa=$(this).attr('at4');
        var url = "<?php echo base_url('index.php/cnm_tipo_tasa_interes_historic/delete') ?>";
        var confirmar = confirm("Desea eliminar el tipo de tasa histórica?");
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
