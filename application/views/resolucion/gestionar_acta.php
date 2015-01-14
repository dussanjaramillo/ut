<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("resolucion/correspondencia_citacion2222", $attributes);
?>
<input type="hidden" id="resultadoss" name="resultadoss" value="0">
<input type="hidden" id="cod_fiscalizacion" name="cod_fiscalizacion" value="<?php echo $consulta[0]['COD_CPTO_FISCALIZACION']; ?>">
<input type="hidden" id="num_citacion" name="num_citacion" value="<?php echo $consulta[0]['NUMERO_CITACION']; ?>">
<input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">

<input type="hidden" id="cod_resolucion" name="cod_resolucion" value="<?php echo $consulta[0]['COD_RESOLUCION']; ?>">
<input type="hidden" id="id" name="id" value="<?php echo $consulta[0]['COD_RESOLUCION']; ?>">
<input type="hidden" id="infor" name="infor" value="">
<input type="hidden" id="nombre" name="nombre" value="">
<!--en caso de recibida  pasa al paso de datos-->
<div id="segundo_paso" style="border: 1px solid #ccc; padding: 20px">
    <table width="100%">
        <tr>
            <td>Fecha</td>
            <td><input type="text" readonly="readonly" name="fecha_formulario" id="fecha_formulario" value="<?php echo date("d/m/Y"); ?>"></td>
            <td>Fecha resolucion</td>
            <td><input type="text" readonly="readonly" name="fecha_resolucion" id="fecha_resolucion" value="<?php echo $consulta[0]['FECHA_CREACION']; ?>"></td>
        </tr>
        <tr>
            <td>Resolucion</td>
            <td><input type="text" readonly="readonly" name="resolucion" id="resolucion" value="<?php echo $consulta[0]['NUMERO_RESOLUCION']; ?>"></td>
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <td>Nombre del representante legal</td>
            <td><input type="text" readonly="readonly" name="nombre_representante" id="nombre_representante" value="<?php echo $consulta[0]['REPRESENTANTE_LEGAL']; ?>"></td>
            <td>ciudad</td>
            <td><input type="text" name="ciudad" id="ciudad" readonly="readonly" value="<?php echo $ciudad['NOMBREMUNICIPIO'] ?>"></td>
        </tr>
        <tr>
            <td>correo electrónico</td>
            <td><input type="email" name="correo" id="correo" readonly="readonly" value="<?php echo $consulta[0]['CORREOELECTRONICO'] ?>" ></td>
        <input type="hidden" name="correo_autoriza" id="correo_autoriza" value="<?php echo $consulta[0]['AUTORIZA_NOTIFIC_EMAIL']; ?>">
        </tr>
        <tr>
            <td>Teléfono</td>
            <td><input type="tel" name="telefono" readonly="readonly" id="telefono" value="<?php echo $consulta[0]['TELEFONO_FIJO']; ?>"></td>
            <td>Domicilio</td>
            <td colspan="1"><input type="tel" readonly="readonly" name="direccion_empresa" id="direccion_empresa" value="<?php echo $consulta[0]['DIRECCION']; ?>"></td>
        </tr>
        <tr>
            <td>Razón social</td>
            <td><input type="text" name="razon_social" readonly="readonly" id="razon_social" value="<?php echo $consulta[0]['NOMBRE_EMPRESA']; ?>"></td>
            <td>NIT</td>
            <td><input type="text" name="nit" id="nit" readonly="readonly" value="<?php echo $consulta[0]['NITEMPRESA']; ?>"></td>
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <td>Nombre del notificador</td>
            <td><input type="text" name="nombre_notificador" readonly="readonly" id="nombre_notificador" value="<?php echo $info_user; ?>"></td>
            <td>Correo electrónico</td>
            <td><input type="email" name="correo_notificador" readonly="readonly" id="correo_notificador" value="<?php echo $user->EMAIL; ?>"></td>
        </tr>
        <tr>
            <td>Teléfono</td>
            <td><input type="tel" name="telefono_notofocador" readonly="readonly" id="telefono_notofocador" value="<?php echo $user->TELEFONO; ?>"></td>
            <td>Dirección </td>
            <td colspan="1">
                <input type="text" id="direccion_notofocador" readonly="readonly" name="direccion_notofocador" value="<?php echo $consulta[0]['DIRECCION_REGIONAL']; ?>">
            </td>
        </tr>
    </table>
</div>
<div id="textarea2" style="display: none">
    <table width="100%">
        <tr>
            <td>
                <textarea id="informacion" style="width: 100%;height: 400px"></textarea>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button id="enviar3" class="btn btn-success"><i class="fa fa-floppy-o"></i> Guardar</button>
            </td>
        </tr>
    </table>
</div>
<div id="tercer_paso" style="">
    <table width='60%'>
        <tr>
            <td>Generar Acta Notificación Personal</td>
            <td><input type="radio" name="notificacion" value="38" onclick="editor(1)"></td>
        </tr>
        <?php if ($consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "s" || $consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "S") { ?>
            <!--<tr>-->
                <!--<td>Generar Acta Notificación Por Correo</td>-->
                <!--<td><input type="radio" name="notificacion" value="39" onclick="editor(87)"></td>-->
                <!--<td><input type="radio" name="notificacion" value="39" onclick="editor(87)"></td>-->
            <!--</tr>-->
        <?php }  ?>
<!--        <tr>
            <td>Generar Notificación Por Aviso</td>-->
            <!--<td><input type="radio" name="notificacion" value="40" onclick="editor(87)"></td>-->
<!--            <td><input type="radio" name="notificacion" value="40" onclick="editor(3)"></td>
        </tr>-->
    </table>
</div>
<?php echo form_close(); ?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="formulario2"></div>
<script>
    $(".fecha").datepicker();
    $(".preload, .load").hide();
    $('#formulario1').dialog({
        autoOpen: true,
        width: 800,
        height: 620,
        modal: true,
        title: "Realizar Acta",
        close: function() {
            $('#formulario1 *').remove();
//            window.location.reload();
        }
    });
    function editor(dato) {
        $('#segundo_paso').hide();
        $(".preload, .load").show();
        $('#textarea2').show();
        var url = "<?php echo base_url('index.php/resolucion/tipo_documento') ?>";
        var id='<?php echo $consulta[0]['COD_RESOLUCION']; ?>';
        var concepto='<?php echo $consulta[0]['COD_CPTO_FISCALIZACION']; ?>';
        $.post(url, {id: id, dato: dato,concepto:concepto})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    msg = msg.split(" :: ");
                    tinyMCE.activeEditor.setContent(msg[1]);
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
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

    $('#enviar3').click(function() {
        var informacion = tinymce.get('informacion').getContent();
        if(informacion==""){
            alert('Campo Texto Vacio');
            return false;
        }
        var nit =<?php echo $consulta[0]['NITEMPRESA'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;
        $('#infor').val(informacion);
        $('#nombre').val(nombre_archivo);
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/resolucion/correspondencia_citacion_gestion') ?>";
        $.post(url, $('#myform').serialize())
                .done(function(msg) {
                    alert('Los Datos Fueron Agregados Con Exito');
                    window.location.reload();
                }).fail(function(msg) {
                    alert('ERROR');
                    $(".preload, .load").hide();
        })
        return false;
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