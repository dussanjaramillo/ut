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
"sAjaxSource": "<?php echo base_url(); ?>index.php/grupos/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "1%" },

                    
                      ],

} );


} );
</script>

<h1>Grupos</h1>
<?php
echo anchor(base_url().'index.php/grupos/add/','<i class="icon-star"></i> Nuevo','class="btn btn-large  btn-primary"');
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Nombre</th> 
     <th>Descripción</th> 
     <th>num</th>
     <th>Acciones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
 <script>

