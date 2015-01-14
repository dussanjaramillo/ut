<?php 
if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php    
        
        $data = $result;        
        $attributes = array('id' => 'medidasFrm', 'name' => 'medidasFrm',
            'class' => 'form-inline', 'method' => 'POST');
        echo form_open(current_url(),$attributes); 
        echo form_open_multipart(base_url(),$attributes);
        ?>
        
        <center>
            <h2>Medidas cautelares</h2>
        </center>
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <input type="hidden" name="vRuta" id="vRuta">
            <input type="hidden" name="vUbicacion" id="vUbicacion">
            <input type="hidden" name="mandamiento" id="mandamiento">
            <input type="hidden" name="idmandamiento" id="idmandamiento" value="<?php echo $post['clave'];?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $informacion[0]['CODEMPRESA'];?>">
            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="<?php echo $cod_coactivo ?>">
            <input type="hidden" name="medida" id="medida" value="<?php echo @$resultMedida->COD_MEDIDACAUTELAR ?>">
            
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
                if (@$result->COD_MEDIDACAUTELAR != ""){
                    $checked1 = TRUE;
                    $checked2 = FALSE;
                }else{
                    $checked1 = FALSE;
                    $checked2 = TRUE;
                }
                echo form_label('Existen medidas cautelares', 'cautelar')."<br>";
                $datacautelar = array(
                    'name'        => 'cautelar',
                    'id'          => 'cautelar',                    
                    'style'       => 'margin:10px',
                    'readonly'    => 'readonly',
                    'onclick'     => 'javascript: return false'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($datacautelar,"1",@$checked1)."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($datacautelar,"0",@$checked2)."&nbsp;&nbsp;NO";

                echo form_error('cautelar','<div>','</div>');
            ?>
            </p>    
            </td>
            <td valign="top">
            <div id="presentaObl" style="display: none">
            <?php
            $checkedoB1 = TRUE;
            $checkedoB2 = FALSE;
                echo form_label('Existen otras obligaciones', 'obligacion')."<br>";
                $dataobligacion = array(
                    'name'        => 'obligacion',
                    'id'          => 'obligacion',
                    'style'       => 'margin:10px',
                    'readonly'    => 'readonly',
                    'onclick'     => 'javascript: return false'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($dataobligacion,"1",@$checkedoB2)."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($dataobligacion,"0",@$checkedoB1)."&nbsp;&nbsp;NO";

                echo form_error('obligacion','<div>','</div>');
            ?>
            </div>
            </td>
        </tr>
        <tr align="center" colspan='2'>
            <td>
                <table border="1" class="table table-striped">
                    <tr>
                        <th>Proceso</th>
                        <th>Ejecutado</th>
                        <th>Regional</th>
                        <th>Contacto</th>
                    </tr>
                    <tr class="warning">
                        <td align="center" colspan="4">No Existen Obligaciones ...</td>
                    </tr>
                </table>
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
                            'style'       => 'width:98%'
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
                <div id="error"></div>
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
       if ($('input[name="cautelar"]:checked').val() == '1') {
            $('#presentaObl').show('slow');
        } else if ($('input[name="cautelar"]:checked').val() == '0') {
            $('#presentaObl').hide('slow');
        } else {
            $('#presentaObl').hide('slow');
        } 
   }); 

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
        var cautelar    = $('input[name="cautelar"]').is(":checked");
        var obligacion  = $('input[name="obligacion"]').is(":checked");
        var observacion = $('#observacion').val();
        char_especiales(observacion);
        var valida = true;
        if (observacion == ''){
            $('#error').html("<font color='red'><b>Ingrese Comentarios</b></font>");
            valida = false;
        }else if (cautelar == false) {
            $('#error').html("<font color='red'><b>Seleccione si existen medidas cautelares o no</b></font>");
            valida = false;
        }else if ($('input[name="cautelar"]:checked').val() == '1'){
            if (obligacion == false) {
                $('#error').html("<font color='red'><b>Seleccione si existen otras obligaciones o no</b></font>");
                valida = false;
            }
        }
        if (valida == true) {
            $(".ajax_load").show("slow");
            $('#guardar').prop("disabled",true);
            $('#medidasFrm').submit();
        }
    });


    $('input[name="cautelar"]').change(function() {
        if ($('input[name="cautelar"]:checked').val() == '1') {
            $('#presentaObl').show('slow');
        } else if ($('input[name="cautelar"]:checked').val() == '0') {
            $('#presentaObl').hide('slow');
        } else {
            $('#presentaObl').hide('slow');
        }
    });
  </script>