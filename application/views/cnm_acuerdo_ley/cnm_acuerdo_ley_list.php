<h1>Acuerdos de Ley</h1>
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
     <th>Id Acuerdo</th>
      <th>Modalidades</th>
     <th>Componentes Acuerdo</th>
     <th>Acuerdo de Ley</th>
     <th>Tipo de Cartera</th>
     <th>Fecha de Inicio de Vigencia</th>
   </tr>
 </thead>
 <tbody>
 <?php

foreach ($tipos->result_array as $data) {?> 
	<tr>

<td><?=$data["COD_ACUERDOLEY"]?></td>
<td><input type="radio" class="at1" name="mod" id="mod"title="Modalidades" at1="<?=$data["COD_ACUERDOLEY"]?>"></td>	
<td><a class="btn btn-small at2" title="Componentes" at2="<?=$data["COD_ACUERDOLEY"]?>"><i class="icon-random"></i></a></td>		
<td><?=$data["NOMBRE_ACUERDO"]?></td>	
<td><?=$data["NOMBRE_CARTERA"]?></td>
<td><?=$data["FECHA_INICIO"]?></td>

	
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
      echo anchor(base_url().'index.php/cnm_acuerdo_ley/add/','<i class="icon-star"></i> Crear','class="btn btn-success"');
?>
</center>

<div id="editar"></div>
</br></br></br>
<div id="modalidad_acuerdo"></div>



  <form id="componente" action="<?= base_url('index.php/cnm_ac_componentes') ?>" method="post" >
    <input type="hidden" id="cod_ac_componente" name="cod_ac_componente">
</form>

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
                      { "sClass": "center" }, 
					  ]

        
    });
});    

$('#cancelar').click(function(){
        	window.history.back()
       });  
       

    $('.at1').click(function(){
    	var cod_acuerdo=$(this).attr('at1');

	var url="<?= base_url('index.php/cnm_ac_modalidades') ?>";
	
	$('#modalidad_acuerdo').load(url,{cod_acuerdo : cod_acuerdo });
    });
       
       
       
    $('.at2').click(function(){
    	var cod_ac_componente=$(this).attr('at2');
    		$('#cod_ac_componente').val(cod_ac_componente);
 $('#componente').submit();

    });
       
    

    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
