<table align="center" style="border: 2px solid">
    <tr>
        <td>Nit</td>
        <td><input type="text" name="nit" id="nit"></td>
        <td>Razon Social</td>
        <td><input type="text" name="razon" id="razon"></td>
    </tr>
    <tr>
        <td>Concepto</td>
        <td><select><option value="">-seleccionar-</option></select></td>
        <td>Instancia</td>
        <td><select><option value="">-seleccionar-</option></select></td>
    </tr>
    <tr>
        <td>Representante Legal</td>
        <td><input type="text" name="representante" id="representante"></td>
        <td>Telefono</td>
        <td><input type="text" name="telefono" id="telefono"></td>
    </tr>
    <tr>
        <td>Estado</td>
        <td><select><option value="">-seleccionar-</option></select></td>
    </tr>
</table>
<br>
<br>
<table align="center" style="border: 2px solid">
    <tr align="center">
        <td colspan="4"><h4>AVALUO</h4></td>
    </tr>
    <tr>
        <td>Identificador Avaluador</td>
        <td><input type="text" name="identificador" id="identificador"></td>
        <td>Nombre</td>
        <td><input type="text" name="nombre" id="nombre"></td>
    </tr>
    <tr>
        <td>Ubicación del Bien</td>
        <td><input type="text" name="ubicacion" id="ubicacion"></td>
        <td>Dirección</td>
        <td><input type="text" name="direccion" id="direccion"></td>
    </tr>
    <tr>
        <td>Localización</td>
        <td><select><option value="">-seleccionar-</option></select></td>
        <td>Uso</td>
        <td><select><option value="">-seleccionar-</option></select></td>
    </tr>
    <tr>
        <td>Tipo de Inmueble</td>
        <td><select><option value="">-seleccionar-</option></select></td>
        <td>Area Total</td>
        <td><input type="text" name="area" id="area"></td>
    </tr>
    <tr>
        <td>Valor del Bien</td>
        <td><input type="text" name="valorbien" id="valorbien"></td>
        <td>Valor Postura</td>
        <td><input type="text" name="valorpostura" id="valorpostura"></td>
    </tr>
    <tr>
        <td>Metodo Utilizado</td>
        <td><select><option value="">-seleccionar-</option></select></td>
    </tr>
</table>
<br>
<br>
<div align="center"><h4>Comisión Del Remate</h4></div>
<br>
<table align="center" style="border: 2px solid">
    <tr align="center" style="width: 2000px; height: 50px">
        <td colspan="2">¿El remate se va a comisionar?</td>
    </tr>
    <tr align="center" style="width: 2000px; height: 50px">
        <td><input type="radio" name="si" id="si" class="confirmar"> Si</td>
        <td><input type="radio" name="si" id="no" class="confirmar"> No</td>
    </tr>
</table>
<br>
<br>
<table align="center">
    <tr>
        <td style="width: 150px"><button id="guardar" class="btn btn-success">Guardar</button></td>
        <td><button id="cancelar" class="btn btn-success">Cancelar</button></td>
    </tr>
</table>
<br>
<br>
<textarea id="informacion" class="informacion" style="width: 1000px;height: 800px"  >
<div align="center"><img src="<?= base_url('img/sena.jpg') ?>" style="width: 130px;height: 160px"></div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br>
<div align="center"><h4>SENA, Más trabajo<br>Ministerio de Trabajo<br>SERVICIO NACIONAL DE APRENDIZAJE<br>
    Carrera 13 65-10 P.B.X. 5461600 Apartado 52418 Fax Bogotá, D.C - Colombia</h4></div>
</textarea>
<br>
<br>
<table align="center" style="border: 2px solid">
    <tr>
        <td>Comentarios</td>
        <td><textarea id="comentarios"></textarea></td>
    </tr>
    <tr>
        <td>Enviar a</td>
        <td><select><option value="">-seleccionar-</option></select></td>
    </tr>
    <tr>
        <td>Numero de radicación de Onbase</td>
        <td><input type="text" name="onbase" id="onbase"></td>
    </tr>
    <tr align="center">
        <td><button id="guardar" class="btn btn-success">Guardar</button></td>>
        <td><button id="pdf" class="btn btn-success">Generar PDF</button></td>>
        <td><button id="imprimir" class="btn btn-success">Imprimir</button></td>>
        <td><button id="cancelar" class="btn btn-success">Cancelar</button></td>>
    </tr>
</table>

<script>
    
          
    tinymce.init({
            mode : "specific_textareas",
            editor_selector : "informacion",
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