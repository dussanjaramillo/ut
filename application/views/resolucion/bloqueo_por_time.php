
<?php echo $post['texto']; ?><p><br>
    Fecha Inicial: <?php echo $post['comienza']; ?><br>
    Fecha Limite: <?php echo $post['vence']; ?><br><p>
<center>
    <button id="volver" class="btn"><i class="fa fa-arrow-circle-left"></i> Volver</button>
    <button id="desbloquear" class="btn btn-success">Cliente no se Presento</button>
</center>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
        height: 320,
        modal: true,
        title: "Sistema Bloqueado",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#volver').click(function() {
        $('#resultado').dialog("close");
    })
    $('#desbloquear').click(function() {
        $(".preload, .load").show();
        var cod_fis= "<?php echo $post['cod_fis']; ?>";
        var nit= "<?php echo $post['nit']; ?>";
        var id = "<?php echo $post['id']; ?>";
        var gestion = "<?php echo $post['gestion']; ?>";
        var fecha = "<?php echo $post['fecha']; ?>";
        var num_citacion = "<?php echo $post['num_citacion']; ?>";
        var url = "<?php echo base_url("index.php/resolucion/siguiente_codigo_del_bolqueo"); ?>";
        $.post(url, {id: id,cod_fis:cod_fis,nit:nit, gestion: gestion, fecha: fecha, num_citacion: num_citacion})
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