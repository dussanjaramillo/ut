<?php
$tipdoc_genmem  =   array('name'=>'tipdoc_genmem','class'=>'search-query','id'=>'tipdoc_genmem');
$razsoc_genmem  =   array('name'=>'razsoc_genmem','class'=>'search-query','id'=>'razsoc_genmem');
$numdic_genmem  =   array('name'=>'numdic_genmem','class'=>'search-query','id'=>'numdic_genmem','value'=>$nit,'type'=>'hidden');
$numexp_genmem  =   array('name'=>'numexp_genmem','class'=>'search-query','id'=>'numexp_genmem');
$numpro_genmem  =   array('name'=>'numpro_genmem','class'=>'search-query','id'=>'numpro_genmem');
$telefo_genmem  =   array('name'=>'telefo_genmem','class'=>'search-query','id'=>'telefo_genmem');
$tipo_opcion    =   array('name'=>'gestion','class'=>'search-query','id'=>'gestion','type'=>'hidden','value'=>$gestion);
$buttonPDF      =   array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-info btn1');
$button         =   array('name'=>'guarda_elaaud','id'=>'guarda_elaaud','type'=>'submit','value'=>'Guardar','content'=>'<i class=""></i> Guardar','class'=>'btn btn-success btn1');
$button1        =   array('name'=>'cancel_elaaud','id'=>'cancel_elaaud','value'=>'cancelar','content'=>'<i class=""></i> Cancelar','class'=>'btn btn-warning btn1');
$atributos      =   array('name'=>'frmtp','id'=>'frmtp','method'=>'post');
?>

<div style="max-width: 950px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?php echo form_open_multipart("insolvencia/generar_actamemorial", $atributos); ?>
    <input type="hidden" id='nit' name='nit' value='<?= @$nit ?>' readonly>
    <input type="hidden" id='fiscalizacion' name='fiscalizacion' value='<?= @$fiscalizacion ?>' readonly>
    <input type="hidden" id='regimen' name='regimen' value='<?= @$regimen ?>' readonly>
    <input type="hidden" id='gestion' name='gestion' value='<?= @$gestion ?>' readonly>
    <input type="hidden" id='titulo' name='titulo' value='<?= @$titulo ?>' readonly>
    <table>
        <tr>
            <td><?= form_input($numdic_genmem)?></td>
        </tr>
    </table>
    <div align="center" style="color: #ffffff;"><h3>Generar memorial para información finalización de acuerdo de pago</h3></div>
    <div align="center">
<img src="<?=  base_url()?>/img/Logotipo_SENA.png" align="middle" width="200" height="200">
</div>
    <br>
    <textarea name="memorial" id="memorial" style=" width: 100%; height: 300px;"></textarea>
    <table cellpadding="15%" align="center">
        <tr>
            <td><?= form_button($button)?></td>
            <td><?= form_button($buttonPDF)?></td>
            <td><?= form_button($button1)?></td>
        </tr>
    </table>
    <div id="pie_pagina">
    <table align="center" style="width: 100%;">
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

    <?=form_close()?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
tinymce.init({    
    selector: "textarea#memorial",
    theme:    "modern",
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

    $('#pdf').on('click',function(){        
        var informacion    = tinymce.get('memorial').getContent();    
        var url = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
        $('#frmtp').attr("action", url);
        $('#frmtp').attr('target', '_blank');
        $('#frmtp').submit();
        $('#frmtp').removeAttr('target');
        $('#frmtp').attr("action", "generar_actamemorial");
        $('#pdf').attr('disabled',true);
        tinymce.init({    
        selector: "textarea#memorial",
        readonly:1
        })
    }); 
    
//     $('#guarda_elaaud').click(function(){
//        tinyMCE.triggerSave();                
//        var file = $('#filecolilla').val();
//        var url = "<?=base_url()?>index.php/insolvencia/guardar_notificacion";
//        if (file == ''){
//            $('#error').show();
//            $('#error').html('Por Favor Adjuntar Documento');
//        }else{            
//            $(".ajax_load").show("slow");
//            $('#frmtp').submit();
//            $('#guarda_actreo').attr('disabled',true);
//        }
// }) 
     
//     $('#genpdf_genmem').on('click',function(){
//         myDivObj = document.getElementById("pie_pagina");
//         var informacion    = tinymce.get('memorial').getContent();
//         var nombre         = "MEMORIAL";
//         var tipo_opcion    = $("#tipo_opcion").val();
//         var url            = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
//         var url1           = "<?=base_url()?>index.php/insolvencia/cargar_resultado";
//         var logo           = $('#logo').val();
//         var pie            = myDivObj.innerHTML;
//         var reg            = $("#tipdoc_genmem").val();
//         var nit            = $("#numdic_genmem").val();
//         
//        var comentarios = informacion;
//         informacion = logo+informacion+pie;
//         
//         redirect_by_post(url, {
//            informacion: informacion,
//            nombre: nombre,
//            viene: '2',
//            comentarios:comentarios
//         }, true);
//         redirect_by_post(url1, {
//           nit:nit                      
//         }, false);
//          
//     });
</script>