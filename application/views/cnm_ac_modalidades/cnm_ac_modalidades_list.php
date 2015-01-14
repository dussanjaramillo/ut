
<?php
if(isset($message))
{
echo $message;	
}
?>

<div class="center-form">
	<center><h3>Modalidades</h3></center>
<br><br>
<table id="tablavar">
 <thead>
    <tr>
     <th>cod variable</th>
     <th>Eliminar</th>
     <th>Modalidad</th>

     
   </tr>
 </thead>
 <tbody>
 <?php

foreach ($modalidades->result_array as $data) {?> 
	<tr>

<td><?=$data["COD_MODALIDAD"]?></td>
<td><a class="btn btn-small atmod" title="Eliminar" atmod="<?=$data["COD_MODALIDAD"]?>" atmod2="<?=$data["NOMBRE_MODALIDAD"]?>"><i class="icon-remove	"></i></a></td>		
<td><?=$data["NOMBRE_MODALIDAD"]?></td>	

	
</tr>
		<?php
}
?> 	
 </tbody>     
</table>
<br><br>
<input type="hidden" id="cod_acuerdo" name="cod_acuerdo" value="<?=$cod_acuerdo?>" >
<center>

<input type='button' name='crear_modalidad' class='btn btn-success' value='Agregar Modalidad'  id='crear_modalidad' >
		

</center>

</div>
 <form id="crea_modal" action="<?= base_url('index.php/cnm_ac_modalidades/add') ?>" method="post" >
    <input type="hidden" id="cod_ac_mod" name="cod_ac_mod">
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
            "sInfo":           "Mostrando _TOTAL_ registros",
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


					  
					  ]

        
    });
});    

$('#cancelar').click(function(){
        	window.history.back()
       });  
	
    $('#crear_modalidad').click(function(){
    	var cod_modalidad_crear=$("#cod_acuerdo").val();
    		$('#cod_ac_mod').val(cod_modalidad_crear);

 $('#crea_modal').submit();

    });

        $('.atmod').click(function(){
	var cod_mod=$(this).attr('atmod');
	var nombre_mod=$(this).attr('atmod2');
        var url = "<?php echo base_url('index.php/cnm_ac_modalidades/delete') ?>";
        var confirmar = confirm("Desea eliminar esta Modalidad?");
         
      
          if(confirmar == true){
          	
        $.post(url, {cod_mod: cod_mod})
        	.done(function(data)
        {
        	alert("Se ha eliminado la Modalidad "+nombre_mod);
        	window.location.reload();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar la Modalidad");
        	
        	});
        
        }
    });
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
