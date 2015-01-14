<p>
<center><h1><?php echo $titulo; ?></h1></center>
<p><br>
<p><br>
<form action="" method="post" id="uploadFile">
    <input type="hidden" name="vista" id="vista" value="<?php echo $vista; ?>">
    <input type="hidden" name="name_reporte" id="name_reporte" value="<?php echo $titulo; ?>">
    <table width="90%" border="0" style="border:1px solid #000">
        <thead style="background-color: #fc7323;color: #FFF">
        <th>Adjuntar</th>
        <th>Subir Archivo</th>
        <th>Documento a Consultar</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="file" name="userFile" id="userFile" size="20" required="required"/>
                </td>
                <td align="center">
                    <input type="submit" value="Carga" class="btn btn-success" id="cargue" />
                </td>
                <td align="center">
                    <div id="archivo">No hay datos adjuntos</div>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<p>
<form id="form1" action="<?php echo base_url('index.php/reporteador/imprimir_certificacion') ?>" method="post" onsubmit="return confirmar()">
    <input type="hidden" id="archivos_nuevos" name="archivos_nuevos">
    <table width="300px" border="0" style="margin: 0 auto;">
        <?php if ($input == 2 || $input == 6 || $input == 3 || $input == 18 || $input == 5) { ?>
            <tr>
                <td>
                    Año
                </td>
                <td align="center" colspan="2">
                    <select name="ano" id="ano">
                        <?php
                        $ano = date('Y');
                        for ($i = 0; $i < 5; $i++) {
                            ?>
                            <option value="<?php echo $ano - $i ?>"><?php echo $ano - $i ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td align="center" colspan="2">
                <input type="hidden"  id="accion" name="accion" value="<?php echo $input; ?>">
                <button class="btn btn-success">Generar</button>
            </td>
        </tr>
    </table>
</form>
<script>
    function eliminar() {
        $('#archivo').html('');
        $('#archivos_nuevos').val('');
        $('#userFile').val('');
    }
    $('#uploadFile').submit(function(e) {
        e.preventDefault();
        var id = "1";
        var name = $('#userFile').val();
        if (name == "") {
            return false;
        }
        jQuery(".preload, .load").show();
        var doUploadFileMethodURL = "<?= site_url('reporteador/doUploadFile'); ?>?id=" + id + "&name=" + name;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
//                console.log(data);
                jQuery(".preload, .load").hide();
                $('#archivo').html(data.message + "     <button class='btn' onclick='eliminar()'><i class='fa fa-trash-o'></i></button>");
                $('#archivos_nuevos').val(data.ruta);
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
    function confirmar() {
        if ($('#archivos_nuevos').val() == '') {
            alert('No hay Archivo a Consultar')
            return false;
        } else
            return true;
    }

    $("#empresa").autocomplete({
        source: "<?php echo base_url("index.php/reporteador/autocompleteemrpesas") ?>",
        minLength: 3,
        search: function(event, ui) {
            $("#preloadmini").show();
        },
        response: function(event, ui) {
            $("#preloadmini").hide();
        }
    });
    $("#preloadmini").hide();
</script>