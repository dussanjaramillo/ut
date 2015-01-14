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
"width":"100%",
"sAjaxSource": "<?php echo base_url(); ?>index.php/gestion/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" ,"sWidth": "2%"}, /*id 0*/
                      { "sClass": "item" },      
                      { "sClass": "item" },
                      { "sClass": "center","sWidth": "10%" },
                      //{ "sClass": "center","sWidth": "15%" }, 
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "1%" },

                    
                      ],

} );


} );
</script> 
<h1>Gestión</h1>
<?php
if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/add'))
{
  echo anchor(base_url().'index.php/gestion/add/','<i class="icon-star"></i> Agregar','class="btn btn-large  btn-primary"');
}
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id Gestión</th>
     <th>Tipo Gestión</th>
     <th>Descripción</th>
     <th>Fecha Creación</th>
     <!--<th>Estado</th>-->
     <th>num</th>
     <th>Accíones</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>

