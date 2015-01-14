<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>
<div class="center-form-large">
<?php   
$data = array( 'id' => 'myForm');
echo form_open(current_url(), $data); ?>
<h2>Crear acto adminitrativo (Nulidades)</h2>
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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/nulidadanaliza/datatableNulidadesCreaActoAdmin",          
        "aoColumns": [  
               { "mDataProp": "COD_NULIDAD" },
               { "mDataProp": "FECHA_RADICACION" },
               { "mDataProp": "COD_FISCALIZACION" },
               { "mDataProp": "DESCRIPCION_ACTO" },
               //Link Gestion
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='javascript:void(0)' onclick='window.location = \"<?=base_url()?>index.php/nulidadanaliza/formActoAdministrativo/" + oObj.aData.COD_NULIDAD + "\"' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Nulidad\"><i class=\"fa fa-save\"></i></a>";
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
        <th width="10%">Cod nulidad</th>
        <th width="10%">Fecha de radicacion</th>
        <th width="10%">Cod Fiscalizacion</th>
        <th width="50%">Crear acto administrativo tipo</th>
        <th>Crear</th>
   </tr>
 </thead>
 <tbody>
     <tr>
         <td></td>
     </tr>
 </tbody>     
</table>
