
<!--subir los archivos de aprobacion--> 
<!--<div class="center-form-large">-->
    <?php
    $attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
    echo form_open_multipart("resolucion/resolucion_imagen", $attributes);
    ?>
    <!--<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Solo se admiten archivos en PDF</div>-->   
    <h4>Cargar Archivo de la Resoluci&oacute;n</h4>
    <br>
    <div >            
              
            <div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center;font-size: 10px;">
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
        
    </div>
    <br>
    <div>
        Numero de Resoluci&oacute;n <input type="text" id="num_resolu" name="num_resolu"  maxlength="15" class="validate[required]" value="">
    </div>
    <div>
        Fecha Documento <br><input type="text" id="nom_resolu" name="nom_resolu" readonly="readonly" maxlength="40" value="">
    </div>
    <p>
    <div align="center" id="boton_finalizar" style="display: none">
        <p >
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'submit-button',
                'value' => 'Confirmar',
                'type' => 'submit',
                'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Aprobar y Confirmar',
                'class' => 'btn btn-success'
            );

            echo form_button($data);
            ?>
        </p>
    </div>
    <?php echo form_close(); ?>
<!--</div>-->
<!--fin de subir archivos--> 
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $('#num_resolu').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#nom_resolu').datepicker({
        buttonText: 'Fecha',
        buttonImageOnly: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        showOn: 'button',
        "maxDate" : "0",
        buttonText: 'Seleccione una fecha',
                buttonImage: '<?php echo base_url() . "img/calendario.png" ?>',
        buttonImageOnly: true,
                numberOfMonths: 1,
    });

    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 400,
        height: 400,
        title:'Resolución',
        close: function() {
            $('#resultado *').remove();
        }
    });
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
            //recupero la extensión de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensión estÃ¡ entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#imagen").val("");
                mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
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