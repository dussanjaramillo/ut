<?php 

$fecha      = array('name'=>'fecha', 'id'=>'fecha', 'readonly'=>'readonly', 'value'=>date('d/m/Y'));
$file       = array('name'=> 'filecolilla','id' => 'filecolilla','class' => 'validate[required]');
$attributes = array("id" => "myform", 'enctype' => 'multipart/form-data');
$button     = array('name'=> 'button','id' => 'button','value' => 'Confirmar','type' => 'button','content' => '<i class="fa fa-cloud-upload fa-lg"></i> Confirmar','class' => 'btn btn-success');



?>

<?=form_open_multipart("mandamientopago/".$tipo, $attributes);?>
    
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
                  <input type="hidden" id="proceso" name="proceso" value="<?php echo $post['clave']?>">
                  <input type="text" readonly="readonly" id="idNit" name="idNit" value="<?php echo $post['nit']?>">
                  <input type="hidden" readonly="readonly" id="fiscalizacion" name="fiscalizacion" value="<?php echo $post['fiscalizacion']?>">
              </td>
            </tr>
            <tr>
                <td align="left" style="width: 50px; "></td>
                <th align="left">Fecha</th>
                <td align="left"> <?=  form_input($fecha);?> </td>
            </tr>
            <tr>
               <td style="width: 50px; "></td>
               <td colspan="2"></p>Selecione el archivo a cargar:</p><?=form_upload($file);?></td> 
            </tr>
                                    
                    <?php
                    $nrActa = $post['clave'];
                    $results=array();
                    $cRuta  = "./uploads/mandamientos/pdf/".$nrActa;
                    $cRuta .= "/mandamiento/";  
                    @$handler = opendir($cRuta);
                    if ($handler){
                        while ($file = readdir($handler)) {
                            if ($file != '.' && $file != '..')
                                $results[] = $file;
                        }
                        closedir($handler);
                    }
                    if(count($results)>0){
                        $cCadena = "";
                        echo "<tr><td align='right' colspan='3'>Lista de Adjuntos<br></td></tr>";
                        for($x=0; $x<count($results); $x++) {
                              $cCadena  = "&nbsp;"; 
                              $cCadena .= ($x+1).". <tr><td align='right' colspan='3'><a href='../../".$cRuta."{$results[$x]}' target=_blank>&nbsp;Mandamiento N° ".$nrActa; // $results[$x]
                              $cCadena .= "</a><br></td></tr>";
                              echo $cCadena;
                        }
                        echo "<br>";                       
                        echo "<input type='hidden' id='documento' name='documento' value='".count($results)."'>";                    
                    } ?>
               
        </table>
        <div class="modal-footer">
            <div id="error"></div>
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <?=form_button($button);?>
        </div>
    </div>

    
<?= form_close()?>
        
    
<script>
    $(document).ready(function() {
        $('#fecha').datepicker({dateFormat: "dd/mm/yy"});
        $('#error').html();
    
        $('#filecolilla').change(function(){ 
        var extensiones_permitidas = new Array(".pdf"); 
        var archivo = $('#filecolilla').val();
        var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        var permitida = false;
        
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
            permitida = true;
            break;
            }
        } 
            if (!permitida) {
                $('#filecolilla').val('');
                $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                $("#filecolilla").focus();
            }else{
                $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                return 1;
            } 
      });

        var documento = $('#documento').val();
        if (documento > 0){
           $('#button').prop("disabled",true);
           $('#filePDF').prop("disabled",true);
           $('#error').html("<font color='red'><b>La Fiscalizacion ya posee archivo adjunto</b></font>");
        }
    });
        
    
    $('#button').click(function(){
        $('#error').html();
        var file = $("#filePDF").val();
        var proceso = $("#proceso").val();
        if (file == ""){
            $('#error').html("<font color='red'><b>Seleccione un archivo a subir</b></font>");
        } else if (proceso == ""){
            $('#error').html("<font color='red'><b>Ingrese el No. de Proceso</b></font>");
            $("#proceso").focus();
        }
        else { 
            if(confirm("Verifique que el documento que va a subir se encuentre firmado") == true) {
            $('#button').prop("disabled",true);
            $('#myform').submit();
            }
          }
       });    
</script>