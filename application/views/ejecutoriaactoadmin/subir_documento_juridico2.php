<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("ejecutoriaactoadmin/documento_juridico2", $attributes);
?>
<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Solo se admiten archivos en PDF</div>
<br>
<div >            

    <div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center;font-size: 12px;">
        <?php
        $data = array(
            'name' => 'userfile',
            'id' => 'imagen',
            'class' => 'validate[required]'
        );
        echo form_upload($data);
        ?>
    </div>
    <input type="hidden" id="id" name="id" value="<?php echo $post['id'] ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit'] ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis'] ?>">
    <input type="hidden" id="cod_revocatoria" name="cod_revocatoria" value="<?php echo $post['cod_revocatoria'] ?>">

</div>
<p>
    <br ><center>
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Confirmar',
            'type' => 'submit',
            'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Enviar',
            'class' => 'btn btn-success'
        );

        echo form_button($data);
        ?>
    </p>
    </center>
<?php echo form_close(); ?>
<!--</div>-->
<!--fin de subir archivos--> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />



<script>
    $('#resultado').dialog({
        autoOpen: true,
        modal: true,
        width: 400,
    });
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
            $(".preload, .load").show();
            return true;
        } else {
            return false;
        }
    }
    $(".preload, .load").hide();
</script>