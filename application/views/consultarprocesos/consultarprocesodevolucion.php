<!--<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />-->
<?php

function truncar($texto, $limite, $terminacion = '...') {
    $subcadena = substr($texto, 0, $limite);
    $indiceUltimoEspacio = strrpos($subcadena, " ");
    return substr($texto, 0, $indiceUltimoEspacio) . $terminacion;
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
?>

<br>
<h1>Traza N&uacute;mero de Devoluci&oacute;n en los Procesos de Devoluciones</h1>
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
foreach ($devoluciones as $colu) {
    ?>


            <tr>
                <td><?= $colu['COD_TRAZAPROCJUDICIAL'] ?></td>
                <td><?= $colu['FECHA'] ?></td>
                <td><?= $colu['NIT'] ?></td>
                <td><?= $colu['RAZON_SOCIAL'] ?></td>
                <td style="display:none"><?= $colu['COD_TIPOGESTION'] ?></td>
    <?php
    switch ($colu['COD_TIPO_PROCESO']) {

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