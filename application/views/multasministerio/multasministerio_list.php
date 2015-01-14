<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
// Responsable: Leonardo Molina

if (isset($custom_error))
    echo $custom_error;
if (isset($message))
    echo $message;
//,'required'=>'true'
$nit = array('name' => 'nit', 'id' => 'nit', 'placeholder' => 'Nit empresa', 'autofocus' => "true");
$button = array('name' => 'nuevo', 'id' => 'nuevo', 'value' => 'Agregar Multa', 'content' => 'Agregar Multa', 'type' => 'submit', 'class' => 'btn btn-primary buscar');
$attributes = array('class' => 'mycustomclass', 'style' => 'color: #000; font-weight:bold;');
?>
<h1>Multas del Ministerio</h1>
<div class="center-form" >
    <?= form_open(base_url() . 'index.php/multasministerio/crearMulta') ?>
    <table>
        <tr><td><?= form_label('Nit de la Empresa', 'nit', $attributes); ?></td></tr>
        <tr>
            <td><?= form_input($nit); ?></td>
            <td><button type="button" class="btn btn-success" onclick="buscarMulta()"><i class="fa fa-search fa-lg"></i> Buscar</button></td>

        </tr>
    </table>
    <!--<td><a href="<?= base_url('index.php/multasministerio/crearMulta') ?>" class="btn btn-success " id="buscar"><i class="fa fa-search fa-lg"></i>Agregar Multa</a></td>-->
    <?= form_button($button); ?>
    <?= form_close() ?>
    <span id="loader" style="display: none; float: right; position: relative">
        <img src="<?= base_url() ?>/img/27.gif" width="40px" height="40px" />
    </span>

</div>
<br><br>
<div id="tablaMostrar">
    <table id="tablaq">
        <thead>
            <tr>
                <th style="width: 10%;">Codigo Multa</th>
                <th>Nit Empresa</th>
                <th style="width: 40%;">Nombre Empresa</th>
                <th>Fecha Creación</th>
                <th>Valor</th>
                <th>Exigible</th>
                <th>Gestionar</th>
            </tr>
        </thead>
        <tbody ></tbody>     
    </table>
</div>
<!-- Modal External-->

<div class="modal hide fade in" id="modal" style="display: none; width: 60%; margin-left: -30%;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detalle multas</h4>
            </div>
            <div class="modal-body">
                <div align="center">
                    <img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" />  
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Volver</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="resultado"></div>
<form id="form5" action="<?= base_url() ?>index.php/multasministerio/edidtar_Multa" method="post">
    <input id="cod_multa" name="cod_multa" type="hidden">
    <input class="nit" name="nit" type="hidden">
