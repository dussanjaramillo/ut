<a name="arriba"></a>
<?php if (!empty($post['id'])) { ?>
    <input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
    <input type="hidden" id="reviso" name="reviso" value="<?php echo $post['reviso']; ?>">
    <input type="hidden" id="concepto" name="concepto" value="<?php echo $post['concepto']; ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<?php } ?>
<div id="resolucion">
    <div align='center' >
        <button id="pdf" class="btn btn-info"><i class="fa fa-file-pdf-o"></i> PDF</button>
        <?php
        if ($user->IDUSUARIO == $consulta[0]['ABOGADO'] && $consulta[0]['COD_ESTADO'] != 23) {
            ?>
            <!--<button id="aprobar">Aprobar</button>-->
            <?php
        }
        if ($user->IDUSUARIO == $consulta[0]['COORDINADOR'] && $consulta[0]['COD_ESTADO'] == 23) {
            ?>
            <!--en caso de que necesiten devolver se descomentarea esto--> 
            <a href="#abajo"><button id="devolver" class="btn btn-success">Devolver a Correcciones</button></a>
            <button id="aprobar" class="btn btn-success"> <i class="fa fa-floppy-o"></i> Aprobar</button>
            <input type="hidden" name="cod_siguiente" id="cod_siguiente" value="27">
            <input type="hidden" name="respuesta" id="respuesta" value="37">
            <?php
        }
        if ($user->IDUSUARIO == $consulta[0]['DIRECTOR_REGIONAL'] && $consulta[0]['COD_ESTADO'] == 27 && $post['concepto']==3) {
            ?>
            <a href="#abajo"><button id="devolver" class="btn btn-success">Devolver a Correcciones</button></a>
            <?php
        }
        ?>
    </div>
    <hr>
</div>
<p><hr><center>Correcciones</center><p>
<div id="resul" style="border:1px solid #ccc">
    <?php
    echo $comentario;
    ?>
</div>

<div id="correcciones_div" style="display: none">
    <p><center>Correcciones</center><p>
        <textarea id="correcciones" name="correcciones" rows="4" maxlength="140" size="140" onkeyup="presion();" class="validate[required, minSize[1], maxSize[200]] span8" data-prompt-position="bottomLeft"></textarea>
        <p>Caracteres disponibles:<span id="cantidad">140</span></p>
    <div align="right"> 
        <a name="abajo"></a>
        <a href="#arriba"><button id="atras" class="btn"><i class="fa fa-arrow-circle-left"></i> Atras</button></a>
        <button id="enviar" class="btn btn-success"><i class="fa fa-floppy-o"></i> Enviar</button>
    </div>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    function presion()
    {
        var canti = document.getElementById('correcciones').value.length;
        var disponibles = 140 - parseInt(canti);
        document.getElementById('cantidad').innerHTML = disponibles;
    }
    $('#correcciones').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $(".preload, .load").hide();
    function comprobarextension() {
        if ($("#imagen").val() != "") {
            var archivo = $("#imagen").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensiÃ³n estÃ¡ entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#imagen").val("");
                mierror = "Comprueba la extensiÃ³n de los archivos a subir.\nSÃ³lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        }
    }
    $(function() {
        var id = $('#id').val();
        var nit = $('#nit').val();
        var reviso = $('#reviso').val();
        var concepto = $('#concepto').val();
        $('#resultado').dialog({
            autoOpen: true,
            width: 800,
            height: 600,
            modal:true,
            title:'Resolución',
            close: function() {
                $('#resultado *').remove();
            }
        });
        $('#devolver').click(function() {
            $('#resolucion').hide();
            $('#correcciones_div').show();
        })
        $('#atras').click(function() {
            $('#resolucion').show();
            $('#correcciones_div').hide();
        })
        $('#enviar').click(function() {
            $(".preload, .load").show();
            jQuery('#correcciones').validationEngine('attach');
            jQuery('#correcciones').validationEngine('validate');
            (jQuery('#correcciones').val() == "") ? corre = false : (jQuery('#correcciones').val().length >= 200) ? corre = false : corre = true;
            if (corre == true) {
                var correcciones = $('#correcciones').val();
                var estado = "28";
                var respuesta = "39";
                var cod_fis = $('#cod_fis').val();
                var url = "<?php echo base_url('index.php/resolucion/resolucion_guardar') ?>";
                $.post(url, {id: id, nit: nit, reviso: reviso, concepto: concepto,cod_fis:cod_fis, correcciones: correcciones, estado: estado,respuesta:respuesta})
                        .done(function(msg) {
                            $('#resul').html(msg);
                            alert("Los datos fueron guardados con exito");
//                            jQuery('#correcciones').val('');
                            window.location.reload();
                        })
                        .fail(function(xhr) {
                            $(".preload, .load").hide();
                            alert("Los datos no fueron guardados");
                        });
            } else {
                alert('Datos incorrectos');
                $(".preload, .load").hide();
            }
        });
        $('#aprobar').click(function() {
        $(".preload, .load").show();
            var url = "<?php echo base_url('index.php/resolucion/resolucion_guardar') ?>";
            var correcciones = "APROBADA CORDINADOR";
            var estado = $('#cod_siguiente').val();
            var respuesta = $('#respuesta').val();
            var cod_fis = $('#cod_fis').val();
            $.post(url, {id: id, nit: nit, reviso: reviso, concepto: concepto, cod_fis:cod_fis,correcciones: correcciones, estado: estado,respuesta:respuesta})
                    .done(function(msg) {
                        alert("Los datos fueron guardados con exito");
                        window.location.reload();
                    })
                    .fail(function(xhr) {
                        $(".preload, .load").hide();
                        alert("Los datos no fueron guardados");
                    });
        });
        $('#pdf').click(function() {
            id = $('#id').val();
            nit = $('#nit').val();
            reviso = $('#reviso').val();
            concepto = $('#concepto').val();
            pdf = "ok"
            var url = "<?php echo base_url('index.php/resolucion/resolucion_pdf?') ?>";
            var url = url + "id=" + id + "&nit=" + nit + "&reviso=" + reviso + "&concepto=" + concepto + "&pdf=" + pdf;
            window.open(url);
        })
    })

    function validacion() {
        if (jQuery("#form").validationEngine('validate') == true) {
            alert("valido");
        }
        else {
            alert('error de validación');
        }
    }
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