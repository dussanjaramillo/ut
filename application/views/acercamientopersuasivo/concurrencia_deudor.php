<!--Registra si el deudor acepta o nola obligación de la deuda -->
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<form name="myform" id="myform" method="post" action="">
    <div  id="gestion">
        <input type="hidden" name="concepto" id="concepto" value="<?php echo @$consulta_deuda[0]['COD_CONCEPTO'] ?>"/>
        <input type="hidden" name="num_liquidacion" id="num_liquidacion" value="<?php echo @$consulta_deuda[0]['NUM_LIQUIDACION'] ?>"/>
        <input type="hidden" name="valor_pago" id="valor_pago" value="<?php echo @$post['valordeudanum'] ?>"/>
        <table>
            <tr>
                <td id="acep_obl" style="display:block" colspan="2">           <br>
                    <h4 class="text-left" style="color:#aaaaaa;">Aceptar Obligaciones</h4> <br>
                    <?php
                    $data = array('name' => 'obligaciones', 'id' => 'acepta', 'value' => '1', 'checked' => FALSE, 'style' => 'margin-left:0px',);
                    echo form_radio($data);
                    ?>
                    Acepta Obligaciones             

                    <?php
                    $data = array('name' => 'obligaciones', 'id' => 'noacepta', 'value' => '2', 'checked' => FALSE, 'style' => 'margin-left:50px');
                    echo form_radio($data);
                    ?>
                    No Acepta Obligaciones
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="acepta_obligacion" name="acepta_obligacion" style="display:none; padding:20px 0 0 0">
                        <table>
                            <tr><td>
                                    <h4 class="text-left" style="color:#aaaaaa;">Acepta Obligaciones</h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>Fecha Concurrencia</span>
                                    <input type="text" class="requerid"  name="fecha_aceptacion" id="fecha_aceptacion" onkeypress="return soloNumeros(event)">
                                    <h4 class="text-left" style="color:#aaaaaa; font-family: 10px;">Seleccione un tipo de pago</h4>
                                    <?php
                                    $data = array('name' => 'tipopago', 'id' => 'pagototal', 'value' => '1', 'checked' => FALSE, 'style' => 'margin-left:0px',);
                                    echo form_radio($data);
                                    ?>              
                                    Pago Total


                                    <?php
                                    $data = array('name' => 'tipopago', 'id' => 'pagoparcial', 'value' => '2', 'checked' => FALSE, 'style' => 'margin-left:50px',);
                                    echo form_radio($data);
                                    ?>  
                                    Pago Parcial

                                    <?php
                                    $data = array('name' => 'tipopago', 'id' => 'acuerdo_pago', 'value' => '3', 'checked' => FALSE, 'style' => 'margin-left:50px',);
                                    echo form_radio($data);
                                    ?>  
                                    Facilidad de Pago

                                    <?php
                                    $data = array('name' => 'tipopago', 'id' => 'no_pago', 'value' => '4', 'checked' => FALSE, 'style' => 'margin-left:50px',);
                                    echo form_radio($data);
                                    ?>  
                                    No Pago
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </table><br>
        <div id="contenido">
            <div id="div_recibo_pago" style="display:none" ></div>
            <div id="recibopagoparcial" ><!--Carga el recibo de pago con el valor del pago parcial -->
            </div>
            <div id="noacepta_obligacion" style="display: none">
                <?php
                $data = array('name' => 'button', 'id' => 'guardanoacepta', 'value' => 'Guardar', 'type' => 'button', 'content' => 'Guardar', 'class' => 'btn btn-success'
                );
                echo form_button($data);
                ?>
            </div>
            <!-- Boton acuerdo Pago.-->
            <div id='div_acuerdopago' style="display:none">
                <?php
                $data = array('name' => 'button', 'id' => 'guardaacuerdopago', 'value' => 'Guardar acuerdo', 'type' => 'button', 'content' => 'Guardar', 'class' => 'btn btn-success');
                echo form_button($data);
                ?>
            </div>
            <div id='div_nopago' style="display:none">
                <?php
                $data = array('name' => 'button', 'id' => 'guardanopago', 'value' => 'Guardar no pago', 'type' => 'button', 'content' => 'Guardar no pago', 'class' => 'btn btn-success');
                echo form_button($data);
                ?>
            </div>
            <div id="respuesta"></div>
            <input type="button" id="btn_guardar" name="btn_guardar" value="Guardar" style="display:none" class="btn btn-success" onclick="guarda_concurrencia()">
        </div>
    </div>
    <?php echo form_hidden('detalle', serialize($post)); ?>
    <?php echo form_hidden('cabecera', serialize($cabecera)); ?>
</form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        jQuery(".preload, .load").hide();
        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "<?php echo "Requerimiento Acercamiento"; ?>",
            close: function() {
                $('#resultado *').remove();
            }
        });

        $("#fecha_aceptacion").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
        $("#acepta").click(function() {
            $("#acepta_obligacion").show();
            $("#noacepta_obligacion").hide();
            $("#btn_guardar").show();
        });
        $("#noacepta").click(function() {
            $("#acepta_obligacion").hide();
            $("#noacepta_obligacion").show();
            $("#div_acuerdopago").hide();
            $("#datos_acuerdo").hide();
            $("#botonguardar2").hide();
            $("#imprime_recibo").hide();
            $("#carta_acuerdo").hide();
            $("#div_recibo_pago").hide();
            $("#btn_guardar").hide();

        });
    });

    function guarda_concurrencia()

    {
        var fecha_acepta = $("#fecha_aceptacion").val();
        var tipo_pago = $("input[name='tipopago']:checked").val();

        if (fecha_acepta == "")
        {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar la fecha en que acepta la obligación</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        if (tipo_pago == '' || tipo_pago == null || tipo_pago == false)
        {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar el tipo de pago</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        else {
            var res = confirm('Seguro desea guardar la información ?');
            if (res == true) {
                //  jQuery(".preload, .load").show();
                $("#ajax_load").show();
                var url = "<?php echo $url3; ?>"; // El script a dÃ³nde se realizarÃ¡ la peticiÃ³n.
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#myform").serialize(), // Adjuntar los campos del formulario enviado.
                    success: function(data)
                    {
                        $("#ajax_load").show();
                        $("#respuesta").css('display', 'block');
                        // jQuery(".preload, .load").hide();

                        $("#respuesta").html(data);
                        //   $("#contenido").css("display", "none");
                        //location.reload();
                                              window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";


                    }
                });
            }
        }

    }
    //guarda no acepta obligacion
    $("#guardanoacepta").click(function() {
        $("#ajax_load").show();
        var url = "<?php echo $url2 ?>"; // El script a dÃ³nde se realizarÃ¡ la peticiÃ³n.
        $.ajax({
            type: "POST",
            url: url,
            data: $("#myform").serialize(),
            success: function(data)
            {
                $("#respuesta").css('display', 'block');
                 $(".ajax_load").hide();
                $("#respuesta").html(data);
                //  reload.location;
                 window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado')    ?>";

            },
            //si ha ocurrido un error
            error: function() {
                $("#ajax_load").hide();
                alert("Ha Ocurrido un Error");
            }
        });
    });
</script>
<style>
    .nombre_gestion{
        text-align: center;  
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
        width:785px;
        height: 93px;

    }
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
    .tabla1
    {
        background-color: white;  
        text-align: left;

    }
    .sub{
        font-weight: bold;
        margin-left:0px;
        font-size: 14px;
    }

</style>





