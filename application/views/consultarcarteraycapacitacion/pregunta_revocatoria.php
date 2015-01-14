<table width='100%'>
    <tr>
        <td>Parcial</td>
        <td><input type="radio" class="radio1" value="Parcial" name="radio_name"></td>
    </tr>
    <tr>
        <td>Total</td>
        <td><input type="radio" class="radio1" value="Total" name="radio_name"></td>
    </tr>
</table>

<script>
    jQuery(".preload, .load").hide();
$('#resultado').dialog({
    autoOpen:true,
    modal:true,
    width:300,
    title:'Revocatoria'
});
id_ejecutoria="<?php echo $post['id_ejecutoria']; ?>";
num_resolucion="<?php echo $post['num_resolucion']; ?>";
$('.radio1').click(function(e){
//    alert(this.value);
    var r=confirm("Confirma que la Rvocatoria es "+this.value+"?");
    if(r==true){
        if(this.value=='Parcial'){
            var cod_siguiente=422;
            var cod_respuesta=1107;
            jQuery(".preload, .load").show();
            var url='<?php echo base_url('index.php/consultarcarteraycapacitacion/guardar_Pregunta_revocatoria') ?>';
            $.post(url,{id_ejecutoria:id_ejecutoria,num_resolucion:num_resolucion,cod_siguiente:cod_siguiente,cod_respuesta:cod_respuesta})
                    .done(function(smg){
                        window.location.reload();
                    }).fail(function(msg){
                        jQuery(".preload, .load").hide();
                    });
        }
    }
});
</script>