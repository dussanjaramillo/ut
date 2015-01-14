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
    echo form_open("procesojudicial/Modificar_Titulo_Judicial", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $cod_titulo);
    ?>
    <?php echo $custom_error; ?>
    <br>



    <?php
    $campos = true;
    // }
    ?>
    <br><br>
    <div style="overflow: hidden; width: 30%; margin: 0 auto">
        <?php echo anchor('#', '<i class="fa fa-folder-open"></i> Datos de enlace Onbase', 'class="btn"'); ?>
    </div>
    <br>
    <div style="overflow: hidden; width: 75%; margin: 0 auto">
        <?php
        if ($campos) {
            ?>

            <h2 class="text-center">Modificar Títulos</h2>
            <br>     
            <div class="columna_izquierda">
                <div class="span3">
                    <?php
                    echo form_label('Nombre Regional <span class="required"></span>', 'codigo_reg');
                    ?>
                    <select name="codigo_reg" class="validate[required]" id="codigo_reg">
                        <option value="<?= $valor_titulo['COD_REGIONAL'] ?>"><?= $valor_titulo['NOMBRE_REGIONAL'] ?></option>
                        <?php
                        foreach ($regional as $row) {
                            ?>
                            <option value="<?= $row->COD_REGIONAL ?>"><?= $row->NOMBRE_REGIONAL ?></option>
                            <?php
                        }
                        ?>        
                    </select>
                </div>
                <div class="span3">
                    <?php
                    echo form_label('No. Escritura<span class="required"></span>', 'n_escritura');
                    $datan_escritura = array(
                        'name' => 'n_escritura',
                        'id' => 'n_escritura',
                        'value' => set_value('n_escritura'),
                        'maxlength' => '8',
                        'class' => 'validate[required,custom[integer], maxSize[8]',
                        'value' => $valor_titulo['NUM_ESCRITURA'],
                        'required' => 'required'
                    );
                    echo form_input($datan_escritura);
                    ?>
                </div>
                <div class="span3">
                    <?php
                    echo form_label('Notaria<span class="required"></span>', 'nombre_Not');
                    $datanombre_Not = array(
                        'name' => 'nombre_Not',
                        'id' => 'nombre_Not',
                        'value' => set_value('nombre_Not'),
                        'maxlength' => '3',
                        'class' => 'validate[required,custom[integer], maxSize[3]',
                        'value' => $valor_titulo['NOTARIA'],
                        'required' => 'required'
                    );

                    echo form_input($datanombre_Not);
                    ?>
                </div>
                <div class="span3"> 
                    <?php
                    echo form_label('Departamento <span class="required"></span>', 'lb_departamento');
                    ?>          
                    <select name="id_departamento" class="validate[required]" id="id_departamento">
                        <option value="<?= $valor_titulo['COD_DEPARTAMENTO'] ?>"><?= $valor_titulo['NOM_DEPARTAMENTO'] ?></option>
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
                    echo form_label('Ciudad <span class="required"></span>', 'id_ciudad');
                    ?>
                    <select name="id_ciudad" class="validate[required]" id="id_ciudad">
                        <option value="<?= $valor_titulo['CODMUNICIPIO'] ?>"><?= $valor_titulo['NOMBREMUNICIPIO'] ?></option>
                    </select>
                </div>
            </div>
            <div class="columna_derecha">
                <div class="span3">
                    <?php
                    echo form_label('Id. Deudor<span class="required"></span>', 'id_propietario');
                    $dataid_propietario = array(
                        'name' => 'id_propietario',
                        'id' => 'id_propietario',
                        'value' => set_value('id_propietario'),
                        'maxlength' => '15',
                        'class' => 'validate[required,custom[integer], maxSize[15]',
                        'value' => $valor_titulo['COD_PROPIETARIO'],
                        'required' => 'required'
                    );

                    echo form_input($dataid_propietario);
                    ?>
                </div>   
                <div class="span3">
                    <?php
                    echo form_label('Nombre Deudor<span class="required"></span>', 'n_propietario');
                    $datanombre_propietario = array(
                        'name' => 'nombre_propietario',
                        'id' => 'nombre_propietario',
                        'value' => set_value('nombre_propietario'),
                        'maxlength' => '100',
                        'class' => 'validate[required,custom[onlyLetterSp], maxSize[30]]',
                        'value' => $valor_titulo['NOMBRE_PROPIETARIO'],
                        'required' => 'required'
                    );

                    echo form_input($datanombre_propietario);
                    ?>
                </div>     
                <div class="span3">
                    <?php
                    echo form_label('Correo Electrónico del Deudor<span class="required"></span>', 'lccorreo');
                    $datacorreo_propietario = array(
                        'name' => 'correo_propietario',
                        'id' => 'correo_propietario',
                        'value' => set_value('correo_propietario'),
                        'maxlength' => '40',
                        'class' => 'validate[requered,custom[email]',
                        'value' => $valor_titulo['CORREO_PROPIETARIO'],
                        'required' => 'required'
                    );
                    echo form_input($datacorreo_propietario);
                    ?>
                </div>      
                <div class="span3">
                    <?php
                    echo form_label('Numero de Matricula Inmobiliaria<span class="required"></span>', 'lbnuminm');
                    $datanum_matr = array(
                        'name' => 'n_matri_inmov',
                        'id' => 'n_matri_inmov',
                        'value' => set_value('n_matri_inmov'),
                        'maxlength' => '15',
                        'class' => 'validate[required], maxSize[15]',
                        'value' => $valor_titulo['NUM_MATRICULA'],
                        'required' => 'required'
                    );
                    echo form_input($datanum_matr);
                    ?>
                </div>      
                <div class="span3">
                    <?php
                    echo form_label('Dirección del Inmueble<span class="required"></span>', 'dirinmueble');
                    $datadirinmueble = array(
                        'name' => 'dirinmueble',
                        'id' => 'dirinmueble',
                        'value' => set_value('dirinmueble'),
                        'maxlength' => '30',
                        'class' => 'validate[required, maxSize[30]',
                        'value' => $valor_titulo['DIRECCION_INMUEBLE'],
                        'required' => 'required'
                    );

                    echo form_input($datadirinmueble);
                    ?>
                </div>
            </div>

            <div style="overflow: hidden; width: 53%; margin: 0 auto">
                <br>
                <p class="pull-right">
                    <?php echo anchor('procesojudicial', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                    <?php
                    $data = array(
                        'name' => 'button',
                        'id' => 'submit-button',
                        'value' => 'Confirmar',
                        'type' => 'submit',
                        'content' => '<i class="fa fa-floppy-o fa-lg"></i> Modificar',
                        'class' => 'btn btn-success'
                    );

                    echo form_button($data);
                    ?>
                </p>
            </div>
        </div>   
    </div>
<?php } else { ?>
    <div class="text-center"><?php
        echo anchor('menuprocesosjudiciales', '<i class="icon-remove"></i> Regresar', 'class="btn"');
    }
    ?>
    <?php echo form_close(); ?>
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
