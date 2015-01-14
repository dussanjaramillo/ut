 <?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
	<h2 class="text-center">Carta Remisión de Liquidación</h2>
	<?php 
	if (isset($message)):
		echo $message;
	endif;

	print_r($plantilla);
	?>
	<textarea id="informacion" style="width: 100%;height: 400px">
		<?php 
			echo($plantilla);
		?>
   </textarea>
</div>

<script>
tinymce.init({
        selector: "textarea#informacion",
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
</script>
	
<!--
/* End of file liquidacioncarta_form.php */
/* Location: ./system/application/views/liquidaciones/liquidacioncarta_form.php */
/* Dev: @jdussan 19/02/2014 */
-->