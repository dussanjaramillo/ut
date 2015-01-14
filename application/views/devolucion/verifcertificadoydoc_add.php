<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 90%; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("devolucion/Verificar_CertDoc", $attributes);
    echo form_hidden('cod_devolucion', $cod_devolucion);
    ?>       
    <br>
    <div class="notificacion_1" style="overflow: hidden; width: 90%; margin: 0 auto">
        <br>
        <h2 class="text-center">Revisar Certificación y Documentos</h2>    
        <br><br>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <table width="300" border="0" align="center">
                <tr>
                    <td width="137"><input type="radio" name="radio_verificacion" id="radio" value="<?php echo REVISION_CORRECTA; ?>" />
                        Es Correcto</td>
                    <td width="147"><input type="radio" name="radio_verificacion" id="radio2" value="<?php echo REVISION_INCORRECTA; ?>" />
                        No es Correcto</td>
                </tr>
            </table>
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
            if($documento!=''){
                 echo anchor('../uploads/devolucion/' . @$documento[0]->NIT .'/pdf/certificacion/'. @$documento[0]->NOMBRE_DOCUMENTO, '<i class="fa fa-eye"></i> Visualizar Documento', 'class="btn btn-info", target="_blank"') . " ";
            }
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
