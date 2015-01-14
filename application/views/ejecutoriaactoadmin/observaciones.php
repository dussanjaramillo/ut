
<table width='100%'>
    <thead style="background: #5bb75b">
    <th>NOMBRE</th>
    <th>DOCUMENTO</th>
</thead>
<?php
foreach ($titulos as $value) {
    echo "<tr><td>" . $value['NOMBRE_DOCUMENTO'] . "</td>";
    echo "<td><a  target='_blank' href='" . $value['RUTA_ARCHIVO'] . "'>ver documento</a></td></tr>";
}
?>
</table>

<hr>
<center><b>Observaciones</b></center>
<?php
echo $observaciones[0]['OBSERVACIONES']
?>
<hr>
<textarea id="informacion" style="width: 100%"></textarea>
<center><button id="enviar" class="btn btn-success">Enviar a Coactivo</button></center>

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />



<script>
    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 600,
    });
    $(".preload, .load").hide();


    $('#enviar').click(function() {
        $(".preload, .load").show();
        var id='<?php echo $post['id'] ?>';
        var informacion=$('#informacion').val();
        if(informacion==""){
            alert('Campo de Observaciones Obligatorio');
            return false;
        }
        var url = "<?php echo base_url('index.php/ejecutoriaactoadmin/enviar_correcciones') ?>";
        $.post(url,{id:id,informacion:informacion})
                .done(function(msg){
//                    $(".preload, .load").hide();
                    window.location.reload();
//            alert('Los datos no Fueron Guardados');
                }).fail(function(msg){
                    $(".preload, .load").hide();
                    alert('Los datos no Fueron Guardados');
                })
    })
</script>