<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">
<?php   
$data = array( 'id' => 'myForm');
echo form_open(current_url(), $data); ?>
<h2>Consultar Auto juridico</h2>
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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/verfpagosprojuridicos/datatable",          
        "aoColumns": [  
               { "mDataProp": "COD_ASIGNACION_FISC" },  
               //{ "mDataProp": "NOMBRE_CREADOR" },  
               //{ "mDataProp": "NOMBRE_ASIGNADO" },  
               { "mDataProp": "NOMBRE_ESTADO" }, 
               //Link Gestion
                /*{ "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='<?=base_url()?>index.php/verfpagosprojuridicos/management/" + oObj.aData.NUM_AUTOGENERADO + "' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-eye\"></i></a>";
                     }
                 },
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='<?=base_url()?>index.php/verfpagosprojuridicos/upload/" + oObj.aData.NUM_AUTOGENERADO + "' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-upload\"></i></a>";
                     }
                 },
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='<?=base_url()?>index.php/verfpagosprojuridicos/printer/" + oObj.aData.NUM_AUTOGENERADO + "' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-pagelines\"></i></a>";
                     }
                 },*/
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='javascript:void(0)' onclick='window.location = \"<?=base_url()?>index.php/verfpagosprojuridicos/form/" + oObj.aData.NUM_AUTOGENERADO + "\"' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-eye-slash\"></i></a>";
                     }
                 }
                 
                 /*,
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='<?=base_url()?>index.php/verfpagosprojuridicos/Edit/" + oObj.aData.NUM_AUTOGENERADO + "' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-eye-slash\"></i></a>";
                     }
                 }*/,    
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='javascript:void(0)' onclick='alertDelete(" + oObj.aData.NUM_AUTOGENERADO + ")' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-trash-o\"></i></a>";
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
<br>
<br>
<table id="tablaq">
  <thead>
    <tr>
      <th>Cod Gestion</th>
      <!--th>Creado por</th>
        <th>Asignado a</th-->
      <th>Estado</th>
      <!--th>Ver géstion</th>
        <th>Subir archivo</th>
        <th>Imprimir</th-->
      <th>Ver</th>
      <!--th>Editar</th-->
      <th>Eliminar</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
    </tr>
  </tbody>
</table>
