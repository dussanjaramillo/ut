<?php
// Responsable: Leonardo Molina
if (isset($message)){
    echo $message;
   }
?>
 <br><br>   
<!--<h1>Liquidación Contratos de Aprendizaje - Auto de pruebas</h1>-->
<h1>Recibir pruebas y translado de alegatos</h1>
<table id="tablaq">
    <thead>
       <tr>
        <th>Numero AutoPruebas</th>
        <th>Nit</th>
        <th>Nombre Empresa</th>
        <th>Fecha Creación</th>
        <th>Valor deuda</th>
        <th>Dias desde creación</th>
        <th>Estado</th>
        <th>Gestionar</th>
      </tr>
    </thead>
    <tbody>

    </tbody>     
</table>
<!-- Modal External-->

  <div class="modal hide fade in" id="modal" style="display: none; width: 60%; height: 50%;margin-left: -30%;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Recibir Pruebas </h4>
        </div>
        <div class="modal-body">
          <div align="center">
              <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />  
            </div>
        </div>
<!--        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
          <a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>
          <input class="btn btn-primary" type="button"  onclick="javascript:printTextArea()" value="Print Text"/>

        </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

    $('#tablaq').dataTable( {
        "sServerMethod": "POST", 
        "bJQueryUI":    true,
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 12,
        "sSearch": '',
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 12,
        "iTotalDisplayRecords": 12,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/recibirpruebastransalegatos/getData",          
        "aoColumns": [  
               { "sClass": "center","mDataProp": "NUM_AUTOGENERADO" },  
               { "mDataProp": "CODEMPRESA" },  
               { "mDataProp": "NOMBRE_EMPRESA" },  
               { "mDataProp": "FECHA_CREACION_AUTO" },  
               { "mDataProp": "SALDO_DEUDA" },
               { "mDataProp": "TOTAL_CAPITAL" },
               { "mDataProp": "NOMBRE_ESTADO" },
               //Link Gestion
                 { "sClass": "center", "mData": null,
                    "bSearchable": true,
                    "bSortable": false,
                    "fnRender": function (oObj) {
                       return "<a href='<?=base_url()?>index.php/recibirpruebastransalegatos/respAuto/" + oObj.aData.NUM_AUTOGENERADO + "/"+oObj.aData.CODEMPRESA+"' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-pencil-square-o\"></i></a>";
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

      function printTextArea() {
          
        childWindow = window.open('','childWindow','location=yes, menubar=yes, toolbar=yes');
        childWindow.document.open();
        childWindow.document.write('<html><head></head><body>');
        //var informacion = tinymce.get('informacion').getContent();
        childWindow.document.write(document.getElementById('targetTextArea').value.replace(/\n/gi,'<br>'));
        childWindow.document.write('</body></html>');
        childWindow.print();
        childWindow.document.close();
        childWindow.close();
      
      }
      
     
    </script>