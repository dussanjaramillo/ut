<!-- Responsable: Leonardo Molina-->
<?php
$DataRadio = array('name' => 'resp', 'id' => 'resp1', 'value' => 'S');
$DataRadio1 = array('name' => 'resp', 'id' => 'resp2', 'value' => 'N');
$nresol = array('name' => 'nresol', 'id' => 'nresol', 'type' => 'hidden', 'value' => $registros->NUMERO_RESOLUCION);
$nrevoc = array('name' => 'nrevoc', 'id' => 'nrevoc', 'type' => 'hidden', 'value' => $registros->COD_REVOCATORIA);
$gnit = array('name' => 'gnit', 'id' => 'gnit', 'type' => 'hidden', 'value' => $registros->NITEMPRESA);
$cfisc = $registros->COD_FISCALIZACION;
$doc = $registros->DOC_REVOCATORIA;
$doc_rev = $registros->DOC_RESPUESTA;
$respRevoc = $registros->RESPUESTA_REVOCATORIA;
$doc_rev_fir = $registros->DOC_RESPUESTA_FIRMADO;
$tfisc = array('name' => 'cfisc', 'id' => 'cfisc', 'type' => 'hidden', 'value' => $cfisc);
?>

<?= form_open_multipart(base_url('index.php/ejecutoriaactoadmin/revocatoriaFirmado/')); ?>
<?= form_input($nresol) ?>
<?= form_input($nrevoc) ?>
<?= form_input($tfisc) ?>
<?= form_input($gnit) ?>


<h1>Revocatoria</h1>
<?php if (!isset($doc_rev_fir)) { ?>
    <div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
        <h5>Adjunte el archivo firmado de revocatoria.</h5>
        <div class="input-append" id="arch0">
            <input type="file" name="archivo0" id="archivo0" onchange="habboton()" class="btn btn-primary file_uploader">
        </div>
        <div>
            <p>Seleccione una opción según sea el caso.</p>
            <p>
                <?= form_radio($DataRadio) . ' Revoca parcial - total.'; ?><br>
                <?= form_radio($DataRadio1) . ' No revoca.'; ?>
            </p>
        </div>
    </div>
    <?php
}
if ($respRevoc == 'S') {
    $RR = 'Revocado parcial - total';
} else if ($respRevoc == 'N') {
    $RR = 'No revocado';
} else
    $RR = 'Pendiente por respuesta';
?>
<br>
<div><div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Revocatoria: <?= $RR ?>.</div></div>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <table cellpadding="10%" style="border: 1px solid #ffffff;">
        <tr>
            <th>Documento solicitud de Revocatoria</th>
            <th></th>
            <th>Documento de Respuesta Firmado</th>
        </tr>
        <tr>
            <td><a href="<?= base_url() ?>uploads/fiscalizaciones/<?= $cfisc ?>/ejecutoriaactoadmin/<?= $doc ?>" target="_blank"><li class="fa fa-file-text"></li> Ver.. </a></td>
            <td style="width: 100px"></td>
            <?php if (isset($doc_rev_fir)) { ?>
                <td><a href="<?= base_url() ?>uploads/fiscalizaciones/<?= $cfisc ?>/ejecutoriaactoadmin/<?= $doc_rev_fir ?>" target="_blank"><li class="fa fa-file-text"></li> Ver.. </a></td>
            <?php } ?>
        </tr>
    </table>
</div>
<br>
<?php if (isset($doc_rev_fir)) {
    echo  form_close();
} ?>
<div class="modal-footer">
    <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
    <?php if (!isset($doc_rev_fir)) { ?>
        <button type="submit" class="btn btn-primary" id="revocatoria"><li class="fa fa-save"></li> Cargar Revocatoria</button>
        <?php
    } else {
        ?>
        <button type="submit" class="btn btn-primary" id="citacion"><li class="fa fa-save"></li> Generar Citaci&oacute;n </button>
            <?php } ?>
</div>
<?= form_close() ?>
<script type="text/javascript">
    $(function() {

        $('#cancelar').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
            //location.href="<?= base_url('index.php/ejecutoriaactoadmin') ?>";

        });
        $('.close').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
            //location.href="<?= base_url('index.php/ejecutoriaactoadmin') ?>";
        });
    });

    $('#citacion').click(function() {
        $(".preload, .load").show();
        var id = '<?php echo $registros->COD_REVOCATORIA; ?>';
        var cod_fis = '<?php echo $registros->COD_FISCALIZACION; ?>';
        var concepto = '<?php echo $registros->COD_CPTO_FISCALIZACION; ?>';
        var id_resolucion = '<?php echo $registros->COD_RESOLUCION; ?>';
        var nit = '<?php echo $registros->NITEMPRESA; ?>';
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/citacion'); ?>";
        $.post(url, {id: id, nit: nit, concepto: concepto, cod_fis: cod_fis, id_resolucion: id_resolucion})
                .done(function() {
                    alert('Los datos fueron guardados con exito');
                    window.location.reload();
                    return false;
                }).fail(function() {
            alert('ERROR EN LA BASE DE DATOS');
            $(".preload, .load").hide();
        });
    })

</script>

