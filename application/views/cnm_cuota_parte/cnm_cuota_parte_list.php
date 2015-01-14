<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large" width='500' height= '700'>
<center>
<h2>Consulta Cartera Cuotas Partes</h2>

<table width="780" class="tabla2">
  
  <tr>
  	<td>&nbsp;</td>	
  	
  	</tr>
  <tr>
  	<td>&nbsp;</td>
    <td><b>Identificación del Tercero</td>
    <td><input type="text" id="cod_empresa" name="cod_empresa" style="width : 91%;" required="required" readonly="readonly" value="<?=$carteras["IDENTIFICACION"]?>"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Razón Social</td>
    <td><input type="text" id="razon_social" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRES"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Sigla</td>
    <td><input type="text" id="sigla" style="width : 91%;" readonly="readonly" value="<?=$carteras["SIGLA"]?>"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
	<td>&nbsp;</td>
    <td><b>Dirección</td>
    <td><input type="text" id="direccion" style="width : 91%;" readonly="readonly" value="<?=$carteras["DIRECCION"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Teléfono Contacto</td>
    <td><input type="text" id="telefono" style="width : 91%;" readonly="readonly" value="<?=$carteras["TELEFONO"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td><b>Correo Electrónico</td>
    <td><input type="text" id="correo_electronico" style="width : 91%;" readonly="readonly" value="<?=$carteras["CORREO_ELECTRONICO"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Regional</td>
    <td><input type="text" id="regional" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_REGIONAL"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
   
   </table>
   
   <table width="780" border="0">
	 <tr>
	 	<td>&nbsp;</td>	
	 	</tr>
	    <tr>
    <td>&nbsp;</td>	
    <td><b>Tipo de Cartera</td>
    <td><input type="text" id="tipo_cartera" name="tipo_cartera" style="width : 91%;" readonly="readonly" value="<?=$carteras["NOMBRE_CARTERA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Identificacion Deuda</td>
    <td><input type="text" id="id_deuda" name="id_deuda" style="width : 91%;" readonly="readonly" value="<?=$carteras["COD_CARTERA_NOMISIONAL"]?>"></td>
    <td>&nbsp;</td>
  </tr>

 
          <tr>
    <td>&nbsp;</td>	
    <td><b>Estado</td>
	<td><input type="text" id="tipo_estado_id" name="tipo_estado_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["DESC_EST_CARTERA"]?>"></td>
    <td>&nbsp;</td>
    <td><b>Fecha Activación</td>
    <td><input type="text" id="fecha_activacion" name="fecha_activacion" style="width : 91%;" readonly="readonly" value="<?=$carteras["FECHA_ACTIVACION"]?>"></td>
  	<td>&nbsp;</td>
  </tr>
  
      <tr>
    <td>&nbsp;</td>	
    <td><b>Valor Deuda:</td>
    <td><input type="text" id="valor_deuda" name="valor_deuda" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["VALOR_DEUDA"], 0, ',', '.')?>"></td>
    <td>&nbsp;</td>
    <td><b>Saldo Deuda:</td>
    <td><input type="text" id="saldo_deuda" name="saldo_deuda" style="width : 91%;" readonly="readonly" value="<?=number_format($carteras["SALDO_DEUDA"], 0, ',', '.')?>"></td>
  	<td>&nbsp;</td>
  </tr>
  


        <tr>
    <td>&nbsp;</td>	
    <td><b>Forma de Pago:</td>
        <td><input type="text" id="forma_pago_id" name="forma_pago_id" style="width : 91%;" readonly="readonly" value="<?=$carteras["FORMA_PAGO"]?>"></td>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr> 

 	 

</table>
  
<table>
<tr><td>&nbsp;</td>	</tr>
<b>Documentos:</b>

    <?php	if($lista_adjuntos!=""){
    		
	foreach ($lista_adjuntos as $key => $value) {
		?>
 <tr>		
&nbsp<?=$value?>
<a href=<?=base_url()?>uploads/carteranomisional/<?=$carteras["COD_CARTERA_NOMISIONAL"]?>/documentoscartera/<?=$value?> class="button">
  Ver
</a> 	
 	
 </tr>			
			<?php }
		}?>


  <tr><td>&nbsp;</td>	</tr> 
        
</table>  
   
</div>
</br>
<center>
<h3>Pensionados Cuotas Partes</h3>
</center>
</br>

<table id="tablapartes">
 <thead>
    <tr>
     <th>Identificación Pensionado</th>
	 <th>Nombres Pensionado</th>
     <th>Fecha Inicial</th>
     <th>Fecha Final</th>
      <th>Cuota Parte Actual</th>
      <th>Capital</th>
      <th>Intereses</th>
    </tr>
  </thead>
 <tbody>
<?php $total = 0;
foreach ($pensionados->result_array as $data) { $total += 1; ?> 
	<tr>

<td><?=$data['IDENTIFICACION_PENSIONADO']  ?></td>
<td><?=$data['NOMBRE_PENSIONADO']  ?></td>	
<td><?=$data['FECHA_INICIAL']  ?></td>	
<td><?=$data['FECHA_FINAL']  ?></td>	
<td><?=number_format($data["CUOTA_PARTE_ACTUAL"], 0, ',', '.') ?></td>	
<td><?=number_format($data["CAPITAL"], 0, ',', '.') ?></td>	
<td><?=number_format($data["INTERESES"], 0, ',', '.') ?></td>	
</tr>
		<?php
}
?> 
 </tbody>  
 </table>
 
 
  <p>
 <center>	 
 <input type='button' name='pagos' class='btn btn-success' value='Visualizar Pagos'  id='pagos' >
 <input type='button' name='regresar' class='btn btn-success' value='Regresar'  id='regresar' >
 
 </center>
   </p> 
   
<div id='consulta_pagos'> </div> 

  <form id="consulta" action="<?= base_url('index.php/carteranomisional/consulta') ?>">

</form>
<script type="text/javascript" language="javascript" charset="utf-8">


 $('#pagos').click(function(){
 	 var id_deuda = $("#id_deuda").val();
	var urlconsultarcnm="<?= base_url('index.php/carteranomisional/visualizar_pagos_ecollect') ?>/";
	$('#consulta_pagos').load(urlconsultarcnm, { id_deuda: id_deuda });

});

 $('#regresar').click(function(){
	$('#consulta').submit();
});

  $('#tablapartes').dataTable({
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
                      ]

        
    });
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script> 