</form>
<script type="text/javascript" language="javascript" charset="utf-8">

    var oTable;
    function buscarMulta() {
        oTable.fnDraw();
    }

    var gestion = '';
    var gestion2 = '';

    oTable = $('#tablaq').dataTable({
        "sServerMethod": "POST",
        "bJQueryUI": true,
        "bProcessing": true,
        "bServerSide": true,
        "bPaginate": true,
        "iDisplayStart": 0,
        "iDisplayLength": 10,
        "sSearch": '',
        "fnServerParams": function(aoData) {
            aoData.push({"name": "nit", "value": $('#nit').val()});
        },
        "autoWidth": true,
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?= base_url() ?>index.php/multasministerio/getData",
        "aoColumns": [
            {"mDataProp": "COD_MULTAMINISTERIO"},
            {"mDataProp": "NIT_EMPRESA"},
            {"mDataProp": "NOMBRE_EMPRESA"},
            {"mDataProp": "FECHA_CREACION"},
            {"mDataProp": "VALOR"},
            //{ "mDataProp": "EXIGIBILIDAD_TITULO" },
            {"sClass": "item", sWidth: '12%', "mData": null,
                "bSearchable": true,
                "bSortable": false,
                "fnRender": function(oObj) {
                    if (oObj.aData.EXIGIBILIDAD_TITULO == '1')
                        gestion2 = 'EXIGIBLE';
                    else
                        gestion2 = 'N/A';
                    return gestion2;
                }
            },
            //Link Gestion
            {"sClass": "item", sWidth: '12%', "mData": null,
                "bSearchable": true,
                "bSortable": false,
                "fnRender": function(oObj) {
                    var gestion = '';
                    var datos = '';
                    // si los datos son exigigles

                    if (oObj.aData.OBSERVACIONES == null) {
                        datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "'";
                        gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="gestionar_documento_juridico2(' + datos + ')" title="Documento Cobro"><i class="fa fa-pencil-square-o"></i> Documento Cobro</button>';
                    } else if (oObj.aData.OBSERVACIONES.indexOf('.txt') != -1) {
                        datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "','" + oObj.aData.NRO_RESOLUCION + "'";
                        gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="subir_documento_juridico(' + datos + ')" title="Subir Documento"><i class="fa fa-pencil-square-o"></i> Subir Documento</button>';
                    } else {

                        if (oObj.aData.CODIGO_RESPUESTA_LIQUIDACION == 0) {
                            if (oObj.aData.EXIGIBILIDAD_TITULO == '1') {
                                datos = "'" + oObj.aData.COD_MULTAMINISTERIO + "','" + oObj.aData.COD_FISCALIZACION + "'";
                                gestion = '<button style="width:155px;" href="#" class="btn btn-small" onclick="generarLiquidacion(' + datos + ')" title="Liquidar"><i class="fa fa-pencil-square-o"></i> Liquidar</button>';
                            }
                        } else {

                            if (oObj.aData.OBSERVACIONES.indexOf('.pdf') != -1 && oObj.aData.NUMERO_COMUNICACION == 1) {
                                datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "'";
                                gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="enviar_datos(' + datos + ')" title="Documento Cobro"><i class="fa fa-pencil-square-o"></i> Enviar a:</button>';
                            } else if (oObj.aData.OBSERVACIONES.indexOf('.pdf') != -1 && oObj.aData.DOCUMENTO_COBRO_COACTIVO == null) {
                                datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "'";
                                gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="gestionar_documento_juridico(' + datos + ')" title="Documento Cobro"><i class="fa fa-pencil-square-o"></i> Documento a Cobro Coactivo</button>';
                            } else if (oObj.aData.DOCUMENTO_COBRO_COACTIVO.indexOf('.txt') != -1) {
                                datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION +"','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "','" + oObj.aData.NRO_RESOLUCION + "'";
                                gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="subir_documento_juridico2(' + datos + ')" title="Subir Documento"><i class="fa fa-pencil-square-o"></i> Subir Documento Coactivo</button>';
                            }
                        }
                        if (gestion == "") {
                            datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "','" + oObj.aData.NRO_RESOLUCION + "'";
                            gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="editar(' + datos + ')" title="Editar"><i class="fa fa-pencil-square-o"></i>Editar</button>';
                        }

                        datos = "'" + oObj.aData.COD_RESOLUCION + "','" + oObj.aData.COD_FISCALIZACION + "','" + oObj.aData.NIT_EMPRESA + "','" + oObj.aData.COD_MULTAMINISTERIO + "','" + oObj.aData.NRO_RESOLUCION + "'";
                        gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="generacion_llamadas(' + datos + ')" title="Historico de Llamadas"><i class="fa fa-pencil-square-o"></i>Historico de Llamadas</button>';

                    }
//                    if (oObj.aData.DOCUMENTO_COBRO_COACTIVO.indexOf('.txt') != -1)
//                        gestion += '<button style="width:155px;" href="#" class="btn btn-small" onclick="subir_documento_juridico(' + oObj.aData.COD_RESOLUCION + ',' + oObj.aData.COD_FISCALIZACION + ',' + oObj.aData.NIT_EMPRESA + ',' + oObj.aData.COD_MULTAMINISTERIO + ',' + oObj.aData.NRO_RESOLUCION + ')" title="Subir Documento"><i class="fa fa-pencil-square-o"></i> Subir Documento a Coactivo</button>';
//                    else 

                    gestion += '<button style="width:155px;" href="<?= base_url() ?>index.php/multasministerio/detalle/' + oObj.aData.COD_MULTAMINISTERIO + '" class="btn btn-small" title="Ver" data-toggle="modal" data-target="#modal" data-keyboard="false" data-backdrop="static"><i class="fa fa-eye"></i> Ver</button>';
                    $(".preload, .load").hide();
                    return gestion;
                }
            }
        ]

    });
    function enviar_datos(id, cod_fis, nit, cod_multa) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/multasministerio/confirmar') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, cod_multa: cod_multa, nit: nit});
//alert('queda pendiente por falta de informacion');
    }
    function subir_documento_juridico2(id, cod_fis, nit) {
        $(".preload, .load").show();
        var documentos = "";
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/subir_documento_juridico') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, documentos: documentos});
    }
    function gestionar_documento_juridico(id, cod_fis, nit, cod_revocatoria) {
        $(".preload, .load").show();
        var cod_estado = '';
        var url = "<?php echo base_url('index.php/multasministerio/gestionar_documento_juridico2') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_revocatoria: cod_revocatoria, cod_estado: cod_estado});
    }

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

    function subir_documento_juridico(id, cod_fis, nit, cod_multa, nresol) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/multasministerio/subir_documento_juridico') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit, cod_multa: cod_multa, nresol: nresol});
    }

    function generacion_llamadas(id, cod_fis, nit) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/llamadas') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, nit: nit});
    }
    function editar(id, cod_fis, nit, multa, resul) {
        $('#cod_multa').val(multa);
        $('.nit').val(nit);
        $('#form5').submit();
    }

    function generarLiquidacion(cod_multaministerio, cod_fis) {
        var url = "<?= base_url() ?>index.php/liquidaciones/getFormMultasMinisterio";
        redirect_by_post(url, {
            cod_multaministerio: cod_multaministerio,
            cod_fis: cod_fis
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
    function gestionar_documento_juridico2(id, cod_fis, nit, multa) {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/multasministerio/gestionar_documento_juridico') ?>";
        $('#resultado').load(url, {id: id, cod_fis: cod_fis, multa: multa, nit: nit});
    }
    setTimeout('$(".preload, .load").hide();', 500);
</script>