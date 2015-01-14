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
"sAjaxSource": "<?php echo base_url(); ?>index.php/motivodevolucion/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "1%" },

                    
                      ],

} );


} );
</script> 
<h1>Motivo Devolución</h1>
<?php
 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('motivodevolucion/add'))
    {
      echo anchor(base_url().'index.php/motivodevolucion/add/','<i class="icon-star"></i> Nuevo','class="btn btn-large  btn-primary"');
    }
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id Motivo</th>
     <th>Motivo Devolución</th>
     <th>Fecha Creación</th>
     <th>Estado</th>
     <th>num</th>
     <th>Accíones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>

