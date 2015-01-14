<?php 
if (isset($message)){
    echo $message;
   }
?>
<script type="text/javascript" language="javascript" charset="utf-8">
    $(document).ready(function() {

    $('#tablaq').dataTable( {
        "sServerMethod": "POST", 
        
        "bProcessing": true,  
        "bServerSide": true,
        "bPaginate" :  true ,
        //"sPaginationType": "bootstrap",
        
        "iDisplayStart" : 0,
        "iDisplayLength" : 10,
        "sSearch": true,
        "bJQueryUI": true,
        "sPaginationType": "four_button",
        "bSort": false,
        "aLengthMenu": [10, 50],
        //"sPaginationType": "full_numbers",
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/requerimientoacercamiento/getData",          
        "aoColumns": [  
               { "mDataProp": "COD_RESOLUCION" },  
               { "mDataProp": "FECHA_CREACION" },  
               { "mDataProp": "NOMBRE_REGIONAL" },  
               { "mDataProp": "NITEMPRESA" },  
              
               {    "mDataProp": "COD_ESTADO",
                    "sName": "Gestionar",
                    "bSearchable": true,
                    "bSortable": false,
                    
                    "fnRender": function (oObj) {
                        return "<form  id=\"f1\" name=\"f1\" action=\"<?=base_url()?>index.php/requerimientoacercamiento/\" method=\"post\"><input type=\"hidden\" name=\"cod_titulo\" id=\"cod_titulo\" value=\""+oObj.aData.COD_RESOLUCION+"\"> <input type=\"submit\" class=\"btn btn-success\" title=\"Gestionar\" value=\"Generar Requerimiento\"> </form>";
                      
                        
                    


                     }
                  }
           ]

    } );
           
} );


//generación de la tabla mediante json
//$(document).ready(function() {
//
//$('#tablaq').dataTable();
//
//
//} );
</script> 
<table id="tablaq">
 <thead>
    <tr>
     <th>N° Titulo    </th>
     <th>Año</th>
     <th>Regional</th>
     <th>Nit</th>
    
      <th>Gestión</th>
   </tr>
 </thead> 
 <tbody>
   

       
   
 </tbody>      
</table>
