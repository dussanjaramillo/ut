<?php

if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php            
        $attributes = array('id' => 'pagoFrm', 'name' => 'pagoFrm',
            'class' => 'form-inline', 'method' => 'POST');
        echo form_open_multipart(current_url(),$attributes); 
        ?>
        <?php echo $custom_error; ?>
        <center>
            <h2>Verificaci&oacute;n del Pago</h2>
        </center>
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="mandamiento" id="mandamiento">
            <input type="hidden" name="aviso" id="aviso" value="11">
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
            <input type='hidden' value='<?php echo $user->IDUSUARIO ?>' name='iduser' id='iduser'>            
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo ?>">
            </td>
       </tr>
       <tr>
            <?php
            $this->load->view('mandamientopago/cabecera',$this->data); 
            ?>
        </tr>     

        </table>
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center" width="55%">
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Pago realizado', 'pago')."<br>";
                $checked1=TRUE;
                $checked2=FALSE;
                $datapago = array(
                    'name'        => 'pago',
                    'id'          => 'pago',
                    'disabled'    => 'disabled',
                    'style'       => 'margin:10px'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($datapago,"1",$checked2)."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($datapago,"0",$checked1)."&nbsp;&nbsp;NO";
                echo form_error('pago','<div>','</div>');
            ?>
            </p>    
            </td>
            <td>
            <div>
            <?php
                echo form_label('Presenta excepciones', 'excepcion')."<br>";
                $dataexcepcion = array(
                    'name'        => 'excepcion',
                    'id'          => 'excepcion',
                    'disabled'    => 'disabled',
                    'style'       => 'margin:10px'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($dataexcepcion,"1",$checked1)."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($dataexcepcion,"0",$checked2)."&nbsp;&nbsp;NO";

                echo form_error('excepcion','<div>','</div>');
            ?>
            </div>
            </td>
        </tr>
        <tr>
            <td align="left">
                <p>
                    <?php
                    echo form_label('Tipo de Excepción Presentada', 'presenta');
                    $selecteexe[''] = "-- Seleccione --";
                    $selecteexe['1'] = "Pago de la obligación.";
                    $selecteexe['2'] = "Existencia del acuerdo de pago";
                    $selecteexe['3'] = "Falta de ejecutoria del título";
                    $selecteexe['4'] = "Pérdida de ejecutoria del título por revocación";
                    $selecteexe['5'] = "Prescripción de la acción de cobro";
                    $selecteexe['6'] = "Ausencia de título ejecutivo o incompetencia del funcionario que lo profirió.";
                    $selecteexe['7'] = "La calidad de deudor solidario";
                    $selecteexe['8'] = "La indebida tasación del monto de la deuda.";
                    echo "<br>" . form_dropdown('presenta', $selecteexe, '', 'id="presentaexe" class="chosen" data-placeholder="seleccione..." ');
                    ?>
                </p>    
            </td>
            <td>
            <p>
            <?php
                echo @form_hidden('aviso', $_POST['aviso']);
                echo form_label('Tipo excepci&oacute;n&nbsp;&nbsp', 'Tipoexcepcion');
                $selecte[''] = "-- Seleccione --";
                $selecte['1'] = "PROCEDE";
                $selecte['2'] = "PROCEDE PARCIALMENTE";
                $selecte['3'] = "NO PROCEDE";
                echo "<br>".form_dropdown('Tipoexcepcion', $selecte, '','id="Tipoexcepcion" class="chosen" data-placeholder="seleccione..." ');

                echo form_error('Tipoexcepcion','<div>','</div>');
            ?>
            </p>    
            </td>
        </tr>
        <tr>
           <td align="left">
                <p>
                <?php
                    echo form_label('Cargar Documento', 'filecolilla');
                    $datadoc = array(
                               'name'        => 'filecolilla',
                               'id'          => 'filecolilla',
                               'value'       => '',//$data->DOC_COLILLA,
                               'maxlength'   => '10',
                             );
                    echo "<br>".form_upload($datadoc);
                ?>
                </p>
            </td>
               <td>
            <p>
            <?php
                echo form_label('Número de folios', 'folios');
                $datafolios = array(
                           'name'        => 'folios',
                           'id'          => 'folios',
                           'value'       => set_value('folios'),
                           'min'         => '0',
                           'max'         => '999',
                           'type'        => 'number',
                         );
                echo "<br>".form_input($datafolios);
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Fecha Onbase', 'fechaonbase');
                $dataFechaOn = array(
                          'name'        => 'fechaonbase',
                          'id'          => 'fechaonbase',
                          'value'       => '',
                          'maxlength'   => '12',
                          'readonly'    => 'readonly',
                          'size'        => '15'
                        );

               echo "<br>".form_input($dataFechaOn)."&nbsp;";
               echo form_error('fechaonbase','<div>','</div>');

               echo form_hidden('fechaonbased',date("d/m/Y"));
            ?>
            </p>    
            </td>
            <td>
            <?php
                echo form_label('N&uacute;mero Onbase', 'colilla');
                $datacolilla = array(
                           'name'        => 'colilla',
                           'id'          => 'colilla',
                           'value'       => '',//$data->DOC_COLILLA,
                           'maxlength'   => '10',
                           'type'        => 'number',
                         );
                echo "<br>".form_input($datacolilla);
            ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <p>
            <?php
                echo form_label('Observaci&oacute;n&nbsp;&nbsp;', 'observacion');
                $data = array(
                            'name'        => 'observacion',
                            'id'          => 'observacion',
                            'value'       => set_value('observacion'),
                            'rows'        => '8',
                            'cols'        => '80',
                            'style'       => 'width:93%'
                          );

                echo "&nbsp;&nbsp;".form_textarea($data);
                echo form_error('observacion','<div><b><font color="red">','</font></b></div>');
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
            <p>
                <div style="display:none" id="error" class="alert alert-danger"></div>
            </p>
            <p>
            <?php 
            $dataguardar = array(
                   'name' => 'button',
                   'id' => 'guardar',
                   'value' => 'Guardar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                   'class' => 'btn btn-success'
                   );            
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';',
                   'content' => '<i class="fa fa-undo"></i> Cancelar',
                   'class' => 'btn btn-warning'
                   );
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>
        </table>
        <?php echo form_close(); ?>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" >
            <img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
        </div>
</div>

  <script type="text/javascript">
      $(document).ready(function() {  
          window.history.forward(-1);
          $('#fechaonbase').datepicker({
              dateFormat: "dd/mm/y",
              maxDate: "0"
              //minDate: new Date('20'+anonot, mesnot, dianot)
          });  
          
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
                      $('#error').show();
                      $('#error').html("<font color='red'><b>Comprueba la extensión de los archivos a subir. \nSólo se pueden subir archivos con extensiones: " + extensiones_permitidas.join()+"</b></font>");
                      $("#filecolilla").focus();
                  }else{
                      $('#error').show();
                      $('#error').html("<font color='red'><b>Archivo Subido Correctamente</b></font>");
                      return 1;
                  } 
            });
      })
      
