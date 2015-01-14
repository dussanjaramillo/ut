<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="container" align="center">
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
                        <div id="archivo">No hay datos adjuntos</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>



    <form id="form1" action="<?php echo base_url('index.php/reporteador/ingresos') ?>" method="POST" target="_blank" onsubmit="return enviar()">
        <div id="accordion">
            <h3>CONCEPTOS RECAUDOS</h3>
            <div>
                <div class="acor">
                    <select name="tipo_concepto_origen[]" id="tipo_concepto_origen" multiple="multiple"  class="select" size="8">
                        <?php $datos = Reporteador::TIPOCONCEPTO() ?>
                        <?php
                        foreach ($datos as $concepto) {
                            ?>
                            <option value="<?php echo $concepto['COD_TIPOCONCEPTO'] ?>"><?php echo $concepto["NOMBRE_TIPO"] ?></option>
                            <?php
                        }
                        ?>
                        ?>
                    </select>
                </div>
                <div class="acor">
                    <input type="button" id="pasar5" class="izq input" value="Pasar »"><input type="button" id="quitar5" class="der input" value="« Quitar"><br />
                    <input type="button" id="pasartodos5" class="izq input" value="Todos »"><input type="button" id="quitartodos5" class="der input" value="« Todos">
                </div>
                <div class="acor">
                    <select name="tipo_concepto_destino[]" id="tipo_concepto_destino" multiple="multiple" class="select" size="8"></select>
                </div>
            </div>
            <h3>SUB-CONCEPTO</h3>
            <div>
                <div class="acor">
                    <select name="tipo_subconcepto_origen[]" id="tipo_subconcepto_origen" multiple="multiple"  class="select" size="8">
                        <?php $datos = Reporteador::TIPOSUBCONCEPTO() ?>
                        <?php
                        foreach ($datos as $concepto) {
                            ?>
                            <option value="<?php echo $concepto['COD_CONCEPTO_RECAUDO'] ?>"><?php echo $concepto["NOMBRE_CONCEPTO"] ?></option>
                            <?php
                        }
                        ?>
                        ?>
                    </select>
                </div>
                <div class="acor">
                    <input type="button" id="pasar6" class="izq input" value="Pasar »"><input type="button" id="quitar6" class="der input" value="« Quitar"><br />
                    <input type="button" id="pasartodos6" class="izq input" value="Todos »"><input type="button" id="quitartodos6" class="der input" value="« Todos">
                </div>
                <div class="acor">
                    <select name="tipo_subconcepto_destino[]" id="tipo_subconcepto_destino" multiple="multiple" class="select" size="8"></select>
                </div>
            </div>
            <h3>DATOS EMPRESA</h3>
            <div>
                <div class="acor">
                    <select name="empresa_origen[]" id="empresa_origen" multiple="multiple"  class="select" size="8">
                        <option value="EMPRESA.CODEMPRESA">NIT</option>
                        <option value="Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION">DIGITO VERTIFICACION</option>
                        <option value="EMPRESA.CIIU">CIIU</option>
                        <option value="EMPRESA.RAZON_SOCIAL">RAZON SOCIAL</option>
                        <option value="EMPRESA.REPRESENTANTE_LEGAL">REPRESENTANTE LEGAL</option>
                        <option value="EMPRESA.DIRECCION">DIRECCION</option>
                        <option value="EMPRESA.TELEFONO_FIJO">TELEFONO</option>
                        <option value="EMPRESA.CORREOELECTRONICO">EMAIL</option>
                        <option value="REGIONAL.NOMBRE_REGIONAL REGIONAL">REGIONAL</option>
                        <option value="MUNICIPIO.NOMBREMUNICIPIO">CIUDAD</option>
                    </select>
                </div>
                <div class="acor">
                    <input type="button" id="pasar4" class="izq input" value="Pasar »"><input type="button" id="quitar4" class="der input" value="« Quitar"><br />
                    <input type="button" id="pasartodos4" class="izq input" value="Todos »"><input type="button" id="quitartodos4" class="der input" value="« Todos">
                </div>
                <div class="acor">
                    <select name="empresa_destino[]" id="empresa_destino" multiple="multiple" class="select" size="8"></select>
                </div>
            </div>
            <h3>REFERENCIA TRANSACCIÓN</h3>
            <div>
                <div class="acor">
                    <select name="pagos_origen[]" id="pagos_origen" multiple="multiple"  class="select" size="8">
                        <option value="T.FECHA_PAGO">FECHA PAGO</option>
                        <option value="BANCO.NOMBREBANCO NOMBRE_BANCO">BANCO ORIGEN</option>
                        <!--<option value="B.">BANCO RECAUDO</option>-->
                        <option value="T.NUM_DOCUMENTO TRANSACCION">NUMERO TRANSACCION</option>
                        <option value="ASOBANCARIA_DET.COD_DETALLE PLANILLA">NUMERO PLANILLA</option>
                        <option value="T.PROCEDENCIA FUENTE">FUENTE RECAUDO</option>
                        <option value="ASOBANCARIA_DET.COD_OPERADOR">CODIGO OPERADOR</option>
                        <option value="T.CODIGO_SIIF">CODIGO SIIF</option>
                        <option value="TIPOCONCEPTO.NOMBRE_TIPO NOMBRE_CONCEPTO">NOMBRE CONCEPTO</option>
                        <option value="CONCEPTORECAUDO.NOMBRE_CONCEPTO NOMBRE_SUBCONCEPTO">NOMBRE SUBCONCEPTO</option>
                    </select>
                </div>
                <div class="acor">
                    <input type="button" id="pasar1" class="izq input" value="Pasar »"><input type="button" id="quitar1" class="der input" value="« Quitar"><br />
                    <input type="button" id="pasartodos1" class="izq input" value="Todos »"><input type="button" id="quitartodos1" class="der input" value="« Todos">
                </div>
                <div class="acor">
                    <select name="pagos_destino[]" id="pagos_destino" multiple="multiple" class="select" size="8"></select>
                </div>
            </div>
            <!--             <h3>ASOBANCARIA ENCABEZADO</h3>
                       <div>
                            <div class="acor">
                                <select name="asobancariae_origen[]" id="asobancariae_origen" multiple="multiple"  class="select" size="8">
            <?php $resolucion = Reporteador::datos_tabla('ASOBANCARIA_ENC') ?>
            <?php
            foreach ($resolucion as $resolucion2) {
                $info = str_replace("_", " ", $resolucion2['COLUMN_NAME']);
                ?>
                                                <option value="ASOBANCARIA_ENC.<?php echo $resolucion2['COLUMN_NAME'] ?>"><?php echo $info ?></option>
                <?php
            }
            ?>
                                </select>
                            </div>
                            <div class="acor">
                                <input type="button" id="pasar3" class="izq input" value="Pasar »"><input type="button" id="quitar3" class="der input" value="« Quitar"><br />
                                <input type="button" id="pasartodos3" class="izq input" value="Todos »"><input type="button" id="quitartodos3" class="der input" value="« Todos">
                            </div>
                            <div class="acor">
                                <select name="asobancariae_destino[]" id="asobancariae_destino" multiple="multiple" class="select" size="8"></select>
                            </div>
                        </div>
                        <h3>ASOBANCARIA DET</h3>
                        <div>
                            <div class="acor">
                                <select name="asobancariad_origen[]" id="asobancariad_origen" multiple="multiple"  class="select" size="8">
            <?php $resolucion = Reporteador::datos_tabla('ASOBANCARIA_DET') ?>
            <?php
            foreach ($resolucion as $resolucion2) {
                $info = str_replace("_", " ", $resolucion2['COLUMN_NAME']);
                ?>
                                                <option value="ASOBANCARIA_DET.<?php echo $resolucion2['COLUMN_NAME'] ?>"><?php echo $info ?></option>
                <?php
            }
            ?>
                                </select>
                            </div>
                            <div class="acor">
                                <input type="button" id="pasar2" class="izq input" value="Pasar »"><input type="button" id="quitar2" class="der input" value="« Quitar"><br />
                                <input type="button" id="pasartodos2" class="izq input" value="Todos »"><input type="button" id="quitartodos2" class="der input" value="« Todos">
                            </div>
                            <div class="acor">
                                <select name="asobancariad_destino[]" id="asobancariad_destino" multiple="multiple" class="select" size="8"></select>
                            </div>
                        </div>-->
            <h3>FILTROS</h3>
            <div >
                <p><br>
                    <input type="hidden" id="archivos_nuevos" name="archivos_nuevos">
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
                            <input type="text" id="empresa" name="empresa"><img id="preloadmini" src="<?php echo base_url('img/319.gif') ?>" width="28" height="28" />
                        </td>
                        <td>
                            Regional
                        </td>
                        <td rowspan="2">
                            <select multiple name="regional[]" id="regional" style="height: 100px">
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
                            <div class="vista">Fecha Pago Inicial</div>
                        </td>
                        <td>
                            <div class="vista"><input type="text" class="fecha" readonly id="fecha_ini" name="fecha_ini" value=""></div>
                        </td>
                        <td><div class="vista">Fecha Pago Final</div></td>
                        <td>
                            <div class="vista"><input type="text" class="fecha" readonly id="fecha_fin" name="fecha_fin" value=""></div>
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
                        <td><div class="vista"><input type="text" class="periodo" readonly id="fecha_periodo_ini" name="fecha_periodo_ini" value="<?php echo isset($_POST['fecha_periodo_ini']) ? $_POST['fecha_periodo_ini'] : '' ?>"></div></td>
                        <td>Periodo Final</td>
                        <td><div class="vista"><input type="text" class="periodo" readonly id="fecha_periodo_fin" name="fecha_periodo_fin" value="<?php echo isset($_POST['fecha_periodo_fin']) ? $_POST['fecha_periodo_fin'] : '' ?>"></div></td>
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
                                    <option value="<">Menor</option>
                                    <option value=">">mayor</option>
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
                </table>
                <table>
                    <tr>
                        <td></td>
                    </tr>
                </table>
                </p>
            </div>
        </div>
        <p><br>
        <table >
            <tr>
            <input type="hidden" id="accion" name="accion" value="0">
            <input type="hidden" id="name_reporte" name="name_reporte" value="<?php echo $titulo; ?>">
            <td  align="center">
                <p>
                    <button class="primer fa fa-bar-chart-o btn btn-success" align="center" id="cunsultar"> Consultar</button>&nbsp;&nbsp;             

                    <button id="pdf" class="btn btn-info fa fa-floppy-o"> PDF</button>&nbsp;&nbsp;

                    <button id="excel" class="btn btn-info fa fa-table" > Excel</button>&nbsp;&nbsp;
                    <button id="excel" class="btn btn fa fa-eraser " onclick="formReset();" > Limpiar</button>
                    <button class="btn btn-success fa fa-floppy-o" id="guardar" style="display: none">Guardar Consultar</button>

            </td>
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

