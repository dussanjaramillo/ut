<table align="center">
    <tr>
        <td>Tipo Proceso</td>
        <td>Numero Proceso</td>
        <td>Creado Por</td>
    </tr>
    <tr>
        <td>
            <select>
                <option value="">-Seleccionar-</option>
            </select>
        </td>
        <td><input type="text" name="proceso" id="proceso"></td>
        <td><select>
                <option value="">-Seleccionar-</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Asignado a:</td>
        <td>Fecha Creacion</td>
        <td>Estado</td>
    </tr>
    <tr>
        <td>
            <select>
                <option value="">-Seleccionar-</option>
            </select>
        </td>
        <td><input type="data" name="proceso" id="fechacrea"></td>
        <td><select>
                <option value="">-Seleccionar-</option>
            </select>
        </td>
    </tr>
    <tr align="center">
        <td colspan="3"><button id="consultar" class="btn btn-success"><i class="fa fa-spinner"></i>   Consultar</button>
                        <button id="cancelar" class="btn btn-success"><i class="fa fa-ban"></i>   Cancelar</button>
                        <button  id="crear" class="btn btn-success"><i class="fa fa-pencil-square-o"></i>   Crear</button>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<div id="adjudicacion" style="display: none;">
    <table align="center" id="adjudicar">
        <thead>
            <th>Tipo Proceso</th>
            <th>N. Proceso</th>
            <th>Creado Por</th>
            <th>Asignado a</th>
            <th>F. Creacion</th>
            <th>Estado</th>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div id="creacion" style="display: none;">
    <table align="center">
        <tr align="center">
            <td colspan="2">Tipo de Documento</td>
        </tr>
        <tr align="center">
            <td colspan="2">
                <select>
                    <option value="">-Seleccionar-</option>
                </select>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2">Desea Crear el Documento a Partir de una Plantilla Guardada?</td>
        </tr>
        <tr align="center">
            <td colspan="2"><select>
                    <option value="">-Seleccionar-</option>
                </select>
            </td>
        </tr>
<!--        <tr align="center">
            <td><button id="si" class="btn btn-success"><i class="fa fa-check-square"></i>  Si</button></td>
            <td><button id="no" class="btn btn-success"><i class="fa fa-times-circle"></i>  No</button></td>
        </tr>-->
    </table>
</div>
<div id="contexto" style="display: none">
    <textarea id="informacion" class="informacion" style="width: 1000px;height: 750px"  >
<div align="center"><img src="<?= base_url('img/sena.jpg') ?>" style="width: 150px;height: 160px"></div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br>
<div align="center"><h3>SENA, Mas trabajo<br><br>Ministerio de Trabajo<br>SERVICIO NACIONAL DE APRENDIZAJE<br>
    Carrera 13 65-10 P.B.X. 5461600 Apartado 52418 Fax Bogota, D.C - Colombia</h3></div>
    </textarea>
    <br>
    <br>
    <table align="center" style="border: 2px solid; width: 800px; height: 100px">
        <tr>
            <td style="width: 200px">Comentarios</td>
            <td align="center"><textarea id="comentarios" style="width: 500px"></textarea></td>
        </tr>
        <tr>
            <td>Enviar a</td>
            <td align="center"><select style="width: 510px"><option value="">-seleccionar-</option></select></td>
        </tr>
        <tr align="center">
            <td colspan="2"><button id="guardar" class="btn btn-success"><i class="fa fa-floppy-o"></i>   Guardar</button>
                <button id="pdf" class="btn btn-success"><i class="fa fa-file-text-o"></i>   Generar PDF</button>
                <button id="imprimir" class="btn btn-success"><i class="fa fa-print"></i>   Imprimir</button>
                <button id="cancelar1" class="btn btn-success"><i class="fa fa-ban"></i>   Cancelar</button></td>
        </tr>
    </table>
</div>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
</style>
<script>
 
  tinymce.init({
        mode: "specific_textareas",
        editor_selector: "informacion",
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
 
 $(document).ready(function() {
       $('#adjudicar').dataTable({
            "sDom": 'T<"clear">lfrtip',
            "oTableTools": {
//            "sSwfPath": "/swf/copy_csv_xls_pdf.swf"
                "sSwfPath": "<?= base_url('/swf/copy_csv_xls_pdf.swf') ?>",
//                "sRowSelect": "multi"
//                "aButtons": ["select_all", "select_none"]
            }
        });
        });
        
  $('#consultar').click(function(){
  $('#adjudicacion').css('display','block');
  $('#contexto').css('display','none');
  }); 
  
  $('#crear').click(function(){
     $('#creacion').dialog({
         autoOpen: true,
          width : 550,
          height : 250,
          modal : true,
          buttons : [{
                   id: 'si',
                   text:'Si',
                   class: 'btn btn-success',
                   click : function(){
                     $('#contexto').css('display','block');   
                     $('#adjudicacion').css('display','none'); 
                     $('#creacion').dialog('close');
                   }
           },{
            id: 'no',
            text:'No',
            class: 'btn btn-success',
            click : function(){
              $('#creacion').dialog('close');
            }
          }]
      
     }); 
  });
  $('#cancelar1').click(function(){
    $('#contexto').css('display','none'); 
  });
  
$('#fechacrea').datepicker();

</script>