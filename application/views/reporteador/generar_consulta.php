<button id="ayudante" style="float: right;margin-top: -30px" class="btn btn-warning">Ayuda</button>
<?php
$titulo = "";
$j = 0;
$tabla = 1;
$foraneas = "";
$foraneas2 = "";
foreach ($consulta as $general) {
    $i = 0;
    $h = 0;
    $thead = "";
    $tbody = "";
    $tbody2 = "";
    $accion = "";
    $tamano = "style='width:70px'";
    $tamano2 = "style='width:90px'";

    foreach ($general as $detalle) {
        $desplegable = "<select class='desplegable' " . $tamano . ">"
                . "<option value=''>  </option>"
                . "<option value='1'> < </option>"
                . "<option value='2'> > </option>"
                . "<option value='3'> >= </option>"
                . "<option value='4'> <= </option>"
                . "<option value='5'> = </option>"
                . "<option value='6'> != </option>"
                . "</select>";
        $desplegable2 = "<select class='desplegable' " . $tamano . ">"
                . "<option value=''>  </option>"
                . "<option value='7'> Igual a </option>"
                . "<option value='8'> Inicia Con </option>"
                . "<option value='9'> Termina Con </option>"
                . "<option value='10'> Que Contenga </option>"
                . "</select>";
        $k = 0;
//            echo $i;
        if ($i % 2 == 0)
            $tbody.= '<tr style="background-color: #f9f9f9">';
        else
            $tbody.= '<tr >';
        foreach ($detalle as $key => $value) {
            if ($i < count($detalle)) {
                $thead.="<th bgcolor='#5BB75B' style='border:1px solid #CCC'>" . $key . "</th>";
            }
            $i++;
//            if($j==2)
//                $tbody2.= 
            if ($j == 2) {
                if ($k == 0) {
                    $tbody.="<td align='center' style='border:1px solid #CCC'><input type='checkbox' value='" . $post['tabla'] . "." . $value . "' class='ckeck_table'></td>";
                    $id_titulo = $value;
                }

                if ($k == 1) {
                    switch ($value) {
                        case "NUMBER":
                            $accion = "<input type='text' " . $tamano2 . " maxlength='10' id='$id_titulo'  class='numbre' />";
                            $value = "NUMERICO";
                            break;
                        case "VARCHAR2":
                            $accion = "<input type='text' " . $tamano2 . " maxlength='10' id='" . $id_titulo . "' class='accion' />";
                            $desplegable = $desplegable2;
                            $value = "TEXTO";
                            break;
                        case "DATE":
                            $accion = "<input type='date' " . $tamano2 . " readonly='readonly' size='15' class='fechas' id='" . $id_titulo . "' />";
                            $value = "FECHA";
                            break;
                        default :
                            $accion = "";
                            $desplegable = "";
                    }
                }
            }
            $tbody.="<td style='border:1px solid #CCC'>" . $value . "</td>";
            if ($tabla == 2 && $k == 2) {
                $foraneas.="||" . $value;
            }
            if ($tabla == 2) {
                $foraneas2[$h][] = $value;
            }
            $k++;
        }
        if ($tabla == 3)
            $tbody.="<td style='border:1px solid #CCC'>" . $desplegable . $accion . "</td>";
        $tbody.= "</tr>";
        $h++;
    }
//        if(count($general))
//        echo count($general);
    $div_ini = "";
    $div_fin = "";
    switch ($j) {
        case 0:
            $style = 'style="float: right;display:none"';
            $div_ini = "<div " . $style . " align='center' id='inf_ayuda' class='modal hide fade modal-body'>";
            break;
        case 1:
            $div_fin = "</div>";
            break;
        case 2:
            $style = 'style=""';
            if ($tabla == 3) {
                $thead2 = "<th class='ckeck_table_todos' bgcolor='#5BB75B'>GESTION "
//                    . "<input type='checkbox' value='" . $post['tabla'] . "' id='ckeck_table_todos'>"
                        . "</th>" . $thead . "<th bgcolor='#5BB75B'>ACCION</th>";
            } else {
                $thead2 = $thead;
            }
            break;
    }
    echo $div_ini;
    ?>
    <table width="100%">
        <thead style="color: #ffffff">
            <?php echo ($tabla != 3) ? $thead : $thead2; ?>
        </thead>
        <tbody>
            <?php echo $tbody; ?>
            <?php // echo (empty($tbody))?$tbody:$tbody2; ?>
        </tbody>
    </table> <br>
    <?php
    echo $div_fin;
    $j++;
    $tabla++;
}
$cantidad = count($foraneas2);
$join = "";
if (isset($foraneas2[0][2])) {
    if (!empty($foraneas2[0][2])) {
        for ($i = 0; $i < $cantidad; $i++) {
            $join.="|||||" . $foraneas2[$i][2] . "|||| JOIN " . $foraneas2[$i][2] . " ON " . $foraneas2[$i][2] . "." . $foraneas2[$i][3] . "=" . $foraneas2[$i][0] . "." . $foraneas2[$i][1] . " ";
        }
    }
}
?>
<div style="display:none ">
    <input type="text" id="foranea" value='<?php echo $foraneas; ?>'>
    <input type="text" id="foranea2" style="width: 100%" value='<?php echo $join; ?>'>
</div>
<p><br>

    <button id="agregar" class="btn btn-success">Agregar</button>
    <button id="consultar" class="btn btn-success">Consultar</button>
    <script>
        $('#ayudante').click(function() {
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

        $('#ckeck_table_todos').click(function() {
            $.each($(".ckeck_table"), function() {
                if ($("#ckeck_table_todos").is(':checked') == true) {
                    $(this).attr("checked", "checked");
                } else {
                    $(this).attr("checked", false);
                }
            });
        });
        $('#agregar').click(function() {
            var nombre_table = $('#tabla').val();
            $(".ckeck_table").each(function(indice, campo) {
                if ($(this).is(':checked') == true) {
                    var r = $('#tarea').val();
                    var agregar = "";
                    var borrar = r.split(",");
                    var valor_check = $(this).val();
                    for (var i = 0; i < borrar.length; i++) {
                        if (valor_check == borrar[i]) {
                            agregar = "1";
                        }
                    }
                    if (agregar == "1") {
                        agregar = r;
                    } else {
                        agregar += valor_check + "," + r;
                    }
                } else {
                    var agregar = "";
                    var r = $('#tarea').val();
                    var borrar = r.split(",");
                    var valor_check = $(this).val();
                    if (borrar) {
                        for (var i = 0; i < borrar.length; i++) {
                            if (valor_check == borrar[i]) {
                                borrar[i] = "";
                            }
                        }
                        for (var i = 0; i < borrar.length; i++) {
                            if (borrar[i] != "") {
                                agregar += borrar[i] + ",";
                            }
                        }
                    }
                }
                $('#tarea').val(agregar);
            });
            var informacion = "";
            var tarea_where2 = $('#tarea_where').val();
            var tarea_where = tarea_where2.split('|||||');
            var tarea3 = "";
            if (tarea_where.length > 1) {
                for (var i = 0; i < tarea_where.length; i++) {
                    var tarea2 = tarea_where[i].split('||||');
                    for (var j = 0; j < tarea2.length; j++) {
                        if (tarea2[j] == nombre_table) {
                            tarea_where[i] = "";
                        }
                    }
                }

                for (var i = 0; i < tarea_where.length; i++) {
                    if (tarea_where[i] != "") {
                        tarea3 += "|||||" + tarea_where[i];
                    }
                }
            }


            informacion = "|||||" + nombre_table;
            $(".desplegable").each(function(indice, campo) {
                var accion = "";
                if ($(this).val() != "") {
                    var desplegable = $(this).val();
                    var valor_texto = $(this).next().val();
                    var nombre = $(this).next().attr('id');
                    if (valor_texto == "")
                        valor_texto = "''";
                    if (desplegable != "")
                        informacion += "||||" + nombre_table + '.' + nombre + "|||" + desplegable + "|||" + valor_texto;
                }
            });
            if (informacion == "|||||" + nombre_table) {
                informacion = "";
            }
            $('#tarea_where').val(tarea3 + informacion);

            //informacion que el usuario visualiza
            $('#campo').html($('#tarea').val());
            var informacion = "";
            var datos = "";
            var tarea_where = $('#tarea_where').val();
            if (tarea_where != "") {
                tarea_where = tarea_where.split('|||||');
                for (i = 1; i < tarea_where.length; i++) {
                    tarea_where2 = tarea_where[i].split('||||');
                    for (j = 0; j < tarea_where2.length; j++) {
                        var tarea_where3 = tarea_where2[j].split('|||');
                        switch (tarea_where3[1]) {
                            case "1":
                                datos = " < " + tarea_where3[2] + "<br>";
                                break;
                            case "2":
                                datos = " > " + tarea_where3[2] + "<br>";
                                break;
                            case "3":
                                datos = " >= " + tarea_where3[2] + "<br>";
                                break;
                            case "4":
                                datos = " <= " + tarea_where3[2] + "<br>";
                                break;
                            case "5":
                                datos = " = " + tarea_where3[2] + "<br>";
                                break;
                            case "6":
                                datos = " != " + tarea_where3[2] + "<br>";
                                break;
                            case "7":
                                datos = " Igual a " + tarea_where3[2] + "<br>";
                                break;
                            case "8":
                                datos = " Inicia Con " + tarea_where3[2] + "<br>";
                                break;
                            case "9":
                                datos = " Termina Con " + tarea_where3[2] + "<br>";
                                break;
                            case "10":
                                datos = " Que Contenga " + tarea_where3[2] + "<br>";
                                break;
                        }
                        informacion += tarea_where3[0] + datos;
                    }
                }
            }
            $('#donde').html(informacion);
        });

        $('.fechas').datepicker();
        $('.numbre').change(function(e) {
            if (isNaN($(this).val())) {
                $(this).val('');
            }
        });

        $('#consultar').click(function() {
            $(".preload, .load").show();
            var url = '<?php echo base_url('index.php/reporteador/consultar') ?>'
            var tarea = $('#tarea').val();
            var tarea_where = $('#tarea_where').val();
            $.post(url, $('#form1').serialize())
                    .done(function(msg) {
                        $(".preload, .load").hide();
                        $('#resultado2').html(msg);
                    }).fail(function() {
                alert("Error en la consulta");
                $(".preload, .load").hide();
            });
            $('#impri').show();
        });
    </script>