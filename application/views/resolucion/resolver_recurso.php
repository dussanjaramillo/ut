<div id="tabla_ini">
    <table width="100%" style="padding: 10px;">
        <tr>
            <?php if(empty($post['nro_recurso'])){ ?>
            <td>Recurso</td>
            <td><input type="radio" id="recurso" name="detalle" value="recurso" onclick="recurso()"></td>
            <?php } ?>
        </tr>
        <!--<tr>
            <td>Pago</td>
            <td><input type="radio" id="pago" name="detalle" value="pago"></td>
        </tr>-->
        <tr>
            <td>Ejecutoria</td>
            <td><input type="radio" id="ejecutoria" name="detalle" value="ejecutoria" onclick="ejecutoria()"></td>
        </tr>
    </table>
</div>

<div id="radicado" style="display: none">
    <?php echo form_open("resolucion/Recurso_resolucion", array("id" => "recursos")); ?>
    <input type="hidden" id="id_resolucion" name="id_resolucion" value="<?php echo $consulta[0]['COD_RESOLUCION']; ?>">
    <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $post['cod_fis']; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $post['id']; ?>">
    <input type="hidden" id="nit" name="nit" value="<?php echo $post['nit']; ?>">
    <table>
        <tr>
            <td>Numero de Radicado</td>
            <td><input type="text" id="num_radicado" name="num_radicado" maxlength="20"></td>
            <td>Fecha</td>
            <td><input type="text" id="fecha" name="fecha" readonly="readonly" value="<?php echo date('d/m/Y') ?>"></td>
        </tr>
        <tr>
            <td>Tipo Documento</td>
            <td><select id="docuemtnto" name="documento">
                    <?php foreach ($tipodocumento as $documento) { ?>
                        <option value="<?php echo $documento['CODTIPODOCUMENTO']; ?>"><?php echo $documento['NOMBRETIPODOC']; ?></option>
                    <?php } ?>
                </select></td>
            <td>Numero de identificaci&oacute;n</td>
            <td><input type="text" id="num_iden" name="num_iden" maxlength="20"></td>
        </tr>
        <tr>
            <td>Nombre a quien Representa</td>
            <td><input type="text" id="nombre" name="nombre" maxlength="20" value="<?php echo $consulta[0]['REPRESENTANTE_LEGAL']; ?>"></td>
            <td>NIS</td>
            <td><input type="text" id="nis" name="nis" maxlength="20" value=""></td>
        </tr>

        <tr>
            <td>Documentos</td>
            <td></td>
        </tr>
    </table>
    <div id="archivos">
        <p>&nbsp;</p>
    </div>
    
    <?php echo form_close(); ?>
<form action="" method="post" id="uploadFile">
    <table class="ajax-file-upload-green">
            <tr>
                <td>
                    <input type="file" name="userFile" id="userFile" size="20" required="required"/>
                </td>
                <td>
                    <input type="submit" class="btn btn-success" value="Carga" id="cargue" />
                </td>
            </tr>
        </table>
    </form>
</div>

<center><button class="ajax-file-upload-green btn btn-success" id="enviar_form2">Enviar</button></center>

<script>
    $('#num_radicado').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#num_iden').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#nis').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#nombre').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#uploadFile').submit(function(e) {
        e.preventDefault();
        var id = "<?php echo $consulta[0]['COD_RESOLUCION']; ?>";
        var name = $('#userFile').val();
        jQuery(".preload, .load").show();
        var doUploadFileMethodURL = "<?= site_url('resolucion/doUploadFile'); ?>?id=" + <?php echo $consulta[0]['COD_RESOLUCION']; ?>;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                $(".preload, .load").hide();
                $('#archivos p').append('<div class="archivo" style="width:100px" ><input type="hidden" name="docuemtos[]" value="' + data.nombre_archivo + '">' + data.nombre_archivo + '</div>');
                $('#userFile').val('');
            },
            error: function(data) {
                alert("El archivo no se ha podido cargar, el formato puede no ser valido");
                jQuery(".preload, .load").hide();
            }
        });

        return false;
        //                    return false;
    });




    cod_fis = "<?php echo $post['cod_fis']; ?>";
    nit = "<?php echo $post['nit']; ?>";

    $('#enviar_form2').click(function() {
        var r = confirm("Esta Seguro de Enviar la Información Suministrada?")
        if (r == false)
            return false;
        $(".preload, .load").show();
        if ($('#num_radicado').val() == "" || $('#num_iden').val() == "") {
            alert('Datos Incompletos');
            $(".preload, .load").hide();
            return false;
        }
        var url5 = "<?php echo base_url("index.php/resolucion/Recurso_resolucion"); ?>";
        $.post(url5, $('#recursos').serialize())
                .done(function(resul) {
                    alert('Los Datos Fueron Ingresados Con Exito');
                    window.location.reload();
                }).fail(function(error) {
            alert('Error Base de Datos ');
            $(".preload, .load").hide();
        })
        return false;
    });
    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 400,
        title: "Gestión",
        modal: true,
        close: function() {
            $('#resultado *').remove();
//            window.location.reload();
        }
    });
    $('#num_iden').keypress(function(e) {
        var num = $('#num_iden').val();
        if (isNaN(num)) {
            $('#num_iden').val('');
        }
    });
    $('#num_iden').change(function(e) {
        var num = $('#num_iden').val();
        if (isNaN(num)) {
            $('#num_iden').val('');
        }
    })
    function recurso() {
        $('#tabla_ini').hide();
        $('#radicado').show();
        $('#resultado').dialog({
            autoOpen: true,
            width: 800,
            title: "Datos del recurso",
//            height: 680,
        })
    }
    function ejecutoria() {
        $(".preload, .load").show();
        var id = "<?php echo $consulta[0]['COD_RESOLUCION']; ?>";
        var url5 = "<?php echo base_url("index.php/resolucion/Recurso_ejecutoria"); ?>";
        $.post(url5, {id: id, cod_fis: cod_fis, nit: nit})
                .done(function(resul) {
                    alert('Los Datos Fueron Ingresados Con Exito');
                    window.location.reload();
                })
                .fail(function(error) {
                    $(".preload, .load").hide();
                    alert('Error Base de Datos ');
                });
    }
</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />