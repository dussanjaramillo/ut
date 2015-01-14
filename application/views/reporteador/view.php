<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>
</p>
<form id="form1" action="<?php echo base_url('index.php/reporteador/reporte') ?>" method="POST"  onsubmit="return enviar()">
    <!--target="_blank"-->
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <table width="80%" border="0">
        <tr>
            <?php if ($vista == 'r_cartera_no_misional'
                    || $vista == 'r_cartera_no_mis_moroso'
                    || $vista == 'r_cartera_no_misional_gen'
                    || $vista == 'r_cartera_no_mis_mensual'
                    ) { ?>
                <td>
                    Tercero
                </td>
                <td >
                    <input type="text" id="empleado" name="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>">
                    <img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>

                <td> 
                    Concepto
                </td>
                <td >
                    <?php $datos = Reporteador::tipocartera() ?>
                    <select id="conceptonm" name="conceptonm">
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
            </tr>
            <tr>
                <td>
                    <div class="vista">Estado Empleado</div>
                </td>
                <td>
                                        <?php $datosemp = Reporteador::estadoempleado() ?>
                    <select id="estado_e" name="estado_e">
                        <option value="-1">Todos...</option>
                        <?php
                        foreach ($datosemp as $estado) {
                            ?>
                            <option value="<?php echo $estado['COD_ESTADO_E'] ?>"><?php echo $estado["NOMBRE_ESTADO_E"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>

            <?php } 
            
				elseif ( $vista == 'r_cartera_no_misional_r') { ?>

                <td> 
                    Concepto
                </td>
                <td >
                    <?php $datos = Reporteador::tipocartera2() ?>
                    <select id="conceptonm" name="conceptonm">
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
             <?php } 
				elseif ( $vista == 'r_cartera_no_mis_cesantias') { ?>

                                <td>
                    Tercero
                </td>
                <td >
                    <input type="text" id="empleado" name="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>">
                    <img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>
             <?php } 
            elseif ( $titulo == 'REPORTE KACTUS' ) { ?>

                <td>
                    Empleado
                </td>
                <td >
                    <input type="text" id="empleado" name="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>">
                    <img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>

                <td> 
                    Concepto
                </td>
                <td >
                    <?php $datos = Reporteador::tipocartera() ?>
                    <select id="concepto2" name="concepto2">
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
            </tr>
            <tr>
                <td>
                    <div class="vista">Periodo</div><input type="checkbox" id="check_fecha" name="check_fecha" style="display: none">
                </td>
                <td>
                    <div class="vista"><input type="text" class="periodo" readonly id="periodo" name="periodo" value=""></div>
                </td>
             <?php }
            else { ?>
                <td>
                    Nit/Empresa
                </td>
                <td >
                    <input type="text" id="empresa" value="<?php echo isset($_POST['empresa']) ? $_POST['empresa'] : '' ?>" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>
            <?php } ?>

            <td>
                Regional
            </td>
            <?php
//            if($vista=='r_devolucion'){ 
//                $sato=4;
//                 }else{
//                     $sato=3;
//                 } 
            ?>
            <td rowspan="<?php echo $sato = 3; ?>">
                <select multiple name="regional[]" id="regional" style="height: 150px">
                    <?php
                    if (isset($_POST['regional'][0])) {
                        if ($_POST['regional'][0] == "-1") {
                            $sele = 'selected="selected"';
                        } else {
                            $sele = '';
                        }
                    } else {
                        $sele = '';
                    }
                    ?>
                    <option <?php echo $sele ?> value="-1">Todos...</option>
                    <?php
                    $datos = Reporteador::regional();
                    $i = 0;
                    foreach ($datos as $regional) {
                        if (isset($_POST['regional'][$i])) {
                            if ($_POST['regional'][$i] == $regional['COD_REGIONAL']) {
                                $selec = 'selected="selected"';
                                $i++;
                            } else {
                                $selec = "";
                            }
                        } else {
                            $selec = "";
                        }
                        ?>
                        <option <?php echo $selec; ?>  value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?></option>
                                    <!--<input type="checkbox" name="regional[]" value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?><br>-->
                        <?php
                    }
                    ?>
                </select> 
            </td>
        </tr>
        <?php if ($titulo == 'REPORTE KACTUS' 
                    || $vista == 'r_cartera_no_misional'
                    || $vista == 'r_cartera_no_mis_moroso'
                    || $vista == 'r_cartera_no_misional_r'
                    || $vista == 'r_cartera_no_misional_gen'
                    || $vista == 'r_cartera_no_mis_mensual'
                    || $vista == 'r_cartera_no_mis_cesantias'
                    ) { ?>
            <tr>

            </tr>
        <?php } else { ?>
            <?php if ($vista == 'r_devolucion' && 1 == 2) { ?>
                <tr>
                    <td>
                        Empleado
                    </td>
                    <td >
                        <input type="text" id="empleado" value="<?php echo isset($_POST['empleado']) ? $_POST['empleado'] : '' ?>" name="empleado"><img id="preloadmini9" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <div class="vista">Fecha Inicial</div>
                </td>
                <td>
                    <div class="vista"><input type="text" class="fecha" readonly id="fecha_ini" name="fecha_ini" value="<?php echo isset($_POST['fecha_ini']) ? $_POST['fecha_ini'] : '' ?>"></div>
                </td>
            </tr>
            <tr>
                <td><div class="vista">Fecha Final</div></td>
                <td>
                    <div class="vista"><input type="text" class="fecha" readonly id="fecha_fin" name="fecha_fin" value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '' ?>"></div>
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
                            if (isset($_POST['concepto'])) {
                                if ($_POST['concepto'] == $concepto['COD_CPTO_FISCALIZACION']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else {
                                $selec = "";
                            }
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $concepto['COD_CPTO_FISCALIZACION'] ?>"><?php echo $concepto["NOMBRE_CONCEPTO"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            <?php } ?>  
            <?php if ($titulo == 'REPORTE KACTUS' 
                    || $vista == 'r_cartera_no_misional'
                    || $vista == 'r_cartera_no_mis_moroso'
                    || $vista == 'r_cartera_no_misional_r'
                    || $vista == 'r_cartera_no_misional_gen'
                    || $vista == 'r_cartera_no_mis_mensual'
                    || $vista == 'r_cartera_no_mis_cesantias'
                    ) { ?>
            </tr>
            <tr>
            <?php } ?>
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
            <?php
            if ($titulo != "Visitas General" && $titulo != 'Contratos FIC' && $titulo != 'Procesos Sancionatorios' && $titulo != 'Reporte Comparativo por Periodos' && $titulo != 'Reporte Estado de Fiscalización' && $titulo != 'Reporte Abogado' && $titulo != 'Recuperación Cartera' && $titulo != 'Reportes por Gestión' && $titulo != 'Reporte Gestión por Empresa') {
                ?>
                <?php if ( $vista != 'r_cartera_no_misional_r') { ?>
                <td>
                    <div class="generarles">
                        Numero de Obligaci&oacute;n
                    </div>
                    <div class="estado_concepto2">
                        Numero de Devolucion
                    </div>
                </td>
                <td>
                	
                    <div style="">
                        <input id="num_obligacion" type="text" name="num_obligacion" value="<?php echo isset($_POST['num_obligacion']) ? $_POST['num_obligacion'] : '' ?>">
                    </div>
                    <?php } ?>
                </td>
            <?php } ?>
            <td>
                <div class="fiscalizador">
                    Fiscalizador
                </div>
                <div class="estado">
                    Estado
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
                <div class="estado_concepto2">
                    Concepto Devoluci&oacute;n
                </div>
            </td>
            <td>
                <?php $datos = Reporteador::tipodevolucion() ?>
                <select id="concepto2" name="concepto2" class="estado_concepto2">
                    <option value="-1">Todos...</option>
                    <?php
                    foreach ($datos as $concepto) {
                        ?>
                        <option value="<?php echo $concepto['COD_CPTO_FISCALIZACION'] ?>"><?php echo $concepto["NOMBRE_CONCEPTO"] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <select class="estado_multa" id="estado_multa" name="estado_multa">
                    <option value="-1">Todos..</option>
                    <option value="1">Aceptadas</option>
                    <option value="0">Devueltas</option>
                </select>
                <select class="estado" id="estado" name="estado">
                    <option value="-1">Todos..</option>
                    <option value="1">Activa</option>
                    <option value="2">Cancelada</option>
                    <option value="3">Remisibilidad</option>
                    <option value="4">Resolución de Prescripción</option>
                </select>
                <input type="text" class="fiscalizador" id="fiscalizador" name="fiscalizador" value="<?php echo isset($_POST['fiscalizador']) ? $_POST['fiscalizador'] : '' ?>"><img id="preloadmini2" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                <input type="text" class="ciiu" id="ciiu" name="ciiu" value="<?php echo isset($_POST['ciiu']) ? $_POST['ciiu'] : '' ?>" ><img id="preloadmini4" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                <div class="div_resolucion">
                    <input type="text" class="abogado_resol" id="abogado_resol" name="abogado_resol" value="<?php echo isset($_POST['abogado_resol']) ? $_POST['abogado_resol'] : '' ?>"><img id="preloadmini3" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </div>
            </td>
        </tr>
        <?php
        if ($titulo == "Reporte Gestión por Empresa") {
            ?>
            <tr>
                <td>Detalle</td>
                <td><input type="checkbox" id="detalle" name="detalle" value="1"></td>
            </tr>
        <?php } ?>
        <tr>
            <td>
                <div class="consolidado">
                    Consolidado
                </div>
            </td>
            <td>
                <div class="consolidado">
                    <?php
                    $chec = '';
                    if (isset($_POST['consolidado']))
                        $chec = 'checked="checked"';
                    ?>
                    <input  type="checkbox" <?php echo $chec; ?> id="consolidado" name="consolidado" value="1" >
                </div>
            </td>
        </tr>
        <tr>
        <input type="hidden" id="accion" name="accion" value="0">
        <input type="hidden" id="name_reporte" name="name_reporte" value="">
        <td colspan="6" align="center">
            <button class="primer2 fa fa-bar-chart-o btn btn-success" align="center" > Consultar</button>&nbsp;&nbsp;

            <button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

            <button id="excel" class="btn btn-info fa fa-table" > Excel</button>&nbsp;&nbsp;
            <button id="limpiar" class="btn btn fa fa-eraser " onclick="formReset();" > Limpiar</button>
        </td>
        </tr>

    </table>
    <table>
        <tr>
            <td></td>
        </tr>
    </table>

</form>

</div>
<div id="resultado"></div>

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
    $('#num_obligacion').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');

    $('#div_regional').hide();
    $('#id_regional').click(function() {
        var r = $('#id_regional').is(':checked');
        if (r == true) {
            $('#div_regional').hide();
        } else {
            $('#div_regional').show();
        }
    });
    function formReset()
    {
//        document.getElementById("form1").reset();
//        $('#fecha_ini').val('');
//        $('#fecha_fin').val('');
//        $('#regional').val('-1');
//        $('#concepto').val('-1');
//        $('#empresa').val('');
        var vista = $('#vista').val();
        location.href = "<?php echo base_url('index.php/reporteador/'); ?>" + "/" + vista;

    }
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
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        showOn: 'button',
        buttonText: 'Seleccione una fecha',
                buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
                numberOfMonths: 1,
    });
    $(".periodo").datepicker({
        buttonText: 'Fecha',
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'mm/yy',
        showOn: 'button',
        buttonText: 'Seleccione una fecha',
                buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
                numberOfMonths: 1,
    });
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
        } else if ($(this).val() == 72 || $(this).val() == 73) {
            $('.estado_concepto2').show();
            $('.fiscalizador').hide();
            $('.estado_multa').hide();
            $('.ciiu').hide();
            $('.div_resolucion').hide();
        } else {
            $('.fiscalizador').hide();
            $('.ciiu').hide();
            $('.div_resolucion').hide();
        }
        if ($(this).val() == 59 || $(this).val() == 65 || $(this).val() == 22
                || $(this).val() == 60
                || $(this).val() == 66
                || $(this).val() == 79
                || $(this).val() == 70
                || $(this).val() == 75 || $(this).val() == 10) {
            $('.consolidado').show();
        } else {
            $('.consolidado').hide();
        }

$('#name_reporte').val($("#reporte option:selected").html());
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
    $('#limpiar').click(function() {
        $('#accion').val("6");
    });

    function enviar() {
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
        
        var accion = $('#accion').val();
        if (accion == 1 || accion == 2 || accion == 0)
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
            $("#preloadmini9").show();
        },
        response: function(event, ui) {
            $("#preloadmini9").hide();
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
    $("#preloadmini9").hide();
    $(".div_resolucion").hide();
    $('.consolidado').hide();

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
    if ($("#reporte").val() == 75) {
        $(".estado").show();
    } else
        $(".estado").hide();

    if ($("#reporte").val() == 36) {
        $(".estado_multa").show();
    } else
        $(".estado_multa").hide();

    if ($("#reporte").val() == 59 || $("#reporte").val() == 65 || $("#reporte").val() == 22
            || $("#reporte").val() == 60
            || $("#reporte").val() == 66
            || $("#reporte").val() == 70
            || $("#reporte").val() == 79
            || $("#reporte").val() == 75
            || $("#reporte").val() == 10) {
        $(".consolidado").show();
    } else
        $(".consolidado").hide();

    if ($("#reporte").val() == 72 || $("#reporte").val() == 73) {
        $(".estado_concepto2").show();
        $(".generarles").hide();
    } else
        $(".estado_concepto2").hide();

$('#name_reporte').val($("#reporte option:selected").html());
</script>