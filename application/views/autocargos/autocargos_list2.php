<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
// Responsable: Leonardo Molina
if (isset($message)){
    echo $message;
   }
?>
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
  <div id="resultado"></div>
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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/autocargos/getData?accion=<?php echo $info; ?>", //1 abogado // 2 coordinador
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
                        console.log(oObj.aData);
                        gestion = "<button onclick=\"activar(\'" + oObj.aData.COD_FISCALIZACION + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Asignar\"><i class=\"fa fa-pencil-square-o\"></i> Asignar</button>";
                       return gestion;
                      }
                  }
           ]

    } );
           
           jQuery(".preload, .load").hide();
    function activar(cod_fis) {
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/autocargos/asignar_abogado') ?>"
        $('#resultado').load(url, {cod_fis: cod_fis});
    }

    $(function() {

        $('#modal').on('hidden', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        });
    });

    </script>