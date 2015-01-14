<div class="Gestion" style="background: #f0f0f0;  width: 98%; margin: auto; overflow: hidden">
    <br>
    <div style="width: 95%; margin: auto; overflow: hidden;alignment-adjust: center">
        <table width="100%" border="0" id="tabla_inicial">
            <tr><td colspan="2">Proceso</td><td colspan="2"  style="color: red"><?php echo $consulta[0]['PROCESOPJ'] ?></td></tr>
            <tr >
                <td >
                    <label for="nit">Identificaci&oacute;n Ejecutado:</label>
                </td>
                <td class="color">
                    <?php echo $consulta[0]['CODEMPRESA'] ?>
                </td>
                <td>
                    <label for="nit">Nombre Ejecutado:</label> 
                </td>
                <td class="color">
                    <?php echo $consulta[0]['NOMBRE_EMPRESA'] ?>
                </td>

                
            </tr>
            <tr  >
                <td>
                    <label for="concepto">Concepto:</label> 
                </td>
                <td class="color">
                <?php echo $consulta[0]['NOMBRE_CONCEPTO'] ?>
                </td>
                <td colspan="1">
                    <label for="representante">Representante Legal:</label> 
                </td>
                <td colspan="1" class="color"><!--
                <?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?>
-->                </td>
            <div style="display: none">
                <select id="secretario" name="secretario" style="display: none">
                    <?php
                    $datos = Mcinvestigacion_model::secretario();
                    foreach ($datos as $secretario) {
                        echo "<option value='" . $secretario['IDUSUARIO'] . "'>" . $secretario['APELLIDOS'] . " " . $secretario['NOMBRES'] . "</option>";
                        $in = $secretario['APELLIDOS'] . " " . $secretario['NOMBRES'];
                    }
                    ?>
                </select>
                <?php echo $in; ?>
            </div>
            <!--</td>-->
            </tr>
            <tr  >
                <td>
                    <label for="ciudad">Ciudad:</label> 
                </td>
                <td class="color">
                    <?php echo $consulta[0]['NOMBREMUNICIPIO'] ?>
                </td>
                <td colspan="1">
                    <label for="valorletra">Direcci&oacute;n</label> 
                </td>
                <td colspan="1" class="color">
                    <?php echo $consulta[0]['DIRECCION']; ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" >
                    <div  style="text-align: center;display: none">
                        <?php
                        $data = array(
                            'name' => 'button',
                            'id' => 'infor',
                            'value' => 'Generar',
                            'type' => 'submit',
//            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Generar',
                            'content' => 'Generar',
                            'class' => 'btn btn-primary addpagobtn'
                        );

                        echo form_button($data);
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="texto" >
    <table width="100%">
        <tr>
        <tr><td> <div id="div_encabezado" style="display:none ">
                <table width="100%" border="0" align="center">
                    <tr>
                        <td width="23%" rowspan="2"><img src="<?php echo base_url('img/Logotipo_SENA.png') ?>" width="200" height="200" /></td>
                        <td width="44%" height="94"><div align="center"><h4><b></b></h4></div></td>
                    </tr>
                    <tr>
                        <td height="50" colspan="2"><div align="center">
                                <input name="Titulo_Encabezado" type="text" id="Titulo_Encabezado" class="input-xxlarge" size="100" />
                            </div></td>
                    </tr>
                </table> 
                </div>
            </td></tr>

        <td>
            <textarea id="informacion" style="width: 100%;height: 400px"><?php echo $documento; ?></textarea>
        </td>
        </tr>
        <tr>
            <td align="center">
                <button  class='pdf btn btn-success addpagobtn'>PDF</button>
                <button  class='enviar2 btn btn-success addpagobtn'>Guardar Temporal</button>
                <button  class='enviar btn btn-success addpagobtn'>Enviar</button>
                <?php if ($traza != '') { ?>
                    <button id="atras" class='btn btn-success addpagobtn'>Observaciones</button>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>
<div id="observa" style="border-radius: 15px;display: none">
    <center>Observaciones</center>
    <div>
        <textarea id="obser" style="width: 100%;"></textarea>
    </div>
    <div align="center">
        <button  class='enviar btn btn-success addpagobtn'>Enviar</button>
        <button  class='atras2 btn btn-success addpagobtn'>Atras</button>
    </div>

</div>
<table width="100%">
    <tr>
        <td>
            <?php echo $traza; ?>
        </td>
    </tr>
</table>

<form id="imprimir_pdf" target = "_blank" action="<?php echo base_url('index.php/mcinvestigacion/imprimir_pdf') ?>" method="post" >
    <textarea id="infor_pdf" name="infor_pdf" style="display: none"></textarea>
    <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
    <input type="hidden" name="titulo_doc" id="titulo_doc" >

</form>

<script>

$(document).ready(function() {
     var cod_respuesta = '<?php echo $post['cod_siguiente'] ?>';
       if (cod_respuesta == '204' || cod_respuesta == '277') {
            $("#tipo_documento").val('1');
        }
});

    $('.pdf').click(function() {
        var titulo = $("#Titulo_Encabezado").val();
        $("#titulo_doc").val(titulo);
        var informacion = tinymce.get('informacion').getContent();
        $('#infor_pdf').val(informacion);
        $('#imprimir_pdf').submit();
    });

    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
        heigth: 500,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#atras').click(function() {
        $('#texto').hide();
        $('#observa').show();
    });
    $('.atras2').click(function() {
        $('#observa').hide();
        $('#texto').show();
    });
    $("#infor").click(function() {
        $('#tabla_inicial').show();
        $('#texto').show();
    });
    tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //    "insertdatetime media nonbreaking save table contextmenu directionality",
                    //  "emoticons template paste textcolor moxiemanager"
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
    $('.enviar').click(function() {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/guardar_Mc_medidas_cautelarias') ?>";
        var nit = "<?php echo $consulta[0]['CODEMPRESA'] ?>";
        var id = "<?php echo $consulta[0]['COD_MEDIDACAUTELAR'] ?>";
        var cod_siguiente = "<?php echo $post['cod_siguiente'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var respuesta = "<?php echo $post['respuesta'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var cod_fis = "<?php echo $post['cod_fis'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var titulo = "<?php echo $post['titulo'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var tipo_doc = "<?php echo $post['tipo_doc'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var id_prelacion = "<?php echo $post['id_prelacion'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var cod_siguiente_prelacion = "<?php echo $post['cod_siguiente_prelacion'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;
        var secretario = $('#secretario').val();
        var obser = $('#obser').val();

        var nom_documento = "<?php echo $nom_documento ?>";
        if (nom_documento != 'no_existe.txt') {
            nom_documento = nom_documento.split('.');
            nombre_archivo = nom_documento[0];
        }

        var temporal = "1";
//        alert(obser);
//        return false;
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == "") {
            alert("Datos Incompletos");
            jQuery(".preload, .load").hide();
            return false;
        }
        $.post(url, {temporal: temporal, obser: obser, tipo_doc: tipo_doc, id_prelacion: id_prelacion, cod_siguiente_prelacion: cod_siguiente_prelacion, titulo: titulo, cod_fis: cod_fis, nombre: nombre_archivo, secretario: secretario, id: id, nit: nit, informacion: informacion, cod_siguiente: cod_siguiente, respuesta: respuesta})
                .done(function() {
//                    alert();
//                    jQuery(".preload, .load").hide();
                    alert('Los Datos Fueron Guardados Con Exito');
//                    jQuery(".preload, .load").hide();
                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function(msg) {
//                    alert(msg);
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        })
    });
    $('.enviar2').click(function() {

        var url = "<?php echo base_url('index.php/mcinvestigacion/guardar_Mc_medidas_cautelarias') ?>";
        var nit2 = "<?php echo $consulta[0]['CODEMPRESA'] ?>";
        var nit = "3eeee3eeeee3";
        var id = "<?php echo $consulta[0]['COD_MEDIDACAUTELAR'] ?>";
        var cod_siguiente = "<?php echo $post['cod_siguiente'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var respuesta = "<?php echo $post['respuesta'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var cod_fis = "<?php echo $post['cod_fis'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var titulo = "<?php echo $post['titulo'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var tipo_doc = "<?php echo $post['tipo_doc'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var id_prelacion = "<?php echo $post['id_prelacion'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var cod_siguiente_prelacion = "<?php echo $post['cod_siguiente_prelacion'] ?>";//id: id,titulo:titulo,cod_siguiente:cod_siguiente
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit2;
        var secretario = $('#secretario').val();
        var obser = $('#obser').val();
        var temporal = "temporal";
//        alert(nom_documento);
//        return false
        $(".preload, .load").show();
        var nom_documento = "<?php echo $nom_documento ?>";
        if (nom_documento != 'no_existe.txt') {
            nom_documento = nom_documento.split('.');
            nombre_archivo = nom_documento[0];
        }
//        alert(obser);
//        return false;
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == "") {
            alert("Datos Incompletos");
            jQuery(".preload, .load").hide();
            return false;
        }
        $.post(url, {temporal: temporal, obser: obser, tipo_doc: tipo_doc, id_prelacion: id_prelacion, cod_siguiente_prelacion: cod_siguiente_prelacion, titulo: titulo, cod_fis: cod_fis, nombre: nombre_archivo, secretario: secretario, id: id, nit: nit, informacion: informacion, cod_siguiente: cod_siguiente, respuesta: respuesta})
                .done(function() {
//                    alert();
//                    jQuery(".preload, .load").hide();
                    alert('Los Datos Fueron Guardados Con Exito');
                    jQuery(".preload, .load").hide();
//                    window.location.reload();
//jQuery(".preload, .load").hide();
                }).fail(function(msg) {
//                    alert(msg);
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        })
    });
    function ajaxValidationCallback()
    {

    }
</script>
<style>
    .color{
        /*color: #FC7323;*/
        font:bold 12px;
    }

</style>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />