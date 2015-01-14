<!--<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />-->
<?php

function truncar($texto, $limite, $terminacion = '...') {
    $subcadena = substr($texto, 0, $limite);
    $indiceUltimoEspacio = strrpos($subcadena, " ");
    return substr($texto, 0, $indiceUltimoEspacio) . $terminacion;
}

$fiscalic = array();
$fisca = $fiscalizacion = array();
foreach ($fiscaliza as $fis) {
    $i = 0;
    if (!in_array($fis['COD_TIPO_INSTANCIA'], $fisca)) {
        $fiscalic[] = $fis['NOMBRE_TIPO_INSTANCIA'];
        $fisca[] = $fis['COD_TIPO_INSTANCIA'];
    }
}
$acuerdoc = array();
$acue = $acuerdopago = array();
foreach ($acuerdo as $acu) {
    $i = 0;
    if (!in_array($acu['COD_TIPO_INSTANCIA'], $acue)) {
        $acuerdoc[] = $acu['NOMBRE_TIPO_INSTANCIA'];
        $acue[] = $acu['COD_TIPO_INSTANCIA'];
    }
}

$nafic = array();
$nafi = $notificaapofic = array();
foreach ($notiafic as $naf) {
    $i = 0;
    if (!in_array($naf['COD_TIPO_INSTANCIA'], $nafi)) {
        $nafic[] = $naf['NOMBRE_TIPO_INSTANCIA'];
        $nafi[] = $naf['COD_TIPO_INSTANCIA'];
    }
}

$notic = array();
$noti = $noticapren = array();
foreach ($notica as $not) {
    $i = 0;
    if (!in_array($not['COD_TIPO_INSTANCIA'], $noti)) {
        $notic[] = $not['NOMBRE_TIPO_INSTANCIA'];
        $noti[] = $not['COD_TIPO_INSTANCIA'];
    }
}

$liquic = array();
$liqu = $liquidaran = array();
foreach ($liquidar as $liq) {
    $i = 0;
    if (!in_array($liq['COD_TIPO_INSTANCIA'], $liqu)) {
        $liquic[] = $liq['NOMBRE_TIPO_INSTANCIA'];
        $liqu[] = $liq['COD_TIPO_INSTANCIA'];
    }
}

$ejeafic = array();
$ejea = $ejeafica = array();
foreach ($ejecutaafic as $ejf) {
    $i = 0;
    if (!in_array($ejf['COD_TIPO_INSTANCIA'], $ejea)) {
        $ejeafic[] = $ejf['NOMBRE_TIPO_INSTANCIA'];
        $ejea[] = $ejf['COD_TIPO_INSTANCIA'];
    }
}

$ejemult = array();
$ejem = $ejemulta = array();
foreach ($ejecutamul as $ejm) {
    $i = 0;
    if (!in_array($ejm['COD_TIPO_INSTANCIA'], $ejem)) {
        $ejemult[] = $ejm['NOMBRE_TIPO_INSTANCIA'];
        $ejem[] = $ejm['COD_TIPO_INSTANCIA'];
    }
}

$avocac = array();
$avoca = $tituloyavoca = array();
foreach ($avocal as $avo) {
    $i = 0;
    if (!in_array($avo['COD_TIPO_INSTANCIA'], $avoca)) {
        $avocac[] = $avo['NOMBRE_TIPO_INSTANCIA'];
        $avoca[] = $avo['COD_TIPO_INSTANCIA'];
    }
}

$acercac = array();
$acerca = $acercapersua = array();
foreach ($acercal as $ace) {
    $i = 0;
    if (!in_array($ace['COD_TIPO_INSTANCIA'], $acerca)) {
        $acercac[] = $ace['NOMBRE_TIPO_INSTANCIA'];
        $acerca[] = $ace['COD_TIPO_INSTANCIA'];
    }
}

$mandac = array();
$manda = $mandapago = array();
foreach ($mandal as $man) {
    $i = 0;
    if (!in_array($man['COD_TIPO_INSTANCIA'], $manda)) {
        $mandac[] = $man['NOMBRE_TIPO_INSTANCIA'];
        $manda[] = $man['COD_TIPO_INSTANCIA'];
    }
}

$invenc = array();
$inven = $investiga = array();
foreach ($investil as $inv) {
    $i = 0;
    if (!in_array($inv['COD_TIPO_INSTANCIA'], $inven)) {
        $invenc[] = $inv['NOMBRE_TIPO_INSTANCIA'];
        $inven[] = $inv['COD_TIPO_INSTANCIA'];
    }
}

$avaluoc = array();
$aval = $mcavaluo = array();
foreach ($avaluol as $ava) {
    $i = 0;
    if (!in_array($ava['COD_TIPO_INSTANCIA'], $aval)) {
        $avaluoc[] = $ava['NOMBRE_TIPO_INSTANCIA'];
        $aval[] = $ava['COD_TIPO_INSTANCIA'];
    }
}

$judicic = array();
$judil = $projudil = array();
foreach ($judicial as $jud) {
    $i = 0;
    if (!in_array($jud['COD_TIPO_INSTANCIA'], $judil)) {
        $judicic[] = $jud['NOMBRE_TIPO_INSTANCIA'];
        $judil[] = $jud['COD_TIPO_INSTANCIA'];
    }
}

$liquicoac = array();
$liquicoal = $liquidacoa = array();
foreach ($liquidacoal as $lic) {
    $i = 0;
    if (!in_array($lic['COD_TIPO_INSTANCIA'], $liquicoal)) {
        $liquicoac[] = $lic['NOMBRE_TIPO_INSTANCIA'];
        $liquicoal[] = $lic['COD_TIPO_INSTANCIA'];
    }
}

$nulidac = array();
$nulidalal = $nulidades = array();
foreach ($nulidal as $nul) {
    $i = 0;
    if (!in_array($nul['COD_TIPO_INSTANCIA'], $nulidalal)) {
        $nulidac[] = $nul['NOMBRE_TIPO_INSTANCIA'];
        $nulidalal[] = $nul['COD_TIPO_INSTANCIA'];
    }
}

$remicibic = array();
$remisibil = $remisibilidad = array();
foreach ($remisil as $rem) {
    $i = 0;
    if (!in_array($rem['COD_TIPO_INSTANCIA'], $remisibil)) {
        $remicibic[] = $rem['NOMBRE_TIPO_INSTANCIA'];
        $remisibil[] = $rem['COD_TIPO_INSTANCIA'];
    }
}

$rematac = array();
$rematela = $mcremata = array();
foreach ($rematal as $rema) {
    $i = 0;
    if (!in_array($rema['COD_TIPO_INSTANCIA'], $rematela)) {
        $rematac[] = $rema['NOMBRE_TIPO_INSTANCIA'];
        $rematela[] = $rema['COD_TIPO_INSTANCIA'];
    }
}

$acuerdocoac = array();
$acuerdocoala = $acuerdacoa = array();
foreach ($acuerdocoal as $aco) {
    $i = 0;
    if (!in_array($aco['COD_TIPO_INSTANCIA'], $acuerdocoala)) {
        $acuerdocoac[] = $aco['NOMBRE_TIPO_INSTANCIA'];
        $acuerdocoala[] = $aco['COD_TIPO_INSTANCIA'];
    }
}

$verificacoac = array();
$verificacoal = $verificacoa = array();
foreach ($verifical as $ver) {
    $i = 0;
    if (!in_array($ver['COD_TIPO_INSTANCIA'], $verificacoal)) {
        $verificacoac[] = $ver['NOMBRE_TIPO_INSTANCIA'];
        $verificacoal[] = $ver['COD_TIPO_INSTANCIA'];
    }
}

$reorganizacioc = array();
$reorganizaciol = $reorganizacio = array();
foreach ($reorganizal as $reo) {
    $i = 0;
    if (!in_array($reo['COD_TIPO_INSTANCIA'], $reorganizaciol)) {
        $reorganizacioc[] = $reo['NOMBRE_TIPO_INSTANCIA'];
        $reorganizaciol[] = $reo['COD_TIPO_INSTANCIA'];
    }
}

