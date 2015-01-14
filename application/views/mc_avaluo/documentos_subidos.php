<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<!--<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>-->

<div id="div_documento_subido_" style="text-align:center;">
    <div id="ruta_archivo" name="ruta_archivo" >
        <div id="archivo_subido" style="text-align:center;">
            <a style="background-color: transparent;color:#006600;" href="<?php echo base_url() . $detalle['ruta']; ?>"  target="_blank" class="linkli">
                <?php echo $detalle['nombre']; ?></a>
            <button type="button" title="Eliminar Documento"  style="background-color: transparent;color:#cc0000;border-color: transparent;  " onclick="eliminarDD('<?php echo base_url() . $detalle['ruta']; ?>', '<?php echo $detalle['nombre']; ?>')">
                <li class="icon-trash" title="Eliminar"></li>
                <span id="loader" style="display: none; float:center; position: relative">
                    <img src="<?= base_url() ?>/img/27.gif" width="40px" height="40px" /></span>
        </div>
    </div>
</div>
<input type="hidden" name="nombre" id="nombre" value="<?php echo $detalle['nombre']; ?>">
<input type="hidden" name="ruta" id="ruta" value="<?php echo $detalle['ruta']; ?>">
<script>
    //para eliminar el documento
    function eliminarDD(ruta, documento) {
        var res = confirm('Seguro desea eliminar el documento?');
        if (res == true) {
            $("#loader").css("display", "block");
            var ruta = ruta;
            var documento = documento;
            var url = "<?php // echo $detalle['eliminar_documento']            ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {"ruta": ruta, "documento": documento},
                success: function(data)
                {
                    $("#div_documento_subido_").hide();
                    $("#adjunta_doc").show();
                    $("#revision").html(data);
                    $("#doc_adjunto").hide();
                    
                },
                //si ha ocurrido un error
                error: function() {
                    $("#ajax_load").hide();
                    alert("Datos incompletos");
                }
            });
        }
    }




</script>