<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<br>
<center>
	<div class="center-form-large" width='500' height= '700'>
<table width="780" class="tabla1">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td><b>Nombre Empleado:</td>
    <td><?=$empleado['NOMBRES']." ".$empleado['APELLIDOS'] ?></td>
    <td><b>Identificacion Empleado:</td>
    <td><?=$empleado['IDENTIFICACION'] ?></td>
</tr>
       <tr>
    <td><b>Direccion:</td>
    <td><?=$empleado['DIRECCION'] ?></td>
    <td><b>Celular:</td>
    <td><?=$empleado['TELEFONO_CELULAR'] ?></td>

  </tr>
<tr>
    <td><b>Telefono Contacto:</td>
    <td><?=$empleado['TELEFONO'] ?></td>
    <td><b>Correo:</td>
    <td><?=$empleado['CORREO_ELECTRONICO'] ?></td>  
  </tr>
     <tr>
    <td><b>Regional:</td>
    <td><?=$empleado['COD_REGIONAL'] ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>


  </tr>
  
  
</table>
</br>
</br>

</center>
<table id="tablaq">
 <thead>
    <tr>
	 <th></th>
     <th>Codigo Bien</th>
     <th>Placa Inventario</th>
     <th>Descripción</th>
	<th>Regional</th>
	<th>Motivo Baja</th>
	<th>Valor Compra</th>
	<th>Valor Depreciación</th>
	<th>Valor por Depreciar</th>
	<th>Valor Deducible</th>
	<th>Fecha Baja</th>


    </tr>
  </thead>
 <tbody>
<?php foreach ($cargas->result_array as $data) {?> 
	
	<tr>

<td><input type="radio" class='at'  id="n_orden" name="n_orden" class="btn btn-success" at ="<?=$data['COD_ACTIVOS_BAJA']  ?>" ></td>	
</td>
<td><?=$data['CODIGO_BIEN'] ?></td>
<td><?=$data['PLACA_INVENATARIO'] ?></td>
<td><?=$data['DESCRIPCION_ELEMENTO'] ?></td>
<td><?=$data['COD_REGIONAL'] ?></td>
<td><?=$data['MOTIVO_BAJA'] ?></td>
<td><?=$data['VALOR_COMPRA'] ?></td>
<td><?=$data['VALOR_DEPRECIACION'] ?></td>
<td><?=$data['VALOR_X_DEPRECIAR'] ?></td>
<td><?=$data['VALOR_DEDUCIBLE'] ?></td>
<td><?=$data['FECHA_BAJA'] ?></td>

</tr>

<?php }?> 

 
 </tbody>  
 </table>
 
 
 <br>
 <center>
 	 <div id="alerta2"></div>	
 	</br>
 	<input type="button" id="crear" name="crear" title="Crear" value="Crear" class="btn btn-success">
  	
</div>
  
  </center>

<form id="crear_c" action="<?= base_url('index.php/cnm_responsabilidad_bienes/creacion') ?>" method="post" >
    <input type="hidden" id="id_carga" name="id_carga">
    <input type="hidden" name="cod_activos" id="cod_activos" value="<?=$cod_activos?>">
</form>

<script type="text/javascript" language="javascript" charset="utf-8">

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

          	"bLengthChange": false,
        	"aoColumns": [ 

 						{ "sClass": "center" }, 
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
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
    
         $('.at').click(function(){
	var cod_cargue = $(this).attr('at');
	$('#id_carga').val(cod_cargue);
}); 
    
         $('#crear').click(function(){
    if ($("#id_carga").val().length < 1) 
       {
       	$("#alerta2").show();
      $('#alerta2').html('<p class="text-warning"><i class="fa fa-warning"></i> Seleccione Un Registro Para Crear La Deuda<p>');
     $('.at').focus();
       return false;
  }
  	$("#alerta2").hide();
  $('#crear_c').submit();
});
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
</style> 