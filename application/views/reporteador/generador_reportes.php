<h3><center>GENERADOR DE REPORTES</center></h3>
<table>
    <tr>
        <td >
            <select id="tabla" name=tabla">
                <option value="-1">Todos...</option>
                <?php
                foreach ($tablas as $tabla) {
                    ?>
                    <option value="<?php echo $tabla['TABLE_NAME'] ?>"><?php echo $tabla["TABLE_NAME"] ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
    </tr>
</table>
<div id="resultado"></div>
<div id="" >
    <form id='form1' style="display: none">
        <div style="display: none">
            <textarea id="tabla_inicial" name="tabla_inicial" style="display: none"></textarea>
            <textarea id="tarea" name="tarea"  style="display: none"></textarea>
            <textarea id="tarea_where" name="tarea_where"  style="display: none"></textarea>
            <textarea id="join" name="join"  style="display: none"></textarea>
        </div>
    </form>
</div>
<div id="resultado3" style="display: none">
    <table width="100%" style="border:1px solid #FFF">
        <tr>
            <td bgcolor="#5BB75B" style="color: #FFF" align="center"><b>CAMPO</b></td>
        </tr>
        <tr>
            <td><div id="campo"></div></td>
        </tr>
        <tr>
            <td bgcolor="#5BB75B" style="color: #FFF" align="center"><b>TABLA INICIAL</b></td>
        </tr>
        <tr>
            <td><div id="tabla_inicial2"></div></td>
        </tr>
        <tr>
            <td bgcolor="#5BB75B" style="color: #FFF" align="center"><b>CONEX&Oacute;N CON OTRAS TABLAS</b></td>
        </tr>
        <tr>
            <td><div id="Conexion"></div></td>
        </tr>
        <tr>
            <td bgcolor="#5BB75B" style="color: #FFF" align="center"><b>DONDE</b></td>
        </tr>
        <tr>
            <td><div id="donde"></div></td>
        </tr>
    </table>
    <p><br>
    <div id="impri" style="display: none" align="center">
        <button class="btn btn-info" id="imprimir">Imprimir</button>
        <button class="btn btn-danger" id="pdf">PDF</button>
        <button class="btn btn-success" id="excel">Excel</button>
        <button class="btn btn-success" id="guardar">Guardar Consultar</button>
    </div>
</div>
<p><br>
<div id="resultado2"></div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $(".preload, .load").hide();
    gestion = 0;
    function ajaxValidationCallback(status, form, json, options) {

    }
    $('#tabla').change(function() {
        $(".preload, .load").show();
        var tarea = $('#tarea').val();
        if (tarea != "") {
            var entro = 1;
            var ayuda = "";
            var foranea = $('#foranea').val();
            if (foranea != "") {
                foranea = foranea.split('||');
                if (foranea.length > 1) {
                    for (var i = 0; i < foranea.length; i++) {
                        ayuda += foranea[i] + ",";
                        if ($('#tabla').val() == foranea[i]) {
                            entro = 0;
                        }
                    }
                    if (entro == 1) {
                        $('#tabla').val('');
                        var r = confirm("La tablas para integrar son: " + ayuda + "\n Desea Generar Nueva Consulta");
                        if (r == true) {
                            window.location.reload();
                        } else {
                            $(".preload, .load").hide();
                            return false;
                        }
                    }
                }
            } else {
                var r = confirm("La tabla no tiene relacion con otras tablas si continua se perdera la consulta");
                if (r == true) {
                    window.location.reload();
                    $('#tarea').val('');
                    $('#tarea_where').val('');
                    $('#foranea').val('');
                    $('#resultado').html('');
                    $('#resultado2').html('');
                    $('#join').val('');
                } else {
                    $('#tabla').val('');
                    $(".preload, .load").hide();
                    return false;
                }
            }

            if ($('#foranea2').val()) {
                if ($('#foranea2').val() != "") {
                    var foranea = $('#foranea2').val();
                    var tabla = $('#tabla').val();
                    foranea = foranea.split("|||||");
                    for (var i = 1; i < foranea.length; i++) {
                        var foranea2 = foranea[i].split('||||');
                        for (var j = 0; j < foranea2.length; j++) {
                            if (foranea2[0] == tabla) {
                                $('#join').val($('#join').val() + " " + foranea2[1]);
                                var cortar = $('#join').val();
                                cortar = cortar.split('JOIN');
                                var agregar = "";
                                for (var i = 1; i < cortar.length; i++) {
                                    agregar += cortar[i] + "<br>";
                                }
                                $('#Conexion').html(agregar);
                                j = foranea2.length;
                            }
                        }
                    }
                }
            }
        } else {
            $('#tabla_inicial').val($(this).val());
            $('#tabla_inicial2').html($(this).val());
        }
        var url = "<?php echo base_url('index.php/reporteador/ayuda') ?>";
        var tabla = $('#tabla').val();
        $.post(url, {tabla: tabla})
                .done(function(msg) {
                    $('#resultado').html(msg);
                    $(".preload, .load").hide();
                }).fail(function() {
            $(".preload, .load").hide();
            alert('Error en la Consulta');
            $('#tabla').val('');
            $('#resultado').html('');
            $('#resultado2').html('');
        });
        $('#resultado3').show();
    });

    $('#imprimir').click(function() {
        printdiv();
    });
    $('#pdf').click(function() {
        $('#accion').val('1');
        $('#form2').submit();
    });

    $('#guardar').click(function() {
        $('#accion').val('3');
        $('#inf_ayuda').css({
            'width': 'auto',
            "position": "fixed",
            "height": 'auto',
            //                "top" : "20",
//            "margin-top": "-500px"
        });
        $('#formulario_final').modal('show');
    });

    $('#excel').click(function() {
        $('#accion').val('2');
        $('#form2').submit();
    });
    function printdiv()
    {
//        var tittle = document.getElementById(id_cabecera);
        var tittle = "";
        var content = document.getElementById('resultado2');
        var printscreen = window.open('', '', 'left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0');
//        printscreen.document.write(tittle.innerHTML);
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
    
    $(".preload, .load").hide();
</script>
<style>
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
