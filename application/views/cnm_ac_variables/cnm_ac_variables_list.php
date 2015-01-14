
<?php
if(isset($message))
{
echo $message;	
}
?>

<div class="center-form-large">
	<center><h3>Variables</h3></center>
<br><br>
<table id="tablavar">
 <thead>
    <tr>
     <th>cod variable</th>
     <th>Eliminar</th>
     <th>Variable</th>
     <th>Minimo</th>
     <th>Máximo</th>
     
   </tr>
 </thead>
 <tbody>
 <?php

foreach ($variable->result_array as $data) {?> 
	<tr>

<td><?=$data["COD_VARIABLE"]?></td>
<td><a class="btn btn-small atvar" title="Eliminar" atvar="<?=$data["COD_VARIABLE"]?>" atvar2="<?=$data["NOMBRE_VARIABLE_ESP"]?>"><i class="icon-remove	"></i></a></td>		
<td><?=$data["NOMBRE_VARIABLE_ESP"]?></td>	
<td><?=$data["MINIMO"]?></td>	
<td><?=$data["MAXIMO"]?></td>	
	
</tr>
		<?php
}
?> 	
 </tbody>     
</table>
<br><br>
<input type="hidden" id="cod_componente" name="cod_componente" value="<?=$cod_componente?>" >
<center>

<input type='button' name='crear_variable' class='btn btn-success' value='Agregar Variable'  id='crear_variable' >
		

</center>

</div>
 <form id="crea_var" action="<?= base_url('index.php/cnm_ac_variables/add') ?>" method="post" >
    <input type="hidden" id="cod_ac_var" name="cod_ac_var">
</form>

  <form id="variables" action="<?= base_url('index.php/cnm_ac_variables') ?>" method="post" >
     <input type="hidden" id="cod_acuerdo" name="cod_acuerdo" value="<?=$cod_componente?>">
</form>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
$('#tablavar').dataTable({
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
        "aaSorting": [[ 0, "asc" ]],
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
	
    $('#crear_variable').click(function(){
    	var cod_variable_crear=$("#cod_componente").val();
    		$('#cod_ac_var').val(cod_variable_crear);

 $('#crea_var').submit();

    });

        $('.atvar').click(function(){
	var cod_var=$(this).attr('atvar');
	var nombre_tasa=$(this).attr('atvar2');
        var url = "<?php echo base_url('index.php/cnm_ac_variables/delete') ?>";
        var confirmar = confirm("Desea eliminar esta variable?");
         
      
          if(confirmar == true){
          	
        $.post(url, {cod_var: cod_var})
        	.done(function(data)
        {
        	alert("Se ha eliminado la variable "+nombre_tasa);
         $('#variables').submit();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar la Variable");
        	
        	});
        
        }
    });
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
