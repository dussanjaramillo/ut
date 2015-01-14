
<div id="primer">
<?php echo $texto ?>
<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autocargos/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px;display: none "><?php echo $texto ?></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
<center>
<button id="" onclick="enviar_pdf()" class="btn btn-success">PDF</button>
<button id="" onclick="aprobar_doc()" class="btn btn-success">Aprobar</button>
<button id="" onclick="devolver()" class="btn btn-success">Devolver</button>
</center>
    </div>
<div id="observa" style="border-radius: 15px;display: none">
    <center>Observaciones</center>
    <div>
        <textarea id="obser" style="width: 100%;"></textarea>
    </div>
    <div align="center">
        <button id="enviar_obser" class='btn btn-success'>Enviar</button>
        <button  class='atras2 btn btn-success' onclick="atras()">Atras</button>
    </div>

</div>
<table width="100%">
    <tr>
        <td>
            <b>Observaciones Anteriores</b><p>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $traza; ?>
        </td>
    </tr>
</table>
<script>
    $('#observa').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#enviar_obser').click(function() {
        var url = "<?php echo base_url('index.php/autopruebas/guardar_trazabilidad2') ?>";
        var num_auto = "<?php echo $post['num_auto'] ?>";
        var cod_fis = "<?php echo $post['cod_fis'] ?>";
        var nit = "<?php echo $post['nit'] ?>";

        var obser = $('#obser').val();
        if (obser == "") {
            alert('Datos Incompletos');
            return false;
        }
        obser="////"+obser+'///'+'<?php echo date("d/m/y") ?>';
        jQuery(".preload, .load").show();
        $.post(url, {num_auto:num_auto,obser:obser,nit:nit,cod_fis:cod_fis})
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("ERROR AL GUARDAR");
        });
    })
    function aprobar_doc () {
        var url = "<?php echo base_url('index.php/autopruebas/aprobar_documento_auto2') ?>";
        var num_auto = "<?php echo $post['num_auto'] ?>";
        var cod_fis = "<?php echo $post['cod_fis'] ?>";
        var nit = "<?php echo $post['nit'] ?>";
        var cod_respu=99;
        jQuery(".preload, .load").show();
        $.post(url, {num_auto: num_auto,cod_respu:cod_respu,cod_fis:cod_fis,nit:nit})
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("ERROR AL GUARDAR");
        });
    }
    
    function devolver(){
        $('#primer').hide();
        $('#observa').show();
    }
    function atras(){
        $('#primer').show();
        $('#observa').hide();
    }
    
$('#resultado').dialog({
    autoOpen:true,
    modal:true,
    width:900,
    title:"Auto de Cargos"
});

function enviar_pdf(){
            $("#form").submit();
        }

$(".preload, .load").hide();
</script>