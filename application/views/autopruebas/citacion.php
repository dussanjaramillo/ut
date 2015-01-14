<!--<pre>
<?php //print_r($consulta) ?>
</pre>-->
<a name="arriba"></a>
<?php echo form_open("notificacionacta/resolucion_guardar", array("id" => "myform")); ?>
<div id="primario">
    <input type="hidden" id="cod_coordinador" name="cod_coordinador" value="<?php echo $consulta[0]['CEDULA_COORDINADOR'] ?>">
    <input type="hidden" id="autoriza" name="autoriza" value="<?php echo $consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] ?>">
    <input type="hidden" id="cod_resolucion" name="cod_resolucion" value="<?php echo $consulta[0]['COD_RESOLUCION'] ?>">
    <input type="hidden" id="nombre_archivo" name="nombre_archivo" value="<?php echo $consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis'] ?>">
    <input type="hidden" id="cod_planilla_citacion" name="cod_planilla_citacion" value="">
    <input type="hidden" id="resolucion" name="resolucion" readonly="readonly" value="<?php echo $consulta[0]['NUMERO_RESOLUCION'] ?>">
    <input type="hidden" id="fecha_resolucion" name="fecha_resolucion" readonly="readonly" value="<?php echo $consulta[0]['FECHA_CREACION'] ?>">
    <input type="hidden" id="fax" name="fax" value="<?php echo $consulta[0]['FAX'] ?>" onkeypress="soloNumero(this);">
    <table width="800px" border='0'>
        <tr>
            <td>C&oacute;digo Regional</td>
            <td><input type="text" id="cod_regional" name="cod_regional" readonly="readonly" value="<?php echo $consulta[0]['COD_REGIONAL'] ?>"></td>
            <td>Regional</td>
            <td><input type="text" id="regional" name="regional" readonly="readonly" value="<?php echo $consulta[0]['NOMBRE_REGIONAL'] ?>"></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n Regional</td>
            <td colspan="1"><input type="text" id="direccion_regional" readonly="readonly" name="direccion_regional" value="<?php echo $consulta[0]['DIRECCION_REGIONAL'] ?>"></td>
            <td>Ciudad</td>
            <td><input type="text" id="ciudad" name="ciudad" readonly="readonly" value="<?php echo $ciudad['NOMBREMUNICIPIO'] ?>"></td>
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <td>NIT</td>
            <td><input type="text" id="nit" name="nit" readonly="readonly" value="<?php echo $consulta[0]['NITEMPRESA'] ?>"></td>
            <td>Raz&oacute;n Social</td>
            <td><input type="text" id="razon_social" readonly="readonly" name="razon_social" value="<?php echo $consulta[0]['NOMBRE_EMPRESA'] ?>"></td>
        </tr>
        <tr>
            <td>Representante legal</td>
            <td><input type="text" id="representante" readonly="readonly" name="representante" value="<?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?>"></td>
            <td>Domicilio</td>
            <td colspan="1">
                <input type="text" id="domicilio" readonly="readonly" name="domicilio" value="<?php echo $consulta[0]['DIRECCION'] ?>">
            </td>
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <td>Coordinador</td>
            <td><input type="text" id="coordinador" readonly="readonly" name="coordinador" value="<?php echo $consulta[0]['COORDINADOR_REGIONAL'] ?>"></td>
        </tr>
        <tr> 
            <td>Proyect&oacute;</td>
            <td><input type="text" id="proyecto" name="proyecto" readonly="readonly" value="<?php echo $info_user; ?>"></td>

            <td>Revis&oacute;</td>
            <td><input type="text" id="reviso" name="reviso" readonly="readonly" value="<?php echo $info_user; ?>"></td>
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <td colspan="6" align="center"><button id="generar" class="btn btn-success">Generar Citaci&oacute;n</button></td>
        </tr>
    </table>
</div>
<div id="secundario" style="display: none">

    <table  border="0" style="border: 0px solid #000">

        <?php
