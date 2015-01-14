<textarea id="informacion" style="width: 100%;height: 400px; "><?php echo $txt; ?></textarea>
<centrar><button id="generar" class="btn btn-success">Generar</button></centrar>
<script>
$('#resultado').dialog({
    autoOpen:true,
    modal:true,
    width:800,
    heigth:600
})
$(".preload, .load").hide();
tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                //    "insertdatetime media nonbreaking save table contextmenu directionality",
                  //  "emoticons template paste textcolor moxiemanager"
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
    $('#generar').click(function(){
        var informacion = tinymce.get('informacion').getContent();
        if(informacion==""){
            alert("El campo texto se encuentra vacio");
            return false
        }
        $(".preload, .load").show();
        var cod_fis='<?php echo $post['cod_fis']; ?>';
        var id='<?php echo $post['id']; ?>';
        var nresol='<?php echo $post['nresol']; ?>';
        var nit='<?php echo $post['nit']; ?>';
        var multa='<?php echo $post['multa']; ?>';
        var url="<?php echo base_url('index.php/multasministerio/guardar_paso_juridico'); ?>";
        $.post(url,{cod_fis:cod_fis,id:id,nit:nit,informacion:informacion,multa:multa,nresol:nresol})
                .done(function(){
//                    $(".preload, .load").hide();
                    alert('Los datos Fueron Guardados Con Exito');
            window.location.reload();
                }).fail(function(){
                    alert('Error en la base de datos');
                    $(".preload, .load").hide();
                })
        
    })
</script>