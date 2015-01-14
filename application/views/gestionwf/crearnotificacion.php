<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<!--
    /**
     * Formulario Para la creacion de nuevos Recordatorios o Timers sin realizar filtro
     * En este formulario se realiza el ingreso de los nuevos Recordatorios o Timers sin realizar filtro
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 29 V 2013 
     */
-->

<?php
if (isset($_POST['botonSubmit'])) {
    echo 'se envio el formulario';
}
?>

<h1>Crear Nuevo Recordatorio o Timer</h1>

<form name="form2"id="form2" action= "<?php echo base_url('index.php/gestionwf/nuevanotificacion1'); ?>" method="POST" onsubmit="return validar();">

    <fieldset style="width: 20%; display: block; margin: 0 auto 0 auto">
        <legend></legend>

        <table style="border-collapse: separate; border-spacing: 20px">
            <tr>
                <th>

                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Direcci&oacute;n:</label>


                    <select name="direccion" id="direccion" required="requered">
                        <option value="0">Seleccione La Direcci&oacute;n</option>
                        <?php
                        foreach ($dato2 as $i => $datos)
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
                        <label style="text-align:left; font-weight: bold">Tipo de Recordatorio: </label>
                        <select name="TIPO_RECORDATORIO" title="Se necesita seleccionar un tipo Recordatorio" required="requered">
                            <option value="">Seleccione un Tipo de Recordatorio</option>
                            <option value="pos">Timer</option>
                            <option value="pre">Recordatorio</option>
                        </select>    

                    </th>
                    </tr>
                    <br>
                    </table>

                    <label style="text-align:left; font-weight: bold">Actividad: </label>
                    <label id="nombre" name="nombre" style="text-align:left; font-weight: bold; color:#faa732"></label>
                    <!--<input type="date" name="CODACTIVIDAD1" value="<?php echo $gestionar; ?>"><br>-->
                    <input type="hidden" name="CODACTIVIDAD" id="codactividad" value="">  



                    <hr style="border:3px solid white; border-radius:300px/10px; height:0px; text-align: center;width:auto" />


                    <table style="border-collapse: separate; border-spacing: 20px">
                        <tr>
                            <td style="border-right:2px solid white; padding-right:20px">
                                <label style="text-align:left; font-weight: bold">Plantilla: </label>
                                <select name="selPlantillas" id ="selPlantillas" title="Se necesita seleccionar una Plantilla" required="requered">
                                    <option value="">Seleccione Plantillas</option>
                                    <?php
                                    foreach ($dato1 as $i => $datos)
                                        echo '<option value = "' . $i . '">' . $datos . '</option>';
                                    ?>
                                </select>    
                            </td>
                            <td>
                                <label style="text-align:left; font-weight: bold">Estado: </label>
                                <select name="ACTIVO" title="Se necesita seleccionar un Estado" required="requered">
                                    <option value="">Seleccione Un Estado</option>
                                    <option value="S">Activo</option>
                                    <option value="I">Inactivo</option>
                                </select>
                            </td>

                        </tr>


                        <tr>
                            <td style="border-right:2px solid white; padding-right:20px">
                                <label style="text-align:left; font-weight: bold">Tipo de Alerta: </label>
                                <input type="checkbox" name="RECORDATORIO_CORREO" id="rcor" value="c"> Recordatorio Por Correo<br>
                                <input type="checkbox" name="RECORDATORIO PANTALLA" id="rpan" value="p"> Recordatorio Pantalla<br>
                            </td>
                            <td>    

                                <label style="text-align:left; font-weight: bold">Roles: </label> 

                                <div style="border : solid 2px #ffffff;
                                     background : #ffffff;
                                     color : #000000;
                                     padding : 4px;
                                     width : 205px;
                                     height : 80px;
                                     overflow : auto; ">

                                    <?php
                                    foreach ($dato as $i => $datos) {
                                        echo '<input type="checkbox" name="selUsuarios[]" id="selUsuarios' . $i . '" multiple= "multiple" value="' . $i . '" >' . ' ' . $datos . '<br>';
                                    }
                                    ?>
                                    <!--        <div class="errors error1"></div>-->


                                </div>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right:2px solid white; padding-right:20px">
                                <label style="text-align:left; font-weight: bold">Tipo Duraci&oacute;n: </label>
                                <select name="TIEMPO_MEDIDA" id="TIEMPO_MEDIDA" title="Se necesita seleccionar una Plantilla" required="requered">
                                    <option value="d">D&iacute;as</option>
                                    <option value="dc">D&iacute;as Calendario</option>
                                    <option value="m">Meses</option>
                                    <option value="a">Años</option>
                                    <option value="s">Semanas</option>
                                </select>

                            </td>

                            <td>
                                <label style="text-align:left; font-weight: bold">Cantidad:</label>
                                <input type="number" name="TIEMPO_NUM" id="valores" title="Se necesita Ingresar una Cantidad de Tiempo" required="requered"onkeypress="return  soloNumeros(event)"><br>

                            </td>
                        </tr>




                        <tr>
                            <td style="border-right:2px solid white; padding-right:20px">
                                <label style="text-align:left; font-weight: bold">Nombre Recordatorio: </label>
                                <input type="text" maxlength="500" name="NOMBRE_RECORDATORIO" title="Se necesita ingresar un Nombre de Recordatorio" required="requered" onkeypress="return  soloLetras(event)"><br>
                            </td>



                        </tr>
                        </form> 
                    </table>

                    <table style="float:right">

                        <tr>
                            <td>
                                <button type="submit" name="botonSubmit" value="Guardar" class="btn btn-success" style="height: 35px; width: 100px; display: block; margin: 0 auto 0 auto" onclick="return tal();"><i class="fa fa-floppy-o"> Guardar</i></button>
                            </td>
                        <form id="form1" action= "<?php echo base_url('index.php/gestionwf/'); ?>">
                            <td>

                                <button type="submit" name="botonSubmit1" value="Volver" class="btn btn-warning" style="height: 35px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-minus-circle"> Cancelar</i></button>

                            </td>
                        </form> 
                        </tr>

                    </table>                  





                    </fieldset>                  

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


                        function validar() {
                            var ok = 0;
                            var ckbox = document.getElementsByName('selUsuarios[]');
                            for (var i = 0; i < ckbox.length; i++) {
                                if (ckbox[i].checked == true) {
                                    ok = 1;
                                }
                            }

                            if (ok == 0) {
                                bootbox.alert('Debe Seleccionar al Menos un Usuario');
                                return false;
                            }
                        }




                        $('#TIEMPO_MEDIDA').change(function() {
                            alerta = $('#TIEMPO_MEDIDA').val();
                            //alert(alerta);
                            if ($('#TIEMPO_MEDIDA').val() == "m") {
                                $('#valores').val('15');
                            }
                            if ($('#TIEMPO_MEDIDA').val() == "d") {
                                $('#valores').val('365');
                            }
                            if ($('#TIEMPO_MEDIDA').val() == "dc") {
                                $('#valores').val('365');
                            }
                            if ($('#TIEMPO_MEDIDA').val() == "a") {
                                $('#valores').val('5');
                            }
                            if ($('#TIEMPO_MEDIDA').val() == "s") {
                                $('#valores').val('5');
                            }

                        });
                        $('#valores').change(function() {
                            var numero = $('#valores').val();
                            var tiempo = $('#TIEMPO_MEDIDA').val();
                            if (tiempo == 's' || tiempo == 'a') {
                                if (numero >= 5)
                                    $('#valores').val('5');
                                else if (numero <= 0)
                                    $('#valores').val('1');
                            } else if (tiempo == 'd') {
                                if (numero >= 365)
                                    $('#valores').val('365');
                                else if (numero <= 0)
                                    $('#valores').val('1');
                            } else if (tiempo == 'dc') {
                                if (numero >= 365)
                                    $('#valores').val('365');
                                else if (numero <= 0)
                                    $('#valores').val('1');
                            }
                            else if (tiempo == 'm') {
                                if (numero >= 15)
                                    $('#valores').val('15');
                                else if (numero <= 0)
                                    $('#valores').val('1');
                            }

                        });

                        function soloLetras(e) {
                            key = e.keyCode || e.which;
                            tecla = String.fromCharCode(key).toLowerCase();
                            letras = " áéíóúabcdefghijklmnñopqrstuvwxyz&1234567890";
                            especiales = "8,46";

                            tecla_especial = false
                            for (var i in especiales) {
                                if (key == '46') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '8') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '37') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '39') {
                                    tecla_especial = true;
                                    break;
                                }
                            }
                            if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                                return false;
                            }
                            return true;

                        }

                        function soloNumeros(e) {
                            key = e.keyCode || e.which;
                            tecla = String.fromCharCode(key).toLowerCase();
                            letras = " 0123456789";
                            especiales = "8";

                            tecla_especial = false
                            for (var i in especiales) {
                                if (key == '46') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '8') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '37') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '39') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '67') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '99') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '86') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '118') {
                                    tecla_especial = true;
                                    break;
                                }

                                if (key == '9') {
                                    tecla_especial = true;
                                    break;
                                }
                                if (key == '11') {
                                    tecla_especial = true;
                                    break;
                                }

                            }

                            if (key == '46') {
                                return false;
                            }

                            if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                                return false;
                            }
                            return true;

                        }


                        function ajaxValidationCallback(status, form, json, options) {
                        }

                        jQuery(".preload, .load").hide();

                        $('#selGestiona').change(function() {
                            var nombregestion = $('#selGestiona option:selected').text();
                            var idgestion = $('#selGestiona').val();

                            $('#nombre').text(nombregestion);
                            $('#codactividad').val(idgestion);
                        });

                        function tal() {
                            bootbox.confirm("Desea Seguir creando Timers o Recordatorios", function(result) {
                                if (result == true) {
                                    bootbox.alert('Seguira Creando Timer o Recordatorios');

                                } else {
                                    bootbox.alert('No Seguira Creado Timers o Recordatorios');
                                    var url = '<?php echo base_url('index.php/gestionwf/'); ?>';
                                    location.href = url;
                                }
                            });
                        }

                    </script> 