$liquidaregic = array();
$liquidaregil = $liquidaregimen = array();
foreach ($liquidalregil as $lir) {
    $i = 0;
    if (!in_array($lir['COD_TIPO_INSTANCIA'], $liquidaregil)) {
        $liquidaregic[] = $lir['NOMBRE_TIPO_INSTANCIA'];
        $liquidaregil[] = $lir['COD_TIPO_INSTANCIA'];
    }
}

$traslac = array();
$trasla = $traslado = array();
foreach ($trasladol as $tra) {
    $i = 0;
    if (!in_array($tra['COD_TIPO_INSTANCIA'], $trasla)) {
        $traslac[] = $tra['NOMBRE_TIPO_INSTANCIA'];
        $trasla[] = $tra['COD_TIPO_INSTANCIA'];
    }
}

$resolac = array();
$resola = $resolado = array();
foreach ($resolprel as $res) {
    $i = 0;
    if (!in_array($res['COD_TIPO_INSTANCIA'], $resola)) {
        $resolac[] = $res['NOMBRE_TIPO_INSTANCIA'];
        $resola[] = $res['COD_TIPO_INSTANCIA'];
    }
}

$devrnmc = array();
$devrnm = $devoluciornm = array();
foreach ($devolucionrnm as $drnm) {
    $i = 0;
    if (!in_array($drnm['COD_TIPO_INSTANCIA'], $devrnm)) {
        $devrnmc[] = $drnm['NOMBRE_TIPO_INSTANCIA'];
        $devrnm[] = $drnm['COD_TIPO_INSTANCIA'];
    }
}

$devdgc = array();
$devdg = $devoluciodg = array();
foreach ($devoluciondg as $ddg) {
    $i = 0;
    if (!in_array($ddg['COD_TIPO_INSTANCIA'], $devdg)) {
        $devdgc[] = $ddg['NOMBRE_TIPO_INSTANCIA'];
        $devdg[] = $ddg['COD_TIPO_INSTANCIA'];
    }
}

$devrmc = array();
$devrm = $devoluciorm = array();
foreach ($devolucionrm as $drm) {
    $i = 0;
    if (!in_array($drm['COD_TIPO_INSTANCIA'], $devrm)) {
        $devrmc[] = $drm['NOMBRE_TIPO_INSTANCIA'];
        $devrm[] = $drm['COD_TIPO_INSTANCIA'];
    }
}

