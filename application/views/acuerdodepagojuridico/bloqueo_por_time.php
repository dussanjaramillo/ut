
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
    $('#desbloquear').click(function(){
        $(".preload, .load").show();
        $("#modal").modal(); 
        var cod_coactivo= "<?php echo $post['cod_coactivo']; ?>";
        var nit= "<?php echo $post['nit']; ?>";
        var acuerdo= "<?php echo $post['acuerdo']; ?>";
        var gestion= "<?php echo $post['gestion']; ?>";
        var estado= "<?php echo $post['estado']; ?>";
        var fecha= "<?php echo $post['fecha']; ?>";
        var tipo= "<?php echo $post['tipo']; ?>";
        var razon = "<?php echo $post['razon']; ?>";
        var ajustado = "<?php echo $post['ajustado']; ?>";
        if (gestion == 736){
            var url = "<?php echo base_url("index.php/acuerdodepagojuridico/editarNotificacion"); ?>";
        }else if (gestion == 514){
            var url = "<?php echo base_url("index.php/acuerdodepagojuridico/verificar_acuerdo"); ?>";
        }
        
        $.post(url,{acuerdo:acuerdo,estado:estado,fecha:fecha,cod_coactivo:cod_coactivo,
        nit:nit,razon:razon,tipo:tipo,ajustado:ajustado})
                .done(function(msg){                    
                    $('.conn').html(msg);
                    $(".preload, .load").hide("slow");
                }).fail(function(msg,fail){
                    alert('ERROR AL GUARDAR');
                    $(".preload").hide();
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