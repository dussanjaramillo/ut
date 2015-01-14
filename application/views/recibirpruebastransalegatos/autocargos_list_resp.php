<?php 
// Responsable: Leonardo Molina
if (isset($message)){
    echo $message;
   }
?>


<h1>Respuesta Auto de Cargos</h1>

<br><br>
<table id="tablaq">
    <thead>
       <tr>
        <th>Numero Documento</th>
        <th>Nit</th>
        <th>Fecha Creación</th>
        <th>Fecha Limite de Pago</th>
        <th>Estado</th>
        <th>Gestionar</th>
      </tr>
    </thead>
    <tbody>

    </tbody>     
</table>

  
 

<!-- Modal External-->

  <div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Respuesta Auto de Cargos</h4>
        </div>
        <div class="modal-body">
          Cargando datos...
        </div>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/autocargos/getData",          
        "aoColumns": [  
               { "mDataProp": "NUM_LIQUIDACION" },  
               { "mDataProp": "NITEMPRESA" },  
               { "mDataProp": "FECHA_LIQUIDACION" },  
               { "mDataProp": "PROYACUPAG_FECHALIMPAGO" },  
               { "mDataProp": "TOTAL_LIQUIDADO" },
               //21-37
                 { "sClass": "center", "mData": null,
                    "bSearchable": true,
                    "bSortable": false,
                    
                    "fnRender": function (oObj) {
                       return "<a href='<?=base_url()?>index.php/autocargos/modelRespAuto/" + oObj.aData.NUM_LIQUIDACION + "/"+oObj.aData.NITEMPRESA+"' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-pencil-square-o\"></i></a>";
                      }
                  }
           ]

    } );
           
} );

$(function() {

    $('#modal').on('hidden', function() {
        $('.modal-body').val("");
        $('#modal').modal('hide').removeData();
    });
    
    
});

</script>

