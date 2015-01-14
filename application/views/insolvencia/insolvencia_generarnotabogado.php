<?php
//$opcion  = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'No se Incluyo la Acreencia del Sena','class'=>'selec_titu');
//$opcion1 = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'Se incluyó pero no corresponde a la cantidad que realmente se debe al SENA','class'=>'selec_titu');
//$opcion2 = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'Se incluyó pero no le dieron la prelación legal que le corresponde a la acreencia del SENA','class'=>'selec_titu');
//$regimen = array('name'=>'regimen','class'=>'search-query','id'=>'regimen','readonly'=>'true','value'=>'','type'=>'hidden');
//$nit     = array('name'=>'nit','class'=>'search-query','id'=>'nit','readonly'=>'true','value'=>'','type'=>'hidden');
$button  = array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-success btn1');
$Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    $logo  = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
?>
<div style="max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<div align="center" style="background-color: #FC7323; color: #ffffff;"><h3>Notificación Abogado</h3></div>   

<table id="documentos">
    <tr>
        <td rowspan="7" style="border-right-color:#000000; border-right-style:solid; border-right-width:1px;">Documentos</td><td><?php echo $opcion?></td>
    </tr>
    <tr>
        <td><?php echo $opcion1?></td>
    </tr>
    <tr>
        <td><?php echo $opcion2?></td>
    </tr>
    <tr>
        <td><?php echo $opcion3?></td>
    </tr>
    <tr>
        <td><?php echo $opcion4?></td>
    </tr>
    <tr>
        <td><?php echo $opcion5?></td>
    </tr>
    <tr>
        <td><?php echo $opcion6?></td>
    </tr>
</table>
<br>
<?= form_input($logo)?>
<br>

<div align="center">
    <img src="<?=  base_url()?>/img/Logotipo_SENA.png" align="middle" width="200" height="200">
</div>  
<br>
<textarea name="memorial" id="memorial" style="width: 100%; height: 300px">

</textarea>
<table align="center" style="width: 100%;" id="pie_de_pagina">
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
<table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button)?></td>
            
<!--            <td><?= form_button($button2)?></td>-->
        </tr>
    </table>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
    tinymce.init({
    
    selector: "textarea",
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
    function redirect_by_post(purl, pparameters, in_new_tab) {
        pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
        in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
        var form = document.createElement("form");
        $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
        if (in_new_tab) {
            $(form).attr("target", "_blank");
        }
        $.each(pparameters, function(key) {
            $(form).append('<textarea name="' + key + '" >'+this+'</textarea>');
        });
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    return false;
    }
     
     $('#pdf').on('click',function(){
         var informacion    = tinymce.get('memorial').getContent();
         var nombre         = "NOTIFICACION_ABOGADO";
         var url            = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
         var url1           = "<?=base_url()?>index.php/insolvencia/index";
         var logo           = $('#logo').val();
         var documentos     = $("<div>").append( $("#documentos").eq(0).clone()).html();
         var pie_de_pagina  = $("<div>").append( $("#pie_de_pagina").eq(0).clone()).html();
         informacion = documentos+logo+informacion+pie_de_pagina;
         redirect_by_post(url, {
            informacion: informacion,
            nombre:nombre,            
            viene:'1'
         }, true);
         redirect_by_post(url1, {
                                   
         }, false);
          
     });

</script>