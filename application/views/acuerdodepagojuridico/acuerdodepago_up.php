<?php 
$file               = array('name'=> 'file','id' => 'file','class' => 'validate[required]');
$attributes         = array("id" => "myform", 'enctype' => 'multipart/form-data');
$dataguardar        = array('name'=>'button','id'=>'guardar','value'=>'Guardar','type'=>'button','content'=>'<i class="fa fa-floppy-o fa-lg"></i> Guardar','class'=>'btn btn-success');
$datacancel         = array('name'=>'button','id'=>'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-minus-circle"></i> Cancelar','class' => 'btn btn-warning');
$fecha              = date ('d/m/Y');
?>

<?=form_open_multipart("acuerdodepago/documento_juridico", $attributes);?>
    
    <div style="display: block" id="divresp">
        <table>
            <tr>
                <td colspan="2">
                    <h4>Cargar PDF</h4>
                </td>
            </tr>
            <tr>
              <td align="left" style="width: 1px; "></td>
              <th align="left">Nit</th>
              <td align="left">
                <input type="text" readonly id="nit" name="nit" value="<?php echo $nit?>">                  
                <input type="hidden" readonly="readonly" id="cod_fis" name="cod_fis" value="<?php echo $cod_fis ?>">
                <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                <input type="hidden" id="cod_estado" name="cod_estado" value="<?php echo $cod_estado; ?>">
                <input type="hidden" id="acuerdo" name="acuerdo" value="<?php echo $acuerdo; ?>">
                <input type="hidden" id="documentos" name="documentos" value="<?php echo $post['documentos'] ?>">
              </td>
            </tr>
            <tr>
                <td align="left" style="width: 50px; "></td>
                <th align="left">Fecha</th>
                <td align="left"> 
                    <input type="text" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
                </td>
            </tr>
            <tr>
               <td style="width: 50px; "></td>
               <td colspan="2"></p>Selecione el archivo a cargar:</p><?=form_upload($file);?></td>
            </tr>
            <tr>
                <td colspan="3">
                    <br>
                    <div style="display:none" id="error" class="alert alert-danger"></div>
                    <br>
                </td>
            </tr>    
            <tr>
                <td align="left" style="width: 1px; "></td>
                <td colspan="2">
                    <?php 
                        echo form_button($dataguardar);
                        echo form_button($datacancel);
                    ?>                                        
                </td>
            </tr>
        </table>
    </div>

    
<?= form_close()?>
        
    
<script>
    $('#guardar').click(function(){        
        var file = $("#file").val();
        if (file == ""){
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione un archivo a subir</b></font>");
        }else{
            if(confirm("Verifique que el documento que va a subir se encuentre firmado") == true) {
                    var archivo = $("#file").val();
                    var extensiones_permitidas = new Array(".pdf");
                    var mierror = "";
                    //recupero la extensiÃ³n de este nombre de archivo
                    var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
                    //alert (extension);
                    //compruebo si la extensiÃ³n estÃ¡ entre las permitidas
                    var permitida = false;
                    for (var i = 0; i < extensiones_permitidas.length; i++) {
                        if (extensiones_permitidas[i] == extension) {
                          permitida = true;
                          break;
                        }
                    }
                    if (!permitida) {
                      jQuery("#file").val("");
                      mierror = "Comprueba la extension de los archivos a subir.<br>Solo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
                    }
                    //si estoy aqui es que no se ha podido submitir
                    if (mierror != "") {
                      $('#error').show();
                      $('#error').html("<font color='red'><b>"+mierror+"</b></font>");
                      $('#button').prop("disabled",false);
                      return false;
                    }else {
                        $('#button').prop("disabled",true);
                        $('#myform').submit();
                    }
            }
        }
    });
    
    
</script>