<div  id="formulario_final" class='modal hide fade modal-body'>
    <form id="form2" action="<?php echo base_url('index.php/reporteador/importar') ?>" method="POST" ><!--target="_blank"-->
        <table width="100%">
            <tr><td colspan="2" align="center" style="background-color: #5BB75B"><b>Guardar este reporte como:</b></td></tr>
            <tr>
                <td  align="center">Nombre de la Consulta</td>
                <td  align="center"><input type="text" maxlength="30" id="nombre" name="nombre"></td> 
            </tr>
            <tr>
                <td  align="center">Privado</td>
                <td  align="center">General</td>
            </tr>
            <tr>
                <td  align="center"><input type="radio" class="radio" name="guardar" checked="checked" value="1"></td>
                <td  align="center"><input type="radio" class="radio" name="guardar" value="2"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <!--<button class="btn btn-success" id="guardar2">Guardar Consultar</button>-->
                    <input type="button" class="btn btn-success" value="Guardar Consultar" id="guardar2" />
                </td>
            </tr>
        </table>
        <input type="text" class="accion" name="accion" style="display: none">
        <textarea id="consulta_info" name="consulta_info" style="display: none"><?php echo base64_encode($consulta); ?></textarea>
    </form>
</div>

<div id="resultados"></div>

<script>
    $('#ciiu').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#empresa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#empleado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#fiscalizador').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');

    $('#div_regional').hide();

    $('#guardar').click(function() {
        $('.accion').val('3');
        var consul = $('#consultar_ya').val();
        $('#consulta_info').val(consul);


        $('#inf_ayuda').css({
            'width': 'auto',
            "position": "fixed",
            "height": 'auto',
            //                "top" : "20",
//            "margin-top": "-500px"
        });
        $('#formulario_final').modal('show');
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
    $(".fecha").datepicker("option", "yearRange", "-99:+0");
    $(".fecha").datepicker("option", "maxDate", "+0m +0d");


    $('.primer').click(function() {

        if ($('#reporte').val() == 61 && $('#cod_operador').val() == '') {
            alert('El campo Operador es obligatorio');
            $('#cod_operador').focus();
            return false;
        }
        if ($('#reporte').val() == 65 && $('#salarios_minimos').val() == '') {
            alert('El campo Nro salarios es obligatorio');
            $('#salarios_minimos').focus();
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
                    $('#guardar').show();
                }).fail(function(msg) {
            jQuery(".preload, .load").hide();
            alert('Datos No Encontrados');
        })
    });

    function ajaxValidationCallback(status, form, json, options) {

    }
    function formReset()
    {
        document.getElementById("form1").reset();
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

    function enviar() {
        if ($('#reporte').val() == 61 && $('#cod_operador').val() == '') {
            alert('El campo Operador es oblogatorio');
            $('#cod_operador').focus();
            return false;
        }
        if ($('#reporte').val() == 65 && $('#salarios_minimos').val() == '') {
            alert('El campo Nro salarios es oblogatorio');
            $('#salarios_minimos').focus();
            return false;
        }
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
    $("#preloadmini").hide();
    $("#preloadmini2").hide();
    $("#preloadmini3").hide();
    jQuery(".preload, .load").hide();

    $('#reporte').click(function() {
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
    });

    $('.smlv').hide();
    $('.ano').hide();
    $('.num_registros').hide();

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
    $(function() {
        $("#accordion").accordion({active: 4});
    });



    $('#pasar1').click(function() {
        $('#pagos_origen option:selected').remove().appendTo('#pagos_destino');
    });
    $('#quitar1').click(function() {
        $('#pagos_destino option:selected').remove().appendTo('#pagos_origen');
    });
    $('#pasartodos1').click(function() {
        $('#pagos_origen option').each(function() {
            $(this).remove().appendTo('#pagos_destino');
        });
    });
    $('#quitartodos1').click(function() {
        $('#pagos_destino option').each(function() {
            $(this).remove().appendTo('#pagos_origen');
        });
    });
    $('.submit').click(function() {
        $('#pagos_destino option').prop('selected', 'selected');
    });


    //asobancaria detalle
    $('#pasar2').click(function() {
        $('#asobancariad_origen option:selected').remove().appendTo('#asobancariad_destino');
    });
    $('#quitar2').click(function() {
        $('#asobancariad_destino option:selected').remove().appendTo('#asobancariad_origen');
    });
    $('#pasartodos2').click(function() {
        $('#asobancariad_origen option').each(function() {
            $(this).remove().appendTo('#asobancariad_destino');
        });
    });
    $('#quitartodos2').click(function() {
        $('#asobancariad_destino option').each(function() {
            $(this).remove().appendTo('#asobancariad_origen');
        });
    });
    $('.submit').click(function() {
        $('#asobancariad_destino option').prop('selected', 'selected');
    });
    //asobancaria encabesado
    $('#pasar3').click(function() {
        $('#asobancariae_origen option:selected').remove().appendTo('#asobancariae_destino');
    });
    $('#quitar3').click(function() {
        $('#asobancariae_destino option:selected').remove().appendTo('#asobancariae_origen');
    });
    $('#pasartodos3').click(function() {
        $('#asobancariae_origen option').each(function() {
            $(this).remove().appendTo('#asobancariae_destino');
        });
    });
    $('#quitartodos3').click(function() {
        $('#asobancariae_destino option').each(function() {
            $(this).remove().appendTo('#asobancariae_origen');
        });
    });
    $('.submit').click(function() {
        $('#asobancariad_destino option').prop('selected', 'selected');
    });

    //asobancaria encabesado
    $('#pasar4').click(function() {
        $('#empresa_origen option:selected').remove().appendTo('#empresa_destino');
    });
    $('#quitar4').click(function() {
        $('#empresa_destino option:selected').remove().appendTo('#empresa_origen');
    });
    $('#pasartodos4').click(function() {
        $('#empresa_origen option').each(function() {
            $(this).remove().appendTo('#empresa_destino');
        });
    });
    $('#quitartodos4').click(function() {
        $('#empresa_destino option').each(function() {
            $(this).remove().appendTo('#empresa_origen');
        });
    });
    $('.submit').click(function() {
        $('#empresa_destino option').prop('selected', 'selected');
    });

    //tipo_concepto
    $('#pasar5').click(function() {
        $('#tipo_concepto_origen option:selected').remove().appendTo('#tipo_concepto_destino');
    });
    $('#quitar5').click(function() {
        $('#tipo_concepto_destino option:selected').remove().appendTo('#tipo_concepto_origen');
    });
    $('#pasartodos5').click(function() {
        $('#tipo_concepto_origen option').each(function() {
            $(this).remove().appendTo('#tipo_concepto_destino');
        });
    });
    $('#quitartodos5').click(function() {
        $('#tipo_concepto_destino option').each(function() {
            $(this).remove().appendTo('#tipo_concepto_origen');
        });
    });
    $('.submit').click(function() {
        $('#tipo_concepto_destino option').prop('selected', 'selected');
    });
    //tipo_subconcepto
    $('#pasar6').click(function() {
        $('#tipo_subconcepto_origen option:selected').remove().appendTo('#tipo_subconcepto_destino');
    });
    $('#quitar6').click(function() {
        $('#tipo_subconcepto_destino option:selected').remove().appendTo('#tipo_subconcepto_origen');
    });
    $('#pasartodos6').click(function() {
        $('#tipo_subconcepto_origen option').each(function() {
            $(this).remove().appendTo('#tipo_subconcepto_destino');
        });
    });
    $('#quitartodos6').click(function() {
        $('#tipo_subconcepto_destino option').each(function() {
            $(this).remove().appendTo('#tipo_subconcepto_origen');
        });
    });
    $('.submit').click(function() {
        $('#tipo_subconcepto_destino option').prop('selected', 'selected');
    });

    $('#guardar2').click(function() {
        var nombre = $('#nombre').val();
        if (nombre == "") {
            alert('Datos Incompletos');
            return false;
        }
        $('#form2').submit();
    });

//$( ".selector" ).accordion({ active: 3 });
</script>
<style>
    .acor{
        float: left;
        text-align: center;
        width: 260px;
    }

    .clear {
        clear: both;
        text-align: center;
    }

    .input {
        border: 1px solid #ccc;
        margin: 25px 1px 0;
        padding: 10px;
    }

    .select {
        border: 1px solid #ccc;
        border-radius: 10px 0 0 10px;
        margin: 0 0 50px;
        padding: 10px;
        width: 250px;
    }
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
