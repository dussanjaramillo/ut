<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<br><br>
<h1><?php echo TIPO_2; ?></h1>
<h2 class="text-center">Reportar Notificación por Correo Fisico</h2>    
<br><br>
<div id="gestion" style="width: 48%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">   
    <fieldset style="width:auto; margin-top:20px;" >
        <center>           
            <table id="tabla1">
                <tr class="sub">
                    <td>
                        <label  class="primario" style="height:10%; width:50%;"> <input type="radio" name="notificacion"  id="not_recibida"  onclick="validar_radio1()" value="1"      />Notificacion Recibida</label>
                        <label  class="primario" style="height:10%;  width:50%;"><input type="radio" name="notificacion"  id="not_devuelta"  onclick="validar_radio2()"  value="2"   />Notificacion Devuelta</label>
                    </td>
                </tr>
            </table>
        </center><br><br>
        <div id="datos_recepcion" class="datos_recepcion" style="width: 80%; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0" >
            <?php
            $attributes = array("id" => "myform", "class" => "myform1");
            echo form_open_multipart("resolucionprescripcion/Respuesta_NotificacionFisica", $attributes);
            echo form_hidden('cod_respuesta', NOTIFICACION_FISICA_RECIBIDA);
            echo form_hidden('cod_coactivo', $cod_coactivo);
            ?>
            <table style="width:100%">
                <tr><th style="text-align:center; font-size: 17px" colspan="2" >Datos Notificación Recibida</th></tr>
                <tr>	 <td>
                        <label  class="primario" style="height:30px; width:auto; "> <br>Fecha Recibida</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;"> <br>
                            <?php
                            $data = array('name' => 'fecha', 'id' => 'fecha', 'class' => 'validate[required]', 'readonly' => 'readonly');
                            echo form_input($data);
                            ?>
                            <label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">Nombre de quien recibe</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <?php
                            $data = array('name' => 'nombre_receptor', 'id' => 'nombre_receptor', 'class' => 'validate[required]');
                            echo form_input($data);
                            ?>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">NIS</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <?php
                            $data = array('name' => 'nis', 'id' => 'nis', 'class' => 'validate[required]', 'onkeypress' => 'return soloNumeros(event)');
                            echo form_input($data);
                            ?>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;" >Radicado N°.</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <?php
                            $data = array('name' => 'num_radicado', 'id' => 'num_radicado', 'class' => 'validate[required]', 'onkeypress' => 'return soloNumeros(event)');
                            echo form_input($data);
                            ?>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">Hora:</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">Hora:
                            <select name="hora" id="hora" style="width:80px; " class="validate[required]">
                                <option value="0">00</option>
                                <?php for ($i = 1; $i <= 23; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php
                                        if ($i < 10) {
                                            echo "0" . $i;
                                        } else {
                                            echo $i;
                                        }
                                        ?></option>
                                <?php } ?>
                            </select>
                            Minutos:
                            <select name="minutos" id="minutos" style="width:80px;" class="validate[required]">
                                <option value="0">00</option>
                                <?php for ($i = 1; $i <= 59; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php
                                        if ($i < 10) {
                                            echo "0" . $i;
                                        } else {
                                            echo $i;
                                        }
                                        ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> 

                    </td>
                </tr>
            </table>
            <br>
            <?php
            echo '<div class="subir_documento" id="subir_documento">';
            echo form_label('<b>Soporte de Entrega<span class="required"></span></b><br>', 'lb_cargar1');
            echo '<div class="alert-success" style="width: 74%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            $data = array(
                'multiple' => '',
                'name' => 'userfile[]',
                'id' => 'imagen',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            echo '<br><br>';
            echo '</div>';
            echo '<br>';
            echo '</div>';
            ?>
            <center>
                <?php
                $data = array('name' => 'button', 'id' => 'Guardar1', 'value' => 'Guardar', 'type' => 'submit',
                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success');
                echo form_button($data);
                ?>
            </center>
            <?php echo form_close(); ?>


        </div>



        <!--------------      NOTIFICACION DEVUELTA ---------->  
        <div id="datos_devolucion" class="datos_devolucion"  style="width: 80%; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0"  >

            <?php
            $attributos = array("id" => "myform", "class" => "myform2");
            echo form_open_multipart("resolucionprescripcion/Respuesta_NotificacionFisica", $attributos);
            echo form_hidden('cod_coactivo', $cod_coactivo);
            ?>
            <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="<?php echo NOTIFICACION_FISICA_RECHAZADO; ?>"/>
            <table style="width:100%">
                <tr><th style="aling:center; margin-top:20px; font-size: 17px" colspan="2" >Datos Notificación Devuelta</th></tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto; "> <br>Fecha Devolución</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;"> <br>
                            <?php
                            $data = array(
                                'name' => 'fecha_dev',
                                'id' => 'fecha_dev',
                                'class' => 'validate[required]',
                                'readonly' => 'readonly'
                            );
                            echo form_input($data);
                            ?>
                            <label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">Causal de devolución</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <select name="causal_devolucion" id="causal_devolucion" class="validate[required]"><?php
                                foreach ($this->data['causales'] as $causal) :
                                    ?>
                                    <option onclick="redireccionar()" value="<?php echo $causal['COD_CAUSALDEVOLUCION']; ?>"><?php echo $causal['NOMBRE_CAUSAL']; ?></option>
                                    <?php
                                    // echo '<option value="' . $causal['COD_CAUSALDEVOLUCION'] . '">' . $causal['NOMBRE_CAUSAL'] . '</option>';
                                endforeach;
                                ?></select>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">NIS</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <?php
                            $data = array(
                                'name' => 'nis',
                                'id' => 'nis',
                                'class' => 'validate[required]',
                                'onkeypress' => 'return soloNumeros(event)',
                            );
                            echo form_input($data);
                            ?>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">Radicado N°.</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">
                            <?php
                            $data = array(
                                'name' => 'num_radicado',
                                'id' => 'num_radicado',
                                'class' => 'validate[required]',
                                'onkeypress' => 'return soloNumeros(event)',
                            );
                            echo form_input($data);
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label  class="primario" style="height:30px; width:auto;">Hora:</label>
                    </td>
                    <td>
                        <div style="margin-left:0px;">Hora:
                            <select name="hora" id="hora" style="width:80px; " class="validate[required]">
                                <option value="0">00</option>
                                <?php for ($i = 1; $i <= 23; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php
                                        if ($i < 10) {
                                            echo "0" . $i;
                                        } else {
                                            echo $i;
                                        }
                                        ?></option>
                                <?php } ?>
                            </select>
                            Minutos:
                            <select name="minutos" id="minutos" style="width:80px;" class="validate[required]">
                                <option value="0">00</option>
                                <?php for ($i = 1; $i <= 59; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php
                                        if ($i < 10) {
                                            echo "0" . $i;
                                        } else {
                                            echo $i;
                                        }
                                        ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <?php
            echo '<div class="subir_documento" id="subir_documento">';
            echo form_label('<b>Soporte de Entrega<span class="required"></span></b><br>', 'lb_cargar1');
            echo '<div class="alert-success" style="width: 74%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            $data = array(
                'multiple' => '',
                'name' => 'userfile[]',
                'id' => 'imagen',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            echo '<br><br>';
            echo '</div>';
            echo '<br>';
            echo '</div>';
            ?>
            <center>
                <?php
                $data = array(
                    'name' => 'button',
                    'id' => 'Guardar2',
                    'value' => 'Guardar',
                    'type' => 'submit',
                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                    'class' => 'btn btn-success'
                );

                echo form_button($data);
                ?>
            </center>
            <?php echo form_close(); ?>


        </div>
    </fieldset>

</div>

<script type="text/javascript">
    $(".datos_recepcion").hide();
    $(".datos_devolucion").hide();
    function redireccionar(valor) {
        var estado = $("#causal_devolucion").val();        
        if(estado=='3'){
            $("#cod_respuesta").val(<?php echo NOTIFICACION_FISICA_RECHAZADO; ?>);
        }else{
            $("#cod_respuesta").val(<?php echo NOTIFICACION_FISICA_DEVUELTA; ?>);
        }
            
    
    }
    function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }
    $(document).ready(function() {
        $("#fecha").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });

        $("#fecha_dev").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
    });

    function ver()
    {
        val_de = $("input[id='de']:checked").val();//si esta chequeado trae el value	
        val_dr = $("input[id='dr']:checked").val();//si esta chequeado trae el value
        if (val_de == 1)
        {
            $("#dat_envio").css("display", "block");
            $("#dat_recepcion").css("display", "none");
        }
        else if (val_dr == 1)
        {
            $("#dat_recepcion").css("display", "block");
            $("#dat_envio").css("display", "none");
        }
    }
    function validar_radio1()
    {

        val_recibida = $("input[id='not_recibida']:checked").val();//si esta chequeado trae el value	
        if (val_recibida == 1)//si esta chequeado trae el value y si el value es 1 va a mostrar el formulario para ingresar datos
        {
            $("#datos_recepcion").css("display", "block");
            $("#datos_devolucion").css("display", "none");
            $(".cod_respuesta").val(<?php echo NOTIFICACION_FISICA_RECIBIDA; ?>);
        }

    }
    function validar_radio2()
    {
            $("#datos_recepcion").css("display", "none");
            $("#datos_devolucion").css("display", "block");
       
    }
    function ajaxValidationCallback(status, form, json, options) {
    }
    function finalizar() {
        $("#gestion").css('display', 'none');

    }
</script>
<style>

    #tabla1{
        border-color:#FFF;
        font-family: Geneva, Arial, Helvetica, sans-serif; 
        font-size: 14px;
        width:380px;
    }

    #tabla1 td{
        border-color:#FFF;
    }

    #tabla2 td{
        border-color:#000;
    }
    .sub{
        font-weight: bold;
    }

    .primario{

        margin-top:3px;
        font-size:16px;
        vertical-align:middle;
        height:20px;
        width:auto;
        display:block;
        float:left;


    }


    .negro2:hover {
        background: #1B92BD; 
        background: -webkit-gradient(linear, left top, left bottom, from(#1B92BD), to(#03F));
        background: -moz-linear-gradient(top,  #1B92BD,  #03F);
    }
    #titulo{
        font-family: Geneva, Arial, Helvetica, sans-serif; 
        font-size: 18px;
        background-color: #5BB75B;
        width:auto;
        text-align:center;
        color:#FFFFFF;
        height:30px;

    }
    #observaciones
    {
        width:auto;
        height: 93px;

    }
</style>