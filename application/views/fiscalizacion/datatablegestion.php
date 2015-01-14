<div>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<h1>Gestión Cobro</h1>

</head>
<body>

<br>
<table id="gestionq">
 <thead>
    <tr>
	<th>FECHA 2</th>	
     <th>USUARIO</th>
     <th>TIPO GESTIÓN</th>
     <th>RESPUESTA</th>
     <th>FECHA</th>
     <th>RADICADO ONBASE </th>
     <th>FECHA RADICADO</th>
     <th>COMENTARIOS</th>
     <th>SOPORTE</th>
     <th>RESPONDER</th>
     
   </tr>
 </thead>
 <tbody>
<?php
$total = 0;
foreach ($funcion->result_array as $data) {$total += 1;?> 
	<tr>

<td><?=$data['COD_GESTION_COBRO'] ?></td>
<td><?=$data['NOMBREUSUARIO']  ?></td>	
<td><?=$data['TIPOGESTION']  ?></td>	
<td><?=$data['NOMBRE_GESTION']  ?></td>	
<td><?=$data['FECHA_CONTACTO']  ?></td>	
<?php 
if(empty($data['NRO_RADICADO_ONBASE']) )
{
?>

<?php
if ($this->ion_auth->is_admin()||$this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_A']||$this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_POR'])
{
	if($data['COD_RESPUESTA']=='6'||$data['COD_RESPUESTA']=='10')
	{
?>
<td class="onbase" onbase="<?=$data['COD_GESTION_COBRO'] ?>"><input type='button' class="onbase" onbase="<?=$data['COD_GESTION_COBRO']?>" value="AGREGAR"></td>
<td class="onbase" onbase="<?=$data['COD_GESTION_COBRO'] ?>"></td>	
<?php
	}else{
?>	
<td><?=$data['NRO_RADICADO_ONBASE'] ?></td>	
<td><?=$data['FECHA_RADICADO'] ?></td>		
<?php		
		
	}
	
	}else{
?>	
<td><?=$data['NRO_RADICADO_ONBASE'] ?></td>	
<td><?=$data['FECHA_RADICADO'] ?></td>		
<?php
}
?>	
<?php
}else{
?>
<td><?=$data['NRO_RADICADO_ONBASE'] ?></td>	
<td><?=$data['FECHA_RADICADO'] ?></td>	
<?php
}
if($data['COD_RESPUESTA']=='2'||$data['COD_RESPUESTA']=='1031'||$data['COD_RESPUESTA']=='880'||$data['COD_RESPUESTA']=='652'||$data['COD_RESPUESTA']=='12'||$data['COD_RESPUESTA']=='13'||$data['COD_RESPUESTA']=='14'
||$data['COD_RESPUESTA']=='15'){
?>
<td><?=base64_decode($data['COMENTARIOS'])  ?></td>	
<?php
}
else{
?>
<td><?=$data['COMENTARIOS']?></td>	
<?php
}

