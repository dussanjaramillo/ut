<h1>Fiscalización</h1>
<div>

<?php
if(isset($message))
{
echo $message;	
}
?>	

<CENTER>

<table>
<tr><td>&nbsp;</td>	</tr>
          <tr>
    <td><b>NIT:</td>
    <td><?=$empresa['CODEMPRESA']?></td>
    <td>&nbsp;</td>	
    <td><b>RAZÓN SOCIAL:</td>
    <td><?=$empresa['NOMBRE_EMPRESA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
     <tr>
    <td><b>TELÉFONO FIJO:</td>
    <td><?=$empresa['TELEFONO_FIJO']?></td>
    <td>&nbsp;</td>	
    <td><b>REPRESENTANTE LEGAL:&nbsp;&nbsp;</td>
    <td><?=$empresa['REPRESENTANTE_LEGAL']?></td>
    <td>&nbsp;</td>
  </tr> 
   <tr><td>&nbsp;</td>	</tr> 
  <tr>
    <td><b>TELÉFONO CELULAR:</td>
    <td><?=$empresa['TELEFONO_CELULAR']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>DIRECCIÓN:</td>
    <td><?=$empresa['DIRECCION']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <tr>
    <td><b>E-MAIL:</td>
    <td><?=$empresa['CORREOELECTRONICO']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><b>ACTIVIDAD ECONÓMICA:</td>
    <td><?=$empresa['ACTIVIDADECONOMICA']?></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
  
    <tr>
    <td><b>NÚMERO EMPLEADOS:</td>
    <td><?=$empresa['NUM_EMPLEADOS']?></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
  </tr> 
  <tr><td>&nbsp;</td>	</tr> 
      
</table>	 	
	
</CENTER>

<?php
if ($this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_A']||$this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_POR'])
{
?>
	<input type='button' name='agregar_fisc' class='btn btn-success' value='Agregar'  id='fisc' at_fisc ="<?=$cod_asign_fisc?>">
<?php
}

        echo form_open(current_url()); 
?>
<br>

<p></p>
<a name="1"></a>
<table id="tablaq">
 <thead>
    <tr>
		<th>Id</th>
     <th>Periodo Inicial</th>
     <th>Periodo Final</th>
     <th>Concepto</th>
     <th>Tipo Gestión</th>
     <th>Usuario</th>
     <th>No Expediente</th>
     <th>Visualizar Gestión</th>
     <th>Novedades</th>
    </tr>
  </thead>
 <tbody>
<?php $total = 0;
foreach ($funcion->result_array as $data) { $total += 1; ?> 
	<tr>

<td><?=$data['NRO_EXPEDIENTE']  ?></td>
<td><?=$data['PERIODO_INI']  ?></td>	
<td><?=$data['PERIODO_FIN']  ?></td>	
<td><?=$data['NOMBRE_CONCEPTO']  ?></td>	
<td><?=$data['TIPOGESTION']  ?></td>	
<td><?=$data['NOMBREUSUARIO']  ?></td>	
<td><?=$data['NO_EXPEDIENTE']  ?></td>	
<td><a href="#2"><input type="radio" class='at'  id="cod_asign" name="cod_asign" class="btn btn-success" at ="<?=$data['COD_FISCALIZACION']  ?>" ></a></td>	
<?php
if ($data['COD_TIPOGESTION']!='309'&&$data['COD_TIPOGESTION']!='10'&&$data['FIN_FISCALIZACION']!='S'&&$data['COD_TIPOGESTION']!='4'&&$data['COD_TIPOGESTION']!='8'&&$data['COD_TIPOGESTION']!='509')
{
if ($this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_A']||$this->ion_auth->user()->row()->IDUSUARIO==$validacion['ASIGNADO_POR'])
{
?>
<td><a  at2 ="<?=$data['COD_FISCALIZACION']   ?>"  at3 ="<?=$data['NIT_EMPRESA']   ?>"  at4 ="<?=$data['COD_CONCEPTO']   ?>" class="btn btn-small at2" title="Novedad" id="codigo_fiscalizacion" name="codigo_fiscalizacion"><i class="icon-edit"></i></a></td>	
		<?php
}else{
?> 
<td><a class="btn btn-small" title="Novedad" disabled="disabled"><i class="icon-edit"></i></a></td>	
		<?php
}
}
else{
?> 
<td><a class="btn btn-small" title="Novedad" disabled="disabled"><i class="icon-edit"></i></a></td>	
		<?php
}
?> 		
</tr>
		<?php
}
?> 
 </tbody>  
 </table>
</br>
 <center>
<?php  echo anchor('fiscalizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
</center>
    <?php echo form_close(); ?>	
 
 <div id='loaddataTable2'> </div>
 <div id='novedad'> </div>
 </div>
<form id="f1" action="<?= base_url('index.php/fiscalizacion/add') ?>" method="post" >
    <input type="hidden" id="cod_asign" name="cod_asign" value="<?=$cod_asign_fisc?>" >
     <input type="hidden" id="nit" name="nit" value="<?=$empresa['CODEMPRESA']?>" >
</form>
 <div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<script >

	$('#validar_soporte').dialog('close');

$(".preload, .load").hide();
$(document).ready(function(){  
	
$('#fisc').click(function(){
$(".preload, .load").show();
 $('#f1').submit();
});

$('.at').click(function(){
	$(".preload, .load").show();
	var id=$(this).attr('at') ;
	var url="<?= base_url('index.php/fiscalizacion/dataTableGestion') ?>";
	
	$('#loaddataTable2').load(url,{id : id });
});

$('.at2').click(function(){
	$(".preload, .load").show();
	var cod_fisc=$(this).attr('at2') ;
	var nit=$(this).attr('at3') ;
	var cod_concepto=$(this).attr('at4') ;
	var url2="<?= base_url('index.php/fiscalizacion/novedades') ?>/";
	$('#novedad').load(url2,{cod_fisc : cod_fisc ,nit : nit ,cod_concepto : cod_concepto });

});
datatable();  
});	

function datatable () {
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
                    
                      ]

        
    });
}    
    
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

<a name="2"></a>