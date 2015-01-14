<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<?php
// Responsable: Leonardo Molina
if (isset($message)) {
    echo $message;
}
?>
<a type="button" class="btn btn-info" href="<?= base_url() ?>index.php/autocargos"><i class="fa fa-gear"></i>
    <span>Volver a Generar Auto de cargos. </span>
</a>
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
<div id="formulario1"></div>

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
        "sAjaxSource": "<?php echo base_url(); ?>index.php/autopruebas/getData?accion=<?php echo $info; ?>", //1 abogado // 2 coordinador
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
                            var data = "";
                            console.log(oObj.aData.COD_RESPUESTA);
                            if (oObj.aData.COD_RESPUESTA == '1326') {
                                gestion = "<button onclick=\"aprobar(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NOMBRE_DOC_GENERADO + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                            }else if (oObj.aData.COD_RESPUESTA == '98') {
                                gestion = "<button onclick=\"aprobar2(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NOMBRE_DOC_FIRMADO + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                            }else if (oObj.aData.COD_RESPUESTA == '1340') {
                                gestion = "<button onclick=\"aprobar3(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.DOCUMENTOS_ALEGATOS + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                            } else if (oObj.aData.COD_RESPUESTA == '1327') {
                                gestion = "<button onclick=\"subir_auto(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NOMBRE_DOC_GENERADO + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Subir Documento</button>";
                            } else if (oObj.aData.COD_RESPUESTA == '1328' || oObj.aData.COD_TIPOGESTION == '29' || oObj.aData.COD_RESPUESTA == '884' || oObj.aData.COD_TIPOGESTION == '311') {
                                gestion = "<button onclick=\"cargar(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NOMBRE_DOC_GENERADO + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Citación\"><i class=\"fa fa-pencil-square-o\"></i> Generar Citaci&oacuten</button>";
                            } else if (oObj.aData.COD_TIPOGESTION == '37') {
                                gestion = "<button onclick=\"realizar_acta(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Citación\"><i class=\"fa fa-pencil-square-o\"></i> Realizar Acta</button>";
                            } else if (oObj.aData.COD_RESPUESTA == '31' && oObj.aData.COD_TIPOGESTION == '313') {
                                gestion = "<button onclick=\"subir_citacion(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Subir Documento\"><i class=\"fa fa-pencil-square-o\"></i> Subir Documento</button>";
                            } else if (
                                    oObj.aData.COD_TIPOGESTION == '38'
                                    || oObj.aData.COD_TIPOGESTION == '39'
                                    || oObj.aData.COD_TIPOGESTION == '40') {
                                gestion = "<button onclick=\"subir_acta(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Subir acta\"><i class=\"fa fa-pencil-square-o\"></i> Subir Acta</button>";
                            } else if (oObj.aData.COD_RESPUESTA == '54') {
                                if (oObj.aData.COD_TIPOGESTION == '313') {
                                    gestion = "<button onclick=\"subir_citacion(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Subir Documento\"><i class=\"fa fa-pencil-square-o\"></i> Subir Documento</button>";
                                }
                                if (oObj.aData.COD_TIPOGESTION == '36') {
                                    gestion = "<button onclick=\"citacion_resep(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Subir Documento\"><i class=\"fa fa-pencil-square-o\"></i>Recepción de la citación</button>";
                                }
                            } else if (oObj.aData.COD_RESPUESTA == '37') {
                                gestion = "<button onclick=\"llego(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.TIPOGESTION + "\',\'" + oObj.aData.FECHA_RECEPCION + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"SE PRESENTO ?\">Se Presento <i class=\"fa fa-question\"></i> </button>";
                            }
                            else {
                                if (oObj.aData.COD_RESPUESTA == '91') {
                                    gestion = "<button onclick=\"setTitleModal(\'Generar Descargos\')\" style=\"width:135px; text-align:left;\" href='<?= base_url() ?>index.php/autopruebas/respAuto/" + oObj.aData.NUM_AUTOGENERADO + "' id=\"Gestionar\"class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square-o\"></i> Respuesta</button>";
                                } else {
                                    gestion = "<button href=\"#\" style=\"width:135px; text-align:left;\" class=\"btn btn-small\" id=\"Gestionar\" disabled=\"true\" title=\"Respuesta Auto\"><i class=\"fa fa-pencil-square\"></i> Respuesta</button>";
                                }
                                if (oObj.aData.COD_RESPUESTA == '96' || oObj.aData.COD_RESPUESTA == '95' || oObj.aData.COD_RESPUESTA == '101') {
                                    gestion += "<button onclick=\"setTitleModal(\'Generar Auto de pruebas\')\" style=\"width:135px; text-align:left;\" href='<?= base_url() ?>index.php/autopruebas/pruebasAuto/" + oObj.aData.NUM_AUTOGENERADO + "/" + oObj.aData.CODEMPRESA + "/1"+ "/" + oObj.aData.COD_FISCALIZACION +"' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Auto de Pruebas\"><i class=\"fa fa-pencil-square\"></i> Auto Pruebas</button>";
                                }else if (oObj.aData.COD_RESPUESTA == '99') {
                                    gestion += "<button onclick=\"setTitleModal(\'Generar Auto de pruebas\')\" style=\"width:135px; text-align:left;\" href='<?= base_url() ?>index.php/autopruebas/pruebasAuto/" + oObj.aData.NUM_AUTOGENERADO + "/" + oObj.aData.CODEMPRESA + "/2"+ "/" + oObj.aData.COD_FISCALIZACION +"' id=\"Gestionar\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Auto de Pruebas\"><i class=\"fa fa-pencil-square\"></i> Auto Pruebas</button>";
                                } else {
                                    gestion += "<button href=\"#\" style=\"width:135px; text-align:left;\" class=\"btn btn-small\" id=\"Gestionar\" disabled=\"true\" title=\"Auto de Pruebas\"><i class=\"fa fa-pencil-square\"></i> Auto Pruebas</button>";
                                }
                                if (oObj.aData.COD_RESPUESTA == '102' || oObj.aData.COD_RESPUESTA == '104' || oObj.aData.COD_RESPUESTA == '105' || oObj.aData.COD_RESPUESTA == '891') {
                                    gestion += "<button onclick=\"setTitleModal(\'Recibir pruebas y traslado alegatos\')\" style=\"width:135px; text-align:left;\" href='<?= base_url() ?>index.php/autopruebas/recibirPruebas/" + oObj.aData.NUM_AUTOGENERADO + "/" + oObj.aData.CODEMPRESA + "' id=\"Recibir Pruebas\" class=\"btn btn-small\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\" title=\"Recibir\"><i class=\"fa fa-pencil-square-o\"></i> Recibir</button>";
                                } else {
                                    gestion += "<button href=\"#\" style=\"width:135px; text-align:left;\" class=\"btn btn-small\" id=\"Gestionar\" disabled=\"true\" title=\"Recibir\"><i class=\"fa fa-pencil-square-o\"></i> Recibir</button>";
                                }
                                if (oObj.aData.COD_RESPUESTA != '770') {
                                    gestion += "<button onclick=\"setTitleModal(\'Traslado Alegatos\')\" href='<?= base_url() ?>index.php/autopruebas/newTraslado/" + oObj.aData.NUM_AUTOGENERADO + "/" + oObj.aData.CODEMPRESA + "' style=\"width:135px; text-align:left;\" id=\"trasAlega\" class=\"btn btn-small\"  title=\"Traslado Alegatos\" data-toggle=\"modal\" data-target=\"#modal\" data-keyboard=\"false\" data-backdrop=\"static\"><i class=\"fa fa-file-text-o\"></i> Traslado Alegatos</button>";
                                } else {
                                    gestion += "<button href=\"#\" disabled=\"true\" style=\"width:135px; text-align:left;\" id=\"trasAlega\" class=\"btn btn-small\"  title=\"Traslado Alegatos\" ><i class=\"fa fa-file-text-o\"></i> Traslado Alegatos</button>";
                                }
                                if (oObj.aData.COD_RESPUESTA == '770') {
                                    data = '"' + oObj.aData.COD_FISCALIZACION + '"';
                                    gestion += "<button style=\"width:135px; text-align:left;\" onclick='generarResolucion(" + data + ")' id=\"Generar Resolución\" class=\"btn btn-small\"  title=\"Generar resolución\"><i class=\"fa fa-file-text-o\"></i> Resolución</button>";
                                }else if (oObj.aData.COD_RESPUESTA == '94' ) {
                                    data = '"' + oObj.aData.COD_FISCALIZACION + '"';
                                    gestion = "<button style=\"width:135px; text-align:left;\" onclick='generarResolucion(" + data + ")' id=\"Generar Resolución\" class=\"btn btn-small\"  title=\"Generar resolución\"><i class=\"fa fa-file-text-o\"></i> Resolución</button>";
                                }else if (oObj.aData.COD_RESPUESTA == '102' ) {
                                    data = '"' + oObj.aData.COD_FISCALIZACION + '"';
                                    gestion += "<button style=\"width:135px; text-align:left;\" onclick='generarResolucion(" + data + ")' id=\"Generar Resolución\" class=\"btn btn-small\"  title=\"Generar resolución\"><i class=\"fa fa-file-text-o\"></i> Resolución</button>";
                                }else if (oObj.aData.COD_RESPUESTA == '104' ) {
                                    data = '"' + oObj.aData.COD_FISCALIZACION + '"';
                                    gestion += "<button style=\"width:135px; text-align:left;\" onclick='generarResolucion(" + data + ")' id=\"Generar Resolución\" class=\"btn btn-small\"  title=\"Generar resolución\"><i class=\"fa fa-file-text-o\"></i> Resolución</button>";
                                } else {
                                    gestion += "<button href=\"#\" style=\"width:135px; text-align:left;\" class=\"btn btn-small\" id=\"Gestionar\" disabled=\"true\" title=\"Generar resolución\"><i class=\"fa fa-file-text-o\"></i>  Resolución</button>";
                                }
                            }
                            gestion += "<button onclick=\"pdf(\'" + oObj.aData.NUM_AUTOGENERADO + "\',\'" + oObj.aData.NUM_CITACION + "\',\'" + oObj.aData.COD_FISCALIZACION + "\',\'" + oObj.aData.CODEMPRESA + "\')\" style=\"width:135px; text-align:left;\" id=\"Gestionar\"class=\"btn btn-small\"  title=\"Documentos ?\">  Ver Documentos </button>";
                            return gestion;
                        }
                    }
                ]

            });
            function pdf(id, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/ver_documentos') ?>";
                $('#resultado').load(url, {id: id,cod_fis:cod_fis});
            }
            function subir_acta(id, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/subir_acta') ?>";
                $('#resultado').load(url, {id: id, num_citacion: num_citacion, cod_fis: cod_fis, nit: nit});
            }
            function realizar_acta(id, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/realizar_acta') ?>";
                $('#formulario1').load(url, {id: id, cod_fis: cod_fis, nit: nit});
            }
            function llego(id, gestion, fecha, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var ruta = "<?php echo base_url("index.php/tiempos/bloqueo"); ?>";
                $.post(ruta, {gestion: gestion, fecha: fecha, codfiscalizacion: cod_fis})
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
                                var url = "<?php echo base_url("index.php/autopruebas/bloqueo"); ?>";
                                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                            } else {
                                var url = "<?php echo base_url('index.php/autopruebas/bloqueo_por_time'); ?>";
                                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion, texto: texto, comienza: comienza, vence: vence});
                            }
                        }).fail(function(msg) {
                    alert("ERROR");
                })
            }
            function citacion_resep(id, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/desicion') ?>";
                $('#formulario1').load(url, {id: id, cod_fis: cod_fis, nit: nit});
            }
            function subir_citacion(id, num_citacion, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/subir_citacion') ?>";
                $('#resultado').load(url, {id: id, num_citacion: num_citacion, cod_fis: cod_fis, nit: nit});
            }
            function subir_auto(id, nom_documento, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/subir_doc') ?>";
                $('#resultado').load(url, {id: id, cod_fis: cod_fis, nom_documento: nom_documento, nit: nit});
            }
            function cargar(id, nom_documento, cod_fis, nit) {
                $(".preload, .load").show();
                var url = "<?php echo base_url('index.php/autopruebas/citacion') ?>";
                $('#resultado').load(url, {id: id, nom_documento: nom_documento, cod_fis: cod_fis, nit: nit});
            }
            function aprobar2(num_auto, nom_documento, cod_fis, nit) {
                $(".preload, .load").show();
                var url = '<?= base_url('index.php/autopruebas/rechazo2') ?>'
                $('#resultado').load(url, {num_auto: num_auto, nit: nit, nom_documento: nom_documento, cod_fis: cod_fis});
            }
            function aprobar3(num_auto, nom_documento, cod_fis, nit) {
                $(".preload, .load").show();
                var url = '<?= base_url('index.php/autopruebas/rechazo3') ?>'
                $('#resultado').load(url, {num_auto: num_auto, nit: nit, nom_documento: nom_documento, cod_fis: cod_fis});
            }
            function aprobar(num_auto, nom_documento, cod_fis, nit) {
                $(".preload, .load").show();
                var url = '<?= base_url('index.php/autopruebas/rechazo') ?>'
                $('#resultado').load(url, {num_auto: num_auto, nit: nit, nom_documento: nom_documento, cod_fis: cod_fis});
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