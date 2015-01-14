

<!-- Responsable: Leonardo Molina-->
<?php
$nresol = array('name' => 'nresol', 'id' => 'nresol', 'type' => 'hidden', 'value' => $registros->NUMERO_RESOLUCION);
$gnit = array('name' => 'gnit', 'id' => 'gnit', 'type' => 'hidden', 'value' => $registros->CODEMPRESA);
$codfisc = array('name' => 'codfisc', 'id' => 'codfisc', 'type' => 'hidden', 'value' => $registros->COD_FISCALIZACION);
$nroRadicado = array('name' => 'nroRadicado', 'class' => 'max', 'maxlength' => '30');
$nis = array('name' => 'nis', 'maxlength' => '30');
$nit = $registros->CODEMPRESA;
$nempresa = $registros->NOMBRE_EMPRESA;
$fres = $registros->FECHA_ACTUAL;
$numres = $registros->NUMERO_RESOLUCION;
$direccion = $registros->DIRECCION;
$valor = $registros->VALOR_TOTAL;
$valorLet = Ejecutoriaactoadmin::valorEnLetras($registros->VALOR_TOTAL, "pesos");
//$valorLet = $registros->VALOR_LETRAS;
?>
<?php $attributes = array("id" => "myform", 'onsubmit' => 'return comprobarextension()'); ?>
<?= form_open_multipart(base_url('index.php/ejecutoriaactoadmin/revocatoriaAdd/'), $attributes); ?>
<?= form_input($nresol) ?>
<?= form_input($codfisc) ?>
<?= form_input($gnit) ?>

<h1>Generar Revocatoria</h1>

<?php
if (isset($resGestion->COD_RESPUESTA)) {

    if ($resGestion->COD_RESPUESTA == '141') {
        $crespuesta = array('name' => 'crespuesta', 'id' => 'crespuesta', 'type' => 'hidden', 'value' => $resGestion->COD_RESPUESTA);
        echo form_input($crespuesta);
        ?>
        <div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El documento de revocatoria ya se ha generado.</div>
        <?php
    }
}
?>