$nomic = array();
$nomiso = $nomision = array();
foreach ($nomisional as $pnm) {
    $i = 0;
    if (!in_array($pnm['COD_TIPO_INSTANCIA'], $nomiso)) {
        $nomic[] = $pnm['NOMBRE_TIPO_INSTANCIA'];
        $nomiso[] = $pnm['COD_TIPO_INSTANCIA'];
    }
}
?>
<body>
    <br>
    <h1>Proceso de la Gesti&oacute;n</h1>
    <br>

    <table id="proceso" width="100%" style="f">
        <thead>
            <tr>
                <td>C&oacute;digo Gesti&oacute;n Cobro</td>
                <td>Fecha Creaci&oacute;n</td>
                <th>N&uacute;mero Documento</th>
                <th>Nombre</th>
                <th style="display:none">Codigo Tipo Gesti&oacute;n</th>
                <th>Nombre Instancia</th>
                <th>Nombre Proceso</th>
                <th>Nombre Actividad</th>
                <th style="display:none">Codigo Respuesta</th>
                <th>Actividad Actual</th>
                <th>Nombre del Responsable</th>
                <th>Comentarios</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $fisca = count($fiscalic);
            foreach ($procesos as $colu) {
                ?>


                <tr>
                    <td><?= $colu['COD_TRAZAPROCJUDICIAL'] ?></td>
                    <td><?= $colu['FECHA'] ?></td>
                    <td><?= $colu['COD_EMPRESA'] ?></td>
                    <td><?= $colu['NOMEMPRESA'] ?></td>
                    <td style="display:none"><?= $colu['COD_TIPOGESTION'] ?></td>
                    <?php
                    switch ($colu['COD_TIPO_PROCESO']) {

                        case 1:
//                        if ($fisca >= 1) {
                            echo '<td>';
                            foreach ($fiscalic as $fis) {
                                echo "<li style='list-style-type:circle'>" . $fis;
                            }
                            echo '</td>';
                            break;
//                        } else {
//                            echo '<td>';
//                            echo '</td>';
//                            break;
//                        }
                        case 2:
                            echo '<td>';
                            foreach ($acuerdoc as $acu) {
                                echo "<li style='list-style-type:circle'>" . $acu;
                            }
                            echo '</td>';
                            break;
                        case 3:
                            echo '<td>';
                            foreach ($nafic as $naf) {
                                echo "<li style='list-style-type:circle'>" . $naf;
                            }
                            echo '</td>';
                            break;
                        case 4:
                            echo '<td>';
                            foreach ($notic as $not) {
                                echo "<li style='list-style-type:circle'>" . $not;
                            }
                            echo '</td>';
                            break;
                        case 5:
                            echo '<td>';
                            foreach ($liquic as $liq) {
                                echo "<li style='list-style-type:circle'>" . $liq;
                            }
                            echo '</td>';
                            break;
                        case 6:
                            echo '<td>';
                            foreach ($ejeafic as $ejf) {
                                echo "<li style='list-style-type:circle'>" . $ejf;
                            }
                            echo '</td>';
                            break;
                        case 7:
                            echo '<td>';
                            foreach ($ejemult as $ejm) {
                                echo "<li style='list-style-type:circle'>" . $ejm;
                            }
                            echo '</td>';
                            break;
                        case 8:
                            echo '<td>';
                            foreach ($avocac as $avo) {
                                echo "<li style='list-style-type:circle'>" . $avo;
                            }
                            echo '</td>';
                            break;
                        case 9:
                            echo '<td>';
                            foreach ($acercac as $ace) {
                                echo "<li style='list-style-type:circle'>" . $ace;
                            }
                            echo '</td>';
                            break;
                        case 10:
                            echo '<td>';
                            foreach ($mandac as $man) {
                                echo "<li style='list-style-type:circle'>" . $man;
                            }
                            echo '</td>';
                            break;
                        case 11:
                            echo '<td>';
                            foreach ($invenc as $inv) {
                                echo "<li style='list-style-type:circle'>" . $inv;
                            }
                            echo '</td>';
                            break;
                        case 12:
                            echo '<td>';
                            foreach ($avaluoc as $ava) {
                                echo "<li style='list-style-type:circle'>" . $ava;
                            }
                            echo '</td>';
                            break;
                        case 13:
                            echo '<td>';
                            foreach ($judicic as $jud) {
                                echo "<li style='list-style-type:circle'>" . $jud;
                            }
                            echo '</td>';
                            break;
                        case 14:
                            echo '<td>';
                            foreach ($liquicoac as $lic) {
                                echo "<li style='list-style-type:circle'>" . $lic;
                            }
                            echo '</td>';
                            break;
                        case 15:
                            echo '<td>';
                            foreach ($nulidac as $nul) {
                                echo "<li style='list-style-type:circle'>" . $nul;
                            }
                            echo '</td>';
                            break;
                        case 16:
                            echo '<td>';
                            foreach ($remicibic as $rem) {
                                echo "<li style='list-style-type:circle'>" . $rem;
                            }
                            echo '</td>';
                            break;
                        case 17:
                            echo '<td>';
                            foreach ($rematac as $rema) {
                                echo "<li style='list-style-type:circle'>" . $rema;
                            }
                            echo '</td>';
                            break;
                        case 18:
                            echo '<td>';
                            foreach ($acuerdocoac as $aco) {
                                echo "<li style='list-style-type:circle'>" . $aco;
                            }
                            echo '</td>';
                            break;
                        case 19:
                            echo '<td>';
                            foreach ($verificacoac as $ver) {
                                echo "<li style='list-style-type:circle'>" . $ver;
                            }
                            echo '</td>';
                            break;
                        case 20:
                            echo '<td>';
                            foreach ($reorganizacioc as $reo) {
                                echo "<li style='list-style-type:circle'>" . $reo;
                            }
                            echo '</td>';
                            break;
                        case 21:
                            echo '<td>';
                            foreach ($liquidaregic as $lir) {
                                echo "<li style='list-style-type:circle'>" . $lir;
                            }
                            echo '</td>';
                            break;
                        case 22:
                            echo '<td>';
                            foreach ($traslac as $tra) {
                                echo "<li style='list-style-type:circle'>" . $tra;
                            }
                            echo '</td>';
                            break;
                        case 23:
                            echo '<td>';
                            foreach ($resolac as $res) {
                                echo "<li style='list-style-type:circle'>" . $res;
                            }
                            echo '</td>';
                            break;
                        case 24:
//                        if ($fisca >= 1) {
                            echo '<td>';
                            foreach ($devrnmc as $drnm) {
                                echo "<li style='list-style-type:circle'>" . $drnm;
                            }
                            echo '</td>';
                            break;
                        case 25:
//                        if ($fisca >= 1) {
                            echo '<td>';
                            foreach ($devdgc as $ddg) {
                                echo "<li style='list-style-type:circle'>" . $ddg;
                            }
                            echo '</td>';
                            break;
                        case 26:
//                        if ($fisca >= 1) {
                            echo '<td>';
                            foreach ($devrmc as $drm) {
                                echo "<li style='list-style-type:circle'>" . $drm;
                            }
                            echo '</td>';
                            break;
                        case 27:
                            echo '<td>';
                            foreach ($nomic as $pnm) {
                                echo "<li style='list-style-type:circle'>" . $pnm;
                            }
                            echo '</td>';
                            break;
                        default:
                            echo '<td>' . "" . '</td>';
                            break;
                    }
                    ?>
                    <td><?= ucwords($colu['TIPO_PROCESO']) ?></td>
                    <td><?= ucwords($colu['TIPOGESTION']) ?></td>
                    <td style="display:none"><?= $colu['COD_TIPO_RESPUESTA'] ?></td>
                    <td><?= ucwords($colu['NOMBRE_GESTION']) ?></td>
                    <td><?= $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] ?></td>

                    <td>
                        <?php
                        $tales = $colu['COD_TRAZAPROCJUDICIAL'];
                        ?>
                        <div id="puntoCorto<?= $tales ?>">
                            <?php
                            if (base64_encode(base64_decode($colu['COMENTARIOS'])) === $colu['COMENTARIOS']) {
//                        echo 'Esta Codificado';
                                $texto = base64_decode($colu['COMENTARIOS']);
                            } else {
//                        echo 'No esta Codificado';
                                $texto = $colu['COMENTARIOS'];
                            }                          
                            echo truncar($texto, 20) . "<a id='puntoEnlace.$tales' onclick='MostrarOcultar($tales);' style='cursor:pointer'>Ver Más</a>";
                            ?>

                        </div>

                        <div id='<?= $tales ?>' style='display:none'>
                            <?php
                            echo wordwrap($texto, 15, "-<br/>", 1). "<br /><a id='puntoEnlace.$tales' onclick='MostrarOcultar($tales);' style='cursor:pointer'>Ver Menos</a>";
                            ?>
                        </div>

                        <!--                        <div id="el_div">
                                                    uno dos tres cuatro cinco seis siete ocho nueve diez once doce trece catorce quince
                                                    dieciseis diecisiete dieciocho diecinueve veinte</div>
                                                <br />
                                                <div style="font-family:Courier New;font-size:8pt;color:Blue;cursor:hand" onclick="gestionarTexto(this);" id="mas">Leer más</div>-->


                    </td>


                </tr>
                <?php
            }
            ?>

        </tbody>    
    </table>

</body>

<script>
    $('#proceso').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        //"bFilter": false, 
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning&uacute;n dato disponible en esta tabla",
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
        "aaSorting": [[0, "desc"]],
        "bLengthChange": false,
        "aoColumns": [
            {"bSearchable": false, "bVisible": false},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
            {"sClass": "center"},
        ]

    });

    function MostrarOcultar(id) {

        var puntoContraible = document.getElementById(id);
//        alert(puntoContraible);
        var puntoCorto = document.getElementById("puntoCorto" + id);
//        alert(puntoCorto);
        if (puntoContraible.style.display == 'none') {
            puntoContraible.style.display = 'block';
            puntoCorto.style.display = 'none';
        } else {
            puntoContraible.style.display = 'none';
            puntoCorto.style.display = 'block';
        }
    }


    function ajaxValidationCallback(status, form, json, options) {
    }

    $(".preload, .load").hide();

</script>