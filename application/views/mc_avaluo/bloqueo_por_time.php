<?php echo $post['texto']; ?><p><br>
    <?php
//    echo "<pre>";
//    print_r($post);
//    echo "</pre>";
    ?>
    Fecha Inicial: <?php echo $post['comienza']; ?><br>
    Fecha Limite: <?php echo $post['vence']; ?><br><p>
<center>
    <button id="volver" class="btn"><i class="fa fa-arrow-circle-left"></i> Volver</button>
    <button id="desbloquear" class="btn btn-success">Ejecutado no se Presento</button>
</center>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $(".preload, .load").hide();
    $('#div_bloqueo').dialog({
        autoOpen: true,
        width: 500,
        height: 320,
        modal: true,
        title: "Sistema Bloqueado",
        close: function() {
            $('#div_bloqueo *').remove();
        }
    });
    $('#volver').click(function() {

      $("#div_bloqueo").dialog("close");
    })
    $('#desbloquear').click(function() {
        $(".preload, .load").show();

        var gestion = "<?php echo $post['gestion']; ?>";
        var fecha = "<?php echo $post['fecha']; ?>";

       
        $('#resultado *').remove();
        var url = "<?php echo base_url("index.php/mc_avaluo/vistas"); ?>";
        $.post(url, { gestion: gestion, fecha: fecha})
                .done(function(msg) {
                    alert('Datos Guardados Con Exito');
                    window.location.reload();
                }).fail(function(msg, fail) {
            $(".preload, .load").hide();
            alert('ERROR AL GUARDAR');
        });
    })
</script>
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>