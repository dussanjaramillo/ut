<form action="POST" id="formulario">
    <table width="100%" class="table table-striped">
        <thead >
        <th></th>
        <th>Nombre del documento</th>
        <th>Entidad Bancaria</th>
        <th>Valor de la aplicaci&oacute;n de la medida</th>
        <th>Observaciones</th>
        </thead>
        <?php
        $cantidad = count($bancos);
        $suma=0;
        for ($i = 0; $i < $cantidad; $i++) {
//            if($i%2==0)
//                echo '<tr style="background: #CCC">';
//            else
//                echo '<tr>'
            ?>
        <tr>
                <td><input type="hidden" id="cod" name="cod[]" value="<?php echo $bancos[$i]['COD_EMBARGO_DINEROS'] ?>"></td>
                <td><a href="<?php echo base_url(RUTA_DES . $post['id'] . "/" . $bancos[$i]['NOMBRE_OFICIO_EMBARGO']); ?>"  target="_blank"><?php echo $bancos[$i]['NOMBRE_OFICIO_EMBARGO'] ?></a></td>
                <td>
                    <select name="banco[]">
                        <?php foreach ($info_bancos as $info) { 
                            if($bancos[$i]['COD_BANCO']==$info['IDBANCO'])
                                $seleccion="selected=selected";
                            else
                                $seleccion="";
                                ?>
                        <option value="<?php echo $info['IDBANCO'] ?>" <?php echo $seleccion; ?>><?php echo $info['NOMBREBANCO'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <?php 
//                echo $bancos[$i]['VALOR']."<br>";
                $suma=$suma+$bancos[$i]['VALOR']; 
                ?>
                <td align='center'><input type="text" style="width: 170px" id="valor_<?php echo $i ?>" name="valor[]" value="<?php echo $datos=(!empty($bancos[$i]['VALOR'])?$bancos[$i]['VALOR']:0);?>" size="10" maxlength="10" onchange="valor(this, 'valor_<?php echo $i ?>')"></td>
                <td><input type="text" id="valor_<?php echo $i ?>" name="observaciones[]" value="<?php echo $bancos[$i]['OBSERVACIONES'] ?>"></td>
            </tr>
        <?php } ?>
    </table>
    <input type="hidden" value="<?php echo $post['id']; ?>" id="id" name="id">
    <input type="hidden" value="<?php echo $post['cod_fis']; ?>" id="cod_fis" name="cod_fis">
    <input type="hidden" value="<?php echo $post['cod_siguiente']; ?>" id="cod_siguiente" name="cod_siguiente">
    <input type="hidden" value="<?php echo $post['nit']; ?>" id="nit" name="nit">
    <input type="hidden" value="<?php echo $post['titulo']; ?>" id="titulo" name="titulo">
    <input type="hidden" value="" id="t" name="t">
</form>
<hr>
<!--<table width="100%">
    <tr align="center">
        <td>Valor Deuda</td>
        <td>$<?php echo number_format($consulta[0]['TOTAL_LIQUIDADO']); ?></td>
        <td>Valor a Debitar</td>
        <td><div id="suma">$<?php echo number_format($suma); ?></div></td>
    </tr>
</table>-->
<center>
<button id="guardar" class="btn btn-success">Guardar</button>
</center>
<script>
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 960,
        modal: true,
        title: "<?php echo $post['titulo'] ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#guardar').click(function(){
        jQuery(".preload, .load").show();
        var url="<?php echo base_url('index.php/mcinvestigacion/resumen_documentos_bancarios'); ?>";
        $.post(url,$('#formulario').serialize())
                .done(function(msg){
                    window.location.reload();
                }).fail(function(){
                    jQuery(".preload, .load").hide();
                    alert("<?php echo ERROR; ?>");
                });
    });
    function valor(dato, campo) {
        dato = dato.value;
        if (isNaN(dato) || dato=="") {
            $('#' + campo).val('0');
            alert("Datos Incorrectos");
        }
        var cantidad = "<?php echo count($bancos); ?>"
        var valor = 0;
        for (var i = 0; i < cantidad; i++) {
            valor = parseInt($('#valor_' + i).val()) + parseInt(valor);
        }
        valor = formatDollar(valor);
        $('#suma').html(valor);
    }
    function formatDollar(num) {
        var p = num.toFixed(0).split(".");
        return "$" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
            return  num + (i && !(i % 3) ? "," : "") + acc;
        }, "");
    }

</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
