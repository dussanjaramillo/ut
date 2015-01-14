

<table width="100%" align="center">
    <?php
    $i = 1;
    foreach ($id_consulta as $id) {
        ?>
        <tr>
            <td>
                Investigaci&oacute;n de Dineros y/o Bienes <?php echo $i; ?>
            </td>
            <td>
                <select class="documento_uno" name="documento_uno" onchange="informacion_txt(this)">
                    <option value=""></option>
                    <?php
                    $consulta = Mcinvestigacion::documentos_vistas($id['COD_MEDIDACAUTELAR']);
                    foreach ($consulta as $documento) {
                        echo "<option value='" . $documento['RUTA_DOCUMENTO_GEN'] . "'>" . $documento['NOMBRE_OFICIO'] . " - " . $documento['FECHA_CREACION'] . "</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <?php
        $i++;
    }
    ?> 
</table>
<div id="contenido"></div>
<div id="imprimir_doc" style="display: none" aling="center"><p><center><button id="imprimir" aling="center" class="btn btn-primary">Imprimir</button></center></div>
<script>
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 450,
        model: true,
        title: "Documentos Generados",
        close: function() {
            $('#resultado *').remove();
        }
    });
    function informacion_txt(dato) {
        $('#imprimir_doc').hide();
        $("#contenido").html('');
        if (dato.value == "")
            return false;
        var infor = dato.value;
        infor = infor.split('.');
        if (infor[1] == "txt") {
            txt(dato.value);
        } else {
            var ruta = "<?php echo base_url() . RUTA_DES . $post['id'] ?>/" + dato.value;
            var direccion = "<a href='" + ruta + "' target='_blank'>Descargar Archivo</a>";
            $("#contenido").html(direccion);
        }
    }
    function txt(dato) {
        jQuery(".preload, .load").show();
        var ruta = "<?php echo RUTA_DES . $post['id'] ?>/" + dato;
        var url = "<?php echo base_url('index.php/mcinvestigacion/abrir_txt') ?>"
        $.post(url, {ruta: ruta})
                .done(function(msg) {
                    jQuery(".preload, .load").hide();
                    msg = '<div id="documento">' + msg + '</div>';
                    $("#contenido").html(msg);
                    $("#contenido").css("border", "1px solid #CCC");
                    $('#imprimir_doc').show();
                }).fail(function() {
            $('#imprimir_doc').hide();
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        });
    }

    $('#imprimir').click(function() {
//        var resultadoHTML = $('#resultadoHTML').text();
//        alert(resultadoHTML);
        printdiv("", "");
    });
    function printdiv(id_cabecera, id_cuerpo)
    {
        var tittle = document.getElementById(id_cabecera);
        var content = document.getElementById('documento');
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
<style>
    .color{
        color: #FC7323;
        font:bold 12px;
    }
    #tabla_inicial{
        border-radius: 50px;
        border: 1px solid #CCC;
    }
</style>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />