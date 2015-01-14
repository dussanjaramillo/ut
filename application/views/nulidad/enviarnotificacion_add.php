<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div style="background: #f0f0f0; width: 90%; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open("nulidad", $attributes);
    ?>       
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div class="notificacion_1" style="overflow: hidden; width: 90%; margin: 0 auto">
        <br>
        <h2 class="text-center">Enviar Notificacion de la Nulidad Via Email</h2>    
        <br><br>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <?php
            $ruta = base_url('uploads/soportes_nulidad/' . $cod_coactivo . '/Expediente/' . $anexo["NOMBRE_DOCUMENTO"]);
            echo '<br>';
            echo form_label('<b>Archivo Adjunto </b><span class="required"></span>', 'lb_archivo');
            echo '<a href="' . base_url('uploads/soportes_nulidad/' . $cod_coactivo . '/Expediente/' . $anexo["NOMBRE_DOCUMENTO"]) . '">' . $anexo["NOMBRE_DOCUMENTO"] . '</a>';
            echo '<br><br>';
            echo form_label('<b>Correo Autorizado </b><span class="required"></span>', 'lb_autorizado');
            echo $correo['EMAIL_AUTORIZADO'];
            echo '<br><br>';
            echo form_label('<b>Mensaje para enviar al Deudor </b><span class="required"></span>', 'lb_comentarios');
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
            'id' => 'enviar_enable',
            'value' => 'Enviar',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'disabled' => 'disabled',
            'class' => 'btn btn-danger enviar'
        );
        $data_2 = array(
            'name' => 'button',
            'id' => 'enviar',
            'value' => 'Enviar',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'class' => 'btn btn-success enviar'
        );
        ?>
        <div class="Boton_Agregar">
            <?php
            echo form_button($data_2) . " ";
            echo anchor('nulidad/Menu_NotificarResolucion', '<i class="icon-remove"></i> Cancelar', 'class="btn"');
            ?>
        </div>
        <div class="Boton_Agregado">
            <?php
            echo form_button($data_1) . " ";
            echo anchor('nulidad/Menu_NotificarResolucion', '<i class="icon-remove"></i> Cancelar', 'class="btn"');
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
    <script>
        $(".preload, .load").hide();
        $(".Boton_Agregado").hide();
        $(".Boton_Agregar").show();
        var estado = '';
        var cod_tiponotificacion = '';
        $('#enviar').click(function() {
            $(".preload, .load").show();
            var cod_coactivo = '<?php echo $cod_coactivo; ?>';
            var url_correo = '<?php echo base_url('index.php/nulidad/Enviar_Correo') ?>';
            var correo = '<?php echo $correo['EMAIL_AUTORIZADO']; ?>';
            var mensaje = $('#comentarios').val();
            var asunto = 'Notificacion de Resolucion al Deudor ---- SENA'
            var adjunto = '<?php echo $ruta; ?>';
            $.post(url_correo, {cod_coactivo: cod_coactivo, correo: correo, mensaje: mensaje, asunto: asunto, adjunto: adjunto})
                    .done(function(msg) {
                        alert("Se ha mandado la notificacion al deudor");
                        window.location = '<?= base_url('index.php/bandejaunificada') ?>';
                    });
        });

    </script>
