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
     <th>Letra</th>
     <th>Nombre Beneficiario</th>
     <th>Vigencia</th>
	<th>Regional</th>
	<th>Fecha Orden</th>
	<th>Orden</th>
	<th>Valor Orden</th>
	<th>Estado</th>
	<th>Contratista</th>
	<th>Valor Excedido</th>
	<th>Valor A Reintegrar</th>
	<th>Recibo de Pago</th>
	<th>Fecha de Pago</th>

    </tr>
  </thead>
 <tbody>
<?php foreach ($cargas->result_array as $data) {?> 
	
	<tr>

<td><input type="radio" class='at'  id="n_orden" name="n_orden" class="btn btn-success" at ="<?=$data['COD_CARGA']  ?>" ></td>	
</td>
<td><?=$data['LETRA'] ?></td>
<td><?=$data['NOMBRE_BENEFICIARIO'] ?></td>
<td><?=$data['VIGENCIA'] ?></td>
<td><?=$data['COD_REGIONAL'] ?></td>
<td><?=$data['FECHA_ORDEN'] ?></td>
<td><?=$data['ORDEN'] ?></td>
<td><?=$data['VALOR_ORDEN'] ?></td>
<td>&nbsp;</td>
<td><?=$data['CONTRATISTA'] ?></td>
<td><?=$data['VALOR_EXCEDIDO'] ?></td>
<td><?=$data['VALOR_REINTEGRAR'] ?></td>
<td><?=$data['RECIBO_PAGO'] ?></td>
<td>&nbsp;</td>


</tr>

<?php }?> 

 
 </tbody>  
 </table>
 <br>
 <center>
 	
 	<input type="button" id="crear" name="crear" title="Crear" value="Crear" class="btn btn-success">
  	
</div>
  
  </center>

<form id="crear_c" action="<?= base_url('index.php/cnm_servicio_medico/creacion') ?>" method="post" >
    <input type="hidden" id="id_carga" name="id_carga">
    <input type="hidden" name="cod_servicio_medico" id="cod_servicio_medico" value="<?=$cod_servicio_medico?>">
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
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "center" }, 

                     
                    
                      ]

        
    });
            
     $('.at').click(function(){
	var cod_cargue = $(this).attr('at') ;
	$('#id_carga').val(cod_cargue);
});       

     $('#crear').click(function(){

  $('#crear_c').submit();
});
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

<style type="text/css"> 
table.tabla1 { border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px} 
</style> 