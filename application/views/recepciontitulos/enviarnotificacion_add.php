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
    echo form_open("recepciontitulos/Titulo_ProximoPrescribir", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s");
    echo form_hidden('fecha', $fecha_hoy);
    ?>
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div class="notificacion_1" style="overflow: hidden; width: 90%; margin: 0 auto">
        <br>
        <h2 class="text-center">Enviar Notificacion de la Resolucion Via Email</h2>    
        <br><br>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <?php
            $ruta = base_url('uploads/recepciontitulos/' . $titulo["COD_FISCALIZACION"] . '/' . $titulo["NOMBRE_DOCUMENTO"]);
            echo '<br>';
            echo form_label('<b>Archivo Adjunto </b><span class="required"></span>', 'lb_archivo');
            echo '<a href="' . base_url('uploads/recepciontitulos/' . $titulo["COD_FISCALIZACION"] . '/Expediente/' . $titulo["NOMBRE_DOCUMENTO"]) . '">' . $titulo["NOMBRE_DOCUMENTO"] . '</a>';
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
    <div class="notificacion_2" style="overflow: hidden; width: 90%; margin: 0 auto">
        <br>
        <h2 class="text-center">Enviar Notificacion por Correo Fisico</h2>    
        <br><br>
        <div  style="width: 80%; border-color: black; border: 1px solid grey; margin: auto; overflow: hidden;alignment-adjust: central ;padding: 15px 50px 0">
            <?php
            echo '<br>';
            echo form_label('<b>Resolucion a Notificar </b><span class="required"></span>', 'lb_archivo');
            echo '<a href="' . base_url('uploads/recepciontitulos/' . $titulo["COD_FISCALIZACION"] . '/' . $titulo["NOMBRE_DOCUMENTO"]) . '">' . $titulo["NOMBRE_DOCUMENTO"] . '</a>';
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
            echo anchor('recepciontitulos/Menu_NotificarResolucion', '<i class="icon-remove"></i> Cancelar', 'class="btn"');
            ?>
        </div>
        <div class="Boton_Agregado">
            <?php
            echo form_button($data_1) . " ";
            echo anchor('recepciontitulos/Menu_NotificarResolucion', '<i class="icon-remove"></i> Cancelar', 'class="btn"');
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
        var autorizacion = "<?php echo $correo['NOMBRE_CONTACTO']; ?>";
        var estado = '';
        var cod_tiponotificacion = '';
        if (autorizacion != '') {
            estado = '771';
            cod_tiponotificacion = '2';
            $(".notificacion_1").show();
            $(".notificacion_2").hide();
        } else {
            estado = '772';
            cod_tiponotificacion = '1';
            $(".notificacion_1").hide();
            $(".notificacion_2").show();
        }
        $('#enviar').click(function() {
            $(".preload, .load").show();
            var comentarios = $('#comentarios').val();
            var cod_titulo = '<?php echo $cod_titulo; ?>';
            var url_notificacion = '<?php echo base_url('index.php/recepciontitulos/actualizar_notificacion') ?>';
            var url_correo = '<?php echo base_url('index.php/recepciontitulos/enviar_correo') ?>';
            $.post(url_notificacion, {cod_titulo: cod_titulo, cod_respuesta: estado, cod_tiponotificacion: cod_tiponotificacion, comentarios: comentarios})
                    .done(function(msg) {
                        if (estado == 771) {
                            var correo = '<?php echo $correo['EMAIL_AUTORIZADO']; ?>';
                            var mensaje = $('#comentarios').val();
                            var asunto = 'Notificacion de Resolucion al Deudor ---- SENA'
                            var adjunto = '<?php echo $ruta; ?>';
                            $.post(url_correo, {correo: correo, mensaje: mensaje, asunto: asunto, adjunto: adjunto})
                                    .done(function(msg) {
                                        alert("Se ha mandado la notificacion al deudor");
                                        $(".Boton_Agregado").show();
                                        $(".Boton_Agregar").hide();
                                        $(".preload, .load").hide();
                                    });
                        } else {
                            alert("Se ha mandado la notificacion al deudor");
                            $(".Boton_Agregado").show();
                            $(".Boton_Agregar").hide();
                            $(".preload, .load").hide();
                        }
                    });
        });

    </script>
