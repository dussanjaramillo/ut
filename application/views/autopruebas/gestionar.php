<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("autopruebas/correspondencia_citacion", $attributes);
?>
<input type="hidden" id="resultadoss" name="resultadoss" value="0">
<input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" value="<?php echo $consulta[0]['COD_CPTO_FISCALIZACION']; ?>">
<input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
<input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
<input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<input type="hidden" id="num_citacion" name="num_citacion" value="<?php echo $consulta[0]['NUMERO_CITACION']; ?>">
<input type="hidden" id="cod_estado" name="cod_estado" value="<?php echo $consulta[0]['COD_ESTADO']; ?>">
<div id="form1">
    <table width="100%">
        <tr>
            <td>Recibida</td>
            <td><input type="radio" name="gestionar" value="1" onclick="gestion('recibida')"></td>
        </tr>
        <tr>
            <td>Devuelta</td>
            <td><input type="radio" name="gestionar" value="2" onclick="gestion('debuelta')"></td>
        </tr>
    </table>
</div>
<!--recibida-->
<div id="form2" style="display: none">
    <table width="100%">
        <tr>
            <td>Fecha</td>
            <td><input type="text" name="fecha_recep" class="fecha" readonly="readonly" value="<?php echo date("d/m/Y"); ?>"></td>
        </tr>
        <tr>
            <td>Nombre de quien recibe</td>
            <td width="50%"><input type="text" id="nombre_recep" name="nombre_recep" maxlength="15"></td>
        </tr>
        <tr>
        </tr>
    </table>
</div>

<!--devuelta-->
<div id="form3" style="display: none">
    <table width="100%">
        <tr>
            <td width="50%">Fecha</td>
            <td width="50%"><input type="text" name="fecha_actual" class="fecha" value="<?php echo date("d/m/Y"); ?>"></td>
        </tr>
        <tr>
            <td width="50%">Razón</td>
            <td>
                <select id="razon" name="razon">
                    <?php
                    foreach ($motivo as $motivos) {
                        ?>
                        <option value="<?php echo $motivos['COD_MOTIVODEVOLUCION']; ?>"><?php echo $motivos['NOMBRE_MOTIVO']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
</div>
<div id="boton" style="display: none">
    <table width="100%">
        <tr>
            <td width="50%">Código de la colilla</td>
            <td width="50%"><input type="text" name="colilla" id="colilla" maxlength="20"></td>
        </tr>
        <tr>
            <td >Cargar archivo</td>
        </tr>
    </table>
    <div id="imagen2">
        <div style="overflow: hidden; clear: both; margin:210px 0; width: 90%; margin: 0 auto; text-align: center">
            <?php
            $data = array(
                'name' => 'userfile',
                'id' => 'imagen',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            ?>
        </div> 
        <div ><p><center><button id="enviar_recibida" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button></center></div>
    </div>
</div>
<input type="hidden" id="cod_resolucion" name="cod_resolucion" value="<?php echo $consulta[0]['COD_RESOLUCION']; ?>">

<?php echo form_close(); ?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="formulario2"></div>
<script>
    $('#nombre_recep').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#colilla').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    
    $(".fecha").datepicker();
    $(".preload, .load").hide();
    $('#formulario1').dialog({
        autoOpen: true,
        width: 300,
        height: 120,
        modal: true,
        title: "Recepcion de la citación",
        close: function() {
            $('#formulario1 *').remove();
        }
    });
    function editor(dato) {
        $('#segundo_paso').hide();
        $('#textarea2').show();
    }
    function gestion(dato) {
        var titulo = "";
        if (dato == "recibida") {
            $('#form1').hide();
            $('#form3').hide();
            $('#form2').show();
            var titulo = "Citación Recibida";
        } else {
            $('#form1').hide();
            $('#form2').hide();
            $('#form3').show();
            $('#resultadoss').val('1');
            var titulo = "Citación Rechazada";
        }
        $('#boton').show();
        $('#formulario1').dialog({
            width: 600,
            height: 300,
            title: titulo
        });
    }
    $('#enviar_recibida').click(function() {
        if($('#colilla').val()==""){
            alert('Datos imcompletos');
            return false;
        }
    });

    $('#enviar3').click(function() {
        //        $(".preload, .load").show();
        //        var url = "<?php echo base_url('index.php/resolucion/correspondencia_citacion') ?>";
        //                $.post(url,$('#myform').serialize())
    });

    tinymce.init({
        selector: "textarea",
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
                mierror = "Comprueba la extensión de los archivos a subir.\nSÃ³lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        } else {
            alert('Datos imcompletos');
            return false;
        }
    }
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
                mierror = "Comprueba la extensión de los archivos a subir.\nSÃ³lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
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
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>