<center>Informaci&oacute;n</center><br>
<?php echo $post['texto']; ?><p><br>
    Fecha Inicial: <?php echo $post['comienza']; ?><br>
    Fecha Limite: <?php echo $post['vence']; ?><br><p>
<center>
    <button id="volver" class="btn btn-info">Volver</button>
    <button id="desbloquear" class="btn btn-danger">Gestionar Solicitud de Informe Comisorio</button>
</center>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
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

        var id = "<?php echo $post['id']; ?>";
        var gestion = "<?php echo $post['gestion']; ?>";
        
        if(gestion=='<?php echo RTA_VEHICULO_COMISIONAR1; ?>'){
          var dato='<?php echo VEHICULO_DESPACHO_INICIO; ?>';
        }else if(gestion=='<?php echo RTA_INMUEBLE_COMISIONAR1; ?>'){
          var dato='<?php echo INMUEBLES_DOCUMENTO_SECUESTRO_INICIO; ?>';
        }else if(gestion=='<?php echo RTA_MUEBLE_COMISIONAR1; ?>'){
          var dato='<?php echo MUEBLES_RESPUESTA_INICIO; ?>';
        }
        
        var fecha = "<?php echo $post['fecha']; ?>";
        var cod_fis = "<?php echo $post['cod_fis']; ?>";
        var nit = "<?php echo $post['nit']; ?>";
        var titulo = "<?php echo $post['titulo']; ?>";
        var id_prelacion = "<?php echo $post['id_prelacion']; ?>";
        var tipo_doc = "<?php echo $post['tipo_doc']; ?>";
        var url = "<?php echo base_url("index.php/mcinvestigacion/avance"); ?>";
        $.post(url, {id: id, tipo_doc:tipo_doc,dato: dato, fecha: fecha, cod_fis: cod_fis, nit: nit, titulo: titulo, id_prelacion: id_prelacion})
                .done(function(msg) {
                    alert('Datos Guardados Con Exito');
                    window.location.reload();
//                    $(".preload, .load").hide();
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