<h1>Rango Tipo Tasa de Interés Histórico</h1>
<?php
if(isset($message))
{
echo $message;	
}
?>
<center>
	<b>
	Tipo Tasa Histórico:&nbsp&nbsp<?=$informacion['NOMBRE_TIPOTASA']?>&nbsp&nbsp&nbsp&nbsp&nbspTipo De Tasa:&nbsp&nbsp<?=$informacion['NOMBRE_TASAINTERES']?>
	</b>
</center>

<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id Rango Tipo Tasa Historica</th>
     <th>Eliminar</th>
     <th>Fecha Inicial de Vigencia</th>
     <th>Fecha Final de Vigencia</th>
     <th>Valor Tasa</th>


   </tr>
 </thead>
 <tbody>
 <?php

foreach ($tipos->result_array as $data) {?> 
	<tr>

<td><?=$data["COD_RANGOTASA"]?></td>
<td><a class="btn btn-small at2" title="Eliminar" at2="<?=$data["COD_RANGOTASA"]?>" ><i class="icon-remove"></i></a></td>	
<td><?=$data["FECHA_INI"]?></td>	

<td><?=$data["FECHA_FIN"]?></td>	
<td><?=$data["VALOR_TASA"]?></td>	
</tr>
		<?php
}
?> 	
 </tbody>     
</table>
<br><br>

<center>
	<input type="hidden" id="cod_tasa_historica" name="cod_tasa_historica" value="<?=$cod_tasa_hist?>">
	
	 <?php  echo anchor('cnm_tipo_tasa_interes_historic', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
    
    <input type='button' name='nuevo' class='btn btn-success' value='Crear Nuevo Rango'  id='nuevo' >

</center>
  <form id="rangotasa" action="<?= base_url('index.php/cnm_rango_tipo_tasa_interes_historica') ?>" method="post" >
     <input type="hidden" id="cod_tasa_hist" name="cod_tasa_hist" value="<?=$cod_tasa_hist?>">
</form>

<div id="editar"></div>
<div id="rangos"></div>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
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


       
$('#nuevo').click(function(){
         var cod_tasa_historica = $("#cod_tasa_historica").val();
var urlcrearrango="<?= base_url('index.php/cnm_rango_tipo_tasa_interes_historica/add') ?>/";
	$('#rangos').load(urlcrearrango,{cod_tasa_historica : cod_tasa_historica });
       });  
       
    $('.at2').click(function(){
	var cod_rango_tasa =$(this).attr('at2');
var cod_tasa_hist=$("#cod_tasa_hist").val();
		
        var url = "<?php echo base_url('index.php/cnm_rango_tipo_tasa_interes_historica/delete') ?>";
        var confirmar = confirm("Desea eliminar este Rango?");
          if(confirmar == true){
          	
        $.post(url, {cod_rango_tasa: cod_rango_tasa})
        	.done(function(data)
        {
        	alert("Se ha eliminado el rango seleccionado ");
 $('#rangotasa').submit();

        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar el rango seleccionado");
        	
        	});
        
        }
    });
    

    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