$('#observacion').keyup(function() {
    var max_chars = 200;
    var chars = $(this).val().length;
    var diff = max_chars - chars;
    if (diff <= 0){
        $('#error').show();
        $('#error').html('Excedio el limite de carácteres en el campo comentarios');
        $('#guardar').attr('disabled',true);   
    }else{
        $('#guardar').attr('disabled',false); 
        $('#error').hide();
    }
}); 

function char_especiales(string){
    var comentarios = string.replace(/[^a-zA-Z 0-9.]+/g,' ');
    $('#observacion').val(comentarios);
}      
      
      $('#guardar').click(function() { 
        $('#error').html();
        tinyMCE.triggerSave();
        var observacion = $('#observacion').val();
        var tipoexcepcion = $('#Tipoexcepcion').val();
        var folios = $('#folios').val();
        var filecolilla = $('#filecolilla').val();
        var fechaonbase = $('#fechaonbase').val();
        var colilla = $('#colilla').val();
        var valida = true;
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'pago'){
            url = url+'/pagoEdit';
        }
        char_especiales(observacion);
        if (observacion == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese una Observaci&oacute;n</b></font>");
            valida = false;
        }else if (tipoexcepcion == ""){ 
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione un tipo de Excepci&oacute;n</b></font>");
            valida = false; 
        }else if (folios == "" || folios > 999){ 
            $('#error').show();
            $('#folios').val('');
            $('#error').html("<font color='red'><b>Ingrese el número de Folio</b></font>");
            valida = false; 
        }else if (filecolilla == ""){ 
            $('#error').show();
            $('#error').html("<font color='red'><b>Por Favor adjuntar el Documento</b></font>");
            valida = false; 
        }else if (colilla == ""){ 
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese Número Onbase</b></font>");
            valida = false; 
        }else if (fechaonbase == ""){ 
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione la fecha Onbase</b></font>");
            valida = false; 
        }                                
        if (valida == true) {
            $(".ajax_load").show("slow");
            $("#pagoFrm").attr("action", url);
            $('#guardar').prop("disabled",true);
            $('#pagoFrm').submit();
            //alert('ok');
        }
    });
    
    
    $('#pdf').click(function() {
        tinyMCE.triggerSave();
        var notify = $('#notificacion').val();
        $('#mandamiento').val(notify);
        $('#pagoFrm').attr("action", "pdf");
        $('#pagoFrm').submit();
        $('#pagoFrm').attr("action", "pago");
    });

  </script>