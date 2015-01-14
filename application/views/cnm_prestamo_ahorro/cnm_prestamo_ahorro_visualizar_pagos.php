<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>




<table id="tablaq">
 <thead>
    <tr>

     <th>Número de Cuota</th>
  <th>Valor Deuda</th>
     <th>Fecha de Pago</th>
     <th>Valor Pago</th>
      <th>Amortizacion</th>
     <th>Interes Corriente</th>
         <th>Interes No Pagos</th>
     <th>Interes Mora</th>
      <th>Saldo</th>


    </tr>
  </thead>
 <tbody>

 </tbody>  
 </table>
<br>
<center>
 <input type='button' name='amortizacion' class='btn btn-success' value='Plan de Amortización'  id='amortizacion' >
</center>

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
                      ]

        
    });
    
    
            
                function ajaxValidationCallback(status, form, json, options) {
	
}
</script>

