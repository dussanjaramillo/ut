<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
Abogados: 
<select id="abogado" name="abogado">
    <?php foreach ($abogados as $abogado) { ?>
    <option value="<?php echo $abogado['IDUSUARIO']; ?>"><?php echo $abogado['APELLIDOS']." ".$abogado['NOMBRES']; ?></option>
        <?php } ?>
</select><br>
<button id="enviar" class="btn btn-success" align="center"> Guardar</button>



<script>
    jQuery(".preload, .load").hide();
$('#resultado').dialog({
    modal:true,
    width:'300',
    autoOpen:true,
    title:"Asignación",
    close:function(){
        $('resultado *').remove()
    }
})

$('#enviar').click(function(){
    jQuery(".preload, .load").show();
    var abogado=$('#abogado').val();
    var cod_fis="<?php echo $post['cod_fis'] ?>";
    var nit="<?php echo $post['nit'] ?>";
    var concepto="<?php echo $post['concepto'] ?>";
    var regional="<?php echo $post['regional'] ?>";
    var num_liquidacion="<?php echo $post['num_liquidacion'] ?>";
    var url="<?php echo base_url('index.php/notificacionacta/guardar_abogado'); ?>";
    $.post(url,{abogado:abogado,cod_fis:cod_fis,nit:nit,num_liquidacion:num_liquidacion,concepto:concepto,regional:regional})
            .done(function(msg){
                window.location.reload();
            }).fail(function(msg,fail){
                jQuery(".preload, .load").hide();
                alert("Documento no Guardado");
            });
});
</script>