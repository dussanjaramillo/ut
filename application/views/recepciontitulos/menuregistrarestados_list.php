<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

    <?php
    echo form_open('recepciontitulos/Listado_RegistrarEstados');
    ?>
    <?php //echo $custom_error; ?>
    <br>
    <center><h2>Registrar Estados Siguientes a Titulos Exigibles</h2></center>
    <center><h2>Títulos Ejecutivos</h2></center>        
        <?php
        $data = array(
            'name' => 'id_opcion',
            'id' => 'id_opcion',
            'checked' => FALSE,
            'style' => 'margin: 10px',
        );
        ?>
    <br>
    <div class="columna_central">                                       
            <?php echo form_radio($data, '1'); ?>Registrar si deudor en proceso de Reorganizacion<br>
            <?php echo form_radio($data, '2'); ?>Registrar si titulo esta Prescrito<br>                        
            <?php echo form_radio($data, '3'); ?>Registrar si hay acumulacion de titulos<br>
        </div>             

    <br>
    
        <center>
            <?php
            $data = array(
                'name' => 'enviar',
                'id' => 'enviar',
                'value' => 'Aceptar',
                'type' => 'submit',
                'content' => '<i class="fa fa-floppy-o fa-lg"></i> Aceptar',
                'class' => 'btn btn-success push'
            );
            echo form_button($data);
            ?>
            <?php echo anchor('', '<i class="icon-remove"></i>Salir', 'class="btn"'); ?>
        </center>

    <?php echo form_close(); ?>
<style>
    .columna_derecha {
        float:right; /* Alineación a la derecha */
        width:310px;
        //border:solid lightblue 1px;
    }

    .columna_izquierda {
        float:left; /* Alineación a la izquierda */
        width:290px;
        //border:solid lightblue 1px;
    }

    .columna_central {
        margin-left:35%; /* Espacio para la columna izquierda */
        margin-right:30%; /* Espacio para la columna derecha */
        //border:solid navy 1px;
    }
</style>
<script>
    jQuery(".preload, .load").hide();
    $('.push').click(function() {
        $(".preload, .load").show();
        setTimeout(function() {
            jQuery(".preload, .load").hide();
        }, 2000);
    });
</script> 