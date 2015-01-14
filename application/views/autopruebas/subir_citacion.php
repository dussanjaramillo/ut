<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("autopruebas/update_citacion", $attributes);
?>
<input type="hidden" name="num_citacion" id="num_citacion" value="<?php echo $post['num_citacion']; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $post['id']; ?>">
    <div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center">
        <?php
        $data = array(
            'name' => 'userfile',
            'id' => 'imagen',
            'class' => 'validate[required]'
        );
        echo form_upload($data);
        ?>
    </div><p><br>
    <input type="hidden" id="id_resolucion" name="id_resolucion" value="<?php echo $post['id'] ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $post['id'] ?>">
    <input type="hidden" id="cod_resolucion" name="cod_resolucion" value="<?php echo $post['id'] ?>">
    <?php 
    if ($citacion == "33") {
            $cod_siguiente = "36";
            $cod_respuesta = "54";
        } else if ($citacion == "34") {
            $cod_siguiente = "84";
            $cod_respuesta = "35";
        } else if ($citacion == "35") {
            $cod_siguiente = "50";
            $cod_respuesta = "91";
        } else if ($citacion == "310") {
            $cod_cita = "3";
            $cod_siguiente = "311";
            $cod_respuesta = "31";
        } else if ($citacion == "311") {
            $cod_cita = "3";
            $cod_siguiente = "50";
            $cod_respuesta = "91";
        }else if ($citacion == "411") {
            $cod_cita = "3";
            $cod_siguiente = "412";
            $cod_respuesta = "31";
        }
    ?>
    <input type="hidden" id="cod_siguiente" name="cod_siguiente" value="<?php echo $cod_siguiente ?>">
    <input type="hidden" id="cod_respuesta" name="cod_respuesta" value="<?php echo $cod_respuesta ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit'] ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis'] ?>">
<center><button class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button></center>
<?php echo form_close(); ?>
<script>
    $('#resultado').dialog({
        autoOpen: true,
        width: 500,
        title: "Subir Archivo",
        height: 200,
        modal:true,
        close: function() {
            $('#resultado *').remove();
        }
    });
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
        } else {
            return false;
        }
    }
</script>