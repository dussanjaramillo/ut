<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
<?php   
$data = array( 'id' => 'myForm');
echo form_open(current_url(), $data); ?>
<h6><a href="<?=base_url()?>index.php/nulidadanaliza">Empresas</a> / <a href="javascript:void(0)">Fiscalizaciones (Empresa <?php echo $empresa->NOMBRE_EMPRESA?>)</a></h6>    
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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/nulidadanaliza/datatableFiscalizaciones/<?php echo trim($empresa->CODEMPRESA)?>",          
        "aoColumns": [  
               { "mDataProp": "COD_FISCALIZACION" },
               { "mDataProp": "GESTION_NOMBRE" },
               //Link Gestion 
                 { "sClass": "center", "mData": null,
                   "bSearchable": true,
                   "bSortable": false,
                   "fnRender": function (oObj) {
                      return " <a href='javascript:void(0)' onclick='window.location = \"<?=base_url()?>index.php/nulidadanaliza/form/" + oObj.aData.COD_GESTIONACTUAL + "\"' class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Fiscalizaciones\"><i class=\"fa fa-save\"></i></a>";
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
        <th width="10%">Cod Fiscalizacion</th>
        <th width="60%">Gestion</th>
        <th>Crear nulidad</th>
   </tr>
 </thead>
 <tbody>
     <tr>
         <td></td>
     </tr>
 </tbody>     
</table>
