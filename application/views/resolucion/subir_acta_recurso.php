<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("resolucion/subir_acta_recurso_archivo", $attributes);
?>
<div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center">
<div>
        Numero de Resoluci&oacute;n <div><input type="text" id="num_resolu" name="num_resolu"  maxlength="15" class="validate[required]" value=""></div>
    </div>
    <div>
        Fecha Documento <div><input type="text" id="nom_resolu" name="nom_resolu" readonly="readonly" maxlength="40" value=""></div>
    </div>
    </div>
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
<center><button class="btn btn-success">Agregar</button></center>
<?php echo form_close(); ?>
<script>
    $('#num_resolu').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#nom_resolu').datepicker(
            {"maxDate" : "0"}
            );
    $('#num_resolu').change(function() {
        var url = "<?php echo base_url('index.php/resolucion/confirmar_resolucion') ?>";
        var id = $('#num_resolu').val();
        if(isNaN(id)){
            $('#num_resolu').val('');
            return false;
        }
        $(".preload, .load").show();
        $.post(url, {id: id})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    if (msg == ""){
                        $('#boton_finalizar').show();
                    }else {
                        $('#boton_finalizar').hide();
                        alert("Este número de Resolución ya fue usado en la fecha "+msg.FECHA_CREACION);
                        $('#num_resolu').val('');
                    }
//                    alert("Los datos fueron guardados con exito");
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
    }); 
    $('#resultado').dialog({
        autoOpen: true,
        width: 600,
        title: "Subir Archivo",
        height: 300,
        modal:true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
function comprobarextension() {
        jQuery('#num_resolu').validationEngine('attach');
        jQuery('#nom_resolu').validationEngine('attach');
        if ($("#num_resolu").val() == "" || $("#nom_resolu").val() == "") {
            alert("Datos Incompletos");
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