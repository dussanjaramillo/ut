
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
$cont=0;
foreach ($observaciones as $value) {
	$cont++;
    echo "<center><tr><td>".$cont.". </td><td>" . $value['OBSERVACIONES'] . "</td></tr></center>";
}

?>
<hr>
<input type="hidden" id="atcoactivo" name="atcoactivo" value="<?=$idcartera?>">
<input type="hidden" id="attipo" name="attipo" value="<?=$tipocartera?>">
<textarea id="informacion" style="width: 100%"></textarea>
<center><button id="enviar" class="btn btn-success">Enviar a Coactivo</button>&nbsp&nbsp<button id="carga_archivos" class="btn btn-success">Cargar Archivos</button></center>

<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />



<script>

	$('#carga_archivos').click(function() {
		var atcoactivo=$('#atcoactivo').val();
		var attipo=$('#attipo').val();
		var cod_recepcion='<?php echo $cod_recepcion[0]['COD_RECEPCIONTITULO'] ?>';
		var urlcoactr="<?= base_url('index.php/carteranomisional/carga_archivo_coact')?>";
		$('#gestion').load(urlcoactr,{atcoactivo : atcoactivo, attipo : attipo, cod_recepcion: cod_recepcion });
	});

    $('#resultado').dialog({
        title:'Observaciones',
        autoOpen: true,
        modal: true,
        width: 850,
                        close: function() {
                    $('#resultado *').remove();
                }
    });
    $(".preload, .load").hide();


    $('#enviar').click(function() {
        $(".preload, .load").show();
         var idcartera=$('#atcoactivo').val();
        var id='<?php echo $cod_recepcion[0]['COD_RECEPCIONTITULO'] ?>';
        var informacion=$('#informacion').val();
        if(informacion==""){
            alert('Campo de Observaciones Obligatorio');
            return false;
        }
        var url = "<?php echo base_url('index.php/carteranomisional/enviar_correcciones') ?>";
        $.post(url,{id:id,informacion:informacion, idcartera: idcartera})
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