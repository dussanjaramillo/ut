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
<h6><a href="<?=base_url()?>index.php/nulidadanaliza">Empresas</a> / <a href="<?=base_url()?>index.php/nulidadanaliza/listFiscalizaciones/<?php echo $empresa->CODEMPRESA?>">Fiscalizaciones (Empresa <?php echo $empresa->NOMBRE_EMPRESA?>)</a> / <a href="javascript:void(0)">Nulidades (Fiscalizacion <?php echo $fiscalizacion->COD_FISCALIZACION?>)</a></h6>    
<h2>Gestionar Nulidades</h2>
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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/nulidadanaliza/datatableNulidades/<?php echo trim($fiscalizacion->COD_FISCALIZACION)?>",          
        "aoColumns": [  
               { "mDataProp": "COD_NULIDAD" },
               { "mDataProp": "FECHA_RADICACION" },
               { "mDataProp": "NOMBRE_ESTADO" },
               //Link Gestion
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return "<a href='javascript:void(0)' onclick='window.location = \"<?=base_url()?>index.php/" + oObj.aData.URL + "\"' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Nulidad\"><i class=\"fa fa-save\"></i></a>";
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
        <th width="25%">Fecha de radicacion</th>
        <th width="50%">Estado</th>
        <th>Gestionar</th>
   </tr>
 </thead>
 <tbody>
     <tr>
         <td></td>
     </tr>
 </tbody>     
</table>
