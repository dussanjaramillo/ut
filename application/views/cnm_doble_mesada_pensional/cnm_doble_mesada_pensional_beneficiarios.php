
<?php
if(isset($message))
{
echo $message;	
}
?>

<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Consulta Doble Mesada Pensional</h2>

<table width="780">
  
<tr>
  	<td>&nbsp;</td>
    <td><b>Nombre Empleado</td>
   <td><input type="text" id="nombre_emp" name="nombre_emp" readonly="readonly" style="width : 80%;" value="<?=$detcartera["NOMBRES"]." ".$detcartera["APELLIDOS"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Identificación Empleado</td>
    <td><input type="text" id="identificacion" name="identificacion" readonly="readonly" style="width : 80%;" value="<?=$detcartera["IDENTIFICACION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Teléfono</td>
    <td><input type="text" id="telefono" name="telefono" readonly="readonly" style="width : 80%;" value="<?=$detcartera["TELEFONO_CELULAR"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  <br>
    <tr>
  	<td>&nbsp;</td>
   	<td><b>Correo Electrónico</td>
    <td><input type="text" id="correo" name="correo" readonly="readonly" style="width : 80%;" value="<?=$detcartera["CORREO_ELECTRONICO"]?>"></td>
     <td>&nbsp;</td>
    <td><b>Identificacion Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" readonly="readonly" style="width : 80%;" value="<?=$detcartera["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Regional</td>
    <td><input type="text" id="regional" name="regional" readonly="readonly" style="width : 80%;" value="<?=$detcartera["NOMBRE_REGIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>
  <br>
    
 </table>
  </br>
  </br>

 <table id="tablabeneficiarios" width="500" class="table table-bordered" border="1">
 <thead class="btn-success">
    <tr>
  <th>IDENTIFICACIÓN</th>
  <th>NOMBRES Y APELLIDOS</th>
  <th>PARENTESCO</th>
    </tr>
  </thead>
 <tbody>
<?php
foreach ($beneficiarios->result_array as $data) {?> 
	<tr>

<td style="background-color: #f9f9f9;" border="1"><?=$data['IDENTIFICACION_BENEFICIARIO']  ?></td>
<td style="background-color: #f9f9f9;" border="1"><?=$data['NOMBRES']  ?></td>	
<td style="background-color: #f9f9f9;" border="1"><?=$data['PARENTESCO']  ?></td>	

</tr>
<?php
} ?>

 </tbody>  
 </table>
  
 </div>


 
 <p>
<center>	 
<input type="hidden" id="vista" name="vista" value="1" >
    <input type="hidden" id="id_cartera_form" name="id_cartera_form" value="<?=$id_cartera ?>">
    <input type="hidden" id="tipo_cartera" name="tipo_cartera" value="<?=$tipo_cartera?>" >

<input type='button' name='add_beneficiario' class='btn btn-success' value='AGREGAR BENEFICIARIO'  id='add_beneficiario' >
<input type='button' name='continuar' class='btn btn-success' value='CONTINUAR'  id='continuar' >

</center>
   </p> 
   
  <form id="beneficiarios" action="<?= base_url('index.php/carteranomisional/carga_archivo_edit') ?>" method="post" >

</form>
  
   
<div id='agregar_beneficiario'> </div> 
<script type="text/javascript" language="javascript" charset="utf-8">
 $('#continuar').click(function(){
 $('#beneficiarios').submit();
});
 $('#add_beneficiario').click(function(){
	var urlconsultarcnm="<?= base_url('index.php/cnm_doble_mesada_pensional/add_beneficiario') ?>/";
	$('#agregar_beneficiario').load(urlconsultarcnm);

});

 
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
