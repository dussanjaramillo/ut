<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
// Responsable: Leonardo Molina
if (isset($message)) {
    echo $message;
}
?>
<h1>Auto de cargos generados Contrato de Aprendizaje</h1>
<table id="tablaq">
    <thead>
        <tr>
            <th>Núm AutoCargos</th>
            <th>Nit Empresa</th>
            <th>Nombre Empresa</th>
            <th>Fecha Creación</th>
            <th>Valor deuda</th>
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
                <h4 class="modal-title"><div id="titleModal"></div></h4>
            </div>
            <div class="modal-body">
                <div align="center">
                    <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />  
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

<div id="resultado"></div>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json

    var gestion = '';
    $('#tablaq').dataTable({
        "sServerMethod": "POST",
        "bJQueryUI": true,
        "bProcessing": true,
        "bServerSide": true,
        "bPaginate": true,
        "iDisplayStart": 0,
        "iDisplayLength": 12,
        "sSearch": '',
        "autoWidth": true,
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 12,
        "iTotalDisplayRecords": 12,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/autopruebas/getData?accion=<?php echo $info; ?>",//1 abogado // 2 coordinador
        "aoColumns": [
            {"sClass": "center", "mDataProp": "NUM_AUTOGENERADO"},
            {"mDataProp": "CODEMPRESA"},
            {"mDataProp": "NOMBRE_EMPRESA"},
            {"mDataProp": "FECHA_CREACION_AUTO"},
            {"mDataProp": "ESTADO_VALOR_FINAL"},
            {"mDataProp": "NOMBRE_GESTION"},
            //Link Gestion
            {"sClass": "item", sWidth: '12%', "mData": null,
                "bSearchable": true,
                "bSortable": false,
                "fnRender": function(oObj) {
                    //91-100
                    var data="";
//                    console.log(oObj.aData);
                    if (oObj.aData.COD_RESPUESTA == '1324'){
                        gestion = "<button onclick=\"aprobar(\'"+oObj.aData.NUM_AUTOGENERADO+"\',\'"+oObj.aData.NOMBRE_DOC_GENERADO+"\',\'"+oObj.aData.COD_FISCALIZACION+"\',\'"+oObj.aData.CODEMPRESA+"\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                    }
                    if (oObj.aData.COD_RESPUESTA == '97'){
                        gestion = "<button onclick=\"aprobar2(\'"+oObj.aData.NUM_AUTOGENERADO+"\',\'"+oObj.aData.NOMBRE_DOC_FIRMADO+"\',\'"+oObj.aData.COD_FISCALIZACION+"\',\'"+oObj.aData.CODEMPRESA+"\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                    }
                    if (oObj.aData.COD_RESPUESTA == '100'){
                        gestion = "<button onclick=\"aprobar3(\'"+oObj.aData.NUM_AUTOGENERADO+"\',\'"+oObj.aData.DOCUMENTOS_ALEGATOS+"\',\'"+oObj.aData.COD_FISCALIZACION+"\',\'"+oObj.aData.CODEMPRESA+"\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                    }
                    return gestion;
                }
            }
        ]

    });
    
    function aprobar(num_auto,nom_documento,cod_fis,nit){
    $(".preload, .load").show();
    var url='<?= base_url('index.php/autopruebas/aprobar_cor') ?>'
    $('#resultado').load(url,{num_auto:num_auto,nit:nit,nom_documento:nom_documento,cod_fis:cod_fis});
    }
    function aprobar2(num_auto,nom_documento,cod_fis,nit){
    $(".preload, .load").show();
    var url='<?= base_url('index.php/autopruebas/aprobar_cor2') ?>'
    $('#resultado').load(url,{num_auto:num_auto,nit:nit,nom_documento:nom_documento,cod_fis:cod_fis});
    }
    function aprobar3(num_auto,nom_documento,cod_fis,nit){
    $(".preload, .load").show();
    var url='<?= base_url('index.php/autopruebas/aprobar_cor3') ?>'
    $('#resultado').load(url,{num_auto:num_auto,nit:nit,nom_documento:nom_documento,cod_fis:cod_fis});
    }

    function setTitleModal(titulo) {
        document.getElementById('titleModal').innerHTML = titulo;
    }


    $(function() {

        $('#modal').on('hidden', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        });
    });

    function generarResolucion(codfisc) {
        var url = "<?= base_url() ?>index.php/notificacionacta/manage";
        var opc = 3;// contrato de aprendizaje
        redirect_by_post(url, {
            cod_fiscalizacion: codfisc,
            opc: opc
        }, false);
    }

    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }
    $(".preload, .load").hide();

</script>