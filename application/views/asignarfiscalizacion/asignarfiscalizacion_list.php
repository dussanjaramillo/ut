<?php 
if (isset($message)){
    echo $message;
   }
?>
<!--//:::::generación de la tabla mediante json-->
<script type="text/javascript" language="javascript" charset="utf-8">

$(document).ready(function() {
//administrar las pestañas del dataTable
$("#tabs").tabs( {
        "activate": function(event, ui) {
            var table = $.fn.dataTable.fnTables(true);
            if ( table.length > 0 ) {
                $(table).dataTable().fnAdjustColumnSizing(false);
            }
        }
    } );

oTable1 = $('#tablanuevas').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/asignarfiscalizacion/dataTablenuevas",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },      
                      //{ "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },

                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false },

                    
                      ],

} );

//:::::Se inicializan los filtros para cada columna que se desee filtrar.
//:::::Documentacion acerca del plugin http://jquery-datatables-column-filter.googlecode.com/svn/trunk/index.html
//:::::File:        jquery.dataTables.columnFilter.js
//:::::Version:     1.5.3.
oTable1.dataTable().columnFilter(

       {
          sPlaceHolder: "head:after",
          
            aoColumns: [
            
            {sSelector: "#idempresa"},
            {sSelector: "#rsocial"},
            null,
            null,
            {sSelector: "#regional_id", type: "select", values: [
              <?php 
                  foreach($regional as $key => $value) {
                      echo '"'.$value->NOMBRE_REGIONAL.'"'.',';
                  }
              ?>
            ]},
            null,
            null,
            null,
            null,
            null,
            null,
            null,
                     
                    
           
           

           
          ]
        }
);


oTable2 = $('#tablaevasoras').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/asignarfiscalizacion/dataTableevasoras",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },      
                      //{ "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },

                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false },

                    
                      ],

} );


//:::::Se inicializan los filtros para cada columna que se desee filtrar.
//:::::Documentacion acerca del plugin http://jquery-datatables-column-filter.googlecode.com/svn/trunk/index.html
//:::::File:        jquery.dataTables.columnFilter.js
//:::::Version:     1.5.3.
oTable2.dataTable().columnFilter(

       {
          sPlaceHolder: "head:after",
          
            aoColumns: [
            
            {sSelector: "#idempresa2"},
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
                     
                    
           
           

           
          ]
        }
);





} );
</script> 
<!--//::::: Estilo que evita el desbordamiento de la tabla. -->

<style type="text/css">
table.dataTable {
  table-layout: fixed; 
  word-wrap:break-word;
}
</style>


<!--//:::::Formulario de identificacion de campos para el filtrado.-->
<h1>Asignar Fiscalización</h1>
<div class="center-form-large">
              <h3>Filtros De Busqueda Empresas Nuevas</h3>
              
              <div class="controls controls-row">
                <div class="span3">
                    NIT
                    <p id="idempresa" class = "filterOption"></p>
                </div>
                <div class="span3">
                    Regional
                    <p id="regional_id" class = "filterOption"></p>
                </div>
              </div>
              
              

              <div class="controls controls-row">
                <div class="span3">
                  Razón Social 
                  <p id="rsocial" class = "filterOption"></p>
                </div>
              <div class="span3">
                  <!--Ciudad-->
                  <p id="ciudad" class = "filterOption"></p>
              </div>
              </div>
              <br><br>


              <h3>Filtros De Busqueda Posibles Deudoras</h3>
              <div class="controls controls-row">
                <div class="span3">
                    NIT
                    <p id="idempresa2" class = "filterOption"></p>
                </div>

              
</div>
</div>

<!--//:::::Contenedor de la Tabla-->
<br><br>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Empresas Nuevas</a></li>
    <li><a href="#tabs-2">Posibles Deudoras</a></li>
  </ul>
  <div id="tabs-1">
    <table id="tablanuevas">
     <thead>
        <tr>
         <th>NIT</th>
         <th>Razón social</th>
         <th>Teléfono</th>
         <th>Dirección</th>
         <!--<th>Ciudad</th>-->
         <th>Regional</th>
         <th>Empresa</th>
         <th>CIIU</th>
         <th>No empleados</th>
         <th>Categoría Matrícula</th>
         <th>Estado</th>
         
         <th>num</th>
         <th>Acciones</th>
         
           
         
       </tr>
     </thead>
     <tbody></tbody>
      <tfoot>
      
      </tfoot>     
    </table>
  </div>

  <div id="tabs-2">
    <table id="tablaevasoras">
     <thead>
        <tr>
         <th>NIT</th>
         <th>Razón social</th>
         <th>Teléfono</th>
         <th>Dirección</th>
         <!--<th>Ciudad</th>-->
         <th>Regional</th>
         <th>Empresa</th>
         <th>CIIU</th>
         <th>No empleados</th>
         <th>Categoría Matrícula</th>
         <th>Estado</th>
         
         <th>num</th>
         <th>Acciones</th>
         
           
         
       </tr>
     </thead>
     <tbody></tbody>
      <tfoot>
      
      </tfoot>     
    </table>
  </div>

