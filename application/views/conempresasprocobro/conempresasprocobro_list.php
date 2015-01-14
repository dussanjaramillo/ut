<?php
if (isset($message)){
    echo $message;
   }
?>
<!--//:::::generación de la tabla mediante json-->
<script type="text/javascript" language="javascript" charset="utf-8">

$(document).ready(function() {

oTable = $('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/conempresasprocobro/dataTable",
"sServerMethod": "POST",
"aoColumns": [
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },

                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false},


                      ],

} );

//:::::Se inicializan los filtros para cada columna que se desee filtrar.
//:::::Documentacion acerca del plugin http://jquery-datatables-column-filter.googlecode.com/svn/trunk/index.html
//:::::File:        jquery.dataTables.columnFilter.js
//:::::Version:     1.5.3.
oTable.dataTable().columnFilter(

       {
          sPlaceHolder: "head:after",

            aoColumns: [

            {sSelector: "#idempresa"},
            {sSelector: "#rsocial"},
            null,
            null,
            {sSelector: "#ciudad"},
            {sSelector: "#regional_id", type: "select", values: [
              <?php
                  foreach($regional as $key => $value) {
                      echo '"'.$value->NOMBRE_REGIONAL.'"'.',';
                  }
              ?>
            ]},
            {sSelector: "#t_empresa", type: "select", values: [
              <?php
                  foreach($empresa as $key => $value) {
                      echo '"'.$value->NOM_TIPO_EMP.'"'.',';
                  }
              ?>
            ]},
            {sSelector: "#ciiu"},
            null,
            {sSelector: "#estado", type: "select", values: [

              //:::::no esta definida la tabla

            ]},
            {sSelector: "#fiscalizador"},
            null,
            null,






          ]
        }
);



} );
</script>
<style type="text/css">
/* hack para evitar el desborde de la tabla*/

table.dataTable {
  table-layout: fixed;
  word-wrap: break-word;
}
</style>

<!--//:::::Formulario de identificacion de campos para el filtrado.-->
<h2>Consultar Empresas En Proceso De Fiscalización</h2>
<div class="center-form-large">
              <h3>Filtros De Busqueda</h3>

              <div class="controls controls-row">
                <div class="span3">
                    NIT
                    <p id="idempresa" ></p>
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
                  Ciudad
                  <p id="ciudad" class = "filterOption"></p>
              </div>
              </div>



               <div class="controls controls-row">
                <div class="span3">
                  CIIU
                  <p id="ciiu" class = "filterOption"></p>
                </div>
                <div class="span3">
                  Estado
                  <p id="estado" class = "filterOption"></p>
                </div>
              </div>


              <div class="controls controls-row">
                <div class="span3">
                  Fiscalizador
                  <p id="fiscalizador" class = "filterOption"></p>
                </div>
                <div class="span3">
                  Tipo de Empresa
                  <p id="t_empresa" class = "filterOption"></p>
                </div>
              </div>

</div>
</div>


<!--//:::::Contenedor de la Tabla-->
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>NIT</th>
     <th>Razón social</th>
     <th>Teléfono</th>
     <th>Dirección</th>
     <th>Ciudad</th>
     <th>Regional</th>
     <th>Empresa</th>
     <th>CIIU</th>
     <th>No empleados</th>
     <th>Estado</th>
     <th>Fiscalizador</th>

     <th>num</th>
     <th>Acciones</th>



   </tr>
 </thead>
 <tbody></tbody>
 <tfoot>

</tfoot>
</table>


