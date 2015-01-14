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

<h1>Crear Nueva Instancia</h1>

<form name="form2"id="form2" action= "<?php echo base_url('index.php/gestioninstancias/nuevoestado'); ?>" method="POST" >

    <fieldset style="width: 20%; display: block; margin: 0 auto 0 auto">
        <legend></legend>

        <table style="border-collapse: separate; border-spacing: 20px">
            <tr>
                <td style="border-right:2px solid white; padding-right:20px">
                    <label style="text-align:left; font-weight: bold">Numero Instancia: </label>                                
                </td>
                <td>
                    <input type="text" maxlength="15" name="NUM_INSTANCIA" title="Se necesita ingresar un Numero de Instancia" required="requered" onkeypress="return  soloNumeros(event)"><br>
                </td>
            </tr>
            <br>
            <!--<hr style="border:3px solid white; border-radius:300px/10px; height:0px; text-align: center;width:auto" />-->

            <tr>
                <td style="border-right:2px solid white; padding-right:20px">
                    <label style="text-align:left; font-weight: bold">Nombre Instancia: </label>                                
                </td>
                <td>
                    <input type="text" maxlength="4000" name="NOM_INSTANCIA" title="Se necesita ingresar un Nombre de Instancia" required="requered" onkeypress="return  soloLetras(event)"><br>
                </td>
            </tr>
            </form> 
        </table>

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

        $('#NUM_INSTANCIA').change(function() {
            if (isNaN($(this).val())) {
                bootbox.alert('Debe ingresar solo valores numericos');
                $(this).val('');
            }

        });

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
                    tecla_especial = false;
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
                    tecla_especial = false;
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
    </script> 

<!--                <script>bootbox.alert('El Codigo Ya Existe');</script>-->
