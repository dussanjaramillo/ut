<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php 
    $attributes = array("id" => "myform");
    if ($demanda_n == 's') {
        echo form_open_multipart("procesojudicial/Agregar_Nueva_Demanda", $attributes);
    } else {
        echo form_open_multipart("procesojudicial/Agregar_Presentar_Demanda", $attributes);
    }
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $id_titulo);
    ?>
    <?php echo $custom_error; ?>
    <br>
    <br>
    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } $campos = true; ?>
    </div>
    <div style="overflow: hidden; width: 72%; margin: 0 auto">
        <?php
        //echo print_r($file);
        if ($campos) {
            //form_hidden('nombre_archivo', $file['upload_data']['file_name']);
            ?>

            <h2 class="text-center">Presentar Demanda</h2>
            <br>     
            <div class="columna_izquierda">
                <div class="span3">
                    <?php
                    echo form_label('No. Radicacion de Proceso<span class="required"></span>', 'lbn_radicacion');
                    $datan_radicacion = array(
                        'name' => 'n_radicacion',
                        'id' => 'n_radicacion',
                        'value' => set_value('n_radicacion'),
                        'maxlength' => '23',
                        'class' => 'validate[required, minSize[23]]',
                        'required' => 'required'
                    );
                    echo form_input($datan_radicacion);
                    ?>
                </div>
                <div class="span3">
                    <?php
                    echo form_label('Juzgado<span class="required"></span>', 'lbnom_juzgado');
                    $datanom_juzgado = array(
                        'name' => 'nom_juzgado',
                        'id' => 'nom_juzgado',
                        'value' => set_value('nom_juzgado'),
                        'maxlength' => '30',
                        'class' => 'validate[required, maxSize[30]]',
                        'required' => 'required'
                    );

                    echo form_input($datanom_juzgado);
                    ?>
                </div>
                <div class="span3"> 
                    <?php
                    echo form_label('Departamento <span class="required"></span>', 'lb_departamento');
                    echo form_error('lb_departamento', '<div>', '</div>');
                    ?>          
                    <select name="id_departamento" class="validate[required]" id="id_departamento">
                        <?php
                        foreach ($departamento as $row) {
                            ?>
                            <option value="<?= $row->COD_DEPARTAMENTO ?>"><?= $row->NOM_DEPARTAMENTO ?></option>
                            <?php
                        }
                        ?>        
                    </select>
                </div>
                <div class="span3">
                    <?php
                    echo form_label('Ciudad <span class="required"></span>', 'lbid_ciudad');
                    ?>
                    <select name="id_ciudad" class="validate[required]" id="id_ciudad">
                        <option value="">Seleccione una ciudad...</option>
                    </select>
                </div>
            </div>
            <div class="columna_derecha">
                <div class="span3">
                    <?php
                    echo form_label('Id. Demandado<span class="required"></span>', 'lbid_demandado');
                    $dataid_demandado = array(
                        'name' => 'id_demandado',
                        'id' => 'id_demandado',
                        'value' => set_value('id_demandado'),
                        'maxlength' => '15',
                        'class' => 'validate[validate[required, maxSize[15]',
                        'required' => 'required'
                    );

                    echo form_input($dataid_demandado);
                    ?>
                </div>   
                <div class="span3">
                    <?php
                    echo form_label('T.P del Abogado Demandante<span class="required"></span>', 'lbtpabogado');
                    $datatpabogado = array(
                        'name' => 'tpabogado',
                        'id' => 'tpabogado',
                        'value' => set_value('tpabogado'),
                        'maxlength' => '15',
                        'class' => 'validate[required, maxSize[15]',
                        'required' => 'required'
                    );
                    echo form_input($datatpabogado);
                    ?>
                </div>           
                <div class="span3">
                    <?php
                    echo form_label('Nombre del Demandado<span class="required"></span>', 'lbnombredemandado');
                    $datanombredemandado = array(
                        'name' => 'nombredemandado',
                        'id' => 'nombredemandado',
                        'value' => set_value('nombredemandado'),
                        'maxlength' => '30',
                        'class' => 'validate[required, maxSize[30]',
                        'required' => 'required'
                    );

                    echo form_input($datanombredemandado);
                    ?>
                </div>
            </div>

            <div class="text-center">
                <p class="pull-right">
                    <br>
                    <?php 
                    if ($demanda_n == 's') {
                        echo anchor('procesojudicial/Lista_Nueva_Demanda', '<i class="icon-remove"></i> Cancelar', 'class="btn"');
                    } else {
                        echo anchor('procesojudicial/Lista_Presentar_Demanda', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); 
                    }
                    ?>
                    <?php
                    $data = array(
                        'name' => 'button',
                        'id' => 'submit-button',
                        'value' => 'Confirmar',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
                        'class' => 'btn btn-success'
                    );

                    echo form_button($data);
                    ?>

                </p>               
            </div>             
        </div>   
        <br>
        <center>
            <?php
            echo '<div class="subir_documento" id="subir_documento">';
            echo form_label('<b>Documento Soporte de la Demanda<span class="required"></span></b><br>', 'lb_cargar1');
            echo '<div class="alert-success" style="width: 71%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
            $data = array(
                'multiple' => '',
                'name' => 'userfile[]',
                'id' => 'userfile',
                'class' => 'validate[required]'
            );
            echo form_upload($data);
            echo '<br><br>';
            echo '</div>';
            echo '<br>';
            echo '</div>';
            ?>
        </center>
    </div>
<?php } else { ?>
    <div class="text-center"><?php
        echo anchor('presentardemanda/carga', '<i class="icon-remove"></i> Regresar', 'class="btn"');
    }
    ?>
    <?php echo form_close(); ?>
    <script>
        jQuery(".preload, .load").hide();
        $("#id_departamento").change(function() {
            $("#id_departamento option:selected").each(function() {
                $(".preload, .load").show();
                departamento = $('#id_departamento').val();
                $.post("llena_ciudad", {
                    id_departamento: departamento
                }, function(data) {
                    $("#id_ciudad").html(data);
                    jQuery(".preload, .load").hide();
                });
            });
        });
    </script>

    <style type="text/css">
        div.preload{
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: white;
            opacity: 0.8;
            z-index: 10000;
        }

        div img.load{
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -64px;
            margin-top: -64px;
            z-index: 15000;
        }
        .columna_derecha {
            float:right; /* Alineaci�n a la derecha */
            width:250px;
            //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineaci�n a la izquierda */
            width:250px;
            //border:solid lightblue 1px;

        }
        .columna_central {
            margin-left:450px; /* Espacio para la columna izquierda */
            margin-right:350px; /* Espacio para la columna derecha */
            //border:solid navy 1px;

        } 
    </style>