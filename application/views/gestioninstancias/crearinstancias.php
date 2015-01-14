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

<h1>Crear Nueva Instancia Vs Proceso</h1>

<form name="form2"id="form2" action= "<?php echo base_url('index.php/gestioninstancias/nuevainstancia'); ?>" method="POST" onsubmit="return validar()">

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
                <br>
                <!--<hr style="border:3px solid white; border-radius:300px/10px; height:0px; text-align: center;width:auto" />-->
                <th>

                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Instancia del Proceso</label>


                    <select name="instancia" id="instancia" required="requered">
                        <option value="0">Seleccione Una Instancia</option>
                        <?php
                        foreach ($dato4 as $i => $datos)
                            echo '<option value = "' . $i . '">' . $datos . '</option>';
                        ?>
                    </select>
                </th>
                </form> 
        </table>
        <br>
        <div class="alert alert-danger" name="oculto" id="oculto" style="display:none">
            <strong>¡Alerta! </strong> Debe Seleccionar alguna direcci&oacute;n.
        </div>

        <div class="alert alert-danger" name="oculto" id="oculto1" style="display:none">
            <strong>¡Alerta! </strong> Debe Seleccionar algun Proceso.
        </div>

        <div class="alert alert-danger" name="oculto" id="oculto2" style="display:none">
            <strong>¡Alerta! </strong> Debe Seleccionar alguna Instancia.
        </div>
        <br>

        <table style="float:right">

            <tr>
                <td>
                    <button type="submit" name="botonSubmit" value="Guardar" class="btn btn-success" style="height: 35px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-floppy-o"> Guardar</i></button>
                </td>
            <form id="form1" action= "<?php echo base_url('index.php/gestioninstancias/'); ?>">
                <td>

                    <button type="submit" name="botonSubmit1" value="Volver" class="btn btn-warning" style="height: 35px; width: 100px; display: block; margin: 0 auto 0 auto"><i class="fa fa-minus-circle"> Cancelar</i></button>

                </td>
            </form> 
            </tr>

        </table>                  
    </fieldset>                  

    <script>

        jQuery(".preload, .load").hide();

        $("#direccion").change(function()
        {
            var direccion = $(this).children(":selected").attr("value");
            if (direccion != "")
            {
                jQuery(".preload, .load").show();
                //var proceso = $("#selProcesos option:selected").val();
                //alert(proceso);
                var url = "<?php echo base_url('index.php/gestioninstancias/traerprocesos'); ?>";
                $.post(url, {direccion: direccion})
                        .done(function(msg) {
                            jQuery(".preload, .load").hide();
                            $('#selProcesos').html(msg)
                        }).fail(function(msg) {
                    jQuery(".preload, .load").hide();
                    alert('Error en consulta de base de datos');
                });
            }
        });

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

        function validar() {
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

            if ($('#instancia').val() == "0") {
                $('#instancia').css('border', '1px solid red');
                $('#oculto1').hide();
                $('#oculto3').hide();
                $('#oculto').hide();
                document.getElementById('oculto2').style.display = 'block';
                return false;
            }

            if ($('#instancia').val() != "0") {
                $('#instancia').css('border', '1px solid green');
            }

        }


        function ajaxValidationCallback(status, form, json, options) {
        }
    </script> 

