<?php
// Responsable: Leonardo Molina
$DataRadio = array('id' => 'resp1', 'name' => 'resp1', 'onclick' => 'contesta()', 'value' => '1');
$DataRadio1 = array('id' => 'resp2', 'name' => 'resp1', 'checked' => 'true', 'onclick' => 'nocontesta()', 'value' => '0');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
$button = array('name' => 'button', 'id' => 'submit-button', 'value' => 'Confirmar', 'type' => 'submit', 'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar', 'class' => 'btn btn-success');
$nejec = array('name' => 'neject', 'type' => 'hidden', 'value' => $numejec);
?>
<h1>Voluntad de Pago</h1>

    <?= form_open_multipart("consultarcarteraycapacitacion/addVoluntadPago", $attributes); ?>
<div style="display: none">
    <div>
        <p>Seleccione una opción según sea el caso.</p>
        <p>
            <?= form_radio($DataRadio) . ' Capacitación'; ?>
            <?= form_radio($DataRadio1) . ' NO Capacitación'; ?>
        </p>
    </div>
</div>
<br>

<div style="display: none" id="divalegato">
    <div class="modal-footer">
        <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
        <?= form_button($button2); ?>
    </div>
</div>

<div style="display: none" id="divresp">
    <div>
        <div id="subirFile" style="float: right; position: relative; width: 50%">
            <div id="file_source"></div>
            <div class="input-append" id="arch0">
                <div >
                    <input type="file" name="archivo0" id="archivo" class="btn btn-primary file_uploader">
                </div>
            </div>
            <span class="btn-info">Seleccione el documento de la capacitación</span>
        </div>
        <table>
            <tr>
                <td></td>
                <td><h4>Seleccione voluntad de pago:</h4></td>
            </tr>
            <tr>
                <td><div style="display: none"><?= form_radio('resp', 'total', false) ?></div></td>
                <td style="width: 100px; "><div style="display: none">Total</div></td>

            </tr>
            <tr>
                <td><?= form_radio('resp', 'acuerdo', false) ?></td>
                <td style="width: 300px; ">Facilidad de pago</td>
                <td style="width: 50px; "></td>
            </tr>
            <tr>
                <td><?= form_radio('resp', 'ninguna', false) ?></td>
                <td style="width: 100px;">Envio Cobro Coactivo</td>
                <td></td>  
            </tr>

        </table>

    </div>

    <div class="modal-footer">
        <?= form_input($nejec) ?>
        <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
        <?= form_button($button); ?>
    </div>


</div>


<?= form_close() ?>


<script>

    function contesta() {
        $("#subirFile").css("display", "none");
        $('#subirFile').toggle("slow");
        $("#divresp").css("display", "none");
        $('#divresp').toggle("slow");
    }
    function nocontesta() {
        $("#divresp").css("display", "block");
        $('#divresp').toggle("slow");
        $("#divresp").css("display", "none");
        $('#divresp').toggle("slow");
        $("#subirFile").css("display", "block");
        $('#subirFile').toggle("slow");

    }


    // funcion para subir varios archivos al servidor
    (function() {
        jQuery("#addImg").on('click', addSource);
    })();

    function addSource() {
        var numItems = jQuery('.file_uploader').length;
        var template = '<div  class="field" id="arch' + numItems + '">' +
                '<div class="input-append">' +
                '<div >' +
                '<input type="file" name="archivo' + numItems + '" id="archivo' + numItems + '"class="btn btn-primary file_uploader">' +
                ' <button type="button" class="close" id="' + numItems + '" onclick="deleteFile(this.id)">&times;</button>' +
                '</div>' +
                '</div>' +
                '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }
    function deleteFile(elemento) {
        $("#arch" + elemento).fadeOut("slow", function() {
            $("#arch" + elemento).remove();
        });

    }
    function comprobarextension() {
        if ($("#archivo").val() != "") {
            var archivo = $("#archivo").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensión de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensión está entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#archivo").val("");
                mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        } else {
            if ($("input[name='resp']:checked").val() == 1)
                alert("Debe seleccionar un archivo");
        }
    }
    $('#cancelar').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });
    $('.close').on('click', function() {
        $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
        $('#modal').modal('hide').removeData();
    });

    nocontesta();

</script>

