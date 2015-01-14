<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


    Valor Cuota Sugerida:<input type="text" id="cuota_sugerida" name="cuota_sugerida" style="width : 10%;" readonly="readonly" value="$ <?=number_format($proyeccion["cuota_sugerida"], 0, ',', '.')?>">

<div id='proyeccion_inicial' class="table table-bordered" border="2">
	<center><h3>PROYECCIÓN DE LA CARTERA</h3></center>
	
<?php /* <table>	    
	    <tr>
    <td>&nbsp;</td>
    <td>Tercero:</td>
    <td><input type="text" id="tercero" style="width : 91%;" readonly="readonly"></td>
    <td>&nbsp;</td>
    <td>Tipo Cartera:</td>
    <td><input type="text" id="cartera" style="width : 91%;" readonly="readonly" value="<?=$tipoCartera["NOMBRE_CARTERA"]?>"></td>
    <td>&nbsp;</td>
  </tr>

</table>
	*/ ?>
<table id="tablaq">
 <thead>
    <tr>
    <th border="1" >Número de Cuota</th>
  	<th border="1" >Valor Deuda</th>
    <th border="1" >Mes Proyectado</th>
    <th border="1" >Valor Cuota</th>
    <th border="1" >Amortizacion Capital</th>
    <th border="1" >Interes Corriente</th>

    </tr>
  </thead>
 <tbody>
<?php foreach ($proyeccion['mensaje'] as $data) {?> 
	<tr>

<td border="2"><?=$data['NO_CUOTA'] ?></td>
<td border="2"><?=number_format($data['CAPITAL'], 0, ',', '.')  ?></td>	
<td border="2"><?=$data['MES_PROYECTADO']  ?></td>
<td border="2"><?=number_format($data['VALOR_CUOTA'], 0, ',', '.')  ?></td>
<td border="2"><?=number_format($data['AMORTIZACION'], 0, ',', '.')  ?></td>
<td border="2"><?=number_format($data['VALOR_INTERES_C'], 0, ',', '.')  ?></td>

</tr>

<?php } ?>
 </tbody>  
 </table>

 </div>
<center>
<input type='button' name='imprimir' class='btn btn-success' value='IMPRIMIR'  id='imprimir' class="printer">
</center>
<script type="text/javascript" language="javascript" charset="utf-8">

 $('#proyeccion_tabla').dialog({
                autoOpen: true,
                width: 1000,
                height: 470,
                modal:true,
                title:'Proyección',
                close: function() {
                  $('#proyeccion_tabla *').remove();
                
                
                }
            });	



 $('#tablaq').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bPaginate": false,
        "bFilter": false,
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
                      ]

        
    });
    
    $('#imprimir').click(function(){ 
    	
    	
    	
var ficha = document.getElementById('proyeccion_inicial');
var ventimp = window.open("");
ventimp.document.write( ficha.innerHTML );
ventimp.document.close();
ventimp.print( );
ventimp.close();
     });    
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

