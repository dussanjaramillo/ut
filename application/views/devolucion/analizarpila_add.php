<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$datacamara = array('name' => 'camara', 'id' => 'camara', 'checked' => TRUE, 'style' => 'margin:10px');
$datanit = array('name' => 'nit', 'id' => 'nit', 'checked' => TRUE, 'style' => 'margin:10px');
$databanco = array('name' => 'banco', 'id' => 'banco', 'checked' => TRUE, 'style' => 'margin:10px');
?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 90%; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("devolucion/Analizar_DevolucionPILA", $attributes);
    echo form_hidden('cod_devolucion', $cod_devolucion);
    ?>       
    <br>
    <div class="notificacion_1" style="overflow: hidden; width: 90%; margin: 0 auto">
        <br>
        <h2 class="text-center">Analizar Devolución</h2>    
        <br><br>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <table align='center'>
                <tr>
                    <td align='center' colspan='2'><h2>Documentos</h2></td>
                </tr>
                <tr>
                    <td><b>Camara y Comercio</b></td>
                    <td>
                        <?=
                        "&nbsp;" . form_radio($datacamara, "") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                        echo "&nbsp;" . form_radio($datacamara, "Soporte Camara y Comercio ") . "&nbsp;&nbsp;NO";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>RUT</b></td>
                    <td>
                        <?=
                        "&nbsp;" . form_radio($datanit, "") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                        echo "&nbsp;" . form_radio($datanit, "RUT ") . "&nbsp;&nbsp;NO";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Soporte de Banco</b></td>
                    <td>
                        <?=
                        "&nbsp;" . form_radio($databanco, "") . "&nbsp;&nbsp;SI&nbsp;&nbsp;";
                        echo "&nbsp;" . form_radio($databanco, "Soportes Bancarios") . "&nbsp;&nbsp;NO";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' align='center'>
                        <div style="display:none" id="error" class="alert alert-danger"></div>
                    </td>       
            </table>
            <br><br>
        </div>
        <br><br>

        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <?php
            echo '<br>';
            echo form_label('<b>Observaciones de Verificación</b><span class="required"></span>', 'lb_comentarios');
            $datacomentarios = array(
                'name' => 'comentarios',
                'id' => 'comentarios',
                'maxlength' => '300',
                'class' => 'span8 comentarios',
                'rows' => '3',
                'required' => 'required'
            );
            echo form_textarea($datacomentarios);
            echo '<br>';
            ?>    
            <br>  
        </div>
    </div>
    <br>
    <center>
        <?php
        $data_1 = array(
            'name' => 'button',
            'id' => 'enviar',
            'value' => 'Enviar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'disabled' => 'disabled',
            'class' => 'btn btn-danger enviar'
        );
        $data_2 = array(
            'name' => 'button',
            'id' => 'enviar',
            'value' => 'Enviar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'class' => 'btn btn-success enviar'
        );
        ?>
        <div class="Boton_Agregar">
            <?php
            echo form_button($data_2) . " ";
            echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning"');
            ?>
        </div>
    </center>

    <?php echo form_close(); ?>
    <style>
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:40%;
            //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:40%;
            // border:solid lightblue 1px;

        }       
    </style>
