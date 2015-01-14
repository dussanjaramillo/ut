<?php
$cantidad = count($bancos);
$valor = 0;
for ($i = 0; $i < $cantidad; $i++) {
    $valor += $bancos[$i]['VALOR'];
}
?>
<table width="100%" >
    <thead >
    <thead style="background: #5bb75b;color: #FFF">
    <th></th>
    <th>Nombre del documento</th>
    <th>Entidad Bancaria</th>
    <th>Valor de la aplicaci&oacute;n de la medida</th>
    <th>Observaciones</th>
</thead>
<?php
$cantidad = count($bancos);
$suma = 0;
for ($i = 0; $i < $cantidad; $i++) {
    if ($i % 2 == 0)
        echo '<tr style="background: #f9f9f9">';
    else
        echo '<tr>'
        ?>
            <!--<tr>-->
    <td><input type="hidden" id="cod" name="cod[]" value="<?php echo $bancos[$i]['COD_EMBARGO_DINEROS'] ?>"></td>
    <td><a href="<?php echo base_url(RUTA_DES . $post['id'] . "/" . $bancos[$i]['NOMBRE_OFICIO_EMBARGO']); ?>"  target="_blank"><?php echo $bancos[$i]['NOMBRE_OFICIO_EMBARGO'] ?></a></td>
    <td>
        <?php echo $bancos[$i]['NOMBREBANCO']; ?>
    </td>
    <td align='center'><?php
        $datos = (!empty($bancos[$i]['VALOR']) ? $bancos[$i]['VALOR'] : 0);
        echo number_format($datos);
        ?></td>
    <td><?php echo $bancos[$i]['OBSERVACIONES']; ?></td>
    </tr>
<?php } ?>
</table>
<br><p>
<br><p>

<center>
    <!--<button id="guardar2" class="btn btn-success">Guardar</button>-->
    <button id="subir_doc" class="btn btn-success">Subir mas Documentos</button>
    <button id="nuevo_oficio" class="btn btn-success">Generar Oficio</button>
</center>
<script>
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
        modal: true,
        title: "<?php echo $post['titulo'] ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    var id = '<?php echo $post['id'] ?>';
    var cod_fis = '<?php echo $post['cod_fis'] ?>';
    var nit = '<?php echo $post['nit'] ?>';
    var titulo = '<?php echo $post['titulo'] ?>';

    $('#subir_doc').click(function() {
        var r = confirm('Desea Subir mas Documentos');
        if (r == true) {
            jQuery(".preload, .load").show();
            var url = "<?php echo base_url('index.php/mcinvestigacion/subir_documentos_nuevos'); ?>";
            var cod_siguiente=283;
            $.post(url, {id: id, cod_fis: cod_fis, nit: nit,cod_siguiente: cod_siguiente})
                    .done(function(msg) {
                        window.location.reload();
                    }).fail(function() {
                jQuery(".preload, .load").hide();
                alert("<?php echo ERROR; ?>");
            });
        }
    });
    $('#nuevo_oficio').click(function() {
        var r = confirm('Desea Realizar el Oficio de Orden de Investigación y Embargo de Dineros');
        if (r == true) {
            jQuery(".preload, .load").show();
            var url = "<?php echo base_url('index.php/mcinvestigacion/subir_documentos_nuevos'); ?>";
            var cod_siguiente=1126;
            $.post(url, {id: id, cod_fis: cod_fis, nit: nit,cod_siguiente: cod_siguiente})
                    .done(function(msg) {
                        window.location.reload();
                    }).fail(function() {
                jQuery(".preload, .load").hide();
                alert("<?php echo ERROR; ?>");
            });
        }
    });
    $('#guardar2').click(function() {
        
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/documentos_bancarios  '); ?>";
        $.post(url, {id: id, cod_fis: cod_fis, nit: nit, titulo: titulo, cod_siguiente: cod_siguiente})
                .done(function(msg) {
                    window.location.reload();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        });
    });
    function eliminar(dato) {
        var id = '<?php echo $post['id']; ?>';
        var tipo = '';
//        alert(dato)
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/informacion_doc_mc') ?>";
        $.post(url, {dato: dato, id: id, tipo: tipo})
                .done(function(msg) {
                    $('#archivos *').remove();
                    $('#archivos').html(msg);

                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Documento no Eliminado');
            jQuery(".preload, .load").hide();
        });
    }

</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
