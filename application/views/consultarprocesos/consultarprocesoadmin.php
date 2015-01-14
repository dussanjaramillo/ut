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
?>

<br>
<h1>Traza de la Fiscalizaci&oacute;n En el  Proceso Administrativa</h1>
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
        foreach ($procesoadmin as $colu) {
            ?>

            <tr>
                <td><?= $colu['COD_GESTION_COBRO'] ?></td>
                <td><?= $colu['FECHA_CONTACTO'] ?></td>
                <td><?= $colu['NIT_EMPRESA'] ?></td>
                <td><?= $colu['RAZON_SOCIAL'] ?></td>
                <td style="display:none"><?= $colu['COD_TIPOGESTION'] ?></td>
                <?php
                switch ($colu['COD_TIPO_PROCESO']) {
                    case 1:
                        echo '<td>';
                        foreach ($fiscalic as $fis) {
                            echo "<li style='list-style-type:circle'>" . $fis;
                        }
                        echo '</td>';
                        break;
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
                    default:
                        echo '<td>' . "" . '</td>';
                        break;
                }
//                if ($colu['COD_TIPO_PROCESO'] == 1) {
//                    echo '<td>';
//                    foreach ($fiscalic as $fis) {
//                        echo "<li style='list-style-type:circle'>" . $fis;
//                    }
//                    echo '</td>';
//                } else if ($colu['COD_TIPO_PROCESO'] == 2) {
//                    echo '<td>';
//                    foreach ($acuerdoc as $acu) {
//                        echo "<li style='list-style-type:circle'>" . $acu;
//                    }
//                    echo '</td>';
//                } else if ($colu['COD_TIPO_PROCESO'] == 3) {
//                    echo '<td>';
//                    foreach ($nafic as $naf) {
//                        echo "<li style='list-style-type:circle'>" . $naf;
//                    }
//                    echo '</td>';
//                } else {
//                    echo '<td>' . 'hola' . '</td>';
//                }
                ?>
                <td><?= ucwords($colu['TIPO_PROCESO']) ?></td>
                <td><?= ucwords($colu['TIPOGESTION']) ?></td>
                <td style="display:none"><?= $colu['COD_TIPO_RESPUESTA'] ?></td>
                <td><?= ucwords($colu['NOMBRE_GESTION']) ?></td>
                <td><?= $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] ?></td>
                <td>
                    <?php
                    $tales = $colu['COD_GESTION_COBRO'];
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
                        echo wordwrap($texto, 13, "-<br/>", 1) . "<br /><a id='puntoEnlace.$tales' onclick='MostrarOcultar($tales);' style='cursor:pointer'>Ver Menos</a>";
                        ?>
                    </div>
                    </td>

            </tr>
            <?php
        }
        ?>

    </tbody>    
</table>

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