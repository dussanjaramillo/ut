<!--
Al desbloquear debe permitir gestionar si el deudor objeto o no.
-->
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>

<?php

echo $post['texto']; ?><p><br>
    Fecha Inicial: <?php echo $post['comienza']; ?><br>
    Fecha Limite: <?php echo $post['vence']; ?><br><p>

<center>
    <button id="volver" class="btn"><i class="fa fa-arrow-circle-left"></i> Volver</button>
    <button id="desbloquear" class="btn btn-success">Desbloquear</button>
</center>

<script>
    $("#ajax_load").css("display", "none");
    $('#div_bloqueo').dialog({
        autoOpen: true,
        width: 500,
        height: 320,
        modal: true,
        title: "Sistema Bloqueado",
        close: function() {
            $('#div_bloqueo *').remove();
            $("#ajax_load").css("display", "none");
        }
    });


    $('#volver').click(function() {
        $("#div_bloqueo").dialog("close");
    })


    $('#desbloquear').click(function() {

        $("#ajax_load").css("display", "block");
        var id = "<?php echo $post['cod_coactivo'] ?>";
        var avaluos = "<?php echo $post['avaluos'] ?>";
        var respuesta = "<?php echo $post['respuesta'] ?>";
        var url = "<?= base_url("index.php/mc_avaluo/abogado") ?>";
       // alert(respuesta);
        if (respuesta == 1417 || respuesta == 1426 || respuesta == 1420) {
            var url = "<?= base_url("index.php/mc_avaluo/objecion") ?>";
        }
        else if (respuesta == 402)
        {
            var url = "<?= base_url("index.php/mc_avaluo/RegistrarReciboHonorarios") ?>";
        }
    
        $.post(url, {id: id, avaluos: avaluos, respuesta: respuesta})
                .done(function(msg) {
                      $('#div_bloqueo *').remove();
                    $("#div_bloqueo").dialog("close");
                    $("#resultado").html(msg);
                  
                }).fail(function(msg, fail) {
            alert('Se ha generado un error');
            $("#ajax_load").hide();
        })


    })

</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
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