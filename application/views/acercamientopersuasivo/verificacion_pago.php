
<div id="gestion">
    <div id="contenido">
        <?php
        $attributes = array("id" => "myform");
        echo form_open_multipart("requerimientoacercamiento/guarda_verificacion_pago", $attributes);
        ?>

        <?php echo form_hidden('detalle', serialize($post)); ?>
    
        <table>
            <tr>
                <td colspan="2">
                    <div id="acepta_obligacion" name="acepta_obligacion" style="display:block; padding:20px 0 0 0">
                        <table>

                            <tr>
                                <td>
                                    <h4 class="text-left" style="color:#aaaaaa; font-family: 10px;">Tipo Pago realizado</h4> <br><br>
<!--                                            <table>
                                        <tr><td>
                                                <span  style="color:red"> VALOR DEUDA:</span>  </td>
                                            <td>
                                                <span>    <input type="text" readonly="readonly"  value="<?php //echo $this->data['datos']['VALOR_PAGAR'];      ?>"></span>
                                            </td>
                                            <td>
                                                <span style="color:red; " >SALDO DEUDA:</span>  </td>
                                            <td>
                                                <span><input  readonly="readonly" type="text"  value=" <?php //if(@$consulta2[0]['SALDO_DEUDA']){echo @$consulta2[0]['SALDO_DEUDA'];} else {echo "0";}       ?>"></span>
                                            </td>
                                        </tr>
                                    </table>-->

                                    <?php
                                   
                                        $data = array('name' => 'tipopago', 'id' => 'pagototal', 'value' => '1', 'checked' => FALSE, 'style' => 'margin-left:50px;',);
                                        echo form_radio($data);
                                        ?>              
                                        Pago Total


                                        <?php
                                   
                                        $data = array('name' => 'tipopago', 'id' => 'pagoparcial', 'value' => '2', 'checked' => FALSE, 'style' => 'margin-left:50px;',);
                                        echo form_radio($data);
                                        ?>  
                                        Pago Parcial
                                        <?php
                                  
                                        $data = array('name' => 'tipopago', 'id' => 'acuerdo_pago', 'value' => '3', 'checked' => FALSE, 'style' => 'margin-left:50px;',);
                                        echo form_radio($data);
                                        ?>  
                                        Acuerdo de Pago<br><br>
                                        <div id="datos_acuerdopago" style="text-align:center;">

                                            <div id="carta_acuerdo" style="display:block; width: 80%; ">
                                                <div id="titulo">Solicitud Acuerdo Pago</div>
                                                <div id="observa" style="border-radius:15px;display:block;width:100%; text-align: center;">
                                                    <center style="color:#44AA44; width: 90%">Observaciones </center>

                                                    <textarea id="observaciones" name="observaciones"style="width: 80%;"></textarea>

                                                </div>
                                                <div id="subir_archivo" name="subir_archivo" style=" display:block; width: 250px; overflow: hidden;   clear: both; margin: 20px 0;  width: 90%; margin-top:30px; text-align: left">
                                                    <h4 class="text-left" style="color:#aaaaaa;">Subir Archivo</h4>
                                                    <div id="file_source" style="width:500px;"></div>
                                                    <div class="input-append" id="arch0" ></div><br>
                                                    <div style="width:500px;" >
                                                        <input type="file" name="archivo0" id="archivo0"  class='btn btn-success file_uploader' />
                                                    </div>
                                                </div>

                                            </div>    

                                        </div>


                                        <?php
                                   
                                        $data = array('name' => 'tipopago', 'id' => 'no_pago', 'value' => '4', 'checked' => FALSE, 'style' => 'margin-left:50px;',);
                                        echo form_radio($data);
                                        ?>  
                                        No Pago
                                   


                                </td>

                            </tr>
                            <tr><td><br><br>
                                    <?php
                                    $data = array('name' => 'button', 'id' => 'guardar', 'value' => 'Guardar', 'type' => 'button', 'content' => 'Guardar', 'class' => 'btn btn-success'
                                    );

                                    echo form_button($data);
                                    ?>

                                </td></tr>
                        </table>
                </td>
            </tr>
        </table>

        <?php echo form_close(); ?>
    </div>
</div>

</div>

<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div id="respuesta"></div>
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
    });
    $("#guardar").click(function() {

        var tipo_pago = $("input[name='tipopago']:checked").val();

        if (!tipo_pago)
        {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Tipo pago es obligatorio</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        else {
            if (tipo_pago == 3)
            {
                comprobarextension();
            }

            else {
                guardar();
            }
        }


    });

    function comprobarextension() {
        if ($("#archivo0").val() != "") {
            var archivo = $("#archivo0").val();
            var extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".pdf", ".jpeg");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //compruebo si la extensiÃ³n estÃ¡ entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#archivo0").val("");
                mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: ' + extensiones_permitidas.join() + '</div>';
                $("#respuesta").css('display', 'block');
                document.getElementById("respuesta").innerHTML = mierror;
                $("#respuesta").fadeOut(3000);
                return false;
            }
            //si estoy aqui es que no se ha podido submitir
            else {

                // alert('ingreso');
                guardar();
            }
        } else {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar un documento</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
    }
    function guardar()
    {
       // $(".ajax_load").show();
        var formData = new FormData($("#myform")[0]);
        $.ajax({
            url: '<?php echo base_url('index.php/acercamientopersuasivo/guarda_verificacionpagos') ?>',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                //   alert("Se Subio el Documento Con Exito ");
                $("#respuesta").css('display', 'block');
                $(".ajax_load").hide();
                $("#respuesta").html(data);
                window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";
            }

            ,
            //si ha ocurrido un error
            error: function() {
                $("#ajax_load").hide();
                alert("Ha Ocurrido un Error");
            }
        });
    }
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