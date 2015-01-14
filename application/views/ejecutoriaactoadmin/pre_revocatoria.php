<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="uno">
    <?php
    echo $texto
    ?>
    <div>
        <center>
        <button id="devolver" class="btn btn-success">Devoluci&oacute;n</button>
        <button id="enviar" class="btn btn-success"> <i class="fa fa-floppy-o"></i> Aprobar</button>
        </center>
    </div>
</div>

<div id="dos" style="display: none">
    Observaciones:<br>
    <textarea id="informacion" name="informacion" maxlength="140" size="140" onkeyup="presion();" style="width: 100%"></textarea>
    
    <p>Caracteres disponibles:<span id="cantidad">140</span></p>
    <center>
        <button id="devolver2" class="btn btn-success">Devoluci&oacute;n</button>
    <button id="atras" class="btn">Atras</button>
    </center>
</div>
<hr>
<div>
    <?php echo $comentario; ?>
</div>

<script>
    function presion()
    {
        var canti = document.getElementById('informacion').value.length;
        var disponibles = 140 - parseInt(canti);
        document.getElementById('cantidad').innerHTML = disponibles;
    }
    $('#informacion').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 900,
        title:'Documento Revocatoria'
//    buttons:[{
//            id:'guarda'
//    }]
    });
    
    id="<?php echo $post['id']?>";
    cod_fis="<?php echo $post['cod_fis']?>";
    concepto="<?php echo $post['concepto']?>";
    nit="<?php echo $post['nit']?>";
    id_resolucion="<?php echo $post['id_resolucion']?>";
    
    $('#devolver').click(function(){
        $('#uno').hide();
        $('#dos').show();
    });
    $('#atras').click(function(){
        $('#dos').hide();
        $('#uno').show();
    });

    $('#devolver2').click(function(){
        $(".preload, .load").show();
        var informacion=$('#informacion').val();
        var url="<?php echo base_url('index.php/ejecutoriaactoadmin/devolucion'); ?>";
        $.post(url,{id:id,nit:nit,concepto:concepto,cod_fis:cod_fis,informacion:informacion,id_resolucion:id_resolucion})
                .done(function(){
                    alert('Los datos fueron guardados con exito');
                    window.location.reload();
                }).fail(function(){
                    alert('ERROR EN LA BASE DE DATOS')
                    $(".preload, .load").hide();
                });
    });
    $('#enviar').click(function(){
        $(".preload, .load").show();
        var url="<?php echo base_url('index.php/ejecutoriaactoadmin/aprobar'); ?>";
        $.post(url,{id:id,nit:nit,concepto:concepto,cod_fis:cod_fis,id_resolucion:id_resolucion})
                .done(function(){
                    alert('Los datos fueron guardados con exito')
                    window.location.reload();
                }).fail(function(){
                    alert('ERROR EN LA BASE DE DATOS');
                    $(".preload, .load").hide();
                });
    });
    $(".preload, .load").hide();
</script>