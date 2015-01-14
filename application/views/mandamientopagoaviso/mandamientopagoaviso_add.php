<?php 
/**
 * mandamientopagoaviso_add -- Vista para seleccionar el tipo de notificacion
 * de los mandamientos de pago.
 *
 * @author		Human Team Technology QA
 * @author		Nicolas Gonzalez R. - nigondo@gmail.com
 * @version		1.0
 * @since		Enero de 2014
 */

if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
        <?php     

        $attributes = array('class' => 'form-inline');
        echo form_open(current_url(),$attributes); ?>
        <center>
        <h1>Notificacion y Firmeza de Mandamiento de Pago</h1>
        <h2>Generar Aviso de Notificacion Mandamiento de Pago</h2>
        </center>
        <div class="center-form-large-20">
        <br>
        <br>
        <table cellspacing="0" cellspading="0" border="0" align="center">
        <tr>
            <td>
            <p>
            <?php
                echo form_label('Seleccione la accion a realizar&nbsp;&nbsp;<span class="required"><font color="red"><b>*</b></font></span>', 'notificacion');
                $datanotificacion = array(
                    'name'        => 'notificacion[]',
                    'id'          => 'notificacion[]',
                    'checked'     => FALSE,
                    'style'       => 'margin:10px',
                    );

                echo "<br>".form_radio($datanotificacion,'aviso')."&nbsp;Generar notificacion por aviso o por citacion<br>";
                echo form_radio($datanotificacion,'citacion')."&nbsp;Actualizar estado citacion notificacion<br>";
                echo form_radio($datanotificacion,'diario')."&nbsp;Actualizar notificacion por aviso publicado en diario<br>";
            ?>
            </p>
            </td>
        </tr>
        <tr>
            <td>
            <p>
            <?php 
            $dataguardar = array(
                   'name' => 'submit-button',
                   'id' => 'submit-button',
                   'value' => 'Aceptar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-floppy-o fa-lg"></i> Aceptar',
                   'class' => 'btn btn-success'
                   );
            echo form_button($dataguardar)."&nbsp;&nbsp;";
            $datacancel = array(
                   'name' => 'button',
                   'id' => 'cancel-button',
                   'value' => 'Cancelar',
                   'type' => 'button',
                   'content' => '<i class="fa fa-trash-o"></i> Cancelar',
                   'class' => 'btn btn-warning'
                   );
            echo form_button($datacancel);
            ?>
            </p>
            </td>
        </tr>
        </table>
        <input type="hidden" name="mandamiento" id="mandamiento" value="<?php echo $_POST['clave']; ?>">
        <?php
            session_start();
            @$_SESSION['idmanda']=$_POST['clave'];
        echo form_close(); 
        ?>

</div>
  <script type="text/javascript">
$(document).ready(function() {   
    $('#submit-button').click(function() {
        var radio = $("input:radio[name='notificacion[]']:checked").val();
        switch(radio){
            case "aviso":
            var url = "<?= base_url('index.php/mandamientopago/aviso')?>";
            window.location = url;
            break;
            
            case "citacion":
            var url = "<?= base_url('index.php/mandamientopago/citacion')?>";
            window.location = url;
            break;
            
            case "diario":
            var url = "<?= base_url('index.php/mandamientopago/diario')?>";
            window.location = url;
            break;
            
        }
    });
});
  </script>
   