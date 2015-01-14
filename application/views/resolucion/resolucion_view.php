<p><br>
<p>
<center>
    <h2><center>SELECCIONE EL PERFIL</center></h2>
    <table id="styletable">
        <thead>
        <th>PERFIL</th>
        </thead>
        <tbody>
            <?php if (!empty($coordinador['CEDULA'])) { ?>
                <tr>
                    <td>
                        <a href="<?php echo base_url('index.php/resolucion') ?>/datos_del_coordinador">Coordinador</a>
                    </td>
                </tr>
            <?php } ?>
            <?php if (!empty($director['CEDULA'])) { ?>
<!--                <tr>
                    <td>
                        <a href="<?php echo base_url('index.php/resolucion') ?>/datos_del_director">Director Regional</a>
                    </td>
                </tr>-->
            <?php } ?>
            <?php if (!empty($abogado['CEDULA'])) { ?>
                <tr>
                    <td>
                        <a href="<?php echo base_url('index.php/resolucion') ?>/datos_del_abogado">Abogado</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</center>
<script>
    $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
         "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
             },
    "fnInfoCallback": null,
            },
    });
</script>