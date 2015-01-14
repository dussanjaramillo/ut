<div class="Gestion" style="background: #f0f0f0;  width: 95%; margin: auto; overflow: hidden">
    <br>
    <div style="width: 95%; margin: auto; overflow: hidden;alignment-adjust: center">
        <table width="100%" border="0" id="tabla_inicial">
            <tr><td colspan="2">Proceso</td><td colspan="2"   style="color: red;text-align:left;" ><?php echo $consulta[0]['PROCESOPJ'] ?></td></tr>
            <tr >
                <td >
                    <label for="nit">Identificaci&oacute;n:</label>
                </td>
                <td class="color">
                    <?php echo $consulta[0]['CODEMPRESA'] ?>
                </td>
                <td>
                    <label for="nit">Ejecutado:</label> 
                </td>
                <td class="color">
                    <?php echo $consulta[0]['NOMBRE_EMPRESA'] ?>
                </td>
            </tr>
            <tr  >
                <td colspan="1">
                    <label for="representante">Representante Legal:</label> 
                </td>
                <td colspan="1" class="color">
                <?php echo $consulta[0]['REPRESENTANTE_LEGAL'] ?>
                </td><!--
                -->
                     <td>
                         <label for="concepto">Concepto:</label> 
                     </td>
                     <td class="color">
                <?php echo $consulta[0]['NOMBRE_CONCEPTO'] ?>
                     </td>
            </tr>
            <tr  >
                <td colspan="1">
                    <label for="valor">Estado</label> 
                </td>
                <td colspan="1" class="color">
                <?php echo $consulta[0]['RESPUESTA'] ?>
                </td>
                <td>
                    <label for="ciudad">Ciudad:</label> 
                </td>

                <td class="color">
                    <?php echo $consulta[0]['NOMBREMUNICIPIO'] ?>
                </td>
            </tr>
            <tr  >
<!--                <td>
                    <label for="fecha">Fecha Vencimiento Liquidaci&oacute;n:</label> 
                </td>
                <td class="color">
                <?php echo $consulta[0]['FECHA_VENCIMIENTO'] ?>
                </td>-->
                <td colspan="1">
                    <label for="valorletra">Direccion</label> 
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
                            'class' => 'btn btn-success'
                        );

                        echo form_button($data);
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<p>
<div id="texto" >

    <table width="100%">
        <tr><td><div id="div_encabezado" style="display:none ">
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
                </div></td></tr>
        <tr>
            <td>
                <textarea id="informacion" style="width: 100%;height: 400px"><?php echo $documento; ?></textarea>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button  class='pdf btn btn-success addpagobtn'>PDF</button>
                <?php if(ID_USER==ID_SECRETARIO): ?>
                 <button id="enviar" class='btn btn-success'>Pre Aprobar</button>
                 <?php else:?> <button id="enviar" class='btn btn-success'>Aprobar</button> <?php endif;?> 
               
                <button id="atras" class='btn btn-success'>Devoluci&oacute;n</button>
            </td>
        </tr>
    </table>
</div>   

<div id="observa" style="border-radius: 15px;display: none">
    <center>Observaciones</center>
    <div>
        <textarea id="obser" onkeyup="presion();" style="width: 100%;" maxlength="140" size="140"></textarea>
        <p>Caracteres disponibles:<span id="cantidad">140</span></p>
    </div>
    <div align="center">
        <button id="enviar_obser" class='btn btn-success'>Devolver</button>
        <button  class='atras2 btn btn-success'>Atras</button>
    </div>

