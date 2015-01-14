<!-- Responsable: Leonardo Molina-->
<script type="text/javascript">
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
     // para reemplazar el contenido desde un jscript
     //tinyMCE.activeEditor.setContent("pepe");
</script>



<form method="post" action="somepage">
    <p class="MsoHeader" style="text-align: center;" align="center"><span style="font-size: 8.0pt;"><img src="<?php echo base_url(); ?>img/senaPdf.png"/></span></p>

    <textarea name="content" id="targetTextArea" style="width:100%; height: 300px">

    </textarea>
    
    
</form>
        

