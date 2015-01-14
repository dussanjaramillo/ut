<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<!--
    /**
     * Formulario Principal de Gestion del WorkFlow
     * En este formulario se realiza la consulta principal para mostrar recordatorios o timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->

<h1>Gesti&oacute;n de Timers y Recordatorios del WorkFlow</h1>


<form id='myform' action="<?php echo base_url('index.php/gestionwf/consultanoti'); ?>" method="POST" onsubmit="return validar()">
    <fieldset style="width: 20%; display: block; margin: 0 auto 0 auto">

        <?php
        if (isset($error))
            echo $error;
        ?>

        <table style="border-collapse: separate; border-spacing: 20px">
            <tr>
                <th>

                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Direcci&oacute;n:</label>


                    <select name="direccion" id="direccion" required="requered">
                        <option value="0">Seleccione La Direcci&oacute;n</option>
                        <?php
                        foreach ($dato1 as $i => $datos)
                            echo '<option value = "' . $i . '">' . $datos . '</option>';
                        ?>
                    </select>
                </th>

                <th>

                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Proceso:</label>
            <div id="direccion"><select name="selProcesos" id ="selProcesos" required="required">
                    <option value="0">Seleccione el proceso</option>
                </select>
                </th>

                </tr>

                <tr>
                    <th>

                        <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Gesti&oacute;n:</label> 
                <div id="gestiona"><select name="selgestion" id="selGestiona" required="requered">
                        <option value="0">Seleccione Un Tipo Gesti&oacute;n</option>
                    </select>
                    </th>
                    <th>

                        <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Consulta:</label>

                        <select name="tiponoti" id="tiponoti" required="requered">
                            <option value="0">Seleccione Un Tipo Notificaci&oacute;n</option>
                            <option value="pos">Timers</option>
                            <option value="pre">Recordatorios</option>
                        </select>
                    </th>
                    </tr>
                    <br>
                    <tr>
                        <th colspan="2">

                            <button class="btn btn-success" type="submit" value="Consultar" id="gestiona1" style="display: block; margin: 0 auto 0 auto; float:center"><i class="fa fa-search-plus"> Consultar</i></button>

                        </th>
                    </tr>
                    </table>
                    <div class="alert alert-danger" name="oculto" id="oculto" style="display:none">
                        <strong>¡Alerta! </strong> Debe Seleccionar alguna direcci&oacute;n.
                    </div>

                    <div class="alert alert-danger" name="oculto" id="oculto1" style="display:none">
                        <strong>¡Alerta! </strong> Debe Seleccionar algun Proceso.
                    </div>

                    <div class="alert alert-danger" name="oculto" id="oculto2" style="display:none">
                        <strong>¡Alerta! </strong> Debe Seleccionar alguna Gestion.
                    </div>

                    <div class="alert alert-danger" name="oculto" id="oculto3" style="display:none">
                        <strong>¡Alerta! </strong> Debe Seleccionar algun Tipo de notificacion.
                    </div>
                    </fieldset>
                    <input type="hidden" name="tipogestion" id="tipogestion">
                    </form>

                    <form action= "<?php echo base_url('index.php/gestionwf/creanotificacion'); ?>" method="post">
                        <button class="btn btn-success" type="submit" value="Crear" id="crear" style="display: block; margin: 0 auto 0 auto; float:center; width:100px"><i class="fa fa-plus-circle"> Crear</i></button>
                    </form>

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

                        .info, .exito, .alerta, .error {
                            font-family:Arial, Helvetica, sans-serif; 
                            font-size:13px;
                            border: 1px solid;
                            margin: 10px 0px;
                            padding:15px 10px 15px 50px;
                            background-repeat: no-repeat;
                            background-position: 10px center;
                            position:relative;
                        }
                        .info {
                            color: #00529B;
                            background-color: #BDE5F8;
                            background-image: url('info.png');
                        }
                        .exito {
                            color: #4F8A10;
                            background-color: #DFF2BF;
                            background-image:url('exito.png');
                        }
                        .alerta {
                            color: #9F6000;
                            background-color: #FEEFB3;
                            background-image: url('alerta.png');
                        }
                        .error {
                            color: #D8000C;
                            background-color: #FFBABA;
                            background-image: url('error.png');
                        }

                        .advertencia{
                            color: #D8000C;
                        }


                    </style>

                    <!--
                        /**
                         * Javascript para obtener los tipos de gestiones
                         * se obtine el tipo de gestion para poder actualizar el combo de tipo actividades
                         * @author Haider Oviedo Martinez :: Thomas MTI
                         * @since 26 II 2013 
                         */
                    -->
                    <script>
                        jQuery(".preload, .load").hide();

                        $("#gestiona1").click(gestiona)

                        $("#direccion").change(function()
                        {
                            var direccion = $(this).children(":selected").attr("value");
                            if (direccion != "")
                            {
                                jQuery(".preload, .load").show();
                                //var proceso = $("#selProcesos option:selected").val();
                                //alert(proceso);
                                var url = "<?php echo base_url('index.php/gestionwf/traerprocesos'); ?>";
                                $.post(url, {direccion: direccion})
                                        .done(function(msg) {
                                            jQuery(".preload, .load").hide();
                                            $('#selProcesos').html(msg)
                                        }).fail(function(msg) {
                                    jQuery(".preload, .load").hide();
                                    alert('Error en consulta de base de datos');
                                });

                                //                $("#selProcesos").load("<?php echo base_url('index.php/gestionwf/traerprocesos'); ?>", "direccion=" + direccion,
                                //                document.getElementById("selProcesos").selectedIndex = "",
                                //                        function() {
                                //                        });
                            }
                        });

                        $("#selProcesos").change(function()

                        {
                            var proceso = $(this).children(":selected").attr("value");
                            if (proceso != "")
                            {
                                //var proceso = $("#selProcesos option:selected").val();
                                //alert(proceso);
                                //                $("#selGestiona").load("<?php echo base_url('index.php/gestionwf/traergestion'); ?>", "proceso=" + proceso,
                                //                        function() {
                                //                        });
                                jQuery(".preload, .load").show();
                                var url = "<?php echo base_url('index.php/gestionwf/traergestion'); ?>";
                                $.post(url, {proceso: proceso})
                                        .done(function(msg) {
                                            jQuery(".preload, .load").hide();
                                            $('#selGestiona').html(msg)
                                        }).fail(function(msg) {
                                    jQuery(".preload, .load").hide();
                                    alert('Error en consulta de base de datos');
                                });
                            }
                        });


                        function gestiona() {
                            var gestiona = $('#selGestiona option:selected').text();
                            $('#tipogestion').val(gestiona);
                            //alert(gestiona);
                        }


                        function ajaxValidationCallback(status, form, json, options) {
                        }

                        jQuery(".preload, .load").hide();

                        function validar() {


//                         if($('#direccion').val()=="0" ||
//                         $('#selProcesos').val()=="0" ||
//                         $('#selGestiona').val()=="0" ||
//                         $('#tiponoti').val()=="0" ){
//                                                 
//                            
//                            
//                     return false;
//                  
//                         }

                            if ($('#direccion').val() == "0") {
                                $('#direccion').css('border', '1px solid red');
                                $('#oculto1').hide();
                                $('#oculto2').hide();
                                $('#oculto3').hide();
                                document.getElementById('oculto').style.display = 'block';

                                return false;

                            }

                            if ($('#direccion').val() != "0") {
                                $('#direccion').css('border', '1px solid green');

                            }

                            if ($('#selProcesos').val() == "0") {
                                $('#selProcesos').css('border', '1px solid red');
                                $('#oculto').hide();
                                $('#oculto2').hide();
                                $('#oculto3').hide();
                                document.getElementById('oculto1').style.display = 'block';
                                return false;
                            }

                            if ($('#selProcesos').val() != "0") {
                                $('#selProcesos').css('border', '1px solid green');
                            }

                            if ($('#selGestiona').val() == "0") {
                                $('#selGestiona').css('border', '1px solid red');
                                $('#oculto1').hide();
                                $('#oculto3').hide();
                                $('#oculto').hide();
                                document.getElementById('oculto2').style.display = 'block';
                                return false;
                            }

                            if ($('#selGestiona').val() != "0") {
                                $('#selGestiona').css('border', '1px solid green');
                            }

                            if ($('#tiponoti').val() == "0") {
                                $('#tiponoti').css('border', '1px solid red');
                                $('#oculto').hide();
                                $('#oculto1').hide();
                                $('#oculto2').hide();
                                document.getElementById('oculto3').style.display = 'block';
                                return false;
                            }

                            if ($('#tiponoti').val() != "0") {
                                $('#tiponoti').css('border', '1px solid green');
                            }

                        }

                    </script>
