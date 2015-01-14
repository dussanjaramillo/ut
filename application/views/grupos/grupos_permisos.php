<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form2">
<h2>Editar permisos predeterminados para el grupo "<?php echo $result->NOMBREGRUPO ?>" *</h2>


<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
var oTable = $('#tablae').dataTable( {
"bScrollInfinite": false,
"bScrollCollapse": false,
"sPaginationType": "full_numbers",
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php echo base_url(); ?>index.php/grupos/elegir/<?php echo $result->IDGRUPO; ?>",
"sServerMethod": "POST",
"aoColumns": [ 
            { "sClass": "center" }, /*id 0*/
            { "sClass": "item" }, /* 1 */
            { "sClass": "item" }, /* 2 */
            { "sClass": "item" },  
            { "sClass": "item" },  
            { "sClass": "center","bSortable": true,"bSearchable": false,"sWidth": "1%" },
            { "bSearchable": false, "bVisible": false },
            
            ],
"fnRowCallback":function( nRow, aData, iDataIndex ) {
          if ( aData[5] ==null )
            {
              $('td:eq(5)', nRow).html( '<a href="#" id="'+aData[0]+'" class="btn btn-small agrega" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );  
            }else
            {
              $('td:eq(5)', nRow).html( '<a href="#" id="'+aData[5]+'" class="btn btn-small elimina" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' ); 
            }
         },  
//agrega a la tabla de predeterminados (PERMISOS_GRUPOS) mediante ajax         
"fnDrawCallback": function( oSettings ) {
             $(".agrega").on('click', function(event) {
             event.preventDefault();
             var ID = $(this).attr("id");
              $("#destino").append('<i class="fa fa-spinner fa-spin"></i>');
             $("#destino").load("<?php echo base_url(); ?>index.php/grupos/predeterminar", {id_menu: ID, id_grupo:<?php echo $result->IDGRUPO; ?>}, function(){
                oTable.fnDraw();  
              });
    
         });
             $(".elimina").on('click', function(event) {
             event.preventDefault();
             var IDP = $(this).attr("id");
              $("#destino").append('<i class="fa fa-spinner fa-spin"></i>');
              $("#destino").load("<?php echo base_url(); ?>index.php/grupos/despredeterminar", {id_permiso: IDP}, function(){
                oTable.fnDraw();
              });
    
         });
    },
} );

} );
</script>

<div id="destino"></div>
<?php
echo anchor(base_url().'index.php/grupos','<i class="fa  fa-arrow-left "></i> Regresar','class="btn btn-large"');
?>
<br><br>
<table id="tablae" class="table">
 <thead>
    <tr>
     <th>id</th>
     <th>Macroproceso</th>
     <th>Aplicación</th>
     <th>Módulo</th>
     <th>Menú</th>
     <th>Estado</th>
     <th>Num</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
</div>

</div>
<div class="clear"></div>

<p>* Estos cambios sólo se verán reflejados para (nuevos?) usuarios</p>