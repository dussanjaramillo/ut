<table width="100%">
    <tr align="center" style="color:red">
        <td><b>No Auto de Cargos</b></td>
        <td><b><?php echo $documentacion_resolcuion[0]['NUMERO_RESOLUCION'] ?></b></td>
    </tr>
    <tr>
        <td>Auto de Cargos Firmada</td>
        <td>
            <select name="resolucion" onchange="informacion(this, '1')">
                <option value=""></option>
                <?php foreach ($documentacion_resolcuion as $documentacion) { ?>
                    <option value="<?php echo $documentacion['RUTA_DOCUMENTO_FIRMADO'] ?>"><?php echo $documentacion['NOMBRE_ARCHIVO_RESOLUCION']; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <?php if (!empty($documentacion_citacion)) { ?>
        <tr>
            <td>Citaciones Generadas </td>
            <td>
                <select name="resolucion" onchange="plantillas(this, '1')">
                    <option value="" ></option>
                    <?php foreach ($documentacion_citacion as $documentacion) { ?>
                        <option value="<?php echo $documentacion['DOCUMENTO_GENERADO'] ?>"><?php echo $documentacion['TIPOGESTION'] ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    <?php } ?>
    <?php if (!empty($correspondencia)) { ?>
        <tr>
            <td>Correspondencia</td>
            <td>
                <select name="resolucion" onchange="informacion(this, '1')">
                    <option value="" ></option>
                    <?php
                    foreach ($correspondencia as $documentacion) {
                        if (!empty($documentacion['RUTA_COLILLA'])) {
                            if (!empty($documentacion['FECHA_DEVOLUCION']))
                                $fecha = $documentacion['FECHA_DEVOLUCION'];
                            if (!empty($documentacion['FECHA_RECEPCION']))
                                $fecha = $documentacion['FECHA_RECEPCION'];
                            ?>
                            <option value="<?php echo $documentacion['RUTA_COLILLA'] ?>"><?php echo $documentacion['NOMBRE_RECEPTOR'] . ", Num Colilla" . $documentacion['NUM_COLILLA'] . ", Fecha" . $fecha ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
    <?php } ?>
    <?php if (!empty($documentacion_recurso)) { ?>
        <tr>
            <td>Solucion Recurso Plantilla</td>
            <td>
                <select name="resolucion" onchange="plantillas(this, '1')">
                    <option value=""></option>
                    <?php
                    foreach ($documentacion_recurso as $documentacion) {
                        $informacion = Resolucion::documentacion_RESOLUCIONRECURSO($documentacion['NUM_RECURSO']);
                        foreach ($informacion as $information) {
                            if ($information['NOM_DOCU_RESOL_RECURSO'] != "") {
                                ?>
                                <option value="<?php echo $information['NOM_DOCU_RESOL_RECURSO'] ?>">Respuesta</option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Solucion Recurso PDF</td>
            <td>
                <select name="resolucion" onchange="informacion(this, '1')">
                    <option value=""></option>
                    <?php
                    foreach ($documentacion_recurso as $documentacion) {
                        $informacion = Resolucion::documentacion_RESOLUCIONRECURSO($documentacion['NUM_RECURSO']);
                        foreach ($informacion as $information) {
                            if ($information['NOMBRE_DOCU_FIRMADO'] != "") {
                                ?>
                                <option value="<?php echo $information['NOMBRE_DOCU_FIRMADO'] ?>">Respuesta</option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
    <?php } ?>
<!--    <tr>
<td>Recursos Plantillas</td>
<td>
    <select name="resolucion" onchange="plantillas(this, '1')">
        <option value=""></option>
    <?php
    foreach ($documentacion_recurso as $documentacion) {
        $informacion = Resolucion::documentacion_RESOLUCIONRECURSO($documentacion['NUM_RECURSO']);
        foreach ($informacion as $information) {
            ?>
                                                                <option value="<?php echo $information['NOM_DOCU_RESOL_RECURSO'] ?>">Respuesta -- <?php echo $information['NOM_DOCU_RESOL_RECURSO'] ?></option>
            <?php
        }
    }
    ?>
    </select>
</td>
</tr>-->
</table>

<div id="resultadoHTML"></div>
<div id="imprimir" align="center" style="display: none">
    <input type="hidden" id="proyecto">
    <button id="imprimir" class="btn btn-primary">Imprimir</button>
</div>



<script>

    num_resolu = "<?php echo $documentacion_resolcuion[0]['NUMERO_RESOLUCION'] ?>";

    id = "<?php echo $post['id'] ?>";
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
        title: "Ver Documentos",
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
    function informacion(dato, ruta) {
        if (dato.value == "") {
            $('#resultadoHTML').html("");
            return false;
        }
        var rutas = (ruta == 1) ? "./uploads/resolcion/" : "./uploads/resolcion/" + id + "_Recurso/";
        var rutas2 = (ruta == 1) ? "" : "" + id + "_Recurso/";
        var pdf = dato.value;
        $('#resultado').dialog({
            width: 800,
            height: 600,
        });
        pdf2(pdf);
    }
    function plantillas(dato, ruta) {
        if (dato.value == "") {
            $('#resultadoHTML').html("");
            return false;
        }
        var rutas = (ruta == 1) ? '<?php echo 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/'; ?>' + dato.value + ".txt" : '<?php echo 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/'; ?>' + "_Recurso/" + dato.value + ".txt";
        var url = "<?php echo base_url("index.php/plantillas/view2") ?>";
        $.post(url, {rutas: rutas})
                .done(function(msg) {
                    $('#resultado').dialog({
                        width: 800,
                        height: 600,
                    });
                    $('#resultadoHTML').html(msg);
                    $('#proyecto').val(msg);
                    $('#resultadoHTML').css("border", "1px solid #CCC");
                    $('#imprimir').show();
                }).fail(function(msg, error) {
            alert('ERROR');
        });
    }
    function pdf2(pdf) {
        $('#imprimir').hide();
        var html = '<a  href="<?php echo base_url() .  'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/'; ?>' +  "/" + pdf + '" target="_blank" style="color:red" align="center">Descargar Documento</a>';
        $('#resultadoHTML').html(html);

    }
    $('#imprimir').click(function() {
//        var resultadoHTML = $('#resultadoHTML').text();
//        alert(resultadoHTML);
        printdiv("", "");
    });


    function printdiv(id_cabecera, id_cuerpo)
    {
        var tittle = document.getElementById(id_cabecera);
        var content = document.getElementById('resultadoHTML');
        tittle = "";
//        content = "hola mundo";
        var printscreen = window.open('', '', 'left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
        printscreen.document.write(tittle);
        printscreen.document.write(content.innerHTML);
        printscreen.document.close();

        var css = printscreen.document.createElement("link");
        css.setAttribute("href", "<?php echo base_url() ?>/css/bootstrap.css");
        css.setAttribute("rel", "stylesheet");
        css.setAttribute("type", "text/css");

        var css1 = printscreen.document.createElement("link");
        css1.setAttribute("href", "<?php echo base_url() ?>/css/style.css");
        css1.setAttribute("rel", "stylesheet");
        css1.setAttribute("type", "text/css");

        printscreen.document.head.appendChild(css);
        printscreen.document.head.appendChild(css1);


        printscreen.focus();
        printscreen.print();
        printscreen.close();
    }


</script>


<?php ?>