//            echo $consulta[0]['COD_ESTADO'];
        if ($consulta[0]['COD_ESTADO'] == "51" || $consulta[0]['COD_ESTADO'] == "29") {
            ?>
            <tr>
                <td>Notificaci&oacute;n Personal</td>
                <td><input type="radio" id="radio1" class="radios" title="Obtener Plantilla" name="radio" value="33" onclick="cargar(1)"></td>
            </tr>
        <?php } else if ($consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "s" || $consulta[0]['AUTORIZA_NOTIFIC_EMAIL'] == "S" && $consulta[0]['COD_ESTADO'] == "34") { ?>
            <tr>
                <td>Notificaci&oacute;n Correo Electr&oacute;nico</td>
                <td><input type="radio" id="radio2" class="radios" title="Obtener Plantilla" name="radio" value="34" onclick="cargar(2)"></td>
            </tr>
        <?php } if ($consulta[0]['COD_ESTADO'] == "35") { ?>
            <tr>
                <td>Notificaci&oacute;n Por Aviso</td>
                <td><input type="radio" id="radio3" class="radios" title="Obtener Plantilla" name="radio" value="35" onclick="cargar(3)"></td>
            </tr>
        <?php } if ($consulta[0]['COD_ESTADO'] == "310") { ?>
            <tr>
                <td>Publicaci&oacute;n de la citaci&oacute;n Art. 68 C.P.A.C.A </td>
                <td><input type="radio" id="radio3" class="radios" title="Obtener Plantilla" name="radio" value="310" onclick="cargar(83)"></td>
            </tr>
        <?php } ?>
        <?php if ($consulta[0]['COD_ESTADO'] == "311") { ?>
            <tr>
                <td>Notificaci&oacute;n por aviso fijado</td>
                <td><input type="radio" id="radio3" class="radios" title="Obtener Plantilla" name="radio" value="311" onclick="cargar(89)"></td>
            </tr>
        <?php } ?>
        <?php if ($consulta[0]['COD_ESTADO'] == "410") { ?>
            <tr>
                <td>Notificaci&oacute;n Personal</td>
                <td><input type="radio" id="radio1" class="radios" title="Obtener Plantilla" name="radio" value="411" onclick="cargar(1)"></td>
            </tr>
        <?php } if ($consulta[0]['COD_ESTADO'] == "414") { ?>
            <tr>
                <td>Publicaci&oacute;n de la citaci&oacute;n Art. 68 C.P.A.C.A </td>
                <td><input type="radio" id="radio3" class="radios" title="Obtener Plantilla" name="radio" value="414" onclick="cargar(83)"></td>
            </tr>
        <?php } ?>
        <?php if ($consulta[0]['COD_ESTADO'] == "415") { ?>
            <tr>
                <td>Notificaci&oacute;n por aviso fijado</td>
                <td><input type="radio" id="radio3" class="radios" title="Obtener Plantilla" name="radio" value="415" onclick="cargar(89)"></td>
            </tr>
        <?php } ?>
    </table>
    <p><br>
    <div id="textarea" style="display: none">
        <table width="100%">
            <tr>
                <td>
                    <textarea id="informacion" style="width: 100%;height: 400px"></textarea>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <button id="enviar2" class="btn btn-success"><i class="fa fa-floppy-o"></i> Enviar</button>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php echo form_close(); ?>
<div id="resul"></div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<style>
    table tr td {
        padding-left: 10px;
    }
</style>
<script>
    $('#fax').validCampoFranz('0123456789');
    var confir = 0;
    function cargar(dato) {
        $('#resultado').dialog({
            width: 800,
            height: 600,
            title: 'Notificación/Citación',
        });
        var fax = $('#fax').val();
        var direccion = $('#call').val() + " " + $('#num1').val() + " # " + $('#num2').val() + " - " + $('#num3').val();
        var num_correo = $('#correo').length;
        var correo = (num_correo != 0) ? $('#correo').val() : "";
        $(".preload, .load").show();
        var concepto = '<?php echo $consulta[0]['COD_CPTO_FISCALIZACION'] ?>';
        var id = "<?php echo $consulta[0]['COD_RESOLUCION'] ?>";
        var url = "<?php echo base_url('index.php/autopruebas/tipo_documento') ?>";
//        alert(confir);
        if (confir == 1) {
            var r = confirm("Los cambios efectuados no han \n sido guardados. ¿Desea continuar?  ");
            if (r == true)
            {
                optener(id, concepto, dato, fax, direccion, correo, url);
                confir = 1;
            } else {
                $(".preload, .load").hide();
            }
        } else {
            optener(id, concepto, dato, fax, direccion, correo, url);
            confir = 1;
        }
    }
    function optener(id, concepto, dato, fax, direccion, correo, url) {
        $.post(url, {id: id, concepto: concepto, dato: dato, fax: fax, direccion: direccion, correo: correo})
                .done(function(msg) {
                    $(".preload, .load").hide();
                    msg = msg.split(" :: ");
                    $('#cod_planilla_citacion').val(msg[0]);
                    tinyMCE.activeEditor.setContent(msg[1]);
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
    }
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 500,
        modal: true,
        title: 'Notificación/Citación',
        close: function() {
            $('#resultado *').remove();
            window.location.reload();
        }
    });
    $('#generar').click(function() {
        var num_correo = $('#correo').length;
        if (num_correo != 0) {
            var email = $('#correo').val();
            var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!expr.test(email)) {
                alert("Error: La dirección de correo " + email + " es incorrecta.");
                return false;
            }
        }
        $('#primario').hide();
        $('#secundario').show();
        $('#resultado').dialog({
            width: 400,
            height: 120,
        });
        return false;
    });
    $('.radios').click(function() {
        $('#textarea').show();
        $('#informacion').focus();
    });

    $('#enviar2').click(function() {
        $(".preload, .load").show();
        var num_resolucion = $('#resolucion').val();
        var cod_fis = $('#cod_fis').val();
        var id = "<?php echo $consulta[0]['COD_RESOLUCION'] ?>";
        var url = "<?php echo base_url('index.php/autopruebas/guardar_archivo') ?>";
        var informacion = tinymce.get('informacion').getContent();
        var nit = '<?php echo $consulta[0]['NITEMPRESA'] ?>';
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;
        $.post(url, {informacion: informacion, id: id, nombre: nombre_archivo, num_resolucion: num_resolucion, cod_fis: cod_fis, nit: nit})
                .done(function(msg) {
                    var url = "<?php echo base_url('index.php/autopruebas/guardar_citacion') ?>";
                    $('#nombre_archivo').val(nombre_archivo);
                    $.post(url, $("form").serialize())
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
                });
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
    function soloNumero(e) {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return false;
//        return /\d/.test(String.fromCharCode(keynum));
//return false;
    }
</script>
<a name="abajo"></a>
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