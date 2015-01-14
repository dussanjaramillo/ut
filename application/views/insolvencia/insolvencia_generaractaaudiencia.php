<?php
//$buttonPDF = array('name' => 'pdf', 'id' => 'pdf', 'value' => 'Generar Pdf', 'content' => '<i class=""></i> Generar Pdf', 'class' => 'btn btn-info btn1');
$button = array('name' => 'guarda_actreo', 'id' => 'guarda_actreo', 'type' => 'submit', 'value' => 'Guardar', 'content' => '<i class=""></i> Guardar', 'class' => 'btn btn-success btn1');
$button1 = array('name' => 'cancel_actreo', 'id' => 'cancel_actreo', 'value' => 'cancelar', 'content' => '<i class=""></i> Cancelar', 'class' => 'btn btn-warning btn1', 'onclick' => 'window.location=\'' . base_url() . 'index.php/insolvencia/index\'');
$atributos = array('name' => 'frmtp', 'id' => 'frmtp', 'method' => 'POST');
$datafile       = array('name'=>'filecolilla','id'=>'filecolilla','value'=>'','maxlength'=>'10','type'=>'file','required'=>'true');
$Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
$logo = array('name' => 'logo', 'id' => 'logo', 'type' => 'hidden', 'value' => $Cabeza);
?>

<?php echo form_open_multipart("insolvencia/guardar_notificacion", $atributos); ?>

<input name="nit" id="nit" type="hidden" value="<?php echo $nit ?>">
<input name="fiscalizacion" id="fiscalizacion" type="hidden" value="<?php echo $fiscalizacion ?>">
<input name="titulo" id="titulo" type="hidden" value="<?php echo $titulo ?>">
<input name="gestion" id="gestion" type="hidden" value="<?php echo $gestion ?>">
<input name="regimen" id="regimen" type="hidden" value="<?php echo $regimen ?>">

<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
      <div align="center">
            <div align="center"><h3><?= $encabezado ?></h3></div>
    <!--<img src="<?= base_url() ?>/img/Logotipo_SENA.png" align="middle" width="200" height="200">-->
    </div>
    <!--      <br>
        <textarea name="memorial" id="memorial" style=" width: 100%; height: 300px;"></textarea><br>
        <div id="pie_pagina">
        <table align="center" style="width: 100%;" >
            <tr>
                <td>
                    <div align='center' style="display:none" id="error" class="alert alert-danger"></div>
                    <div id="ajax_load" class="ajax_load" style="display: none">
                        <div class="preload" id="preload" >
                            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td valign="middle"><center>SENA Mas Trabajo</center></td>
            </tr>
            <tr>
                <td valign="middle"><center>Ministerio de Trabajo</center></td>
            </tr>
            <tr>
                <td valign="middle"><center>SERVICIO NACIONAL DE APRENDIZAJE</center></td>
            </tr>
            <tr>
                <td valign="middle"><center>Carrera 13 65-10 P.B.X. 5461600. Apartado 52418 . Fax 5461688 . Bogota D.C. . Colombia </center></td>
            </tr>
        </table>
        </div>
        
    -->
    <table align="center" cellpadding="15%">
        <tr>
            <!--<td><?= form_button($buttonPDF) ?></td>-->
        </tr>
        <tr id='cargarNotificacion'>
            <td>
                <div><b>Adjuntar Documento</b></div>
                <div><?= form_upload($datafile); ?></div><br>
            </td>
        </tr>
    </table>

    <table cellpadding="15%" align="center">
        <tr>
            <td><?= form_button($button) ?></td>
            <!--<td><?= form_button($buttonPDF) ?></td>-->
            <td><?= form_button($button1) ?></td><?= form_input($logo) ?>
        </tr>
    </table>

</div>

<?= form_close() ?>
<script type="text/javascript" language="javascript" charset="utf-8">
    tinymce.init({
        selector: "textarea#memorial",
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

    $('#pdf').on('click', function() {
        var informacion = tinymce.get('memorial').getContent();
        var url = "<?= base_url() ?>index.php/insolvencia/pdfRevocatoria";
        $('#frmtp').attr("action", url);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "guardar_notificacion");
        $('#pdf').attr('disabled', true);
        tinymce.init({
            selector: "textarea#memorial",
            readonly: 1
        });
    });



</script>