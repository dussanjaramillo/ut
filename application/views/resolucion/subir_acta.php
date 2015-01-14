<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("resolucion/subir_acta_archivo", $attributes);
?>
<input type="hidden" name="num_citacion" id="num_citacion" value="<?php echo $post['num_citacion']; ?>">
<input type="hidden" name="id" id="id" value="<?php echo $post['id']; ?>">
<input type="hidden" name="cod_fis" id="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<input type="hidden" name="nit" id="nit" value="<?php echo $post['nit']; ?>">
Nombre del Documento
<input type="text" name="nombre_archivo" id="nombre_archivo" maxlength="30" value="">
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
<center><button class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button></center>
<?php echo form_close(); ?>
<script>
    $('#nombre_archivo').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
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
        jQuery('#num_resolu').validationEngine('attach');
        jQuery('#nombre_archivo').validationEngine('attach');
        var nombre_archivo=jQuery('#nombre_archivo').val();
        if(nombre_archivo==""){
            alert('Campo de Nombre se encuentra vacío');
            return false;
        }
        
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