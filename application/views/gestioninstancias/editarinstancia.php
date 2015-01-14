<!--
    /**
     * Formulario Para Editar los registros de recordatorios o Timers
     * En este formulario se la actualizacion de los recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<h1>Actualizar Instancia Vs Proceso</h1>
<form name = "f1" id="f1" action= "<?php echo base_url('index.php/gestioninstancias/actualizarinstancia'); ?>" method="post">
    <?php
    foreach ($editarins as $fila) {
        $var = $fila['COD_MACROPROCESO'];
        $var1 = $fila['COD_TIPO_INSTANCIA'];
        $var2 = $fila['COD_TIPO_PROCESO'];
        $var3 = $fila['COD_INSTANCIAS_PROCESOS'];
        ?>
        <input type="hidden" name="instanciaproceso" id="instanciaproceso" value="<?php echo $var3; ?>">
        <table style="border-collapse: separate; border-spacing: 20px">
            <tr>
                <th>

                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Tipo de Proceso:</label>
                    <select name="selProcesos" id ="selProcesos" value="<?php echo $fila['COD_TIPO_PROCESO']; ?>" required="required">
                        <?php
                        foreach ($dato3 as $i => $datos)
                            if ($i == $var2) {
                                ?>
                                <option value="<?php echo $i ?>" selected="selected"><?php echo $datos ?></option>
                                <?php
                            } else {
                                echo '<option value = "' . $i . '">' . $datos . '</option>';
                            }
                        ?>
                    </select>
                </th>

                <th>



                    <label style="text-align:left; font-weight: bold"><span class="advertencia">*</span>Instancia del Proceso</label>


                    <select name="instancia" id="instancia" value="<?php echo $fila['COD_TIPO_INSTANCIA']; ?>" required="requered">
                        <?php
                        foreach ($dato4 as $i => $datos)
                            if ($i == $var1) {
                                ?>
                                <option value="<?php echo $i ?>" selected="selected"><?php echo $datos ?></option>
                                <?php
                            } else {
                                echo '<option value = "' . $i . '">' . $datos . '</option>';
                            }
                        ?>
                    </select>

                </th>

            </tr>
            <br>

            </form> 
        </table>

        <button type="submit" name="botonSubmit" id="actualizar" class="btn btn-success"><i class="fa fa-refresh"> Actualizar</i></button>
    </form>

    <?php
}
?>

<script>
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