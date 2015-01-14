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
"sScrollY": "300px",
"bPaginate": false,
"sAjaxSource": "<?php echo base_url(); ?>index.php/asignacionsemiautomatica/datatable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },      
                      { "sClass": "center" },
                      { "sClass": "center" }, 
                      

                    
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
            
            null,
            null,
            {sSelector: "#ciudad"},

            {sSelector: "#t_empresa"},
            {sSelector: "#ciiu"},
           

           
          ]
        }
);



} );
</script> 


<!--//:::::Formulario de identificacion de campos para el filtrado.-->
<h1>Empresas Posibles Deudoras Sin Fiscalizar</h1>
<div class="center-form-large">
<?php echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
              <h3>Filtros De Busqueda</h3>
              
                   
              

              <div class="controls controls-row">
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
                  Tipo de Empresa
                  <p id="t_empresa" class = "filterOption"></p>
                </div> 
              </div>

         <div class="controls controls-row">
        <p align="center">
        
               
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Asignar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Asignar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
        </p>
        </div>
            <?php echo form_close(); ?>      
          
</div>


<!--//:::::Contenedor de la Tabla-->
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>NIT</th>
     <th>Razón social</th>
     <th>Ciudad</th>
     <th>Empresa</th>
     <th>CIIU</th>
    </tr>
 </thead>
 <tbody></tbody>
 <tfoot>
  
</tfoot>     
</table>
