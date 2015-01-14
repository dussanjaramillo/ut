<p><br>
<p>
<center>
    <h2><center>SELECCIONE EL PERFIL</center></h2>
    <table id="styletable">
        <thead>
        <th>PERFIL</th>
        </thead>
        <tbody>
            <?php
            foreach ($permiso as $per) {
                if ($per['IDGRUPO'] == ABOGADO) {
                    ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('index.php/mcinvestigacion') ?>/abogado">ABOGADO</a>
                                </td>
                            </tr>
                <?php } ?>
                <?php if ($per['IDGRUPO'] == SECRETARIO) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo base_url('index.php/mcinvestigacion') ?>/secretarios">SECRETARIO</a>
                            </td>
                        </tr>
                <?php } ?>
                <?php if ($per['IDGRUPO'] == COORDINADOR) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo base_url('index.php/mcinvestigacion') ?>/coordinador">COORDINADOR</a>
                            </td>
                        </tr>
                <?php }
            }
            ?>
        </tbody>
    </table>
</center>
<script>
    $('#styletable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
    });
</script>