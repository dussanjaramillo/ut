<?php 
if (isset($message)){
    echo $message;
   }
?>
 
<h1>Fiscalizaciones Asignadas</h1>





<CENTER>
				
 </CENTER>
<br>
<table id="tablaemp">
 <thead>
    <tr>

     <th>Nit</th>
     <th>Razón Social</th>
     <th>Fecha Asignación</th>
     <th>Fiscalización</th>
    </tr>
  </thead>
 <tbody>
<?php $total = 0;
foreach ($empresas->result_array as $data) { $total += 1;?> 
	<tr>


<td><?=$data['CODEMPRESA']  ?></td>	
<td><?=$data['NOMBRE_EMPRESA']  ?></td>	
<td><?=$data['FECHA_ASIGNACION']  ?></td>	
<td><input type="radio" class='at' id="nit_asig_fisc" name="nit_asig_fisc" class="btn btn-success" at ="<?=$data['CODEMPRESA']  ?>" at3 ="<?=$data['COD_ASIGNACIONFISCALIZACION']  ?>" ></td>	

		
</tr>
		<?php
}
?> 
 </tbody>  
 </table>

<br>


<CENTER>
<h2>Otras Asignaciones Fiscalización</h2>
<br>


   		NIT:<input name="nit_empresa_fisc" id="nit_empresa_fisc" size="15" value="" type="text" style="text-align:right;" class="validate[custom[onlyNumber],maxSize[9],groupRequired[dato]]">
<br>
	<a href="#abajo"><input type='button' id='consultar_empresa_asign' name='consultar_empresa_asign' class='btn btn-success' value='CONSULTAR OTRAS EMPRESAS' ></a>
</CENTER>
<br>
<div id='empresas_fisc'> </div>

<form id="f2" action="<?= base_url('index.php/fiscalizacion/datatable') ?>" method="post" >
    <input type="hidden" id="cod_nit" name="cod_nit">
    <input type="hidden" name="cod_asignacion_fisc" id="cod_asignacion_fisc">
</form>

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script type="text/javascript" language="javascript" charset="utf-8">
$(".preload, .load").hide();

$(document).ready(function(){ 

$('.at').click(function(){
$(".preload, .load").show();
var nit_asign = $(this).attr('at');
 $('#cod_nit').val(nit_asign);
var cod_asign_fisc = $(this).attr('at3');
 $('#cod_asignacion_fisc').val(cod_asign_fisc); 
  $('#f2').submit();
});	


$('#consultar_empresa_asign').click(function(){
	$(".preload, .load").show();
	var idemp=$("#nit_empresa_fisc").val();
	var urlnitemp="<?= base_url('index.php/fiscalizacion/dataTableEmpresas') ?>";
	$('#empresas_fisc').load(urlnitemp,{idemp : idemp });
});
datatable();
});	  


    $( "#nit_empresa_fisc" ).autocomplete({
      source: "<?php echo base_url("index.php/fiscalizacion/traernits") ?>",
      minLength: 3
      });

//generación de la tabla mediante json
function datatable () {
 $('#tablaemp').dataTable({
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
 		"fnInfoCallback": null
 	},
 	"aaSorting": [[ 2, "desc" ]],
 	"bLengthChange": false,
 	//"bPaginate": false,
 	"aoColumns": [
 		{ "sClass": "center" },
 		{ "sClass": "center" },
 		{ "sClass": "center" },
 		{ "sClass": "center" },
 	],
});

}





$(".preload, .load").hide();

function ajaxValidationCallback(status, form, json, options) {
}

</script>

<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>

<a name="abajo"></a>
