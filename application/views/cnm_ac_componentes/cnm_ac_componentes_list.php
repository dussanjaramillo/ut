<h1>Componente</h1>
<?php
if(isset($message))
{
echo $message;	
}
?>
<br><br>
<center>
	<b>
	Acuerdo de Ley:&nbsp&nbsp<?=$infoac['NOMBRE_ACUERDO']?>&nbsp&nbsp&nbsp&nbsp&nbspTipo de Cartera:&nbsp&nbsp<?=$infoac['NOMBRE_CARTERA']?>
	</b>
</center>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>cod componente</th>
     <th>Eliminar</th>
     <th>Componente</th>
     <th>Calculo</th>
     <th>Tasa</th>
     <th>Formula Interes</th>
     <th>Aplica a</th>
     <th>Valor</th>
     <th>Ptos. Ad.</th>
   </tr>
 </thead>
 <tbody>
 <?php

foreach ($tipos->result_array as $data) {?> 
	<tr>

<td><?=$data["COD_COMPONENTE"]?></td>
<td><a class="btn btn-small at3" title="Eliminar" at3="<?=$data["COD_COMPONENTE"]?>" at4="<?=$data["NOMBRE_COMP_ESP"]?>"><i class="icon-remove	"></i></a></td>		
<td><?=$data["NOMBRE_COMP_ESP"]?></td>	
<td><?=$data["NOMBRE_CALCULO_COMP"]?></td>	
<?php if($data["CALCULO"]==1)  { ?>
<td><?=$tipo_tasa_resp[$data["TIPO_TASA"]]?></td>
<?php }elseif($data["CALCULO"]==2)  { ?>
<td><?=$tipo_tasa_h_resp[$data["TIPO_TASA"]]?></td>
<?php }else{ ?>
<td></td>
<?php }?>
<td><?=$data["NOMBRE_FORMULA_INT"]?></td>
<td><?=$data["APLICAR_A"]?></td>
<td><?=$data["VALOR"]?></td>
<td><?=$data["PUNTOS_ADICIONALES"]?></td>
	
</tr>
		<?php
}
?> 	
 </tbody>     
</table>
<br><br>

<center>
<?php  echo anchor('cnm_acuerdo_ley', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
<input type='button' name='crear' class='btn btn-success' value='Crear'  id='crear' >
		

</center>
<input type="hidden" id="cod_acuerdo_c" name="cod_acuerdo_c" value="<?=$componente?>" >



<div id="crear"></div>
<br><br>
<div id="variables"></div>

  <form id="crea" action="<?= base_url('index.php/cnm_ac_componentes/add') ?>" method="post" >
    <input type="hidden" id="cod_ac_comp" name="cod_ac_comp">
</form>

  <form id="componentes" action="<?= base_url('index.php/cnm_ac_componentes') ?>" method="post" >
     <input type="hidden" id="cod_ac_componente" name="cod_ac_componente" value="<?=$componente?>">
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
	var url="<?= base_url('index.php/cnm_ac_variables') ?>";
	
	$('#variables').load(url,{cod_acuerdo : cod_acuerdo });
    });
       


    
        $('#crear').click(function(){
    	var cod_acuerdo_crear=$("#cod_acuerdo_c").val();
    		$('#cod_ac_comp').val(cod_acuerdo_crear);
 $('#crea').submit();

    });
       
    $('.at3').click(function(){
	var cod_componente=$(this).attr('at3');
	var nombre_tasa=$(this).attr('at4');
        var url = "<?php echo base_url('index.php/cnm_ac_componentes/delete') ?>";
        var confirmar = confirm("Desea eliminar este componente?");
          if(confirmar == true){
          	
        $.post(url, {cod_componente: cod_componente})
        	.done(function(data)
        {
        	alert("Se ha eliminado el Componente "+nombre_tasa);
        	
        	 $('#componentes').submit();
        	  })
        	.fail(function(data)
        {
        alert("No se pudo Eliminar Componente");
        	
        	});
        
        }
    });
    

    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
