<!--
    /**
     * Formulario Para Editar los registros de recordatorios o Timers
     * En este formulario se la actualizacion de los recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<h1>Actualizar Recordatorio o Timer</h1>
<form name = "f1" id="f1" action= "<?php echo base_url('index.php/gestionwf/actualizarnotificacion'); ?>" method="post" onsubmit="return validar();">
    <?php
    foreach ($usuarios as $col2) {
        $users[] = $col2['NOMBREGRUPO'];
        $iduser[] = $col2['CODRECORDADO'];
    }
    ?>


    <?php
    foreach ($editar as $fila) {
        $var = $fila['CODPLANTILLA'];
        ?>    
        <input type="hidden" name='CODRECORDATORIO' id="CODRECORDATORIO" value="<?php echo $fila['CODRECORDATORIO']; ?>"<
               <fieldset style="width: 20%; display: block; margin: 0 auto 0 auto">
            <legend></legend>  

            <table style="border-collapse: separate; border-spacing: 20px">
                <tr>
                    <td style="border-right:2px solid gray; padding-right:10px">
                        <label style="text-align:left; font-weight: bold">Plantilla: </label>
                        <select name="selPlantillas" id ="selPlantillas" title="Se necesita seleccionar una plantilla" required="requiered" value="<?php echo $fila['CODPLANTILLA']; ?>">

                            <?php
                            foreach ($notificacion as $i => $datos)
                                if ($i == $var) {
                                    ?>
                                    <option value="<?php echo $i ?>" selected="selected"><?php echo $datos ?></option>
                                    <?php
                                } else {
                                    echo '<option value = "' . $i . '">' . $datos . '</option>';
                                }
                            ?>



                        </select>   

                        <!--<?php echo $var; ?>-->
                    </td>

                    <td>
                        <label style="text-align:left; font-weight: bold">Estado: </label>
                        <select name="ACTIVO" value="<?php echo $fila['ACTIVO']; ?>">
                            <?php
                            if ($fila['ACTIVO'] == 'S') {
                                echo '<option value="S">Activo</option>';
                                echo '<option value="I">Inactivo</option>';
                            } else {
                                echo '<option value="I">Inactivo</option>';
                                echo '<option value="S">Activo</option>';
                            }
                            ?>
                        </select>  
                    </td>
                </tr>

                <tr>
                    <td style="border-right:2px solid gray; padding-right:10px">
                        <label style="text-align:left; font-weight: bold">Duraci&oacute;n: </label>
                        <select name="TIEMPO_MEDIDA" id="TIEMPO_MEDIDA" value="<?php echo $fila['TIEMPO_NUM']; ?>" required="requiered">        

                            <?php
                            switch ($fila['TIEMPO_MEDIDA']) {
                                case 'd':
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="a">Años</option>';
                                    echo '<option value="s">Semanas</option>';
                                    break;
                                case 'dc':
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="a">Años</option>';
                                    echo '<option value="s">Semanas</option>';
                                    break;
                                case 'm':
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="a">Años</option>';
                                    echo '<option value="s">Semanas</option>';
                                    break;
                                case 'a':
                                    echo '<option value="a">Años</option>';
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="s">Semanas</option>';
                                    break;
                                case 's':
                                    echo '<option value="s">Semanas</option>';
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="a">Años</option>';
                                    break;
                                case '':
                                    echo '<option value="s">Semanas</option>';
                                    echo '<option value="d">D&iacute;as</option>';
                                    echo '<option value="dc">D&iacute;as Calendario</option>';
                                    echo '<option value="m">Meses</option>';
                                    echo '<option value="a">Años</option>';
                                    break;
                            }
                            ?>            
                        </select>

                    </td>

                    <td>
                        <label style="text-align:left; font-weight: bold">Cantidad: </label>    

                        <input type="number" name="TIEMPO_NUM" value="<?php echo $fila['TIEMPO_NUM']; ?>"id="valores" title="Se necesita ingresar una cantida de tiempo" required="requiered"><br>

                    </td>
                </tr>

                <tr>
                    <td style="border-right:2px solid gray; padding-right:10px">
                        <label style="text-align:left; font-weight: bold">Roles: </label>           
                        <div style="border : solid 2px #aaa;
                             background : #ffffff;
                             color : #000000;
                             padding : 4px;
                             width : 205px;
                             height : 80px;
                             overflow : auto;">
                             <?php
                             $j = 0;
                             $cant = count($iduser);

                             foreach ($dato as $i => $datos) {
                                 if ($j < $cant) {
                                     foreach ($dato as $a => $datos) {
                                         if ($iduser[$j] == $a) {
                                             echo '<input type="checkbox" checked="checked" style="text-align:left" name="selUsuarios[]" id="selUsuarios" multiple= "multiple" value="' . $a . '">' . ' ' . $datos . '<br>';
                                         }
                                     }
                                     $j = $j + 1;
                                 } else {
                                     echo '<input type="checkbox" style="text-align:left" name="selUsuarios[]" id="selUsuarios" multiple= "multiple" value="' . $i . '">' . ' ' . $datos . '<br>';
                                 }
                             }
                             ?>
                        </div>
                        <br>
                    </td>

                    <td>
                        <label style="text-align:left; font-weight: bold">Nombre: </label>    

                        <input type="text" maxlength="500" name="NOMBRE_RECORDATORIO" value="<?php echo $fila['NOMBRE_RECORDATORIO']; ?>" title="Se necesita ingresar un nombre" required="requiered">


                    </td>
                </tr>


            </table>




            <button type="submit" name="botonSubmit" id="actualizar" class="btn btn-success"><i class="fa fa-refresh"> Actualizar</i></button>
    </form>



    <?php
}
?>

<script>


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
</script>