<h1><?php echo($segurodet[0]['NOMBRE_CARTERA'])?></h1>
  <form id="crea" action="<?= base_url('index.php/cnm_ac_componentes/add') ?>" method="post" >
    <input type="hidden" id="cod_ac_comp" name="cod_ac_comp">
</form>

<table id="tablaq">
 <thead>
    <tr>
    <th>Identificación Aseguradora</th>
  	<th>Aseguradora</th>
    <th>Fecha Inicio</th>
    <th>Fecha Fin</th>
    <th>Valor Tasa</th>
    </tr>
  </thead>
 <tbody>
<?php foreach ($segurodet as $data) {?> 
	<tr>

<td><?=$data['ID_ASEGURADORA']?></td>
<td><?=$data['ASEGURADORA']?></td>
<td><?=$data['FECHA_INI_VIGENCIA']?></td>
<td><?=$data['FECHA_FIN_VIGENCIA']?></td>
<td><?= str_replace(",", "0,", $data['VALOR_TASA'])?></td>

</tr>

<?php } ?>
 </tbody>  
 </table>

<p></p>
<p></p>
<input type="hidden" id="vista_flag" name="vista_flag" value="1" >
<input type="hidden" id="concepto" name="concepto" value="<?=($segurodet[0]['CONCEPTO'])?>" >
<input type="hidden" id="concepto_nombre" name="concepto_nombre" value="<?=($segurodet[0]['NOMBRE_CARTERA'])?>" >

<center>
<?php  echo anchor('cnm_acuerdo_ley', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); 
echo "  ";?>
<input type='button' name='crear' class='btn btn-success' value='Ingresar Nueva Vigencia'  id='crear' >
<?php echo form_close(); ?>
		

</center>
<div id="ingresar_seguro"></div>
<script type="text/javascript" language="javascript" charset="utf-8">

 $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": false,
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

          	"bLengthChange": false,
        	"aoColumns": [ 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      ]

        
    });
    
    
    $('#crear').click(function(){
 var tipo_cartera = $("#concepto").val();
  var nombre_cartera = $("#concepto_nombre").val();

var urlagregar="<?= base_url('index.php/cnm_seguros/add') ?>/";
	$('#ingresar_seguro').load(urlagregar,{ tipo_cartera : tipo_cartera,  nombre_cartera : nombre_cartera  });
});
    
    function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
