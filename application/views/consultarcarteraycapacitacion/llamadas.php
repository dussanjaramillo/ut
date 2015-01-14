<?php echo $historico_llamadas; ?>

<textarea id="informacion" style="width: 100%" class="validate[required, custom[onlyLetterSp]]"></textarea>
    <button id="enviar" name="enviar" class="btn btn-success">Guardar</button>
    <script>
        $(document).ready(function(){
//            $("#informacion").validationEngine('attach', {
//                promptPosition: "topLeft"
//            });
        jQuery(".preload, .load").hide();
        $('#resultado').dialog({
            autoOpen: true,
            modal: true,
            width: 600,
            title: 'Historico de llamadas',
        });
        id_ejecutoria = "<?php echo $post['id_ejecutoria']; ?>";
        $('#enviar').click(function() {
            var informacion=$('#informacion').val();
            informacion="////"+informacion+'///'+'<?php echo date("d/m/y") ?>';
            jQuery(".preload, .load").show();
            var url = '<?php echo base_url('index.php/consultarcarteraycapacitacion/guardar_llamadas') ?>';
            $("#informacion").attr("data-prompt-position", "topLeft");
                $("#informacion").data("promptPosition", "topLeft");
            if($("#informacion").validationEngine('validate') == false) {
            
            $.post(url, {id_ejecutoria: id_ejecutoria,informacion:informacion})
                    .done(function(smg) {
                        window.location.reload();
//                        jQuery(".preload, .load").hide();
                    }).fail(function(msg) {
                jQuery(".preload, .load").hide();
            });
            }else{
                $(".preload, .load").hide();
//                $('#informacion').validationEngine('showPrompt');
            }
        });
        });
    </script>