?>
<?php if($data['COD_RESPUESTA']=='3'){?>
	
<td><input type="radio" class='atdocumento'  id="cod_doc" name="cod_doc" class="btn btn-success"  atdocumento ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
<?php }
elseif($data['COD_RESPUESTA']=='9'){
?> 
<td><input type="radio" class='atautorizacion_noti'  id="cod_doc" name="cod_doc" class="btn btn-success"  atautorizacion_noti ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='6'){
?> 
<td><input type="radio" class='atnotivisita'  id="cod_doc" name="cod_doc" class="btn btn-success"  atnotivisita ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='10'){
?> 
<td><input type="radio" class='atcom_pr_sena'  id="cod_doc" name="cod_doc" class="btn btn-success"  atcom_pr_sena ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='7'){
?> 
<td><input type="radio" class='at_inf_visita'  id="cod_doc" name="cod_doc" class="btn btn-success"  at_inf_visita ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='13'||$data['COD_RESPUESTA']=='14'||$data['COD_RESPUESTA']=='15'){
?> 
<td><input type="radio" class='at_soporte_pago'  id="cod_doc" name="cod_doc" class="btn btn-success"  at_soporte_pago ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='12'){
?> 
<td><input type="radio" class='at_soporte_pago_ingreso'  id="cod_doc" name="cod_doc" class="btn btn-success"  at_soporte_pago_ingreso ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='11'){
?> 
<td><input type="radio" class='at_acta_via_gub'  id="cod_doc" name="cod_doc" class="btn btn-success"  at_acta_via_gub ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
elseif($data['COD_RESPUESTA']=='652'){
?> 
<td><input type="radio" class='archivo'  id="cod_doc" name="cod_doc" class="btn btn-success"  archivo ="<?=$data['COD_GESTION_COBRO']  ?>" ></td>
		<?php
}
else{
?> 
<td><input type="radio" class="btn btn-success"  atdocumento =""  disabled="disabled"></td>
		<?php
}
?>



<?php

if ($validacion['COD_TIPOGESTION']!=309)
{
if ($this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_A']||$this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_POR'])
{
?>
<?php
if($data['COD_RESPUESTA']=='4'&&$data['COD_GESTION_COBRO']==$data['COD_GESTIONACTUAL']){
?> 
<input type="hidden" id="programar_vist" name="programar_vist" value="<?=$data['COD_GESTION_COBRO']  ?>" >	
<td><a class="btn btn-small" title="Programar" id="atprogrvisita" name="atprogrvisita"  ><i class="icon-edit"></i></a></td>				
<?php
}
elseif($validacion['COD_TIPOGESTION']=='509'&&$data['COD_GESTION_COBRO']==$data['COD_GESTIONACTUAL']&&$data['COD_RESPUESTA']=='10'){
?> 
<input type="hidden" id="present_sena" name="present_sena" value="<?=$data['COD_GESTION_COBRO']  ?>" >	
<td><a class="btn btn-small" title="Descripción Visita" id="presentv" name="presentv"  ><i class="icon-edit"></i></a></td>				
<?php
}
elseif($validacion['COD_TIPOGESTION']=='509'&&$data['COD_GESTION_COBRO']==$data['COD_GESTIONACTUAL']&&$data['COD_RESPUESTA']=='6'){
?> 
<input type="hidden" id="informe_vist" name="informe_vist" value="<?=$data['COD_GESTION_COBRO']  ?>" >	
<td><a class="btn btn-small" title="Elaborar Informe" id="informevisita" name="informevisita"  ><i class="icon-edit"></i></a></td>				
<?php
}elseif($this->ion_auth->is_admin()&&$data['COD_RESPUESTA']=='12'&&$data['COD_GESTION_COBRO']==$data['COD_GESTIONACTUAL']){
?> 
<input type="hidden" id="cod_gestion" name="cod_gestion" value="<?=$data['COD_GESTION_COBRO']  ?>" >	
<td><a class="btn btn-small atsop" title="Aprobacion" id="aprobacion_soporte" name="aprobacion_soporte" atsop="<?=$data['COD_GESTION_COBRO']  ?>" ><i class="icon-edit"></i></a></td>			
<?php
}else{
?> 
<td><a class="btn btn-small" title="Novedad" disabled="disabled"><i class="icon-edit"></i></a></td>	

		<?php
}
?> 

<?php
}
elseif($data['COD_RESPUESTA']=='12'&&$this->ion_auth->in_menu('fiscalizacion/aprobacion_soporte')&&$data['COD_GESTION_COBRO']==$data['COD_GESTIONACTUAL']){
?> 
<input type="hidden" id="cod_gestion" name="cod_gestion" value="<?=$data['COD_GESTION_COBRO']  ?>" >	
<td><a class="btn btn-small atsop" title="Aprobacion" id="aprobacion_soporte" name="aprobacion_soporte" atsop="<?=$data['COD_GESTION_COBRO']  ?>" ><i class="icon-edit"></i></a></td>			

<?php
}
else{
?> 
<td></td>
<?php
}

}
else{
?> 
<td></td>
<?php
}
?> 

</tr>
		<?php
}
?> 
 </tbody>  
 </table>
 <div id='aprobacion'> </div>
 <div id='ver_correo'> </div>
  <div id='ver_autorizacion_noti_email'> </div>
    <div id='ver_notificacion_visita'> </div>
    <div id='ver_comunicacion_presentarse_sena'> </div>
    <div id='ver_informe_visita'> </div>
    <div id='ver_sop_pago'> </div>
    <div id='ver_acta_gub_sena'> </div>
    <div id='ver_archivo'> </div>
    <div id='presentarse_sena_form'> </div> 
	<div id='registro_onbase_div'> </div> 
    </div>
     
  <form id="generar_doc_visita" action="<?= base_url('index.php/fiscalizacion/consulta_generar_documento_visita_ofi') ?>" method="post" >
    <input type="hidden" id="cod_gestion_programar_visita" name="cod_gestion_programar_visita" >
</form>

  <form id="elaborar_informe_visita" action="<?= base_url('index.php/fiscalizacion/agregar_documentos_informe_visita') ?>" method="post" >
    <input type="hidden" id="cod_gestion_elaborar_informe_visita" name="cod_gestion_elaborar_informe_visita" >
</form>

  <form id="elaborar_liquidacion" action="<?= base_url('index.php/liquidaciones') ?>" method="post" >
    <input type="hidden" id="cod_gestion_liquidacion" name="cod_gestion_liquidacion" >
</form>

 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</body>
<script >
$(".preload, .load").hide();