</div>
<table width="100%">
    <tr>
        <td>
            <?php if ($traza != '') { ?>
                <b>Observaciones Anteriores</b><p>
                <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $traza; ?>
        </td>
    </tr>
</table>


<form id="imprimir_pdf" target="_new" action="<?php echo base_url('index.php/mcinvestigacion/imprimir_pdf') ?>" method="post">
    <textarea id="infor_pdf" name="infor_pdf" style="display: none"></textarea>
    <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
    <input type="hidden" name="titulo_doc" id="titulo_doc" >
</form>

<script>
$(document).ready(function() {
   
});
function  ajaxValidationCallback(){}

    function presion()
    {
        var canti = document.getElementById('obser').value.length;
        var disponibles = 140 - parseInt(canti);
        document.getElementById('cantidad').innerHTML = disponibles;
    }

    $('.pdf').click(function() {
        var informacion = tinymce.get('informacion').getContent();
        var titulo = $("#Titulo_Encabezado").val();
        $("#titulo_doc").val(titulo);
        var cod_respuesta = '<?php echo $post['cod_siguiente'] ?>';
        
        if (cod_respuesta == '277' || cod_respuesta == '204' ) {
            $("#tipo_documento").val('1');
        }

        $('#infor_pdf').val(informacion);
        $('#imprimir_pdf').submit();
    });
    var cod_siguiente_prelacion = "<?php echo $post['cod_siguiente_prelacion'] ?>";
    var id_prelacion = "<?php echo $post['id_prelacion'] ?>";
    var id = "<?php echo $post['id'] ?>";
    var nit = "<?php echo $post['nit'] ?>";
    var id_mc = "<?php echo $post['id_mc'] ?>";
    var cod_siguiente = "<?php echo $post['cod_siguiente'] ?>";
    var cod_fis = "<?php echo $post['cod_fis'] ?>";
    var respuesta = "<?php echo $post['respuesta'] ?>";
    var titulo = "<?php echo $post['titulo'] ?>";
    var tipo_doc = "<?php echo $post['tipo_doc'] ?>";



    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 900,
        height: 450,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('#enviar_obser').click(function() {
        var url = "<?php echo base_url('index.php/mcinvestigacion/guardar_trazabilidad') ?>";
        var devol = "<?php echo $post['devol'] ?>";

        var obser = $('#obser').val();
        if (obser == "") {
            alert('Datos Incompletos');
            return false;
        }
        jQuery(".preload, .load").show();
        $.post(url, {id: id, nit: nit, cod_fis: cod_fis, tipo_doc: tipo_doc, infor: obser, devol: devol, respuesta: respuesta, titulo: titulo,
            cod_siguiente_prelacion: cod_siguiente_prelacion, id_prelacion: id_prelacion})
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        });
    })
    $("#infor").click(function() {
        $('#tabla_inicial').hide();
        $('#texto').show();
    });
    $('#atras').click(function() {
        $('#texto').hide();
        $('#observa').show();
    });
    $('.atras2').click(function() {
        $('#observa').hide();
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
    $('#enviar').click(function() {
        $(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/update_Mc_medidas_cautelarias') ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + nit;

        var nom_documento = "<?php echo $nom_documento ?>";
        if (nom_documento != 'no_existe.txt') {
            nom_documento = nom_documento.split('.');
            nombre_archivo = nom_documento[0];
        }

        var informacion = tinymce.get('informacion').getContent();
        $.post(url, {tipo_doc: tipo_doc, titulo: titulo, nombre: nombre_archivo, nit: nit, respuesta: respuesta, id: id, cod_fis: cod_fis, informacion: informacion,
            cod_siguiente: cod_siguiente, id_mc: id_mc, cod_siguiente_prelacion: cod_siguiente_prelacion, id_prelacion: id_prelacion})
                .done(function() {
                    alert('Los Datos Fueron Guardados Con Exito');
                    window.location.reload();
//$(".preload, .load").hide();
                }).fail(function() {
            $(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        })
    })
</script>
<style>
    .color{
        /*color: #FC7323;*/
        font:bold 12px;
    }
    #tabla_inicial{
        border-radius: 50px;
        border: 0px solid #CCC;
    }
    #tabla_inicial tr td{
        margin: 20px;
    }
</style>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />