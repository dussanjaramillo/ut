<?php
// Responsable: Leonardo Molina
if (isset($message)) {
    echo $message;
}
?>

<h1>Consultar Cartera y Pago</h1>

<table id="tablaq">
    <thead>
        <tr>
            <th>N. Ejecutoria</th>
            <th>Nit Empresa</th>
            <th>Nombre Empresa</th>
            <th>Saldo cartera</th>
            <th>Tipo cartera</th>
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
                <h4 class="modal-title">Consultar Cartera y Pago</h4>
            </div>
            <div class="modal-body" >
                <div align="center">
                    <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />  
                </div>
            </div>
            <!--        <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                      <a href="#" class="btn btn-primary mce-text" id="text-mce_127">Continuar</a>
                    </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="resultado"></div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json

    var gestion = "";
    $('#tablaq').dataTable({
  
        "sServerMethod": "POST",
        "bJQueryUI": true,
        "bProcessing": true,
        "bServerSide": true,
        "bPaginate": true,
        "iDisplayStart": 0,
        "iDisplayLength": 10,
        "sSearch": '',
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/consultarcarteraycapacitacion/getData",
        "aoColumns": [
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "COD_EJECUTORIA"},
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "NITEMPRESA"},
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "NOMBRE_EMPLEADOR"},
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "VALOR_TOTAL"},
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "NOMBRE_CONCEPTO"},
            {"bSearchable": true, "bSortable": true, "sClass": "center", "mDataProp": "NOMBRE_GESTION"},
            //Link Gestion
            {"sClass": "item", "mData": null,
                "bSearchable": true,
                "sWidth": "12%",
                "bSortable": false,
                "fnRender": function(oObj) {
                    gestion = "";
                    switch (oObj.aData.COD_TIPOGESTION) {
                        case "434":
                            var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "','" + oObj.aData.DETALLE_COBRO_COACTIVO + "'";
                            gestion = '<button title="Subir Documento" class="fa fa-archive" onclick="subir_documento_juridico(' + cargar + ')" > Subir Documento</button>';
                            break;
                        case 433:
                        case '433':
                            var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                            gestion = '<button title="Generar Documento" class="fa fa-asterisk " onclick="gestionar_documento_juridico2(' + cargar + ')" > Documento Cobro Coactivo</button>';
                            break;
                        default :
//                            if (oObj.aData.COD_TIPOGESTION == 421)
//                                gestion += "<button type=\"button\" id=\"gestion421\" onclick=\"gestion421('" + oObj.aData.COD_EJECUTORIA + "','" + oObj.aData.NUMERO_RESOLUCION + "')\" style=\"width:138px; text-align:left;\" class=\"btn btn-small\"  data-keyboard=\"false\" data-backdrop=\"static\" title=\"Revocatoria\"><i class=\"fa fa-pencil-square-o\"></i> Revocatoria parcial?</button>";
                            if (oObj.aData.COD_TIPOGESTION == 74 || oObj.aData.COD_TIPOGESTION == 422 || oObj.aData.COD_TIPOGESTION==421)
                                gestion += "<button type=\"button\" href='<?= base_url() ?>index.php/consultarcarteraycapacitacion/consultarCartera/" + oObj.aData.COD_EJECUTORIA + "/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" style=\"width:138px; text-align:left;\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Consultar\"><i class=\"fa fa-pencil-square-o\"></i> Consultar</button>";
                            else
                                gestion += "<button type=\"button\" style=\"width:138px; text-align:left;\" class=\"btn btn-small\" disabled=\"true\" title=\"Consultar\"><i class=\"fa fa-pencil-square-o\"></i> Consultar</button>";
                            if (oObj.aData.COD_RESPUESTA == 148 ||
                                    oObj.aData.COD_RESPUESTA == 190)
                                gestion += "<button type=\"button\"href='<?= base_url() ?>index.php/consultarcarteraycapacitacion/documentoCobro/" + oObj.aData.COD_EJECUTORIA + "/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"DocCobro\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Documento cobro\"><i class=\"fa fa-book\"></i> Documento Cobro</button>";
                            else if (oObj.aData.COD_RESPUESTA == 186)
                                gestion += "<button type=\"button\"href='<?= base_url() ?>index.php/consultarcarteraycapacitacion/documentoCobro/" + oObj.aData.COD_EJECUTORIA + "/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"DocCobro\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Documento cobro\"><i class=\"fa fa-book\"></i>Subir el Documento</button>";
                            else if (oObj.aData.COD_RESPUESTA == 1109)
                                gestion += "<button type=\"button\"  style=\"width:138px; text-align:left;\"class=\"btn btn-small\" data-keyboard=\"false\" onclick=\"llamadas('" + oObj.aData.COD_EJECUTORIA + "')\" title=\"Documento cobro\"><i class=\"fa fa-book\"></i>Gestion de Llamadas</button>";
                            else
                                gestion += "<button type=\"button\" id=\"DocCobro\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" disabled=\"true\"title=\"Documento cobro\"><i class=\"fa fa-book\"></i> Documento Cobro</button>";
                            if (oObj.aData.COD_RESPUESTA == 190 || oObj.aData.COD_RESPUESTA == 1109)
                                gestion += "<button type=\"button\"href='<?= base_url() ?>index.php/consultarcarteraycapacitacion/voluntadPago/" + oObj.aData.COD_EJECUTORIA + "/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Voluntad de Pago\"><i class=\"fa fa-usd\"></i> Voluntad Pago</button>";
                            else
                                gestion += "<button type=\"button\" id=\"Gestionar\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" disabled=\"true\"title=\"Voluntad de Pago\"><i class=\"fa fa-usd\"></i> Voluntad Pago</button>";

                            if (oObj.aData.COD_RESPUESTA == 152) {
                                gestion = "<button type=\"button\" id=\"PagoTotal\" style=\"width:138px; text-align:left;\"class=\"btn btn-small\" title=\"Documento cobro\" onclick=\"pagoTotal(" + oObj.aData.NITEMPRESA + ") \"><i class=\"fa fa-book\"></i> Pago Total</button>";
                            } else if (oObj.aData.COD_RESPUESTA == 153) {
                                gestion = "<a type=\"button\" href='<?= base_url() ?>index.php/acuerdodepago/'id=\"AcuerdoPago\" style=\"width:116px; text-align:left;\"class=\"btn btn-small\" title=\"Documento cobro\"><i class=\"fa fa-book\"></i> Facilidad de pago</a>";
                            } else if (oObj.aData.COD_RESPUESTA == 154) {
                                gestion = "<a type=\"button\" href='<?= base_url() ?>index.php/recepciontitulos/' id=\"CobroCoactivo\" style=\"width:116px; text-align:left;\"class=\"btn btn-small\" title=\"Documento cobro\"><i class=\"fa fa-book\"></i> Cobro Coactivo</a>";
                            }
                    }

                    return gestion;

                }
            }
        ]
    });
    function subir_documento_juridico(id, cod_fis, nit, cod_revocatoria, documentos) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/subir_documento_juridico') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria, documentos: documentos});
    }
    function gestionar_documento_juridico2(id, cod_fis, nit, cod_revocatoria) {
        $(".preload, .load").show();
        var cod_estado = 434;
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/gestionar_documento_juridico2') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria, cod_estado: cod_estado});
    }
    function llamadas(id_ejecutoria) {
        jQuery(".preload, .load").show();
        var url = '<?php echo base_url('index.php/consultarcarteraycapacitacion/llamadas') ?>';
        $('#resultado').load(url, {id_ejecutoria: id_ejecutoria});
    }
    function gestion421(id_ejecutoria, num_resolucion) { // en caso de aya una revocatoria hay que preguntar si es parcia o total
        jQuery(".preload, .load").show();
        var url = '<?php echo base_url('index.php/consultarcarteraycapacitacion/pregunta_revocatoria') ?>';
        $('#resultado').load(url, {id_ejecutoria: id_ejecutoria, num_resolucion: num_resolucion});
    }

    $(function() {

        $('#modal').on('hidden', function() {
            $('#modal').modal('hide').removeData();
        });
        $('#cancelar').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        });
        $('.close').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        });
    });

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

    function pagoTotal(nit) {
        var url = "<?= base_url() ?>index.php/aplicacionmanualdepago/aplicarpago";
        redirect_by_post(url, {
            idEmpresa: nit
        }, false);

    }
    jQuery(".preload, .load").hide();
</script>
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