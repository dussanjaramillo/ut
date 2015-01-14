<?php 
if (isset($message)){
    echo $message;
   }
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
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

$('#tablas').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/tasasysalarios/datatablesalario",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "center" },
                      { "sClass": "center" },      
                      { "sClass": "center" },      
                      { "sClass": "center" },                            
                      { "sClass": "center" }, 
                      { "bSearchable": false, "bVisible": false },

                    
                      ],

} );

$('#tablau').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/tasasysalarios/datatableuvt",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "center" },
                      { "sClass": "center" },      
                                                  
                     
                      { "bSearchable": false, "bVisible": false },

                    
                      ],

} );

$('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/tasasysalarios/datatablesuperintendencia",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "center" },
                      { "sClass": "center" },      
                      { "sClass": "center" },      
                      { "sClass": "center" },                            
                      { "sClass": "center" },                           
                      { "sClass": "center" },
                      { "bSearchable": false, "bVisible": false },

                    
                      ],

} );

$('#tablat').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/tasasysalarios/datatabletasas",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "center" },
                      { "sClass": "center" },      
                      { "sClass": "center" },      
                      { "sClass": "center" },                            
                      { "sClass": "center" },                           
                      { "sClass": "center" },                           
                      { "sClass": "center" },
                      { "bSearchable": false, "bVisible": false },

                    
                      ],

} );


} );

</script> 


<h1>Tasas y Salarios</h1>
<br><br>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Superintendencia</a></li>
    <li><a href="#tabs-2">Tasas</a></li>
    <li><a href="#tabs-3">UVT</a></li>
    <li><a href="#tabs-4">Salario</a></li>
  </ul>
  <div id="tabs-1">
    
    <table id="tablaq">
    <thead>
      <tr>
       <th>Id</th>
       <th>Tasa superintendencia</th>
       <th>Tipo tasa</th>
       <th>Vigencia Desde</th>
       <th>Vigencia Hasta</th>
       <th>Fecha creación</th>
       <th>Estado</th>
       <th>num</th>
      </tr>
    </thead>
  <tbody></tbody>     
    </table>
  <p>
    <div align="center">
                
                <?php 
                echo anchor(base_url().'index.php/tasasysalarios/addtasasuperintendencia/','<i class="icon-star"></i> Actualizar','class="btn btn-success"');
        
                ?>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php echo form_close(); ?>

      </div>
  </p>

  </div>
  <div id="tabs-2">
    <table id="tablat">
    <thead>
      <tr>
       <th>Id</th>
       <th>Valor tasa</th>
       <th>Tipo tasa</th>
       <th>Vigencia Desde</th>
       <th>Vigencia Hasta</th>
       <th>Fecha creación</th>
       <th>Acuerdo</th>
       <th>Estado</th>
       <th>num</th>
      </tr>
    </thead>
  <tbody></tbody>     
    </table>
  <p>
    <div align="center">
                
                <?php 

                echo anchor(base_url().'index.php/tasasysalarios/addtasas/','<i class="icon-star"></i> Actualizar','class="btn btn-success"');
           
                ?>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php echo form_close(); ?>

      </div>
  </p>
  </div>
  <div id="tabs-3">
    <table id="tablau">
    <thead>
      <tr>
       <th>Año</th>
       <th>SMLV</th>
       <th>Tasa UVT</th>
       
       <th>num</th>
      </tr>
    </thead>
  <tbody></tbody>     
    </table>
  <p>
    <div align="center">
                
                <?php 

                echo anchor(base_url().'index.php/tasasysalarios/addtasauvt/','<i class="icon-star"></i> Actualizar','class="btn btn-success"');
           
                ?>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php echo form_close(); ?>

      </div>
  </p>
</div>
  <div id="tabs-4">
     <table id="tablas">
    <thead>
      <tr>
       <th>Id</th>
       <th>SMMLV</th>
       <th>Vigencia Desde</th>
       <th>Vigencia Hasta</th>
       <th>Fecha creación</th>
       <th>Estado</th>
       <th>num</th>
      </tr>
    </thead>
  <tbody></tbody>     
    </table>
  <p>
    <div align="center">
                
                
                <?php 

                echo anchor(base_url().'index.php/tasasysalarios/addtasasalario/','<i class="icon-star"></i> Actualizar','class="btn btn-success"');
           
                ?>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php echo form_close(); ?>

      </div>
  </p>
  </div>
</div>


