<?php 
if (isset($message)){
    echo $message;
   }
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

$('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/aplicaciondepago/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center", "bVisible": false  }, /*id 0*/
                      { "sClass": "center" },
                      { "sClass": "center" },      
                      { "sClass": "center" }, 
                      { "sClass": "center" },      
                      { "bSearchable": false, "bVisible": false },

                    
                      ],

} );


} );
</script> 
<h1>Administración Aplicacion de Pagos</h1>
<?php
if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/add'))
    {
      echo anchor(base_url().'index.php/estados/add/','<i class="icon-star"></i> Nuevo','class="btn btn-large  btn-primary"');
    }
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Distribución capital</th>
     <th>Distribución interés corriente</th>
     <th>Distribución interés mora</th>
     <th>Fecha Creación</th>
     <th>num</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>

