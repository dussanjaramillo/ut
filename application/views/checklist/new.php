<h2>Administración Checklist Recepción Títulos</h2>
<br /><br />
    <?php if (isset($message)) echo $message; ?>

    <?php echo form_open('checklist/guardar2') ?>
        <table cellpadding="10">
            <tr>
                <td>
                    Concepto cartera
                </td>
                <td>
                    <?php echo form_dropdown('concepto', $conceptos)?>
                </td>
            </tr>
            <tr>
                <td>
                    Descripción
                </td>
                <td>
                    <?php echo form_input('descripcion') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Texto a mostrar
                </td>
                <td>
                    <?php echo form_textarea('texto', '', 'rows="10" cols="40"  style="width: 962px; height: 123px;"') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Orden
                </td>
                <td>
                    <?php echo form_input('orden', '1') ?>
                </td>
            </tr>
            <tr>
                <td>
                    Activo
                </td>
                <td>
                    <?php echo form_dropdown('activo', array('0'=>'NO','1'=>'SI'), '1') ?>
                </td>
            </tr>
            <tr>
                <td align='center'> <?php echo form_submit('guardar','Guardar', 'class=btn btn-success') ?> </td>
                <td> <?php echo anchor('checklist/listar', 'Cancelar', 'class="btn btn-warning"'); ?> </td>                
                
            </tr>
        </table>
    </form>
