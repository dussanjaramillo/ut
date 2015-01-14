<div id="textarea">
    Nombre Documento<br>
    <input type="text" id="nombre" name="nombre">Los valores al momento de modificarlo son ejem: %-nombre_valor-%
    <textarea id="informacion" name="informacion" style="width: 100%;height: 400px"></textarea>
    <center><button id="enviar" class="btn btn-success">Guardar</button></center>
</div>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<script>
    $(".preload, .load").hide();
    $('#enviar').click(function(){
        var informacion = tinymce.get('informacion').getContent();
        var nombre=$('#nombre').val();
        if(informacion=="" || nombre==""){
            alert('Datos Imcompletos');
            return false;
        }
        $(".preload, .load").show();
        var url='<?php echo base_url('index.php/plantillas/guardar_archivo') ?>';
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
         nombre_archivo = nombre_archivo.replace(":", "_");
         nombre_archivo = nombre_archivo.replace(":", "_");
         nombre_archivo = nombre_archivo.replace(" ", "_");
         nombre_archivo=nombre_archivo+"_"+nombre;
        $.post(url,{nombre2:nombre,nombre:nombre_archivo,informacion:informacion,opcion:"1"})
                .done(function(msg){
                    window.location.reload();
                }).fail(function(smg,fail){
                    alert('Error al guardar');
                    $(".preload, .load").hide();
                })
    })
    
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
<style>
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>