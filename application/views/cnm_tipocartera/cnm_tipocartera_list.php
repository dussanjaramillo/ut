<h1>Tipo Cartera No Misional</h1>
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
     <th>Id Cartera</th>
     <th>Editar</th>
     <th>Eliminar</th>
     <th>Nombre Cartera</th>
     <th>Tipo</th>
     <th>Estado</th>
     <th>F. Creación</th>
     <th>Ultima Edición</th>

   </tr>
 </thead>
 <tbody>
 <?php
 $total = 0;
foreach ($tipos as $data) {
	$total += 1; ?> 
	<tr>
<td><?=$data->COD_TIPOCARTERA?></td>
<td><a class="btn btn-small at" title="Editar" at="<?=$data->COD_TIPOCARTERA?>" at3="<?=$data->NOMBRE_CARTERA?>"><i class="icon-edit"></i></a></td>
<td><a class="btn btn-small at2" title="Eliminar" at2="<?=$data->COD_TIPOCARTERA?>" at4="<?=$data->NOMBRE_CARTERA?>"><i class="icon-remove"></i></a></td>	
<td><?=$data->NOMBRE_CARTERA?></td>	
<td>No Misional</td>

<?php if($data->COD_ESTADO==0)
{
	$estado="Inactivo";
	}
else{
		$estado="Activo";
	}

  ?>
<td><?=$estado?></td>	
<td><?=$data->FECHA_CREACION  ?></td>	
<td><?=$data->FECHA_EDICION  ?></td>	
	
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
      echo anchor(base_url().'index.php/cnm_tipocartera/add/','<i class="icon-star"></i> Crear','class="btn btn-success"');
?>
</center>

<div id="editar"></div>

<script type="text/javascript" language="javascript" charset="utf-8">

$('#cancelar').click(function(){
        	window.history.back()
       });

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
					  { "sClass": "center" }, 
                      ]

        
    });
});    
    $('.at2').click(function(){
	var cod_tipo_cartera=$(this).attr('at2');
	var nombre_cartera=$(this).attr('at4');
        var url = "<?php echo base_url('index.php/cnm_tipocartera/delete') ?>";
        var confirmar = confirm("Desea eliminar el tipo de cartera?");
          if(confirmar == true){
          	
        $.post(url, {cod_tipo_cartera: cod_tipo_cartera})
        	.done(function(data)
        {
        	alert("Se ha eliminado la cartera "+nombre_cartera);
        	window.location.reload();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar la Cartera, Ya existen créditos creados para este concepto.");
        	
        	});
        
        }
    });
    
    $('.at').click(function(){
	var cod_cartera=$(this).attr('at');
	var nombre=$(this).attr('at3');
	var url3="<?= base_url('index.php/cnm_tipocartera/edit') ?>/";
	$('#editar').load(url3,{cod_cartera : cod_cartera, nombre: nombre });

});
    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
