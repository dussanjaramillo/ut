<table>
    <tr>
        <td>Nombre Empleador</td>
        <td><input id="nombreempleador" type="text" maxlength="128" value="<?php echo $consulta[0]['NOMBRE_EMPLEADOR'] ?>" name="nombreempleador"></td>
    </tr>
    <tr>
        <td>Valor</td>
        <td><?php echo number_format($consulta[0]['VALOR_TOTAL']) ?></td>
    </tr>
    <tr>
        <td>Valor</td>
        <td><input id="valor_letras" type="text" maxlength="128" value="<?php echo $consulta[0]['VALOR_LETRAS'] ?>" name="valor_letras"></td>
    </tr>
    <tr>
        <td colspan="2">
            <button id="enviar">Aprobar y Enviar</button>
        </td>
    </tr>
</table>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
        $(".preload, .load").hide();
        $('#resultado').dialog({
            autoOpen: true,
            width: 400,
            height: 300,
            modal:true,
            close: function() {
                $('#resultado *').remove();
            }
        })
        $('#enviar').click(function() {
            $(".preload, .load").show();
            var id ="<?php echo $consulta[0]['COD_RESOLUCION'] ?>";
            var valor ="<?php echo $consulta[0]['VALOR_TOTAL'] ?>";
            var letras = $('#valor_letras').val();
            var url = "<?php echo base_url('index.php/resolucion/guardar_modificacion') ?>";
            $.post(url, {id: id, valor: valor, letras: letras})
                    .done(function(msg) {
                        $(".preload, .load").hide();
                        alert("Los datos fueron guardados con exito");
                        window.location.reload();
                    })
                    .fail(function(xhr) {
                        $(".preload, .load").hide();
                        alert("Los datos no fueron guardados");
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