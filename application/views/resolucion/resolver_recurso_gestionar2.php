
<div id="form1">
    <table width="100%" align="center">
        <tr>
            <td>Regional</td>
            <td>Resolucion</td>
            <td>Fecha resoluicon</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_REGIONAL'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NUMERO_RESOLUCION'] ?></td>
            <td class="colores"><?php echo $consulta[0]['FECHA_CREACION'] ?></td>
        </tr>
        <tr>
            <td>Nombre Del Empleador</td>
            <td>Nit de la empresa</td>
            <td>Direccion de la empresa</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NITEMPRESA'] ?></td>
            <td class="colores"><?php echo $consulta[0]['DIRECCION'] ?></td>
        </tr>
        <tr>
            <td>Nombre de la empresa</td>
            <td>Concepto</td>
            <td>Valor de la obligacion</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?></td>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_CONCEPTO'] ?></td>
            <td class="colores"><?php echo number_format($consulta[0]['VALOR_TOTAL']) ?></td>
        </tr>
        <tr>
            <td>Valor en Letras</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['VALOR_LETRAS'] ?></td>
        </tr>
        <tr>
            <td><div style="display: none">Vigencia</div></td>
        </tr>
        <tr>
            <td colspan="3"><div style="display: none"><textarea id="vigencia" style="width: 100%" id="vigencias" name="vigencias"></textarea></div></td>
        </tr>
        <tr>
            <td><div style="display: none">Argumentos</div></td>
        </tr>
        <tr>
            <td colspan="3"><div style="display: none"><textarea id="argumentos" style="width: 100%" id="argumentos" name="argumentos"></textarea></div></td>
        </tr>
        <tr>
            <td>Director Regional</td>
            <td>Revisor</td>
            <td>Proyector</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $consulta[0]['NOMBRE_DIRECTOR'] ?></td>
            <td class="colores"><?php echo $info_user ?></td>
            <td class="colores"><?php echo $info_user ?></td>
        </tr>
        <tr>
            <td colspan="3"><hr></td>
        </tr>
        <tr>
            <td colspan="3" align='center'><b>La informacion de la nueva liquidacion es:</b></td>
        </tr>
        <tr>
            <td colspan="3"><hr></td>
        </tr>
        <tr>
            <td>Numero Liquidacion</td>
            <td>Valor Nueva Liquidacion</td>
            <td>Valor en letras</td>
        </tr>
        <tr>
            <td class="colores"><?php echo $liquidacion['NUM_LIQUIDACION'] ?></td>
            <td class="colores"><?php echo number_format($liquidacion['SALDO_DEUDA']) ?></td>
            <td class="colores"><?php echo $info=Resolucion::valorEnLetras($liquidacion['SALDO_DEUDA'],"pesos"); ?></td>
        </tr>

    </table>
    <center><button id="continuar" class="btn btn-success">Continuar</button></center>
</div>
<div id="textarea" style="display: none"><textarea id="informacion" name="informacion" style="width: 100%;height: 400px"><?php echo $texto ?></textarea>
    <center><button id="enviar" class="btn btn-success">Enviar</button></center>
</div>
<hr>
<div>
    Motivo Devolucion:<p>
        <?php echo $comentario ?>
</div>
<script>
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
//        height: 600,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#enviar').click(function() {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/guardar_archivo') ?>";
        var informacion = tinymce.get('informacion').getContent();
        informacion = informacion + "<br><p>ARGUMENTOS<br>" + $('#argumentos').val() + "<br><p>VIGENCIA<br><p>" + $('#vigencia').val();
        var nit =<?php echo $consulta[0]['NITEMPRESA'] ?>;
        var num_recurso =<?php echo $consulta[0]['NUM_RECURSO'] ?>;
        var id =<?php echo $consulta[0]['COD_RESOLUCION'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;
        $.post(url, {informacion: informacion,id: id, nombre: nombre_archivo})
                .done(function(msg) {
                    var url = "<?php echo base_url('index.php/resolucion/update_recurso') ?>";
                    $.post(url, {id: id, nombre_archivo: nombre_archivo,num_recurso:num_recurso})
                            .done(function(msg) {
                                alert("Los datos fueron guardados con exito");
                                window.location.reload();
                            })
                            .fail(function(xhr) {
                                $(".preload, .load").hide();
                                alert("Los datos no fueron guardados");
                            });

                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                })
    });
    $('#continuar').click(function() {
        $('#form1').hide();
        $('#textarea').show();
    });
    tinymce.init({
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
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