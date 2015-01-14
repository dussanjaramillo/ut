<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
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

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<table>
    <tr>
        <td align="center">Tipo</td>
        <td>
            <select id="tipo" class='opcional'>
                <option  value="">-Seleccionar-</option>
                <?php foreach ($tipodocumento->result_array as $tipo) { ?>
                    <option value="<?= $tipo['DESCRIPCION'] ?>"><?= $tipo['NOMBRETIPODOC'] ?></option>
                <?php } ?>
            </select>
        </td>
        <td align="center">Documento</td>
        <td><input type="text" name="documento" id="documento" class='opcional'><img id="loadm" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
        <td align="center">Planilla</td>
        <td><input type="text" name="planilla" id="planilla" class='opcional' ></td>
    </tr>
    <tr>
        <td>Razón Social</td>
        <td colspan="7"><input class='opcional' type="text" name="razon" id="razon" style="width: 1045px"><img id="loadrazon" src="<?php echo base_url('img/319.gif') ?>" width="26" height="28" /></td>
    </tr>
    <tr>
        <td align="center">Fecha Inicial</td>
        <td>
            <input type="date" class="fecha opcional" id="fechai">
        </td>
        <td align="center">Fecha Final</td>
        <td>
            <input type="date" class="fecha opcional" id="fechaf">
        </td>
        <td align="center">Regional</td>
        <td>
            <select id="regional" class='opcional'>
                <option value="">-Seleccionar-</option>
                <?php foreach ($regional->result_array as $region) { ?>
                    <option value="<?= $region['COD_REGIONAL'] ?>"><?= $region['COD_REGIONAL'] . "--" . $region['NOMBRE_REGIONAL'] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><b>Periodo</b></td>
        <td align="center">Año</td>
        <td>
            <?php
            $date = date('Y') - 20;
            ?><select name="annoperiodoinicial" id="annoperiodoinicial" class='opcional'>
                <option value="">-Seleccionar-</option><?php
                for ($i = $date; $i <= date('Y'); $i++) {
//            
                    ?>
                    <option value="<?= $i ?>"><?= $i ?></option>     
                <?php }
                ?>
            </select>  
        </td>
        <td align="center">Mes</td>
        <td>
            <?php $mes = array(1 => "enero", 2 => "febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "octubre", 11 => "Noviembre", 12 => "Diciembre") ?>
            <select name="mesperiodoinicial" id="mesperiodoinicial" class='opcional'>
                <option value="">-Seleccionar-</option>
                <?php foreach ($mes as $valor => $meses) { ?>
                    <option value="<?= $valor ?>"><?= $meses ?></option>
                <?php }
                ?></select></td>
    </tr>
    <tr>
        <td>Cod. Operador</td>
        <td><input type="text" id="operador" class='opcional'></td>
        <td>Valor Mayor a:</td>
        <td><input type="text" id="valordesde" class='opcional'></td>
        <td>Valor Menor a:</td>
        <td><input type="text" id="valorhasta" class='opcional'></td>
    </tr>
</table>
<div id="cargaobligacion" align="center"></div>
<div id="alerta1"></div>
<br>
<table align="center">
    <tr>
        <td style="width: 100px"><button id="consultar" class="btn btn-success">Consultar</button><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" /></td>
        <td><button type="button" id="limpiar" class="btn btn-success">Nueva Búsqueda</button></td>
    </tr>
</table>
<br>
<br>
<div action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
    <table id="conempresa">
        <thead>
        <th>Nit</th>
        <th>Digito</th>
        <th>Razón Social</th>
        <th>Planilla</th>
        <th>Valor Nonima</th>
        <th>Valor Aporte</th>
        <th>Período Pago</th>
        <th>Empleados</th>
        <th>Registro 1</th>
        <th>Registro 2</th>
        <th>Registro 3</th>
        <th>Banco</th>
        <th>Seleccion</th>
        <!--<th>ELIMINAR</th>-->
        </thead>
        <tbody id="rempresa"></tbody>
    </table>
</div>
<br>
<table align="center">
    <tr>
        <td style="width: 210px"><button type="button" id="inactivar" class="btn btn-success">Inactivar Período</button></td>
        <td style="width: 210px"><button type="button" id="modificar" class="btn btn-success">Modificar Planilla</button></td>
    </tr>
</table>
<form id="empresa1" method="POST" action="<?= base_url('index.php/pila/activarinactivar') ?>"  >
    <input type="hidden" name="nit" id="nit1">
    <input type="hidden" name="razonsocial" id="razonsocial1">
</form>
<form id="empresa2" method="POST" action="<?= base_url('index.php/pila/generarcertificados') ?>">
    <input type="hidden" name="nit1" id='nit2'>    
    <input type="hidden" name="razonsocial1" id='razonsocial2'>    
</form>
<form id="empresa3" method="POST" action="<?= base_url('index.php/devolucion/manage') ?>">
    <input type="hidden" name="nit" id='nit3'>    
    <input type="hidden" name="razonsocial" id='razonsocial3'>   
    <input type="hidden" name="planilla" id='nplanilla'>    
</form>
<form id="empresa4" method="POST" action="<?= base_url('index.php/devolucion/modificar_planillas') ?>">
    <input type="hidden" name="nit" id='nit4'>    
    <input type="hidden" name="razonsocial" id='razonsocial4'>   
    <input type="hidden" name="planilla" id='nplanilla4'>    
</form>

<div id="carga" class="carga"></div>
<div id="detalleeliminar" class="eliminacion" style="display: none">
    <table border="3" align="center">
        <tr>
            <td colspan="2"><h5>Esta seguro de eliminar la fila con los sigientes datos:</h5></td>
        </tr>
        <tr>
            <td>NIT:</td>
            <td><input type="text" readonly="readonly" disabled="disabled" name="nit" id="nit12"</td>
        </tr>
        <tr>
            <td>RAZON SOCIAL:</td>
            <td><input type="text" readonly="readonly" disabled="disabled" name="razon" id="razon1"></td>
        </tr>
        <tr>
            <td>PLANILLA No:</td>
            <td><input type="text" readonly="readonly" disabled="disabled" name="nplanilla" id="nplanilla"></td>
        </tr>
    </table>
</div>
<div id="tablaplanilla2"></div>

<script type="text/javascript">

    $('#limpiar').click(function() {
        $('input,select').each(function(key, val) {
            $(this).val('');
        });
        $('#rempresa *').remove();
        $('#conempresa').dataTable().fnClearTable();
    });

    $('#generard').click(function() {
//        index.php/devolucion/manage
        var nit = $('#nit3').val();
        var rsocial = $('#razonsocial3').val();
        var planilla = $('#nplanilla').val();

        if (nit != "" && rsocial != "" && planilla != "")
            $('#empresa3').submit();

    });
    $('#modificar').click(function() {
//        modificar_planillas
        var nit = $('#nit3').val();
        var rsocial = $('#razonsocial3').val();
        var planilla = $('#nplanilla').val();
        if (nit != "" && rsocial != "" && planilla != "")
            $('#empresa4').submit();
    });

    $(".preload, .load").hide();
    $('#tablaplanilla2').hide();

    $('.fecha').datepicker({dateFormat: "yy-mm-dd"});
    $("#preloadmini").hide();
    $("#loadrazon").hide();
    $("#loadm").hide();

    $(document).ready(function() {
        $('#conempresa').dataTable().fnDestroy();
        var tabla = $('#conempresa').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "sServerMethod": "POST",
            "sDom": 'T<"clear">lfrtip',
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "fnInfoCallback": null,
            },
            "oTableTools": {
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                "sRowSelect": "multi"
            },
        });
    });


    $('#consultar').click(function() {

        $('#cargaobligacion *').remove();
        $('#rempresa *').remove();

        var tipo = $('#tipo').val();
        var documento = $('#documento').val();
        var planilla = $('#planilla').val();
        var razon = $('#razon').val();
        var fechai = $('#fechai').val();
        var fechaf = $('#fechaf').val();
        var regional = $('#regional').val();

        var operador = $('#operador').val();
        var valordesde = $('#valordesde').val();
        var valorhasta = $('#valorhasta').val();
        var mesperiodoinicial = $('#mesperiodoinicial').val();
        var annoperiodoinicial = $('#annoperiodoinicial').val();

        var url = "<?= base_url('index.php/pila/detalleempresa') ?>";

        if ((annoperiodoinicial != "" && mesperiodoinicial == "") || (annoperiodoinicial == "" && mesperiodoinicial != "")) {
            $('#cargaobligacion *').remove();
            if ($('#pi').length == 0)
                $('#cargaobligacion').append('<h5 style="color : red" id="pi">Debe Seleccionar AÃ±o y mes de periodo Inicial</h5>')

        } else
        if (tipo != "" || documento != "" || planilla != "" || razon != "" || fechai != "" || fechaf != "" || regional != ""
                || operador != "" || valordesde != "" || valorhasta != "" ||
                (mesperiodoinicial != "" && annoperiodoinicial != "")
                ) {
            $('#conempresa').dataTable().fnClearTable();
            $("#consultar").hide();
            $("#preloadmini").show();

            $.post(url, {tipo: tipo, documento: documento,
                planilla: planilla, razon: razon, fechai: fechai,
                fechaf: fechaf, regional: regional, operador: operador, valordesde: valordesde,
                valorhasta: valorhasta,
                mesperiodoinicial: mesperiodoinicial, annoperiodoinicial: annoperiodoinicial}, function(data) {
//                console.log(data);


                $.each(data, function(key, val) {
//                alert(val.CODEMPRESA)

                    $('#conempresa').dataTable().fnAddData([
                        val.N_INDENT_APORTANTE,
                        val.DIG_VERIF_APORTANTE,
                        val.NOM_APORTANTE,
                        val.N_RADICACION,
                        "$ "+number_format(val.INGRESO),
                        "$ "+number_format(val.TOTAL_APORTES),
                        val.PERIDO_PAGO,
                        number_format(val.N_TOTAL_EMPLEADOS),
                        '<input type="radio" class="seleccion1" name="registro1" planilla="' + val.N_RADICACION + '" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" >',
                        '<input type="radio" class="seleccion2" name="registro2" planilla="' + val.N_RADICACION + '" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" >',
                        '<input type="radio" class="seleccion3" name="registro3" planilla="' + val.N_RADICACION + '" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" >',
                        '<input type="radio" class="seleccion4" name="registrobanco" planilla="' + val.N_RADICACION + '" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" >',
                        '<input type="radio" class="seleccion" name="seleccion" planilla="' + val.N_RADICACION + '" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" >'
//                    '<button id="eliminar1" class="eliminar btn btn-success" planilla="' + val.N_RADICACION + '" name="eliminar" razonsocial="' + val.NOM_APORTANTE + '" nit="' + val.N_INDENT_APORTANTE + '" nplanilla="' + val.N_RADICACION + '" >Eliminar</button>'

                    ]);
                });
                $('.eliminar').on("click", function() {

                    var nit = $(this).attr('nit');
                    var razonsocial = $(this).attr('razonsocial');
                    var nplanilla = $(this).attr('planilla');

//                alert(nplanilla);

//                alert(nit);
                    $('#nit12').val(nit);
                    $('#razon1').val(razonsocial);
                    $('#nplanilla').val(nplanilla);
//             $(this).parents('tr:last').remove(); 
                    var confirmar = $(this);
                    $('#detalleeliminar').dialog({
                        width: 500,
                        height: 300,
                        modal: true,
                        buttons: [{
                                id: 'confirmar',
                                text: 'Confirmar',
                                class: 'btn btn-success',
                                click: function() {
                                    var url1 = "<?= base_url('index.php/pila/eliminarempresa') ?>";
//                                   alert(nplanilla);
                                    $.post(url1, {nit: nit, razonsocial: razonsocial,
                                        nplanilla: nplanilla});
                                    confirmar.parents('tr:last').remove();
                                    $('#detalleeliminar').dialog('close');
                                }
                            },
                            {
                                id: 'cancelar',
                                text: 'Cancelar',
                                class: 'btn btn-success',
                                click: function() {
                                    $('#detalleeliminar').dialog('close');
                                }
                            }]
                    });
                });

                $('.seleccion1').click(function() {
                    $(".preload, .load").show();


                    var url1 = "<?= base_url('index.php/pila/registraplanillauno') ?>";
                    var razonsocial = $(this).attr('razonsocial');
                    var nit = $(this).attr('nit');

                    $('.carga').load(url1, {razonsocial: razonsocial, nit: nit}, function(data) {
                        $('.carga').dialog({
                            width: 900,
                            height: 500,
                            modal: true,
                            buttons: [{
                                    id: 'aceptar',
                                    text: 'Aceptar',
                                    class: 'btn btn-success',
                                    click: function() {
                                        $('.carga').dialog('close');
                                        $('.carga *').remove();
                                    }
                                }],
                            close: function() {
                                $('.seleccion1 *').remove();
                                $('.carga *').remove();
                            }
                        });
                        $(".preload, .load").hide();
                    });

                });


                $('.seleccion2').click(function() {
                    $(".preload, .load").show();

                    $('#estilotabla').dataTable({
                        "sDom": 'T<"clear">lfrtip',
                        "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                            "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
                            "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
                        }
                    });
                    var planilla = $(this).attr('planilla');
                    var url2 = "<?= base_url('index.php/pila/registraplanillados') ?>";
                    var razonsocial1 = $(this).attr('razonsocial');
                    var nit1 = $(this).attr('nit');

                    $('#tablaplanilla2').load(url2, {planilla: planilla}, function(data) {
                        $('#tablaplanilla2').dialog({
                            autoOpen: true,
                            width: 1450,
                            height: 300,
                            modal: true,
                            buttons: [{
                                    id: 'aceptar',
                                    text: 'Aceptar',
                                    class: 'btn btn-success',
                                    click: function() {
                                        $('#tablaplanilla2').dialog('close');
                                        $('#tablaplanilla2 *').remove();
                                    }
                                }],
                            close: function() {
                                $('#tablaplanilla2').dialog('close');
                                $('#tablaplanilla2 *').remove();
                            }
                        });
                        $(".preload, .load").hide();
                    });


                });

                $('.seleccion3').click(function() {
                    $(".preload, .load").show();
                    var planilla = $(this).attr('planilla');
                    var url3 = "<?= base_url('index.php/pila/registraplanillatres') ?>";
                    var razonsocial2 = $(this).attr('razonsocial');
                    var nit2 = $(this).attr('nit');
                    $('.carga').load(url3, {planilla: planilla}, function(data) {
                        $('.carga').dialog({
                            width: 500,
                            height: 300,
                            modal: true,
                            buttons: [{
                                    id: 'aceptar',
                                    text: 'Aceptar',
                                    class: 'btn btn-success',
                                    click: function() {
                                        $('.carga').dialog('close');
                                        $('.carga *').remove();
                                    }
                                }],
                            close: function() {
                                $('.seleccion1 *').remove();
                                $('.carga *').remove();
                            }
                        });
                        $(".preload, .load").hide();
                    });
                });

                $('.seleccion4').click(function() {
                    $(".preload, .load").show();


                    var url4 = "<?= base_url('index.php/pila/registrobanco') ?>";
                    var razonsocial3 = $(this).attr('razonsocial');
                    var nit3 = $(this).attr('nit');
                    var planilla = $(this).attr('planilla');
                    $('.carga').load(url4, {planilla: planilla}, function(data) {
                        $('.carga').dialog({
                            width: 500,
                            height: 500,
                            modal: true,
                            buttons: [{
                                    id: 'aceptar',
                                    text: 'Aceptar',
                                    class: 'btn btn-success',
                                    click: function() {
                                        $('.carga').dialog('close');
                                        $('.carga *').remove();
                                    }
                                }],
                            close: function() {
                                $('.seleccion1 *').remove();
                                $('.carga *').remove();
                            }
                        });
                        $(".preload, .load").hide();
                    });
                });

                $('.seleccion').click(function() {

                    var nit = $(this).attr('nit');
                    var razonsocial = $(this).attr('razonsocial');
                    var planilla = $(this).attr('planilla');

                    $('#nit1').val(nit);
                    $('#razonsocial1').val(razonsocial);
                    $('#nit2').val(nit);
                    $('#razonsocial2').val(razonsocial);
                    $('#nit3').val(nit);
                    $('#razonsocial3').val(razonsocial);
                    $('#nplanilla').val(planilla);
                    $('#nit4').val(nit);
                    $('#razonsocial4').val(razonsocial);
                    $('#nplanilla4').val(planilla);
                });

                $("#consultar").show();
                $("#preloadmini").hide();

            });

        }
    });

    $('#inactivar').click(function() {
        var nit1 = $('#nit1').val();
        var razonsocial1 = $('#razonsocial1').val();
        if (nit1 == "" && razonsocial1 == "") {
            alert('es necesario un nit y la razon social');
        }
        else {

            $('#empresa1').submit();
//            alert('han sido guardados sus datos');
        }

    });

    $('#generarc').click(function() {
        var nit2 = $('#nit2').val();
        var razonsocial2 = $('#razonsocial2').val();
//      alert(nit2+"***"+razonsocial2);
        if (nit2 == "" && razonsocial2 == "") {
            alert('es necesario un nit y una razon social');
        }
        else {
            $('#empresa2').submit();
        }
    })
//--------------------------------------------------------------------------------------------    
//  Autocomplete acuerdo de pago
//--------------------------------------------------------------------------------------------    


    $("#documento").autocomplete({
        source: "<?php echo base_url("index.php/pila/autocompletaremrpesa") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#loadm").show();
        },
        response: function(event, ui) {
            $("#loadm").hide();
        }
    });
    $("#razon").autocomplete({
        source: "<?php echo base_url("index.php/pila/autocompletaremrpesarazonsocial") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#loadrazon").show();
        },
        response: function(event, ui) {
            $("#loadrazon").hide();
        }
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
        var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