$(document).ready(function(){
$('.onbase').click(function(){
	var cod_gestion_cobro= $(this).attr('onbase');
	var urlonbase="<?= base_url('index.php/fiscalizacion/registro_onbase') ?>/";
	$('#registro_onbase_div').load(urlonbase,{cod_gestion_cobro : cod_gestion_cobro});

});

$('.atsop').click(function(){
	var cod_gestion=$(this).attr('atsop');
	var url3="<?= base_url('index.php/fiscalizacion/aprobacion_soporte') ?>/";
	$('#aprobacion').load(url3,{cod_gestion : cod_gestion});

});


$('#atprogrvisita').click(function(){
	$(".preload, .load").show();
	var cod_gestion=$("#programar_vist").val();
	$('#cod_gestion_programar_visita').val(cod_gestion);
 $('#generar_doc_visita').submit();


});

$('#informevisita').click(function(){
	$(".preload, .load").show();
	var cod_gestion=$("#informe_vist").val();
	$('#cod_gestion_elaborar_informe_visita').val(cod_gestion);
 $('#elaborar_informe_visita').submit();


});



$('#presentv').click(function(){
	$(".preload, .load").show();
	var cod_gestion=$("#present_sena").val();
	var urlpresenciasena="<?= base_url('index.php/fiscalizacion/detalle_asistencia_sena') ?>/";
	$('#presentarse_sena_form').load(urlpresenciasena,{cod_gestion : cod_gestion});
});

$('#liquidacion').click(function(){
	$(".preload, .load").show();
	var cod_gestion=$("#liquid").val();
	$('#cod_gestion_liquidacion').val(cod_gestion);
 $('#elaborar_liquidacion').submit();


});

$('.atdocumento').click(function(){
	$(".preload, .load").show();
	var cod_gestion_cobro= $(this).attr('atdocumento');

	var urlcorreover="<?= base_url('index.php/fiscalizacion/ver_correo') ?>/";
	$('#ver_correo').load(urlcorreover,{cod_gestion_cobro : cod_gestion_cobro});

});

$('.atautorizacion_noti').click(function(){
	$(".preload, .load").show();
	var cod_gestion_cobro= $(this).attr('atautorizacion_noti');

	var urlcorreover="<?= base_url('index.php/fiscalizacion/ver_autorizacion_noti') ?>/";
	$('#ver_autorizacion_noti_email').load(urlcorreover,{cod_gestion_cobro : cod_gestion_cobro});

});

$('.atnotivisita').click(function(){
	$(".preload, .load").show();
	var notivisita= $(this).attr('atnotivisita');

	var urlnotivisit="<?= base_url('index.php/fiscalizacion/ver_notificacion_visita') ?>/";
	$('#ver_notificacion_visita').load(urlnotivisit,{notivisita : notivisita});

});



$('.atcom_pr_sena').click(function(){
	$(".preload, .load").show();
	var com_sena= $(this).attr('atcom_pr_sena');

	var urlcom_pr_sena="<?= base_url('index.php/fiscalizacion/ver_comunicacion_sena') ?>/";
	$('#ver_comunicacion_presentarse_sena').load(urlcom_pr_sena,{com_sena : com_sena});

});

$('.at_inf_visita').click(function(){
	$(".preload, .load").show();
	var com_sena= $(this).attr('at_inf_visita');

	var urlinfv="<?= base_url('index.php/fiscalizacion/ver_informe_visita') ?>/";
	$('#ver_informe_visita').load(urlinfv,{com_sena : com_sena});

});

$('.at_soporte_pago').click(function(){
	$(".preload, .load").show();
	var sop_p= $(this).attr('at_soporte_pago');

	var urlsopp="<?= base_url('index.php/fiscalizacion/ver_soporte_pago') ?>/";
	$('#ver_sop_pago').load(urlsopp,{sop_p : sop_p});

});

$('.at_soporte_pago_ingreso').click(function(){
	$(".preload, .load").show();
	var sop_p= $(this).attr('at_soporte_pago_ingreso');

	var urlsopp="<?= base_url('index.php/fiscalizacion/ver_soporte_pago_ingreso') ?>/";
	$('#ver_sop_pago').load(urlsopp,{sop_p : sop_p});

});


$('.at_acta_via_gub').click(function(){
	$(".preload, .load").show();
	var acta_gub_sena= $(this).attr('at_acta_via_gub');

	var urlinfv="<?= base_url('index.php/fiscalizacion/ver_comunicacion_acta_gub') ?>/";
	$('#ver_acta_gub_sena').load(urlinfv,{acta_gub_sena : acta_gub_sena});

});

$('.archivo').click(function(){
	$(".preload, .load").show();
	var archivo_gest= $(this).attr('archivo');

	var urlinfv="<?= base_url('index.php/fiscalizacion/ver_archivo') ?>/";
	$('#ver_archivo').load(urlinfv,{archivo_gest : archivo_gest});

	});
	datatable();
});

function datatable () {
 $('#gestionq').dataTable({
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
					  { "sClass": "center" }, 
                      { "sClass": "center" }, 
                      ]

            });
            
    }
    
        function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

 
