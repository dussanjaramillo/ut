<?php
$tipdoc_revpro = array('name' => 'tipdoc_revpro', 'class' => 'search-query', 'id' => 'tipdoc_revpro', 'value' => @$regimen->NOMBRETIPODOC, 'readonly' => 'true');
$razsoc_revpro = array('name' => 'razsoc_revpro', 'class' => 'search-query input-xxlarge', 'id' => 'razsoc_revpro', 'value' => @$regimen->RAZON_SOCIAL, 'readonly' => 'true');
$nit_revpro = array('name' => 'nit_revpro', 'class' => 'search-query', 'id' => 'nit_revpro', 'value' => @$regimen->NITEMPRESA, 'readonly' => 'true');
$instan_revpro = array('name' => 'instan_revpro', 'class' => 'search-query', 'id' => 'instan_revpro', 'readonly' => 'true', 'value' => @$regimen->COD_REGIMENINSOLVENCIA);
$titulo_asigabo = array('text' => 'titulo_remdoc', 'class' => 'search-query', 'id' => 'titulo_remdoc', 'readonly' => 'true', 'value' => $regimen->COD_RECEPCIONTITULO, 'class' => 'search-query');
$numexp_revpro = array('name' => 'numexp_revpro', 'class' => 'search-query', 'id' => 'numexp_revpro', 'type' => 'hidden', 'value' => @$regimen->NUM_PROCESO);
$promot_revpro = array('name' => 'promot_revpro', 'class' => 'search-query', 'id' => 'promot_revpro', 'min' => 1, 'max' => 50, 'required' => 'true');
$telefo_revpro = array('name' => 'telefo_revpro', 'class' => 'search-query', 'min' => 1, 'max' => 9999999999, 'id' => 'telefo_revpro', 'required' => 'true', 'type' => 'number');
$adjunt_revpro = array('name' => 'adjunt_revpro', 'class' => 'search-query', 'id' => 'adjunt_revpro', 'type' => 'file');
$opcion_revpro = array('name' => 'opcion_revpro', 'id' => 'opcion_revpro', 'type' => 'radio', 'value' => 'S', 'required' => 'true');
$opcion1_revpro = array('name' => 'opcion_revpro', 'id' => 'opcion_revpro', 'type' => 'radio', 'value' => 'N', 'required' => 'true');
$button = array('name' => 'acepta_revpro', 'id' => 'acepta_revpro', 'type' => 'submit', 'value' => 'Aceptar', 'content' => '<i class=""></i> Aceptar', 'class' => 'btn btn-success btn1');
$button1 = array('name' => 'cancel_revpro', 'id' => 'cancel_revpro', 'value' => 'Cancelar', 'content' => '<i class=""></i> Cancelar', 'class' => 'btn btn-warning btn1', 'onclick' => 'window.location=\'' . base_url() . 'index.php/insolvencia\'');
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
?>
<div style="display: none" id="divcarg">  


    <br><br><br><br>
    <div class="modal-footer">
        <a href="#" class="btn btn-default cancelar" data-dismiss="modal" >Cancelar</a>
<?= form_button($button); ?>            
    </div>

</div>  
<div style="max-width: 895px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?=
form_open_multipart("insolvencia/guardar_revisar_proyeto", $attributes);
echo form_hidden($titulo_asigabo);
?>
    <input type="hidden" id='num_fisca' name='num_fisca' value='<?= @$regimen->COD_FISCALIZACION ?>' readonly>
    <div align="center"><?= $titulo_encabezado ?></div>
    <table >
        <tr>
            <td>Tipo de Documento</td><td><?= form_input($tipdoc_revpro) ?><?= form_input($numexp_revpro) ?></td>
            <td>Razón Social</td><td><?= form_input($razsoc_revpro) ?></td>
        </tr>
        <tr>
            <td>Nit</td><td><?= form_input($nit_revpro) ?></td>
            <td>Instancia</td><td><?= form_input($instan_revpro) ?></td>
        </tr>
        <tr>
            <td> </td>
        </tr>
    </table>
    <div id="div-border" style="border: 1px solid #ccc;width:100%;"></div>
    <table cellpadding="5%">
        <tr>
            <td>
                <table cellpadding="5%">
                    <tr>
                            <td><?= form_label('Promotor/Liquidador', 'infor'); ?></td>
                            <td>
<!--                                <select name="infor" id="infor" required>

                                        <option value=''>--Seleccione--</option>
                                        <?php foreach ($usuarios as $row) {
                                            echo '<option value="' . $row->IDUSUARIO . '">' . $row->NOMBRES . ' ' . $row->APELLIDOS . '</option>';
                                        }
                                        ?>
                                </select>-->
                                <?= form_input($promot_revpro);?>
                            </td>
                    </tr>
            <!--        <tr>
                       <td>Promotor</td><td><?= form_input($promot_revpro) ?></td> 
                    </tr>-->
                    <tr>
                        <td>Adjuntar</td><td><div id="subirFile" style="margin-left: 50%;float: right; ">

<!--            <p><span id="addImg" class="btn btn-info"><i class="fa fa-cloud-upload"></i> Agregar mas...</span></p>-->
                                <div class="input-append" id="arch0">
                                    <div >
                                        <input type="file" name="filecolilla" id="filecolilla" required="true">
                                    </div>
                                </div>
                                <div id="file_source"></div>
                            </div></td>
                    </tr>
                </table>
            </td>


            <td>
                <table>
                    <tr>
                        <td>Teléfono</td><td><?= form_input($telefo_revpro) ?></td> 
                    </tr>
                    <tr>
                        <td>Bien Incluido</td><td style="border-top-color:#000000; border-top-style:solid; border-top-width:1px; border-left-color:#000000; border-left-style:solid; border-left-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;"><?= form_checkbox($opcion_revpro) ?>Si</td>
                    </tr>
                    <tr>
                        <td></td><td style="border-bottom-color:#000000; border-bottom-style:solid; border-bottom-width:1px; border-left-color:#000000; border-left-style:solid; border-left-width:1px;border-right-color:#000000; border-right-style:solid; border-right-width:1px;"><?= form_checkbox($opcion1_revpro) ?>No</td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button) ?></td>
            <td><?= form_button($button1) ?></td>
        </tr>
    </table>
    <?= form_close() ?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    function comprobarextension() {
        if ($("#filecolilla").val() != "") {
            var archivo = $("#filecolilla").val();
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
                jQuery("#imagen").val("");
                mierror = "Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        }
    }
    function addSource() {
        var numItems = jQuery('.file_uploader').length;
        var template = '<div  class="field" id="arch' + numItems + '">' +
                '<div class="input-append">' +
                '<div >' +
                '<input type="file" name="archivo' + numItems + '" id="archivo' + numItems + '"class="btn btn-primary file_uploader">' +
                ' <button type="button" class="close" id="' + numItems + '" onclick="deleteSource(this.id)">&times;</button>' +
                '</div>' +
                '</div>' +
                '</div>';
        jQuery(template).appendTo('#file_source');
        console.log(numItems);
    }
    function deleteSource(elemento) {
        $("#arch" + elemento).fadeOut("slow", function() {
            $("#arch" + elemento).remove();
        });
    }
    (function() {
        jQuery("#addImg").on('click', addSource);

    })();
</script>
