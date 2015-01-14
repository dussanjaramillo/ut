<div id="form1">
    <table width="100%" align="center">
        <tr>
            <td>Regional</td>
            <td>Resolucion</td>
            <td>Fecha Resoluci&oacute;n</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_REGIONAL'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NUMERO_RESOLUCION'] ?></td>
            <td class="colores"><?php echo $consulta[0]['FECHA_CREACION'] ?></td>
        </tr>
        <tr>
            <td>Nombre de la Empresa</td>
            <td>Nit de la Empresa</td>
            <td>Direccion de la Empresa</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_EMPRESA'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NITEMPRESA'] ?></td>
            <td class="colores"><?php echo $consulta[0]['DIRECCION'] ?></td>
        </tr>
        <tr>
            <td>Nombre del Representante Legal</td>
            <td>Concepto</td>
            <td>Valor de la Obligacion</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_CONCEPTO'] ?></td>
            <td class="colores"><?php echo number_format($consulta[0]['VALOR_TOTAL']) ?></td>
        </tr>
        <tr>
            <td>Valor en Letras</td>
        </tr>
        <tr>
            <td class="colores" colspan="3"><?php echo $consulta[0]['VALOR_LETRAS'] ?></td>
        </tr>
        <tr>
            <td><div style="display: none">Vigencia</div></td>
        </tr>
        <tr>
            <td colspan="3"><div style="display: none"><textarea id="vigencia" style="width: 100%" id="vigencias" name="vigencias"></textarea></div></td>
        </tr>
        <tr>
            <td><div style="display: none">Argumentos</div></td>
        </tr>
        <tr>
            <td colspan="3"><div style="display: none"><textarea id="argumentos" style="width: 100%" id="argumentos" name="argumentos"></textarea></div></td>
        </tr>
        <tr>
            <td>Director Regional</td>
            <td>Revisor</td>
            <td>Proyector</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_DIRECTOR'] ?></td>
            <td class="colores"><?php echo $info_user ?></td>
            <td class="colores"><?php echo $info_user ?></td>
        </tr>
        <?php if ($consulta2[0]['CANTIDAD'] == 1) { ?>
            <tr>
                <td colspan="3">
            <center><button id="reliquidacion" class="btn btn-success">Envio a Reliquidaci&oacute;n</button></center>
            </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="3"><hr></td>
        </tr>
        <tr>
            <td colspan="3" align='center'><b>La informacion de la nueva liquidacion es:</b></td>
        </tr>
        <tr>
            <td colspan="3"><hr></td>
        </tr>
        <tr>
            <td>Numero Liquidacion</td>
            <td>Valor Nueva Liquidacion</td>
            <td>Valor en Letras</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $liquidacion['NUM_LIQUIDACION'] ?></td>
            <td class="colores"><?php echo number_format($liquidacion['SALDO_DEUDA']) ?></td>
            <td class="colores"><?php echo $info = Resolucion::valorEnLetras($liquidacion['SALDO_DEUDA'], "pesos"); ?></td>
        </tr>

    </table>
    <center><button id="continuar" class="btn btn-success">Continuar</button></center>
</div>
<div id="textarea" style="display: none"><textarea id="informacion" name="informacion" style="width: 100%;height: 400px"><?php echo $texto ?></textarea>
    <center><button id="enviar" class="btn btn-success">Enviar</button> <button onclick="detalle()" class=" btn btn-success">Detalle Liquidaci&oacute;n</button></center>

</div>
<div style="display: none;z-index: 1000" id="detalle_info">
    <?php
    $table = "";
    $cod = $consulta[0]['COD_CPTO_FISCALIZACION'];
    if ($cod == 1) {
        $cantidad = count($detalle);
        $table = "<table border='1'><tr>";
        for ($i = 0; $i < $cantidad; $i++) {
            $table.= "<td>";
            $table.= "<table border='1'>";
            $detalles = $detalle[$i];
            $table .="<tr><td colspan='2' align='center' style='background:#5bb75b'>" . number_format($detalles['ANO']) . "</td></tr>";
            $table .="<tr><td>VALOR SUELDO</td><td>" . number_format($detalles['VALORSUELDOS']) . "</td></tr>";
            $table .="<tr><td>VALOR SOBRE SUELDOS</td><td>" . number_format($detalles['VALORSOBRESUELDOS']) . "</td></tr>";
            $table .="<tr><td>SALARIO INTEGRAL</td><td>" . number_format($detalles['SALARIOINTEGRAL']) . "</td></tr>";
            $table .="<tr><td>SALARIO ESPECIE</td><td>" . number_format($detalles['SALARIOESPECIE']) . "</td></tr>";
            $table .="<tr><td>SUPER NUMERARIOS</td><td>" . number_format($detalles['SUPERNUMERARIOS']) . "</td></tr>";
            $table .="<tr><td>JORNALES</td><td>" . number_format($detalles['JORNALES']) . "</td></tr>";
            $table .="<tr><td>AUXILIO TRANSPORTE</td><td>" . number_format($detalles['AUXILIOTRANSPORTE']) . "</td></tr>";
            $table .="<tr><td>HORAS EXTRAS</td><td>" . number_format($detalles['HORASEXTRAS']) . "</td></tr>";
            $table .="<tr><td>DOMINICALES FESTIVOS</td><td>" . number_format($detalles['DOMINICALES_FESTIVOS']) . "</td></tr>";
            $table .="<tr><td>RECARGO NOCTURNO</td><td>" . number_format($detalles['RECARGONOCTURNO']) . "</td></tr>";
            $table .="<tr><td>VIATICOS</td><td>" . number_format($detalles['VIATICOS']) . "</td></tr>";
            $table .="<tr><td>BONIFICACIONES</td><td>" . number_format($detalles['BONIFICACIONES']) . "</td></tr>";
            $table .="<tr><td>COMISIONES</td><td>" . number_format($detalles['COMISIONES']) . "</td></tr>";
            $table .="<tr><td>POR SOBREVENTAS</td><td>" . number_format($detalles['POR_SOBREVENTAS']) . "</td></tr>";
            $table .="<tr><td>VACACIONES</td><td>" . number_format($detalles['VACACIONES']) . "</td></tr>";
            $table .="<tr><td>TRAB. DOMICILIO</td><td>" . number_format($detalles['TRAB_DOMICILIO']) . "</td></tr>";
            $table .="<tr><td>PRIMA TEC SALARIAL</td><td>" . number_format($detalles['PRIMA_TEC_SALARIAL']) . "</td></tr>";
            $table .="<tr><td>AUXILIO ALIMENTACION</td><td>" . number_format($detalles['AUXILIO_ALIMENTACION']) . "</td></tr>";
            $table .="<tr><td>PRIMA SERVICIO</td><td>" . number_format($detalles['PRIMA_SERVICIO']) . "</td></tr>";
            $table .="<tr><td>PRIMA LOCALIZACION</td><td>" . number_format($detalles['PRIMA_LOCALIZACION']) . "</td></tr>";
            $table .="<tr><td>PRIMA VIVIENDA</td><td>" . number_format($detalles['PRIMA_VIVIENDA']) . "</td></tr>";
            $table .="<tr><td>GAST REPRESENTACION</td><td>" . number_format($detalles['GAST_REPRESENTACION']) . "</td></tr>";
            $table .="<tr><td>PRIMA ANTIGUEDAD</td><td>" . number_format($detalles['PRIMA_ANTIGUEDAD']) . "</td></tr>";
            $table .="<tr><td>PRIMA EXTRALEGALES</td><td>" . number_format($detalles['PRIMA_EXTRALEGALES']) . "</td></tr>";
            $table .="<tr><td>PRIMA VACACIONES</td><td>" . number_format($detalles['PRIMA_VACACIONES']) . "</td></tr>";
            $table .="<tr><td>PRIMA NAVIDAD</td><td>" . number_format($detalles['PRIMA_NAVIDAD']) . "</td></tr>";
            $table .="<tr><td>CONTRATOS AGRICOLAS</td><td>" . number_format($detalles['CONTRATOS_AGRICOLAS']) . "</td></tr>";
            $table .="<tr><td>REMU SOCIOS INDUSTRIALES</td><td>" . number_format($detalles['REMU_SOCIOS_INDUSTRIALES']) . "</td></tr>";
            $table .="<tr><td>HORA CATEDRA</td><td>" . number_format($detalles['HORA_CATEDRA']) . "</td></tr>";
            $table .="<tr><td>OTROS PAGOS</td><td>" . number_format($detalles['OTROS_PAGOS']) . "</td></tr>";
            $table .="</table><p>";
            $table.= "</td>";
        }
        $table.= "</tr>";
        $table .="</table><p>";
    }
    if ($cod == 2) {
        $cantidad = count($detalle);
        for ($i = 0; $i < $cantidad; $i++) {
            $table = "<table border='0' width='100%'>";
            $detalles = $detalle[$i];
            if (!empty($detalles['NUM_LIQUIDACION'])) {
                $table .="<tr><td colspan='2' align='center' style='background:#5bb75b'>PRESUNTIVA</td></tr>";
                $table .="<tr><td >NUM lIQUIDACION</td><td>" . number_format($detalles['NUM_LIQUIDACION']) . "</td></tr>";
                $table .="<tr><td>VLR CONTRATO TODOCOSTO</td><td>" . number_format($detalles['VLR_CONTRATO_TODOCOSTO']) . "</td></tr>";
                $table .="<tr><td>VLR CONTRATO MANO OBRA</td><td>" . number_format($detalles['VLR_CONTRATO_MANO_OBRA']) . "</td></tr>";
                $table .="<tr><td>PAGOS FIC DESCONTAR</td><td>" . number_format($detalles['PAGOS_FIC_DESCONTAR']) . "</td></tr>";
            }
            if (!empty($detalles['ANO'])) {
                $table .="<tr><td colspan='2' align='center' style='background:#5bb75b'>NORMATIVA</td></tr>";
                $table .="<tr><td>ANO</td><td>" . $detalles['ANO'] . "</td></tr>";
                $table .="<tr><td>NRO_TRABAJADORES</td><td>" . $detalles['NRO_TRABAJADORES'] . "</td></tr>";
                $table .="<tr><td>TOTAL_ANO</td><td>" . $detalles['TOTAL_ANO'] . "</td></tr>";
                $table .="<tr><td>MESCOBRO</td><td>" . $detalles['MESCOBRO'] . "</td></tr>";
            }
        }
        $table .="</table><p>";
    }

    echo $table;
    ?>
</div>
<script>

    function detalle() {
        $('#detalle_info').show();
        $('#detalle_info').dialog({
            autoOpen: true,
            modal: true,
            title: 'Información de la Resolución',
            width: 600,
        });
        return false;
    }

    $('#reliquidacion').click(function() {
        var r=confirm('¿Esta Seguro que Desea Reliquidar?');
        if(r==false){
            return false;
        }
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/recurso_reliquidacion') ?>";
        var cod_fis = '<?php echo $post['cod_fis'] ?>';
        $.post(url, {cod_fis: cod_fis})
                .done(function() {
                    alert('Los Datos Fueron Guardados con Exito');
                    window.location.reload();
                }).fail(function() {
//            alert('Error al Guardar')
            $(".preload, .load").hide();
        });
    });

    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
        title: 'Información de la Resolución',
//        height: 600,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#enviar').click(function() {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/guardar_archivo') ?>";
        var informacion = tinymce.get('informacion').getContent();
        informacion = informacion + "<br><p>ARGUMENTOS<br>" + $('#argumentos').val() + "<br><p>VIGENCIA<br><p>" + $('#vigencia').val();
        var nit =<?php echo $consulta[0]['NITEMPRESA'] ?>;
        var id =<?php echo $consulta[0]['COD_RESOLUCION'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;

        var num_liquidacion = '<?php echo $liquidacion['NUM_LIQUIDACION'] ?>';
        var nuevo_valor = '<?php echo $liquidacion['SALDO_DEUDA'] ?>';
        var letras = '<?php echo $info = Resolucion::valorEnLetras($liquidacion['SALDO_DEUDA'], "pesos"); ?>';

        $.post(url, {informacion: informacion, id: id, nombre: nombre_archivo})
                .done(function(msg) {
                    var url = "<?php echo base_url('index.php/resolucion/guardar_recurso') ?>";
                    $.post(url, {id: id, nombre_archivo: nombre_archivo, num_liquidacion: num_liquidacion, nuevo_valor: nuevo_valor, letras: letras})
                            .done(function(msg) {
                                alert("Los datos fueron guardados con exito");
                                window.location.reload();
                            })
                            .fail(function(xhr) {
                                $(".preload, .load").hide();
                                alert("Los datos no fueron guardados");
                            });

                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                })
    });
    $('#continuar').click(function() {
        $('#form1').hide();
        $('#textarea').show();
    });
    tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });
</script>
<style>
    .colores{
        color: slategrey;
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