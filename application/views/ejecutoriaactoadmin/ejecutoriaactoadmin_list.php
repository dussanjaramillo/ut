<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
// Responsable: Leonardo Molina
if (isset($message)) {
    echo $message;
}
?>
<a type="button" class="btn btn-info" href="<?= base_url() ?>index.php/consultarcarteraycapacitacion"><i class="fa fa-gear"></i>
    <span>Consultar Cartera y Pago </span>
</a>

<h1>Ejecutoria acto administrativo</h1>

<table id="tablaq">
    <thead>
        <tr>
            <th>Número resolución</th>
            <th>Nit Empleador</th>
            <th>Nombre Empleador</th>
            <th>Estado resolución</th>
            <th>Tipo Cartera</th>
            <th>Fecha</th>
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
                <h4 class="modal-title">Gestión Ejecutoria Acto Administrativo</h4>
            </div>
            <div class="modal-body" >
                <div align="center">
                    <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />  
                </div>
            </div>
            <!--        <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
                        <a href="#" class="btn btn-primary mce-text ejecutoria" id="text-mce_127">Ejecutoriar Resolución</a>
                    </div>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="resultado" ></div>
<div id="formulario1" ></div>

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
        "iDisplayLength": 10,
        "sPaginationType": "full_numbers",
        "sSearch": '',
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/ejecutoriaactoadmin/getData?accion=<?php echo $accion; ?>",
                "aoColumns": [
                    {"mDataProp": "NUMERO_RESOLUCION"},
                    {"mDataProp": "NITEMPRESA"},
                    {"mDataProp": "NOMBRE_EMPRESA"},
                    {"mDataProp": "TIPOGESTION"},
                    {"mDataProp": "NOMBRE_CONCEPTO"},
                    {"mDataProp": "FECHA_CREACION"},
                    //Link Gestion
                    {"sClass": "center", "mData": null,
                        "bSearchable": true,
                        "bSortable": false,
                        "fnRender": function(oObj) {
                            gestion = "";
                            switch (oObj.aData.COD_ESTADO) {
                                case "410":
                                case "414":
                                case "415":

                                    if (oObj.aData.COD_ESTADO == "410")
                                        var info = " Citacion Personal";
                                    else if (oObj.aData.COD_ESTADO == "414")
                                        var info = " Citacion Articulo 68";
                                    else if (oObj.aData.COD_ESTADO == "415")
                                        var info = " Citacion por aviso fijado";
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
                                    gestion += '<button title="Realizar Citación" class="fa fa-envelope-o" onclick="cargar(' + cargar + ')" >' + info + '</button>';
                                    break;
                                case "427":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                    gestion += '<button title="Generar Documento" class="fa fa-asterisk " onclick="gestionar_documento_juridico2(' + cargar + ')" > Documento Cobro Coactivo</button>';
                                    break;
                                case "425":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                    gestion += '<button title="Recepcion de llamadas" class="fa fa-phone-square" onclick="generacion_llamadas(' + cargar + ')" > Recepci&oacute;n de llamada</button>';
                                    cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
                                    gestion += '<button title="Enviar a" class="fa fa-asterisk " onclick="enviar_datos(' + cargar + ')" > Gestionar</button>';
                                    break;
                                case "424":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                    gestion += '<button title="Subir Documento" class="fa fa-archive" onclick="subir_documento_juridico2(' + cargar + ')" > Subir Documento</button>';
                                    break;
                                case "418":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "','" + oObj.aData.DETALLE_COBRO_COACTIVO + "'";
                                    gestion += '<button title="Subir Documento" class="fa fa-archive" onclick="subir_documento_juridico(' + cargar + ')" > Subir Documento</button>';
                                    break;
                                case "418":
                                    gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/gestionRevocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil\"></i> Generar Citaci&oacute;n</button>";
                                    break;
                                case "417":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                    gestion += '<button title="Generar Documento" class="fa fa-asterisk " onclick="gestionar_documento_juridico(' + cargar + ')" > Documento Cobro</button>';
                                    break;
                                case "416":
                                    console.log();
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
//                                    gestion = '<button title="Enviar a" class="fa fa-asterisk " onclick="enviar_datos(' + cargar + ')" > Gestionar</button>';
                                    if (oObj.aData.RES_REVOCATORIA == 'N') {
                                        if (oObj.aData.EJECUTORIA && oObj.aData.RES_REVOCATORIA == 'N') {
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" disabled=\"true\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                            var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                            gestion += '<button title="Generar Documento" class="fa fa-asterisk " onclick="gestionar_documento_juridico(' + cargar + ')" > Documento Cobro</button>';
                                        } else if (oObj.aData.EJECUTORIA) {
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" disabled=\"true\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                        } else {
//                                            gestion = '<button title="Enviar a" class="fa fa-asterisk " onclick="enviar_datos(' + cargar + ')" > Gestionar</button>';
                                            var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_REVOCATORIA + "'";
                                            gestion += '<button title="Generar Documento" class="fa fa-asterisk " onclick="gestionar_documento_juridico(' + cargar + ')" > Documento Cobro</button>';
                                        }
                                    } else {
                                        gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                    }
//                                        gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                    break;
                                case "411":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.NUMERO_CITACION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
                                    gestion += '<a href="javascript:" title="Subir Documento" class="fa fa-archive" onclick="subir_citacion(' + cargar + ')"></a>';
                                    break;
                                case "412":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
                                    gestion += '<a href="javascript:" title="Recepcion de la citación" class="fa fa-cloud-upload" onclick="citacion_resep(' + cargar + ')"></a>';
                                    break;
                                case "413":
                                    var cargar = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_ESTADO + "','" + oObj.aData.FECHA_ENVIO_CITACION + "','" + oObj.aData.NUMERO_CITACION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "'";
                                    gestion += '<a href="javascript:" title="SE PRESENTO ?" class="fa fa-question" onclick="llego(' + cargar + ')"></a>';
                                    break;
                                default :
                                    if (oObj.aData.REVOCATORIA) {
                                        if (oObj.aData.REVOCATORIA2 == '0') {
                                            gestion += "<button style='width: 137px' onclick=\"pre_aprobar('" + oObj.aData.COD_REVOCATORIA + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.COD_CPTO_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_RESOLUCION + "')\" class=\"btn btn-small\" ><i class=\"fa fa-pencil-square-o\"></i> Pre-Aprobar</button>";
                                        } else if (oObj.aData.REVOCATORIA2 == '2') {
                                            gestion += "<button style='width: 137px' onclick=\"correcion_aprobar('" + oObj.aData.COD_REVOCATORIA + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.COD_CPTO_FISCALIZACION + "','" + oObj.aData.NITEMPRESA + "','" + oObj.aData.COD_RESOLUCION + "')\" class=\"btn btn-small\" ><i class=\"fa fa-pencil-square-o\"></i> Correccion Revocatoria</button>";
                                        } else if (oObj.aData.RES_REVOCATORIA == 'N') {
                                            if (oObj.aData.EJECUTORIA && oObj.aData.RES_REVOCATORIA == 'N') {
                                                gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" disabled=\"true\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                                gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/gestionRevocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil\"></i> Generar Citaci&oacute;n</button>";
                                            } else if (oObj.aData.EJECUTORIA) {
                                                gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" disabled=\"true\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                            } else {
//                                                gestion = "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
//                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/revocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Revocatoria\"><i class=\"fa fa-book\"></i> Revocatoria</button>";
                                                gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/gestionRevocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil\"></i> Generar Citaci&oacute;n</button>";
                                            }
                                        } else if (oObj.aData.RES_REVOCATORIA == 'S' && oObj.aData.EJECUTORIA) {
//                                            AKAAAAAAAAAAA
                                        } else if (oObj.aData.DOC_RESPUESTA_FIRMADO != '' && oObj.aData.DOC_RESPUESTA_FIRMADO != null && oObj.aData.DOC_RESPUESTA_FIRMADO != 'NULL') {
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/gestionRevocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil\"></i> Generar Citaci&oacute;n</button>";
//ejecutoria                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                        } else {
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/gestionRevocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil\"></i> Firmar revocatoria</button>";
                                        }
                                    } else {
//                            console.log(oObj.aData);
                                        if (oObj.aData.EJECUTORIA)
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" disabled=\"true\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
                                        else
                                            gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/planilla/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Ejecutoriar\"><i class=\"fa fa-pencil-square-o\"></i> Ejecutoriar</button>";
//                                     if (oObj.aData.REVISO!=1)   
                                        gestion += "<button style='width: 137px' href='<?= base_url() ?>index.php/ejecutoriaactoadmin/revocatoria/" + oObj.aData.NUMERO_RESOLUCION + "' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Revocatoria\"><i class=\"fa fa-book\"></i> Revocatoria</button>";
                                    }

//                        gestion += "<button style='width: 137px' href='<?base_url()?>index.php/ejecutoriaactoadmin/tales/" + oObj.aData.COD_FISCALIZACION +"' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Revocatoria\"><i class=\"fa fa-book\"></i> tales</button>";
                            }
                            return gestion;
//                            return gestion+oObj.aData.COD_ESTADO;

                        }
                    }
                ]

            });

            $(function() {
                $('.modal').on('hidden', function() {
                    $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
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

            function pre_aprobar(id, cod_fis, concepto, nit, id_resolucion) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/pre_revocatoria') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, concepto: concepto, nit: nit, id_resolucion: id_resolucion});
            }
            function correcion_aprobar(id, cod_fis, concepto, nit, id_resolucion) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/correcion_aprobar') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, concepto: concepto, nit: nit, id_resolucion: id_resolucion});
            }
            $(".preload, .load").hide();
            function cargar(id, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/resolucion/citacion') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
            }
            function enviar_datos(id, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/confirmar') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
//alert('queda pendiente por falta de informacion');
            }
            function generacion_llamadas(id, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/llamadas') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
            }
            function subir_documento_juridico(id, cod_fis, nit, cod_revocatoria, documentos) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/subir_documento_juridico') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria, documentos: documentos});
            }
            function subir_documento_juridico2(id, cod_fis, nit, cod_revocatoria) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/subir_documento_juridico2') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria});
            }
            function gestionar_documento_juridico(id, cod_fis, nit, cod_revocatoria) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/gestionar_documento_juridico') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria});
            }
            function gestionar_documento_juridico2(id, cod_fis, nit, cod_revocatoria) {
                $(".preload, .load").show();
                var cod_estado = 418;
                var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/gestionar_documento_juridico2') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria, cod_estado: cod_estado});
            }
            function subir_citacion(id, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/resolucion/subir_citacion') ?>";
                $('#resultado').load(url, {id: id, num_citacion: num_citacion, cod_fis: cod_fis, nit: nit});
            }
            function citacion_resep(id, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/resolucion/desicion') ?>";
                $('#formulario1').load(url, {id: id, cod_fis: cod_fis, nit: nit});
            }
            function llego(id, gestion, fecha, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
                $.post(ruta, {gestion: gestion, fecha: fecha})
                        .done(function(msg) {
//                    var datos = eval(msg);
//                    alert(msg.vencido);
                            var texto = msg.texto;
                            var comienza = msg.comienza;
                            var vence = msg.vence;
                            if (msg.bandera == "0") {
                                vence = "Recordatorio Pendiente";
                                comienza = "Recordatorio Pendiente";
                                texto = "Recordatorio Pendiente";
                            }
                            if ($.trim(msg.vencido) == "1") {
                                var url = "<?php echo base_url("index.php/resolucion/bloqueo"); ?>";
                                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                            } else {
                                var url = "<?php echo base_url('index.php/resolucion/bloqueo_por_time'); ?>";
                                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                            }
                        }).fail(function(msg) {
                    alert("ERROR");
                })
            }
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


