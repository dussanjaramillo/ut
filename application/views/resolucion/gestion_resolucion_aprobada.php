<?php echo $texto ?>

<center>
    <button id="aprobar" class="btn btn-success">Aprobar</button>
    <a href="#abajo"><button id="devolucion" class="btn btn-success">Devolucion</button></a>
</center>
<p>
<div id="contene" style="display: none">
    Motivo Devoluci&oacute;n<br>
    <textarea id="contenido" name="contenido" style="width:100%" maxlength="140" size="140" onkeyup="presion();"></textarea>
    <p>Caracteres disponibles:<span id="cantidad">140</span></p>
    <a name='abajo'>
        <center><button id="devolver" class="btn btn-success">Enviar</button></center>
</div>
<hr>
<div>
    Motivo Devolucion:<p>
        <?php echo $comentario ?>
</div>
<script>

    function presion()
    {
        var canti = document.getElementById('contenido').value.length;
        var disponibles = 140 - parseInt(canti);
        document.getElementById('cantidad').innerHTML = disponibles;
    }
    $('#contenido').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
//        height: 600,
        modal: true,
        title: 'Documento que Resuelve el Recurso',
        close: function() {
            $('#resultado *').remove();
        }
    });
    id = '<?php echo $post['id']; ?>';
    cod_fis = '<?php echo $post['cod_fis']; ?>';
    nit = '<?php echo $post['nit']; ?>';
    num_recurso = '<?php echo $plantilla['NUM_RECURSO'] ?>';

    $('#aprobar').click(function() {
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/resolucion/update_resolucion_hija') ?>';
        var cod_siguiente = "48";
        var contenido = "Documento enviado para aprobacion";
        $.post(url, {id: id, num_recurso: num_recurso, nit: nit, cod_fis: cod_fis, contenido: contenido, cod_siguiente: cod_siguiente})
                .done(function() {
                    window.location.reload();
                }).fail(function() {
            $(".preload, .load").hide();
            alert('Datos no guardados');
        });
    });

    $('#devolucion').click(function() {
        $('#contene').show();
    });
    $('#devolver').click(function() {
        $(".preload, .load").show();
        var url = '<?php echo base_url('index.php/resolucion/update_resolucion_hija') ?>';
        var cod_siguiente = "315";
        var contenido = $('#contenido').val();
        $.post(url, {id: id, num_recurso: num_recurso, nit: nit, cod_fis: cod_fis, contenido: contenido, cod_siguiente: cod_siguiente})
                .done(function() {
                    window.location.reload();
                }).fail(function() {
            $(".preload, .load").hide();
            alert('Datos no guardados');
        });
    });
</script>


<style>
    .colores{
        color: slategrey;
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
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />