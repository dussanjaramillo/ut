<?php 
if ($message){
    echo $message;die;
    
   }
    $asunto  = array('name'=> 'Asunto','class'=>'search-query','id'=>'Asunto','maxlength'=>'100','required'=>'required','class'=>'input-xxlarge');
    $correo  = array('name'=> 'correo','class'=>'search-query','id'=>'correo','maxlength'=>'100','required'=>'required','type'=>'text','value'=>$empresa->CORREOELECTRONICO);
    $button  = array('name'=> 'Enviar','id'=>'Enviar','value'=>'Enviar','type'=>'submit','content'=>'<i class=""></i> Enviar','class'=>'btn btn-success btn1');
    $button1 = array('name'=> 'Imprimir','id'=>'Imprimir','value'=>'Imprimir','content'=>'<i class=""></i> Imprimir','class'=>'btn btn-success btn1');
    $button2 = array('name'=> 'can','id'=>'can','value'=>'Cancelar','content'=>'Cancelar','class'=>'btn btn-success btn1');
    $attributes = array('target' => '_blank');
?>
<div style="max-width: 886px;min-width: 320px;padding: 40px;width: 88%;background: none repeat scroll 0 0 #F0F0F0;margin: auto;">
<?=  form_open('devolucion/emailNotification',$attributes)?>
    <table  style="width: 700px;">
    <tr>
        <td><?=form_label('Asunto','Asunto')?></td><td><?=form_input($asunto)?><?=form_input($correo)?></td>
    </tr>
    <tr>
        <td><?=form_label('Texto','Texto')?></td><td><textarea name="cuerpo_mensaje" id="cuerpo_mensaje" style="width:530px; height: 300px;"></textarea></td>
    </tr>
</table>
<table align="center" cellpadding="15%">
    <tr>
        <td><?= form_button($button);?></td>
        <td><?= form_button($button1);?></td>
        <td><?= form_button($button2);?></td>
    </tr>
</table>
    <?= form_close()?>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
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
     
     $('#Imprimir').on('click',function(){
         var informacion    = tinymce.get('cuerpo_mensaje').getContent();
         var nombre         = "MENSAJE";
         var asunto     = "Asunto: "+$("#Asunto").val();
         var url = "<?=base_url()?>index.php/devolucion/pdf";
         informacion = asunto+informacion;
         redirect_by_post(url, {
            informacion: informacion,
            nombre: nombre,

         }, true);       
          
     });
   $("#can").confirm({
        title:"",
        text:"¿Esta Sguro de Cancelar?",
        confirm: function(button) {
            //$('#modal').modal('hide');
            $('.modal-body').html('<div align="center"><img src="<?= base_url() ?>/img/27.gif" width="150px" height="150px" /></div>');
            $('#modal').modal('hide').removeData();
        },
        cancel: function(button) {
            
        },
        confirmButton: "SI",
        cancelButton: "NO"
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