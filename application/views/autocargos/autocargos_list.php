<?php
// Responsable: Leonardo Molina
if (isset($message)){
    echo $message;
   }
?>
<a type="button" class="btn btn-info" href="<?= base_url()?>index.php/autopruebas"><i class="fa fa-gear"></i>
<span>Gestionar Auto de cargos Generados. </span>
</a>
<h1>SGVA Contratos de Aprendizaje - Auto de Cargos</h1>
<table id="tablaq">
    <thead>
       <tr>
        <th>Numero Estado Cuenta</th>
        <th>Nit Empresa</th>
        <th>Nombre Empresa</th>
        <th>Fecha Creación</th>
        <th>Valor final</th>
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
          <h4 class="modal-title">Auto de Cargos</h4>
        </div>
        <div class="modal-body">
          <div align="center">
              <img src="<?=  base_url()?>/img/27.gif" width="150px" height="150px" />  
            </div>
        </div>
<!--    <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
          <a href="#" class="btn btn-primary mce-text" id="text-mce_127">Guardar</a>
          <input class="btn btn-primary" id="generarPdf" type="button" value="Print Text"/>
        </div>-->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json

var gestion="";
    $('#tablaq').dataTable( {
        "sServerMethod": "POST", 
        "bJQueryUI": true,
        "bProcessing": true,  
        "bServerSide": true,
        "bPaginate" :  true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 12,
        "sSearch": '',
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 12,
        "iTotalDisplayRecords": 12,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/autocargos/getData",          
        "aoColumns": [  
               { "mDataProp": "ESTADO_NRO_ESTADO" },  
               { "mDataProp": "ESTADO_NIT_EMPRESA" },
               { "mDataProp": "NOMBRE_EMPRESA" },
               { "mDataProp": "ESTADO_FECHA_CREACION" },  
               { "mDataProp": "ESTADO_VALOR_FINAL" },  
               { "mDataProp": "ESTADO_ESTADO" },
               //Link Gestion
                 { "sClass": "center", "mData": null,
                    "bSearchable": true,
                    "bSortable": false,
                    "fnRender": function (oObj) {
                        
                        if(!oObj.aData.NROCUENTA)
                        gestion = "<a href='<?=base_url()?>index.php/autocargos/generarAuto/" + oObj.aData.ESTADO_NRO_ESTADO + "' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Gestionar\"><i class=\"fa fa-pencil-square-o\"></i></a>";
                        else  
                            gestion = "<a href='#' id=\"Gestionar\"class=\"btn btn-small\" disabled=\"true\" title=\"Auto generado\"><i class=\"fa fa-pencil-square-o\"></i></a>";
                       return gestion;
                      }
                  }
           ]

    } );
           


    $(function() {

        $('#modal').on('hidden', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        });
    });

    </script>