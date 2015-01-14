<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>




<table id="tablaq">
 <thead>
    <tr>
    <th>Número de Cuota</th>
  	<th>Valor Deuda</th>
    <th>Mes Proyectado</th>
    <th>Valor Cuota</th>
    <th>Amortizacion Capital</th>
    <th>Interes Corriente</th>
    <th>Interes Acumulado</th>
    <th>Interes Mora</th>
    <th>Cesantia</th>
    <th>Pago</th>
    <th>Distribución Capital</th>
    <th>Distribución Interés C.</th>
    <th>Pago Cesantias</th>
    </tr>
  </thead>
 <tbody>
<?php foreach ($detalle_pagos->result_array as $data) {?> 
	<tr>

<td><?=$data['NO_CUOTA'] ?></td>
<td><?=number_format($data['CAPITAL'], 0, ',', '.')  ?></td>	
<td><?=$data['MES_PROYECTADO']  ?></td>
<td><?=number_format($data['VALOR_CUOTA'], 0, ',', '.')  ?></td>
<td><?=number_format($data['AMORTIZACION'], 0, ',', '.')  ?></td>
<td><?=number_format($data['VALOR_INTERES_C'], 0, ',', '.')  ?></td>
<td><?=number_format($data['SALDO_INTERES_NO_PAGOS'], 0, ',', '.')  ?></td>
<td><?=number_format($data['INTERES_MORA_GEN'], 0, ',', '.')  ?></td>
<td><?=number_format($data['CESANTIAS'], 0, ',', '.')  ?></td>
<td><?=number_format($data['VALOR_PAGADOS'], 0, ',', '.')  ?></td>
<td><?=number_format($data['AMORTIZACION']-$data['SALDO_AMORTIZACION'], 0, ',', '.')  ?></td>
<td><?=number_format($data['VALOR_INTERES_C']-$data['SALDO_INTERES_C'], 0, ',', '.')  ?></td>
<td><?=number_format($data['CESANTIAS_APLICADA'], 0, ',', '.')  ?></td>
</tr>

<?php } ?>
 </tbody>  
 </table>



<script type="text/javascript" language="javascript" charset="utf-8">

 $('#consulta_pagos').dialog({
                autoOpen: true,
                width: 1500,
                height: 470,
                modal:true,
                title:'Pagos',
                close: function() {
                  $('#consulta_pagos *').remove();
                
                
                }
            });	



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

                      ]

        
    });
    
    
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

