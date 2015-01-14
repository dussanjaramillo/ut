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
"sAjaxSource": "<?php echo base_url(); ?>index.php/intereshistorico/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" }, 
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },

                    
                      ],

} );


} );
</script> 
<h1>Interés Histórico</h1>
<?php
 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('intereshistorico/add'))
    {
      echo anchor(base_url().'index.php/intereshistorico/add/','<i class="icon-star"></i> Crear','class="btn btn-large  btn-primary"');
    }
?>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Tipo Tasa Histórica</th>
     <th>Tipo de Tasa</th>
     <th>num</th>
     <th>Editar Tasa</th>
     <th>Rangos de Tasa</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>

