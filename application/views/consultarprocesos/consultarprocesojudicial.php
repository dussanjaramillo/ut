<!--<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />-->
<?php
function truncar($texto, $limite, $terminacion = '...') {
    $subcadena = substr($texto, 0, $limite);
    $indiceUltimoEspacio = strrpos($subcadena, " ");
    return substr($texto, 0, $indiceUltimoEspacio) . $terminacion;
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
?>

<br>
<h1>Traza Por Titulo del Proceso Judicial</h1>
<br>

<table id="proceso" width="100%" style="f">
    <thead>
        <tr>
            <td>C&oacute;digo Gesti&oacute;n Cobro</td>
            <td>Fecha Creaci&oacute;n</td>
            <th>N&uacute;mero Documento</th>
            <th>Nombre Deudor</th>
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
        if ($judiciales):
            foreach ($judiciales as $colu) {
                ?>


                <tr>
                    <td><?= $colu['COD_TRAZAPROCJUDICIAL'] ?></td>
                    <td><?= $colu['FECHA'] ?></td>
                    <td><?= $colu['COD_PROPIETARIO'] ?></td>
                    <td><?= $colu['NOMBRE_PROPIETARIO'] ?></td>
                    <td style="display:none"><?= $colu['COD_TIPOGESTION'] ?></td>
                    <?php
                    switch ($colu['COD_TIPO_PROCESO']) {

                        case 13:
                            echo '<td>';
                            foreach ($judicic as $jud) {
                                echo "<li style='list-style-type:circle'>" . $jud;
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

                            echo truncar($texto, 50) . "<a id='puntoEnlace.$tales' onclick='MostrarOcultar($tales);' style='cursor:pointer'>Ver Más</a>";
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
        endif;
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