<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <h5>Adjunte el archivo de revocatoria y luego genere la respuesta.</h5>

    <div class="input-append" id="arch0">
        <input type="file" name="archivo0" id="archivo0" onchange="habboton()" class="btn btn-primary file_uploader">
    </div>
    <div>
        <table>
            <tr>
                <td>Número radicado:</td><td> <?= form_input($nroRadicado) ?></td>
            </tr>
            <tr>
                <td>NIS:</td><td> <?= form_input($nis) ?></td>
            </tr>
            <?php if ($consulta2[0]['CANTIDAD'] == 1) { ?>
                <tr>
                    <td>
                        Envio de proceso a:
                    </td>
                    <td colspan="1">
                <center><button id="reliquidacion" class="btn btn-success">Reliquidaci&oacute;n</button></center>
                </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>
<br>





<textarea name="informacion" id="informacion" style="width:100%; height: 300px;">

        <p>El Director del Servicio Nacional de Aprendizaje SENA Regional XXXXX, en uso de sus facultades legales y en especial las conferidas por el C&oacute;digo Contencioso Administrativo, y el art&iacute;culo 33 de la Ley 789 de 2002</p>
        <h2>C O N S I D E R A N D O</h2>
        <p>
        <p>Que mediante <strong>Resoluci&oacute;n No <?= $numres ?> del <?= $fres ?></strong>, el Director del SENA Regional XXXXX orden&oacute; el pago de una obligaci&oacute;n dineraria a favor del Fondo Nacional de Formaci&oacute;n Profesional de la Industria de la FIC al Empleador <strong><?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong>con domicilio en <?= $direccion ?> por valor de<strong> <?= $valorLet ?> M/CTE.</strong> <strong>($ <?= $valor ?>)</strong>.</p>
        <p>Que revisado el expediente se encuentra que SE PONE Q SE ENCONTRO Q DA LUGAR A REVOCAR O EN CASO DE QUE SEA SOLICITADA SE CITA LA COMUNICACI&Oacute;N Y LOS ARGUMENTOS DE LA EMPRESA</p>
        <p>
        <p><strong>Consideraciones del Despacho:</strong></p>
        <p>
        <p>El Servicio Nacional de Aprendizaje SENA, es una Instituci&oacute;n P&uacute;blica, cuya misi&oacute;n es la de cumplir con la funci&oacute;n que le corresponde al Estado, de invertir en el desarrollo t&eacute;cnico de los trabajadores colombianos, ofreciendo y ejecutando la formaci&oacute;n profesional integral, para la incorporaci&oacute;n y el desarrollo social, econ&oacute;mico y tecnol&oacute;gico del pa&iacute;s. En este sentido lo que se busca con ello es, para poder cumplir con la misi&oacute;n, impartir capacitaci&oacute;n que le permita a los trabajadores aprender a desarrollar destrezas y habilidades a fin de ponerlas en pr&aacute;ctica en las diferentes empresas que est&aacute;n obligadas por la Ley a tener aprendices en proceso de formaci&oacute;n.</p>
        <p>Ahora bien, en el asunto bajo estudio resulta fundamental poner de presente la figura de la revocatoria directa, la cual tiene el prop&oacute;sito de dar a la autoridad que profiri&oacute; el acto administrativo la oportunidad de corregir lo actuado por ella misma, inclusive de oficio, ya sea con fundamento en consideraciones relativas al inter&eacute;s particular del recurrente, o por una causa de inter&eacute;s general que consiste en la recuperaci&oacute;n del imperio de la legalidad o en la reparaci&oacute;n de un da&ntilde;o p&uacute;blico.</p>
        <p>Es as&iacute; como el <a name="69"></a>Art&iacute;culo 93 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo consagra que <strong><em>&ldquo;Los actos administrativos deber&aacute;n ser revocados por las mismas autoridades que los hayan expedido o por sus inmediatos superiores jer&aacute;rquicos o funcionales, de oficio o a solicitud de parte, en cualquiera de los siguientes casos:</em></strong></p>
        <p><strong><em>&nbsp;</em></strong></p>
        <p><strong><em>1. Cuando sea manifiesta su oposici&oacute;n a la Constituci&oacute;n Pol&iacute;tica o a la ley. </em></strong></p>
        <p><strong><em>2. Cuando no est&eacute;n conformes con el inter&eacute;s p&uacute;blico o social, o atenten contra &eacute;l. </em></strong></p>
        <p><strong><em>3. Cuando con ellos se cause agravio injustificado a una persona.&rdquo;</em></strong></p>
        <p>
        <p>De conformidad con los preceptos normativos antes transcritos, el Despacho entrar&aacute; a analizar el procedimiento adelantado, por medio del cual se orden&oacute; el pago de una obligaci&oacute;n dineraria a favor del Fondo Nacional de Formaci&oacute;n Profesional de la Industria de la FIC al Empleador <strong><?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong></p>
        <p>El d&iacute;a 29 de mayo de 2012, se realiz&oacute; el seguimiento y control al empleador <strong><?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong> con el prop&oacute;sito de verificar el cumplimiento del pago de la contribuci&oacute;n al Fondo Nacional de Formaci&oacute;n Profesional de la Industria de la FIC, y de ser pertinente, elaborar el correspondiente estado de cuenta.</p>
        <p>Que como resultado de la verificaci&oacute;n realizada, el mismo 29 de mayo de 2012 se elabor&oacute; el estado de cuenta correspondiente, el cual arroj&oacute; como resultado una deuda a favor del SENA y a cargo de <strong><?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong>, por valor de <strong>XXXXXXXXXXXX</strong>.</p>
        <p>Posteriormente se profiri&oacute; la Resoluci&oacute;n No. xxxxx del xxx de xxxx de 201x por la cual se orden&oacute; el pago de una obligaci&oacute;n dineraria a favor del Fondo Nacional de Formaci&oacute;n Profesional de la Industria de la FIC.</p>
        <p>Revisado el expediente, encuentra el Despacho que la Resoluci&oacute;n No. xxxx del xxx de xxxx de 201x debe ser revocada de oficio, pues obs&eacute;rvese que &hellip;&hellip;</p>
        <p>
        <p>As&iacute; las cosas, teniendo en cuenta que al expedirse la Resoluci&oacute;n No. xxxxx de 2013 se caus&oacute; un agravio injustificado a <strong><?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong>, y que dicho agravio se enmarca dentro de la causal se&ntilde;alada en el numeral 3 del art&iacute;culo 93 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, la misma debe ser revocada de oficio conforme a las pruebas obrantes en el expediente y el estado de cuenta generado.</p>
        <p>
        <p>En m&eacute;rito de lo anteriormente expuesto este Despacho,</p>
        <p>
        <h3>1.&nbsp;&nbsp;&nbsp;&nbsp; RESUELVE</h3>
        <p>&nbsp;</p>
        <p><strong>ART&Iacute;CULO PRIMERO:</strong> <strong>REVOCAR </strong>de oficio en todas y cada una de sus partes el contenido de la Resoluci&oacute;n No. 02023 del 4 de junio de 2013 de conformidad con la parte considerativa del presente Acto Administrativo.</p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        <p><strong>ART&Iacute;CULO SEGUNDO: </strong>Ordenar a Cartera y Recaudo de Relaciones Corporativas e Internacionales realizar el abono del pago efectuado correspondiente a la liquidaci&oacute;n F026-012 del 29 de mayo de 2012.</p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>ARTICULO TERCERO: </strong>Notificar al representante legal o apoderado de la empresa<strong> <?= $nempresa ?></strong> con<strong> NIT <?= $nit ?> </strong>el contenido de la presente resoluci&oacute;n conforme a lo se&ntilde;alado en los art&iacute;culos 67y siguientes del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo, haci&eacute;ndole saber que contra el presente acto administrativo no procede ning&uacute;n recurso.</p>
        <p>&nbsp;</p>
        <p><strong>ARTICULO CUARTO: </strong>La presente resoluci&oacute;n rige a partir de la fecha de su ejecutoria.</p>
        <p>
        <p><strong>NOTIF&Iacute;QUESE Y C&Uacute;MPLASE</strong></p>
        <p>Dada en xxxxxx a los <?= $dia ?> dia(s) del mes <?= $mes ?> del año <?= $ano ?></p>
        <p>&nbsp;</p>
        <p><strong>xxxxxxxxxxxxx</strong></p>
        <p>Director Regional xxxxxxxxxxxxxxxxx</p>
        <p>
        <p>Revis&oacute;: xxxxxxxxx</p>
        <p>Abogado del despacho regional</p>
        <p>
        <p>Proyect&oacute;: xxxxxxxxxx</p>
        <p>Abogada Relaciones Corporativas</p>

</textarea>

<div class="modal-footer">
    <a href="#" class="btn btn-default" data-dismiss="modal" id="cancelar">Cancelar</a>
    <!--        <button id="pre_abrobado">pre-abrobar</button>-->
    <button type="button" class="btn btn-primary mce-text" id="generarPdf"><li class="fa fa-download"></li> Descargar y Pre-Aprobar revocatoria</button>
    <!--<button type="submit" class="btn btn-primary" id="revocatoria" disabled="true"><li class="fa fa-save"></li> Generar Revocatoria</button>-->
</div>
<?= form_close() ?>  
<script type="text/javascript">
    $(function() {

        if ($('#crespuesta').val() == '141') {
            $('#revocatoria').attr('disabled', false);
        }

        $('#cancelar').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();


        });
        $('.close').on('click', function() {
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();

        });
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

    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >' + this + '</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        return false;
    }

    $('#generarPdf').on('click', function() {
        var informacion = tinymce.get('informacion').getContent();
        var cfisca = $('#codfisc').val();
        var nresol = $('#nresol').val();
        var gnit = $('#gnit').val();
        var nombre = "Revoc" + cfisca + "_" + nresol;
        var url = "<?= base_url() ?>index.php/ejecutoriaactoadmin/pdfRevocatoria";
        redirect_by_post(url, {
            informacion: informacion,
            nombre: nombre,
            cfisca: cfisca,
            nresol: nresol,
            gnit: gnit
        }, true);
//         location.href="<?base_url('index.php/ejecutoriaactoadmin')?>";

        $("#myform").submit();
//        alert('');
//        $('#generarPdf').attr('disabled',true);
//        $('#revocatoria').attr('disabled',false);

    });

    $('#reliquidacion').click(function() {
        var r = confirm('¿Esta Seguro que Desea Reliquidar?');
        if (r == false) {
            return false;
        }
        var cod_fis = '<?php echo $registros->COD_FISCALIZACION ?>';
        var url = "<?php echo base_url('index.php/resolucion/recurso_reliquidacion') ?>";
        $(".preload, .load").show();
        $.post(url, {cod_fis: cod_fis})//19803
                .done(function(msg) {
                    alert('Los Datos Fueron Guardados con Exito');
                    window.location.reload();
                }).fail(function(msg, msg2) {
            console.log(msg + msg2)
            window.location.reload();
        });
    });


    function comprobarextension() {
        
        if ($("#archivo0").val() != "") {
            var archivo = $("#archivo0").val();
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
                jQuery("#archivo0").val("");
                mierror = "Comprueba la extensión de los archivos a subir.\n Sólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
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

