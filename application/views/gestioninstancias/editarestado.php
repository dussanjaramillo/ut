<!--
    /**
     * Formulario Para Editar los registros de recordatorios o Timers
     * En este formulario se la actualizacion de los recordatorios o Timers
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
-->
<h1>Actualizar Instancia</h1>
<form name = "f1" id="f1" action= "<?php echo base_url('index.php/gestioninstancias/actualizarestado'); ?>" method="post">
    <?php
    foreach ($editar as $fila) {
        ?>    
          <fieldset style="width: 20%; display: block; margin: 0 auto 0 auto">
            <legend></legend>  

            <table style="border-collapse: separate; border-spacing: 20px">
                <tr>
                    <td>
                        <label style="text-align:left; font-weight: bold">Numero Instancia: </label>    

                        <input type="text" maxlength="15" name="COD_TIPO_INSTANCIA" value="<?php echo $fila['COD_TIPO_INSTANCIA']; ?>" title="Se necesita ingresar un Numero de Instancia" required="requiered" onkeypress="return  soloNumeros(event)">


                    </td>
                </tr>
                
                <input type="hidden" name="instancia" id="tipogestion" value="<?php echo $fila['COD_TIPO_INSTANCIA']; ?>">
                
                <tr>
                    <td>
                        <label style="text-align:left; font-weight: bold">Nombre Instancia: </label>    

                        <input type="text" maxlength="4000" name="NOMBRE_TIPO_INSTANCIA" value="<?php echo $fila['NOMBRE_TIPO_INSTANCIA']; ?>" title="Se necesita ingresar un Nombre de Instancia" required="requiered" onkeypress="return  soloLetras(event)">


                    </td> 
                </tr>


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