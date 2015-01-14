<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>
</p>
<form id="form1" action="<?php echo base_url('index.php/reporteador/reporte') ?>" method="POST" target="_blank" onsubmit="return enviar()">
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <table width="80%" border="0">
        <tr>
            <td>
                Nit/Empresa
            </td>
            <td >
                <input type="text" id="empresa" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
            </td>
            <td>
                Regional
            </td>
            <td rowspan="3">
                <select multiple name="regional[]" id="regional" style="height: 150px">
                    <option value="-1">Todos...</option>
                    <?php
                    $datos = Reporteador::regional();
                    foreach ($datos as $regional) {
                        ?>
                        <option value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?></option>
                            <!--<input type="checkbox" name="regional[]" value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?><br>-->
                        <?php
                    }
                    ?>
                </select> 
            </td>
        </tr>
        <tr>
            <td>
                <div class="vista">Fecha Inicial</div>
            </td>
            <td>
                <div class="vista"><input type="text" class="fecha" readonly id="fecha_ini" name="fecha_ini" value=""></div>
            </td>
        </tr>
        <tr>
            <td><div class="vista">Fecha Final</div></td>
            <td>
                <div class="vista"><input type="text" class="fecha" readonly id="fecha_fin" name="fecha_fin" value=""></div>
            </td>
        </tr>

        <tr>
            <td> 
                Concepto
            </td>
            <td >
                <?php $datos = ($vista == 'r_contrato_aprendizaje') ? '3' : ''; ?>
                <?php $datos = Reporteador::concepto($datos) ?>
                <select id="concepto" name="concepto">
                    <option value="-1">Todos...</option>
                    <?php
                    foreach ($datos as $concepto) {
                        ?>
                        <option value="<?php echo $concepto['COD_CPTO_FISCALIZACION'] ?>"><?php echo $concepto["NOMBRE_CONCEPTO"] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td>Reporte</td>
            <td>
                <select id="reporte" name="reporte">
                    <?php
                    if ($desplegable == "")
                        echo "<option value='-1'></option>";
                    else {
                        echo $desplegable;
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>

            <td>
                <div style="">
                    Numero de Obligaci&oacute;n
                </div>
            </td>
            <td>
                <div style="">
                    <input id="num_obligacion" type="text" name="num_obligacion">
                </div>
            </td>

            <td>
                <div class="fiscalizador">
                    Fiscalizador
                </div>
                <div class="ciiu">
                    Cod. Ciiu
                </div>
                <div class="div_resolucion">
                    Abogado
                </div>
                <div class="estado_multa">
                    Estado de Multa
                </div>
            </td>
            <td>
                <select class="estado_multa" id="estado_multa" name="estado_multa">
                    <option value="-1">Todos..</option>
                    <option value="1">Aceptadas</option>
                    <option value="0">Devueltas</option>
                </select>
                <input type="text" class="fiscalizador" id="fiscalizador" name="fiscalizador"><img id="preloadmini2" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                <input type="text" class="ciiu" id="ciiu" name="ciiu"><img id="preloadmini4" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                <div class="div_resolucion">
                    <input type="text" class="abogado_resol" id="abogado_resol" name="abogado_resol"><img id="preloadmini3" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </div>
            </td>
        </tr>
        <tr>
        <input type="hidden" id="accion" name="accion" value="0">
        <input type="hidden" id="name_reporte" name="name_reporte" value="">
        <td colspan="6" align="center">
            <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>   &nbsp;&nbsp;             
            <button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;
            <button id="excel" class="btn btn-info fa fa-table" > Excel</button>
        </td>
        </tr>

    </table>
    <table>
        <tr>
            <td></td>
        </tr>
    </table>

</form>
<div id="resultado">

</div>
</div>

<div style="clear: both">
    <br>
    <table id="table" class="table table-bordered">
        <thead class="btn-success">
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div id="resultados"></div>

<script>

    $('#empresa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#empleado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#fiscalizador').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#ciiu').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#abogado_resol').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');

    $('#div_regional').hide();
    $('#id_regional').click(function() {
        var r = $('#id_regional').is(':checked');
        if (r == true) {
            $('#div_regional').hide();
        } else {
            $('#div_regional').show();
        }
    });
    $('#btn_regional').click(function() {
        $('#inf_ayuda').css({
            'width': 'auto',
            "position": "fixed",
            "height": 'auto',
            //                "top" : "20",
            "left": "70%",
            "top": "20%",
            "margin-left": "-500px"
        });
        $('#inf_ayuda').modal('show');
    });




    jQuery(".preload, .load").hide();
    $(".fecha").datepicker({
        buttonText: 'Fecha',
        buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy'
    });
    $(".fecha").datepicker("option", "yearRange", "-99:+0");
    $(".fecha").datepicker("option", "maxDate", "+0m +0d");
    $('.primer').click(function() {
        if ($('#reporte').val() == '-1') {
            alert('Seleccione un Reporte');
            return false
        }
        if ($('#detalle').is(':checked') == true) {
            if ($('#empresa').val() == '') {
                alert('El campo Nit/Empresa Obligatorio');
                return false;
            }
        }
        if (($('#reporte').val() == 37 && $('#empresa').val() == "") || ($('#reporte').val() == 7 && $('#empresa').val() == "")) {
            alert('El campo Nit/Empresa Obligatorio');
            return false;
        }
//        if ($('#empresa').val() == '') {
//            alert('Campo Nit obligatorio');
//            return false
//        }
//        if ($('#reporte').val() == 3 && $('#concepto').val() == "-1") {
//            alert('El campo Concepto es Obligatorio');
//            return false;
//        }
        $("#table thead *").remove();
        $("#table tbody *").remove();
        $('#accion').val("3");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/reporte'); ?>";
        $.post(url, $('#form1').serialize())
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultados').html(msg);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
    });
    $('#reporte').click(function() {
        var informa = $('#reporte option:selected').text();
        $('#name_reporte').val(informa);
        if ($(this).val() == 35)
            $('#concepto').val('2');
        if ($(this).val() == 29 ||
                $(this).val() == 27 ||
                $(this).val() == 3 ||
                $(this).val() == 26 ||
                $(this).val() == 31) {
            $('.fiscalizador').show();
            $('.ciiu').hide();
            $('.div_resolucion').hide();
        } else if ($(this).val() == 4
                || $(this).val() == 6
                || $(this).val() == 18
                || $(this).val() == 32
                || $(this).val() == 53
                || $(this).val() == 55
                || $(this).val() == 56
                ) {
            $('.fiscalizador').hide();
            $('.ciiu').hide();
            $('.div_resolucion').show();
        } else if ($(this).val() == 16) {
            $('.fiscalizador').hide();
            $('.ciiu').show();
            $('.div_resolucion').hide();
        } else if ($(this).val() == 36) {
            $('.fiscalizador').hide();
            $('.estado_multa').show();
            $('.ciiu').hide();
            $('.div_resolucion').hide();
        } else {
            $('.fiscalizador').hide();
            $('.ciiu').hide();
            $('.div_resolucion').hide();
        }


    });

    informa = $('#reporte option:selected').text();
    $('#name_reporte').val(informa);
    function ajaxValidationCallback(status, form, json, options) {

    }

    $('#nit').change(function() {
        if (isNaN($(this).val()))
            $(this).val('');
    });
    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });

    function enviar() {
        var accion = $('#accion').val();
        if (accion == 1 || accion == 2)
            return true;
        else
            return false;
    }
    $("#empresa").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteemrpesas") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini").show();
        },
        response: function(event, ui) {
            $("#preloadmini").hide();
        }
    });
    $("#empleado").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteempleado") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini").show();
        },
        response: function(event, ui) {
            $("#preloadmini").hide();
        }
    });
    $("#fiscalizador").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocomplete_fiscalizador") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini2").show();
        },
        response: function(event, ui) {
            $("#preloadmini2").hide();
        }
    });
    $("#ciiu").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocomplete_ciiu") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini4").show();
        },
        response: function(event, ui) {
            $("#preloadmini4").hide();
        }
    });
    $("#abogado_resol").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocomplete_abogado") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini3").show();
        },
        response: function(event, ui) {
            $("#preloadmini3").hide();
        }
    });
    $("#preloadmini").hide();
    $("#preloadmini2").hide();
    $("#preloadmini3").hide();
    $("#preloadmini4").hide();
    $(".div_resolucion").hide();

    if ($("#reporte").val() == 3 ||
            $("#reporte").val() == 26 ||
            $("#reporte").val() == 27 ||
            $("#reporte").val() == 29 ||
            $("#reporte").val() == 31) {
        $(".fiscalizador").show();
    } else
        $(".fiscalizador").hide();

    if ($("#reporte").val() == 4 ||
            $("#reporte").val() == 6 ||
            $("#reporte").val() == 32 ||
            $("#reporte").val() == 53 ||
            $("#reporte").val() == 55 ||
            $("#reporte").val() == 56 ||
            $("#reporte").val() == 18) {
        $(".div_resolucion").show();
    } else
        $(".div_resolucion").hide();

    if ($("#reporte").val() == 16) {
        $(".ciiu").show();
    } else
        $(".ciiu").hide();

    if ($("#reporte").val() == 36) {
        $(".estado_multa").show();
    } else
        $(".estado_multa").hide();


</script>