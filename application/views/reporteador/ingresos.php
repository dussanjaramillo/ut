<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="container" align="center">
    <p>
    <h1><?php echo $titulo; ?></h1>
    <p><br>
    <form action="" method="post" id="uploadFile">
        <table width="90%" border="0" style="border:1px solid #000">
            <thead style="background-color: #fc7323;color: #FFF">
            <th>Adjuntar</th>
            <th>Subir Archivo</th>
            <th>Documento a Consultar</th>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="file" name="userFile" id="userFile" size="20" required="required"/>
                    </td>
                    <td align="center">
                        <input type="submit" value="Carga" class="btn btn-success" id="cargue" />
                    </td>
                    <td align="center">
                        <div id="archivo">
                            <?php
                            if (isset($post['html_archivo'])) {
                                echo $post['html_archivo'];
                            } else {
                                echo 'No hay datos adjuntos';
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <p>
    <form id="form1" action="<?php echo base_url('index.php/reporteador/ingresos') ?>" method="POST"  onsubmit="return enviar()">
        <input type="hidden" id="archivos_nuevos" name="archivos_nuevos" value="<?php echo isset($_POST['archivos_nuevos']) ? $_POST['archivos_nuevos'] : '' ?>">
        <input type="hidden" id="html_archivo" name="html_archivo" value="<?php echo isset($_POST['html_archivo']) ? $_POST['html_archivo'] : '' ?>">
        <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
        <table width="90%" border="0" id="tablaas" cellspacing="1000">
            <tr>
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
                <td>
                    Nit/Empresa
                </td>
                <td >
                    <input type="text" id="empresa" name="empresa" value="<?php echo isset($_POST['empresa']) ? $_POST['empresa'] : '' ?>"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>
                <td>
                    Regional
                </td>
                <td rowspan="2">
                    <select multiple name="regional[]" id="regional" style="height: 100px">
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
                            <option <?php echo $selec; ?> value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?></option>
                                <!--<input type="checkbox" name="regional[]" value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?><br>-->
                            <?php
                        }
                        ?>
                    </select> 
                </td>
            </tr>
            <tr>
                <td>
                    <div class="vista">Fecha Pago Inicial</div>
                </td>
                <td>
                    <div class="vista"><input type="text" class="fecha" readonly id="fecha_ini" name="fecha_ini" onkeypress="return prueba(event);" value="<?php echo isset($_POST['fecha_ini']) ? $_POST['fecha_ini'] : '' ?>"></div>
                </td>
                <td><div class="vista">Fecha Pago Final</div></td>
                <td>
                    <div class="vista"><input type="text" class="fecha"  readonly id="fecha_fin" name="fecha_fin" onkeypress="return prueba(event);" value="<?php echo isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '' ?>"></div>
                </td>
            </tr>

            <tr>
                <td> 
                    Concepto
                </td>
                <td >
                    <?php $datos = Reporteador::TIPOCONCEPTO() ?>
                    <select id="concepto" name="concepto">
                        <option value="-1">Todos...</option>
                        <?php
                        foreach ($datos as $concepto) {
                            if (isset($_POST['concepto'])) {
                                if ($_POST['concepto'] == $concepto['COD_TIPOCONCEPTO']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else
                                $selec = "";
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $concepto['COD_TIPOCONCEPTO'] ?>"><?php echo $concepto["NOMBRE_TIPO"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
<!--                <td>Numero planilla</td>
                <td><input type="text" id="num_planilla" name="num_planilla"></td>-->
                <td>Municipio</td>
                <td>
                    <input type="text" class="municipio" id="municipio" name="municipio" value="<?php echo isset($_POST['municipio']) ? $_POST['municipio'] : '' ?>"><img id="preloadmini5" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                </td>
                <td>Codigo Operador</td>
                <td rowspan="3">
                    <select multiple name="cod_operador[]" id="cod_operador" style="height: 120px">
                        <option value="-1">Todos...</option>
                        <?php
                        $i = 0;
                        $datos = Reporteador::operdore();
                        foreach ($datos as $regional) {
                            if (isset($_POST['cod_operador'][$i])) {
                                if ($_POST['cod_operador'][$i] == $regional['COD_OPERADOR']) {
                                    $selec = 'selected="selected"';
                                    $i++;
                                } else {
                                    $selec = "";
                                }
                            } else {
                                $selec = "";
                            }
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $regional['COD_OPERADOR'] ?>"><?php echo $regional['COD_OPERADOR'] ?> - <?php echo $regional["ENTIDAD_RECAUDO"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <!--<input type="text" id="cod_operador" name="cod_operador">-->
                </td>
            </tr>
            <tr>
                <td>Banco</td>
                <td>
                    <select name="id_banco" >
                        <option value=""></option>
                        <?php
                        foreach ($info_bancos as $banco) {
                            if (isset($_POST['id_banco'])) {
                                if ($_POST['id_banco'] == $banco['IDBANCO']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else
                                $selec = "";
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $banco['IDBANCO'] ?>"><?php echo $banco['NOMBREBANCO'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>N&uacute;mero de Trabajadores</td>
                <td>
                    <select id="n_trabajadores0" name="n_trabajadores0" class="trabajador">
                        <?php
                        $sele = 0;
                        if (isset($_POST['n_trabajadores0'])) {
                            $sele = $_POST['n_trabajadores0'];
                        }
                        ?>
                        <option value="1" <?php echo ($sele == 1 ? 'selected="selected"' : '') ?>>Entre</option>
                        <option value="2" <?php echo ($sele == 2 ? 'selected="selected"' : '') ?>>Igual</option>
                    </select>
                    <input type="text" id="n_trabajadores1" name="n_trabajadores1" class="trabajador" value="<?php echo isset($_POST['n_trabajadores1']) ? $_POST['n_trabajadores1'] : '' ?>">
                    <input type="text" id="n_trabajadores2" name="n_trabajadores2" class="trabajador" value="<?php echo isset($_POST['n_trabajadores2']) ? $_POST['n_trabajadores2'] : '' ?>">
                </td>
            </tr>
            <tr>
                <td>Periodo Inicial</td>
                <td><div class="vista"><input type="text" class="periodo" readonly onkeypress="return prueba(event);" id="fecha_periodo_ini" name="fecha_periodo_ini" value="<?php echo isset($_POST['fecha_periodo_ini']) ? $_POST['fecha_periodo_ini'] : '' ?>"></div></td>
                <td>Periodo Final</td>
                <td><div class="vista"><input type="text" class="periodo" readonly onPaste="return false" onkeypress="return prueba(event);"  id="fecha_periodo_fin" name="fecha_periodo_fin" value="<?php echo isset($_POST['fecha_periodo_fin']) ? $_POST['fecha_periodo_fin'] : '' ?>"></div></td>
            </tr>
            <tr>
                <td>Valor </td>
                <td>
                    <select id="valor_select" name="valor_select">
                        <option value="1">Entre</option>
                        <option value="2">Igual</option>
                    </select>
                </td>
                <td>Valor Inicial</td>
                <td><input type="text" id="valor1" name="valor1" value="<?php echo isset($_POST['valor1']) ? $_POST['valor1'] : '' ?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></td>
                <td><div class="valor2">Valor Final</div></td>
                <td><div class="valor2"><input type="text" id="valor2" name="valor2" value="<?php echo isset($_POST['valor2']) ? $_POST['valor2'] : '' ?>" onkeypress ="num(this);" onblur = "num(this);" onkeyup = "num(this);"></div></td>
            </tr>
            <tr>
<!--                <td>Codigo Concepto</td>
                <td><input type="text" id="cod_concepto_sub" name="cod_concepto_sub"></td>-->
                <td>Tipo de Empresa</td>
                <td>
                    <select id="tipo_empresa" name="tipo_empresa">
                        <option value="-1"></option>
                        <option value="2">Privada</option>
                        <option value="1">Publicas</option>
                        <option value="3">Mixta</option>
                    </select>
                </td>

                <td>
                    Fuente
                </td>
                <td>
                    <select id="procedencia" name="procedencia">
                        <option value="-1"></option>
                        <?php
                        $i = 0;
                        $datos = Reporteador::procedencia();
                        foreach ($datos as $regional) {
                            if (isset($_POST['procedencia'])) {
                                if ($_POST['procedencia'] == $regional['PROCEDENCIA']) {
                                    $selec = 'selected="selected"';
                                    $i++;
                                } else {
                                    $selec = "";
                                }
                            } else {
                                $selec = "";
                            }
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $regional['PROCEDENCIA']; ?>"><?php echo $regional['PROCEDENCIA']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    Cod. Ciiu
                </td>
                <td>
                    <select class="ciiu" id="ciiu" name="ciiu">
                        <option value=""></option>

                        <?php
                        $datos = Reporteador::ciiu();
                        foreach ($datos as $banco) {
                            if (isset($_POST['ciiu'])) {
                                if ($_POST['ciiu'] == $banco['CLASE']) {
                                    $selec = 'selected="selected"';
                                } else {
                                    $selec = "";
                                }
                            } else
                                $selec = "";
                            ?>
                            <option <?php echo $selec; ?> value="<?php echo $banco['CLASE'] ?>"><?php echo $banco['CLASE'] ?> - <?php echo $banco['DESCRIPCION'] ?></option>
                        <?php } ?>
                    </select>
                    <!--<input type="text" class="ciiu" id="ciiu" name="ciiu"><img id="preloadmini4" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />-->
                </td>

            </tr>
            <tr>
                <td>Tipo Documento</td>
                <td>
                    <select id="tipo_doc" name="tipo_doc">
                        <option value="-1">Todos</option>
                        <?php
                        $sele = 0;
                        if (isset($_POST['tipo_doc'])) {
                            $sele = $_POST['tipo_doc'];
                        }
                        ?>
                        <option value="1" <?php echo ($sele == 1 ? 'selected="selected"' : '') ?>>Natural</option>
                        <option value="2" <?php echo ($sele == 2 ? 'selected="selected"' : '') ?>>Juridico</option>
                    </select>
                </td>
                <td>
                    <div class="tipo_registro">
                        Tipo Registro
                    </div>
                </td>
                <td>
                    <div class="tipo_registro">
                        <select name="tipo_registro">
                            <?php
                            $sele = 0;
                            if (isset($_POST['tipo_registro'])) {
                                $sele = $_POST['tipo_registro'];
                            }
                            ?>
                            <option value="1" <?php echo ($sele == 1 ? 'selected="selected"' : '') ?>>Registro Tipo 1</option>
                            <option value="2" <?php echo ($sele == 2 ? 'selected="selected"' : '') ?>>Registro Tipo 2</option>
                            <option value="3" <?php echo ($sele == 3 ? 'selected="selected"' : '') ?>>Registro Tipo 3</option>
                        </select>
                    </div>
                </td>
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
                <td>
                    <div class="smlv">
                        Nro salarios 
                    </div>
                    <div class="num_registros">
                        N&uacute;mero de Registros
                    </div>
                </td>
                <td>
                    <div class="smlv">
                        <input type="text" id="salarios_minimos" name="salarios_minimos" value="" style="width: 20px" maxlength="2">
                        <select id="salario_enc" name="salario_enc" style="width: 80px">
                            <?php
                            $sele = 0;
                            if (isset($_POST['salario_enc'])) {
                                $sele = $_POST['salario_enc'];
                            }
                            ?>
                            <option value="<" <?php echo ($sele == '<' ? 'selected="selected"' : '') ?>>Menor</option>
                            <option value=">" <?php echo ($sele == '>' ? 'selected="selected"' : '') ?>>mayor</option>
                        </select>
                        <select id="ano_salario" name="ano_salario" style="width: 70px">
                            <?php
                            $anos = date('Y');
                            for ($i = 0; $i < 6; $i++) {
                                ?>
                                <option value="<?php echo $anos - $i ?>"><?php echo $anos - $i ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div class="num_registros">
                        <input type="text" id="num_registros" name="num_registros" value="100">
                    </div>
                </td>
                <td>
                    <div class="num_registros">
                        Ordenar
                    </div>
                    <div class="ano">
                        AÑO
                    </div>
                </td>
                <td rowspan="1">
                    <div class="smlv" style="color: #fc7323">
                        <input type="hidden" id="valor_salario" name="valor_salario">
                        <div id="resul_salario"></div>
                    </div>
                    <div class="num_registros">
                        <select id="ordenar" name="ordenar">
                            <option value="DESC">Valor Mayor</option>
                            <option value="ASC">Valor Menor</option>
                        </select>
                    </div>
                    <div class="ano">
                        <select multiple name="ano[]" id="ano" style="height: 70px">
                            <?php
                            $ano = date('Y');
                            for ($i = 0; $i < 10; $i++) {
                                ?>
                                <option value="<?php echo $ano - $i ?>"><?php echo $ano - $i ?></option>
                                    <!--<input type="checkbox" name="regional[]" value="<?php echo $regional['COD_REGIONAL'] ?>"><?php echo $regional["NOMBRE_REGIONAL"] ?><br>-->
                                <?php
                            }
                            ?>
                        </select> 
                    </div>
                </td> 
            </tr>
            <tr>
            <input type="hidden" id="accion" name="accion" value="0">
            <input type="hidden" id="name_reporte" name="name_reporte" value="<?php echo $titulo; ?>">
            <td colspan="6" align="center">
                <button class="primer2 fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>&nbsp;&nbsp;             

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

<!--
<div id="liquidaciones">
 <div class="tabbable">
  <ul class="nav nav-tabs">
   <li class="active"><a href="#normativa" data-toggle="tab">Liquidación Normativa</a></li>
   <li><a href="#presuntiva" data-toggle="tab">Liquidación Presuntiva</a></li>
  </ul>
  <div class="tab-content">
   <div id="mensaje"></div>
   fdskjafhasjd fjlsajdfkjsabdflsadfjlnsdkjfskjdfnskjadfjsdnfkjs kjfnsladkfnslkdfkjñ asdkjn
   <div class="tab-pane active" id="normativa"></div>
  </div>
 </div>
</div>-->


<div id="resultados"></div>

<script>
    $('#ciiu').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#empresa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#empleado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#fiscalizador').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');

    $('#div_regional').hide();

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
    $("#municipio").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocomplete_municipio") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini5").show();
        },
        response: function(event, ui) {
            $("#preloadmini5").hide();
        }
    });
    $("#preloadmini4").hide();
    $("#preloadmini5").hide();

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
                maxDate: "0",
        onClose: function(selectedDate) {
            $("#fecha_ini").datepicker("option", "maxDate", selectedDate)
        }
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
                maxDate: "0",
//        onClose: function(selectedDate) {
//            $("#fecha_periodo_fin").datepicker("option", "maxDate", selectedDate)
//        }
    });
    $(".fecha").datepicker("option", "yearRange", "-99:+0");
    $(".fecha").datepicker("option", "maxDate", "+0m +0d");


    $('.primer').click(function() {
        if ($('#reporte').val() == 61 && $('#cod_operador').val() == '' && $('#consolidado').is(':checked') == false) {
            alert('El campo Operador es obligatorio');
            $('#cod_operador').focus();
            return false;
        }

        if ($('#reporte').val() == 65 && $('#salarios_minimos').val() == '') {
            alert('El campo Nro salarios es obligatorio');
            $('#salarios_minimos').focus();
            return false;
        }
        if ($('#n_trabajadores0').val() == 1 && $('#n_trabajadores1').val() != "" && $('#n_trabajadores2').val() == "") {
            alert('El campo es obligatorio');
            $('#n_trabajadores2').focus();
            return false;
        }
        $("#table thead *").remove();
        $("#table tbody *").remove();
        $('#accion').val("3");
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/ingresos'); ?>";
        $.post(url, $('#form1').serialize())
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    $('#resultados').html(msg);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
    });

    function ajaxValidationCallback(status, form, json, options) {

    }
    function formReset()
    {
//        document.getElementById("form1").reset();
        $('#empresa').val('');
        $('#regional').val('-1');
        $('#fecha_ini').val('');
        $('#fecha_fin').val('');
        $('#concepto').val('-1');
        $('#cod_operador').val('-1');
        $('#municipio').val('');
        $('#id_banco').val('');
        $('#n_trabajadores1').val('');
        $('#n_trabajadores2').val('');
        $('#fecha_periodo_ini').val('');
        $('#fecha_periodo_fin').val('');
        $('#valor1').val('');
        $('#valor2').val('');
        $('#tipo_empresa').val('-1');
        $('#procedencia').val('');
        $('#ciiu').val('');
    }

    $('#excel').click(function() {
        $('#accion').val("1");
    });
    $('#pdf').click(function() {
        $('#accion').val("2");
    });
    $('#cunsultar').click(function() {
        $('#accion').val("0");
    });
    $('#limpiar').click(function() {
        $('#accion').val("6");
    });

    function enviar() {

        var fecha_periodo_fin = $('#fecha_periodo_fin').val();
        var fecha_periodo_ini = $('#fecha_periodo_ini').val();
        if (fecha_periodo_ini != '' || fecha_periodo_fin != '') {
            fecha_periodo_ini = fecha_periodo_ini.split('/');
            fecha_periodo_fin = fecha_periodo_fin.split('/');
            if (fecha_periodo_fin[1] < fecha_periodo_ini[1]) {
                alert('El valor del periodo no es correcto');
                return false;
            } else if (fecha_periodo_fin[0] < fecha_periodo_ini[0]) {
                alert('El valor del periodo no es correcto');
                return false;
            }
        }



        if ($('#reporte').val() == 61 && $('#cod_operador').val() == '' && $('#consolidado').is(':checked') == false) {
            alert('El campo Operador es obligatorio');
            $('#cod_operador').focus();
            return false;
        }
        if ($('#reporte').val() == 65 && $('#salarios_minimos').val() == '') {
            alert('El campo Nro salarios es obligatorio');
            $('#salarios_minimos').focus();
            return false;
        }
        if ($('#n_trabajadores0').val() == 1 && $('#n_trabajadores1').val() != "" && $('#n_trabajadores2').val() == "") {
            alert('El campo obligatorio');
            $('#n_trabajadores2').focus();
            return false;
        }
        if ($('#valor_select').val() == 1 && $('#valor1').val() != "" && $('#valor2').val() == "") {
            alert('El campo obligatorio');
            $('#valor2').focus();
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

    $('#valor_select').change(function(e) {
        var selec = $('#valor_select').val();
        if (selec == 1)
            $('.valor2').show();
        else
            $('.valor2').hide();
    });
    $('#n_trabajadores0').change(function(e) {
        n_traba();
    });
    function n_traba() {
        var selec = $('#n_trabajadores0').val();
        if (selec == 1)
            $('#n_trabajadores2').show();
        else
            $('#n_trabajadores2').hide();
    }
    $("#preloadmini").hide();
    $("#preloadmini2").hide();
    $("#preloadmini3").hide();
    jQuery(".preload, .load").hide();

    $('#reporte').click(function() {
        repo();
    });
    function repo() {
        var reporte = $('#reporte').val();
        if (reporte == 59)
            $('.ano').show();
        else
            $('.ano').hide();
        if (reporte == 63)
            $('.num_registros').show();
        else
            $('.num_registros').hide();
        if (reporte == 65)
            $('.smlv').show();
        else
            $('.smlv').hide();
        if (reporte == 71)
            $('.tipo_registro').show();
        else
            $('.tipo_registro').hide();
        if (reporte == 66
                || reporte == 68
                || reporte == 63
                || reporte == 64
                || reporte == 65
                || reporte == 76
                || reporte == 60
                || reporte == 69
                || reporte == 71
                )
            $('.consolidado').hide();
        else
            $('.consolidado').show();

        $('#name_reporte').val($("#reporte option:selected").html());
    }


    $('.smlv').hide();
    $('.ano').hide();
    $('.num_registros').hide();
    $('.consolidado').hide();
    $('.tipo_registro').hide();

    $('#uploadFile').submit(function(e) {
        e.preventDefault();
        var id = "1";
        var name = $('#userFile').val();
        if (name == "") {
            return false;
        }
        jQuery(".preload, .load").show();
        var doUploadFileMethodURL = "<?= site_url('reporteador/doUploadFile'); ?>?id=" + id + "&name=" + name;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
//                console.log(data);
                jQuery(".preload, .load").hide();
                $('#archivo').html(data.message + "     <button class='btn' onclick='eliminar()'><i class='fa fa-trash-o'></i></button>");
                $('#archivos_nuevos').val(data.ruta);
                $('#html_archivo').val(data.message + "     <button class='btn' onclick='eliminar()'><i class='fa fa-trash-o'></i></button>");
                $('#userFile').val('');
            },
            error: function(data) {
                alert("El archivo no se ha podido cargar, el formato puede no ser valido");
                jQuery(".preload, .load").hide();
            }
        });

        return false;
        //                    return false;
    });
    function eliminar() {
        $('#archivo').html('');
        $('#archivos_nuevos').val('');
        $('#userFile').val('');
    }
    $('#num_registros').validCampoFranz('0123456789');
    $('#salarios_minimos').validCampoFranz('0123456789');
    $('#num_registros').change(function() {
        if ($('#num_registros').val() == "") {
            $('#num_registros').val('100');
        }
    });
    $('#salarios_minimos').change(function() {
        if ($('#salarios_minimos').val() == "") {
            $('#salarios_minimos').val('10');
        }
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/reporteador/salario'); ?>";
        var salario = $('#salarios_minimos').val()
        var ano_salario = $('#ano_salario').val()
        $.post(url, {salario: salario, ano_salario: ano_salario})
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    var msg2 = msg + " * " + salario + " = " + msg * salario
                    $('#resul_salario').html(msg2);
                    $('#valor_salario').val(msg * salario);
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert("Error en la consulta")
        });

    });

    function num(c) {
        c.value = c.value.replace(/[A-z-\/\*\,\ \;\:\{\}\[\]\^\`\¨\´\+\~\°\|\¬\!\¡\'\?\¿\=\)\(\&\%\$\#\"\<\>\Ñ\ñ\+]/, '');
        // x = c.value;
        // c.value = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        var num = c.value.replace(/\./g, '');
        if (!isNaN(num))
        {
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/, '');
            c.value = num;
        }
    }


    $('#name_reporte').val($("#reporte option:selected").html());
    function prueba(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8)
            return true; // backspace
        if (tecla == 32)
            return true; // espacio
        if (e.ctrlKey && tecla == 86) {
            return true;
        } //Ctrl v
        if (e.ctrlKey && tecla == 67) {
            return true;
        } //Ctrl c
        if (e.ctrlKey && tecla == 88) {
            return true;
        } //Ctrl x

        patron = /[a-zA-Z]/; //patron

        te = String.fromCharCode(tecla);
        return patron.test(te); // prueba de patron
    }

    n_traba();
    repo();

</script>
<style>
    .periodo {
        width: 100px
    }
    .fecha{
        width: 100px
    }
    .trabajador{
        width: 60px
    }

</style>