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
"sScrollX": "100%", 
"sScrollXInner": "98%", 
"bScrollCollapse": true,
"bAutoWidth": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/usuarios/dataTable",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" }, 
                      { "sClass": "item" },
                      { "sClass": "item" }, 
                      { "sClass": "item" },
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "sClass": "item" }, 
                      { "bSearchable": false,"bSortable": false,"sClass": "center" }, 
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false },

                    
                      ],
"fnRowCallback":function( nRow, aData, iDataIndex ) {
          if ( aData[8] ==1 )
            {
               $('td:eq(8)', nRow).html( '<a href="<?php echo base_url(); ?>index.php/usuarios/deactivate/'+aData[0]+'" class="btn btn-small" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' );  
            }else
            {
              $('td:eq(8)', nRow).html( '<a href="<?php echo base_url(); ?>index.php/usuarios/activate/'+aData[0]+'" class="btn btn-small" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );  
            }
         },                      

} );


} );
</script>
<style type="text/css">
/* hack para evitar el desborde de la tabla*/

table.dataTable {
	table-layout: fixed;
	word-wrap: break-word;
}
</style>
<h1>Usuarios</h1>
<?php
echo anchor(base_url().'index.php/usuarios/add/','<i class="fa fa-star-o"></i> Nuevo','class="btn btn-large btn-primary"');
?>
<br>
<br>
<table id="tablaq">
  <thead>
    <tr>
      <th>Id</th>
      <th>Alias</th>
      <th>E-mail</th>
      <th>Nombres</th>
      <th>Apellidos</th>
      <th>Cargo</th>
      <th>Grupo</th>
      <th>Última visita</th>
      <th>Estado</th>
      <th>num</th>
      <th>Accíones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
