<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
<?php   
$data = array( 'id' => 'myForm');
echo form_open(current_url(), $data); ?>
<h2>Lista de avaluos para crear despacho comisionario</h2>
<?php 
if (isset($message)){
    echo $message;
}
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
function dataTable(){
   
    $('#tablaq').dataTable( {
        "sServerMethod": "POST", 
        "bJQueryUI": true,
        "bProcessing": true,  
        "bServerSide": true,
        "bPaginate" :  true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 10,
        "sSearch": '',
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/mcremate/datatablesolicerti",          
        "aoColumns": [  
               { "mDataProp": "COD_FISCALIZACION"},  
               { "mDataProp": "COD_MEDIDACAUTELAR" },
               { "mDataProp": "COD_AVALUO" },
               //Link Gestion
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='javascript:void(0)' onclick='window.location = \"<?=base_url()?>index.php/mcremate/formcomisorio/" + oObj.aData.COD_AVALUO + "\"' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Fiscalizaciones\"><i class=\"fa fa-eye-slash\"></i></a>";
                     }
                 }
           ]

    } );
}

$(document).ready(function() {
    dataTable();          
} );

function alertDelete(id){
   
    if (confirm('¿Desea eliminar el auto seleccionado?')){
        location.href = "<?=base_url()?>index.php/verfpagosprojuridicos/delete/" + id;
    }
}
</script>
<br><br>
<table id="tablaq">
 <thead>
    <tr>
        <th>Fiscalizacion</th>
        <th>Cod Medida Cautelar</th>
        <th>Cod avaluo</th>
        <th>Crear Despacho Comisorio</th>
   </tr>
 </thead>
 <tbody>
     <tr>
         <td></td>
     </tr>
 </tbody>     
</table>
