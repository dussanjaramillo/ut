<?php
$opcion         = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'No se Incluyo la Acreencia del Sena','class'=>'selec_titu');
$opcion1        = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'Se incluyó pero no corresponde a la cantidad que realmente se debe al SENA','class'=>'selec_titu');
$opcion2        = array('name'=>'opcion','id'=>'opcion','type'=>'radio','value'=>'Se incluyó pero no le dieron la prelación legal que le corresponde a la acreencia del SENA','class'=>'selec_titu');
$regimen        = array('name'=>'regimen','class'=>'search-query','id'=>'regimen','readonly'=>'true','value'=>$codigo_regins,'type'=>'hidden');
$nit            = array('name'=>'nit','class'=>'search-query','id'=>'nit','readonly'=>'true','value'=>$nit,'type'=>'hidden');
$tipo_opcion    = array('name'=>'tipo_opcion','class'=>'search-query','id'=>'tipo_opcion','readonly'=>'true','value'=>$tipo_opcion,'type'=>'hidden');
$recdoc         = array('name'=>'recdoc','class'=>'search-query','id'=>'recdoc','readonly'=>'true','value'=>$recdoc,'type'=>'hidden');
$button         = array('name'=> 'pdf','id'=>'pdf','value'=>'Generar Pdf','content'=>'<i class=""></i> Generar Pdf','class'=>'btn btn-success btn1','disabled'=>'true');
$button1        = array('name'=> 'imprimir','id'=>'imprimir','value'=>'Imprimir','content'=>'<i class=""></i> Imprimir','class'=>'btn btn-success btn1');
$button2        = array('name'=> 'salir','id'=>'salir','value'=>'Salir','content'=>'<i class=""></i> Finalizar','class'=>'btn btn-success btn1');
$Cabeza = '<br>
    <div align="center">
    <img src="' . base_url('img/Logotipo_SENA.jpg') . '" width="100" height="100" />
    </div>';
    $logo  = array('name'=>'logo' ,'id'=>'logo','type'=>'hidden','value'=>$Cabeza);
?>
<div id="ela_pdf" style="max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <div align="center" style="background-color: #FC7323; color: #ffffff;"><h3>Resultado de La Objeci&oacute;n</h3></div>   

<table>
    <tr>
        <td>
            <?=form_input($regimen)?><?=form_input($nit)?><?=form_input($tipo_opcion)?><?=form_input($recdoc)?>
        </td>
    </tr>
    <tr>
        <td rowspan="3" style="border-right-color:#000000; border-right-style:solid; border-right-width:1px;">Motivo</td><td><?= form_checkbox($opcion)?>-</td><td>No se Incluyo la Acreencia del Sena </td>
    </tr>
    <tr>
        <td><?= form_checkbox($opcion1)?>-</td><td>Se incluyó pero no corresponde a la cantidad que realmente se debe al SENA</td>
    </tr>
    <tr>
        <td><?= form_checkbox($opcion2)?>-</td><td>Se incluyó pero no le dieron la prelación legal que le corresponde a la acreencia del SENA. </td>
    </tr>
</table>
<br><?= form_input($logo)?>
<textarea name="memorial" id="memorial" style="width: 100%; height: 300px">
    
<table style="width: 100%" border="1">
    <tr>
        <td align="center">Memorial Elaborado Por el Apoderado</td>
    </tr>
    <tr>
        <td align="left">Número del Proceso:</td>
    </tr>
    <tr>
        <td align="left">Promotor:</td>
    </tr>
    <tr>
        <td align="left">Valor de la Acreencia:</td>
    </tr>
    </table>
</textarea>
<table align="center" cellpadding="15%">
        <tr>
            <td><?= form_button($button)?></td>
        </tr>
    </table>
</div>
<div id="salir"  style=" display: none; max-width: 870px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
    <table align="center" cellpadding="15%">
        <tr>   
            <div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Generado Exitosamente.</div>
            <td><?= form_button($button2)?></td>
        </tr>
    </table>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
     $('.selec_titu').click(function(){
           $("#pdf").attr('disabled',false);
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
     $('#salir').on('click',function(){
        var tipo = $('tipo_opcion').val;
        //location.href="<?=base_url('index.php/insolvencia/objetarpruebas')?>";
     });
     $('#pdf').on('click',function(){
         var informacion    = tinymce.get('memorial').getContent();
         var nombre         = "MEMORIAL";
         var regi_insol     = $("#regimen").val();
         var motivo         = $("input[name='opcion']:checked").val();
         var nit            = $("#nit").val();
         var regimen        = $("#regimen").val();
         var recdoc         = $("#recdoc").val();
         var tipo_opcion    = $("#tipo_opcion").val();
         
         var url = "<?=base_url()?>index.php/insolvencia/pdfRevocatoria";
         var logo   = $('#logo').val();        
         informacion = logo+informacion;
         redirect_by_post(url, {
            informacion: informacion,
            nombre: nombre,
            regi_insol:regi_insol,
            motivo:motivo,
            viene:'0'
         }, true);
         $('#salir').css('display','block');
         $('#ela_pdf').css('display','none');
         if (tipo_opcion == 'reorganizacional'){
            var url1 = "<?=base_url()?>index.php/insolvencia/acta_reorganizacion1";
         }else {
            var url1 = "<?=base_url()?>index.php/insolvencia/asistiraudienciaobjeciones";
         }
         redirect_by_post(url1, {
            nit: nit,
            regimen:regimen,  
            tipo_opcion:tipo_opcion,
            recdoc:recdoc
         }, false);
          
     });
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
</script>





