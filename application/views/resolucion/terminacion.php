<table width="100%">
    <tr>
        <td>
            <textarea id="informacion" style="width: 100%;height: 400px"></textarea>
        </td>
    </tr>
    <tr>
        <td align="center">
            <button id="enviar4">Enviar</button>
        </td>
    </tr>
</table>
<script>
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        title: "GENERAR AUTO DE TERMINACION",
        height: 600,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
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
</script>