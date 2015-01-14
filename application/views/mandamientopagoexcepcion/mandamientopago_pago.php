<?php

if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<!-- <div class="center-form"> -->
        <?php            
        $attributes = array('id' => 'pagoFrm', 'name' => 'pagoFrm',
            'class' => 'form-inline', 'method' => 'POST');
        echo form_open(current_url(),$attributes); 
        echo form_open_multipart(base_url(),$attributes);        
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
                $datapago = array(
                    'name'        => 'pago',
                    'id'          => 'pago',
                    'checked'     => FALSE,
                    'style'       => 'margin:10px'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($datapago,"1")."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($datapago,"0")."&nbsp;&nbsp;NO";

                echo form_error('pago','<div>','</div>');
            ?>
            </p>    
            </td>
            <td>
            <div id="presentaExc" style="display: none">
            <?php
                echo form_label('¿Deudor presenta excepciones en Termino?', 'excepcion')."<br>";
                $dataexcepcion = array(
                    'name'        => 'excepcion',
                    'id'          => 'excepcion',
                    'checked'     => FALSE,
                    'style'       => 'margin:10px'
                    );
                //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "&nbsp;".form_radio($dataexcepcion,"1")."&nbsp;&nbsp;SI<br>";
                echo "&nbsp;".form_radio($dataexcepcion,"0")."&nbsp;&nbsp;NO";

                echo form_error('excepcion','<div>','</div>');
            ?>
            </div>
            </td>
        </tr>
        <tr id="excepciones" style="display:none">
            <td>
            <p>
            <?php
                echo form_error('telefono','<div>','</div>');
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
            $dataguardar = array('name' => 'button','id' => 'guardar','value' => 'Guardar','type' => 'button','content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar','class' => 'btn btn-success');            
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $datacancel = array('name' => 'button','id' => 'cancel-button','value' => 'Cancelar','type' => 'button','onclick' => 'window.location=\''.base_url().'index.php/bandejaunificada/procesos\';','content' => '<i class="fa fa-undo"></i> Cancelar','class' => 'btn btn-warning');
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
    $('input[name="pago"]').change(function() {
        if ($('input[name="pago"]:checked').val() == '0') {
            $('#presentaExc').show('slow');                
        } else if ($('input[name="pago"]:checked').val() == '1') {
            $('#presentaExc').hide('slow');
            $('#excepciones').hide('slow');            
        } else {
            $('#presentaExc').hide('slow');
            $('#excepciones').hide('slow');
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
        tinyMCE.triggerSave();
        var pago = $('input[name="pago"]').is(":checked");
        var excepcion = $('input[name="excepcion"]').is(":checked");
        var observacion = $('#observacion').val();
        var tipo = $('#tipo').val();
        var url = '<?php echo base_url('index.php/mandamientopago/')?>';
        if (tipo == 'pago'){
            url = url+'/pago';
        }
        char_especiales(observacion);
        var valida = true;
        if (pago == false) {
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Pago realizado</b></font>");
            valida = false;
        }else if (pago == true && ($('input[name="pago"]:checked').val() == '0') && excepcion == false){
            $('#error').show();
            $('#error').html("<font color='red'><b>Seleccione Excepcion</b></font>");
            valida = false;
        }else if (observacion == "") {
            $('#error').show();
            $('#error').html("<font color='red'><b>Ingrese una Observaci&oacute;n</b></font>");
            valida = false;
        }
            
        if (valida == true) {
            $(".ajax_load").show("slow");
            $("#pagoFrm").attr("action", url);
            $('#pagoFrm').removeAttr('target');
            $('#guardar').prop("disabled",true);
            $('#pagoFrm').submit();
            //alert('ok');
        }
    });
        
    

  </script>