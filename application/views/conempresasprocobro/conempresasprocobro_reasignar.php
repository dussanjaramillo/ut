<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (isset($message)) {
    echo $message;
}
?>
<!--// |:::::Inicio formulario principal.-->
<div class="center-form-large">
    <?php echo form_open(current_url()); ?>
    <?php echo $custom_error; ?>
    <!--// |:::::Campos ocultos necesarios para la insercion-->
    <?php echo form_hidden('id', $result->CODEMPRESA) ?>
    <?php echo form_hidden('city', $result->COD_MUNICIPIO) ?>
    <?php
    if (isset($asignada->ASIGNADO_A))
        echo form_hidden ('fiscal_actual', $asignada->ASIGNADO_A);
    else
        echo form_hidden('fiscal_actual', '');
        ?>
    <?php 
    if (isset($asignada->ASIGNADO_A))
    echo form_hidden('cod_asignacion', $asignada->COD_ASIGNACIONFISCALIZACION) ;
    else
    echo form_hidden('cod_asignacion', '') ;
            ?>
    <?php
    $data_regional = array(
        'name' => 'regional_user',
        'id' => 'regional_user',
        'value' => $this->session->userdata('regional'),
        'type' => 'hidden'
    );
    echo form_input($data_regional);
    ?>


    <h2>Reasignar Manualmente Una Fiscalizacion</h2>
    <div class="controls controls-row">
        <div class="span3">
<?php
echo form_label('NIT<span class="required">*</span>', 'nit');
$data = array(
    'name' => 'nit',
    'id' => 'nit',
    'value' => $result->CODEMPRESA,
    'maxlength' => '12',
    'class' => 'span3',
    'readonly' => 'readonly'
);

echo form_input($data);
echo form_error('nit', '<div style="color: red">', '</div>');
?>
        </div>
        <div class="span3">
            <?php
            echo form_label('Ciudad<span class="required">*</span>', 'ciudad');
            foreach ($ciudad as $row) {
                $selectci[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;
            }
            echo form_dropdown('ciudad', $selectci, $result->COD_MUNICIPIO, 'id="ciudad" class="span3" disabled="disabled" data-placeholder="seleccione..." ');

            echo form_error('ciudad', '<div style="color: red">', '</div>');
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span3">
<?php
echo form_label('Razón Social<span class="required">*</span>', 'razonsocial');
$data = array(
    'name' => 'razonsocial',
    'id' => 'razonsocial',
    'value' => $result->RAZON_SOCIAL,
    'maxlength' => '12',
    'class' => 'span3',
    'readonly' => 'readonly'
);

echo form_input($data);
echo form_error('razonsocial', '<div style="color: red">', '</div>');
?>
        </div>
        <div class="span3">
            <?php
            echo form_label('Dirección<span class="required">*</span>', 'direccion');
            $data = array(
                'name' => 'direccion',
                'id' => 'direccion',
                'value' => $result->DIRECCION,
                'maxlength' => '100',
                'class' => 'span3',
                'readonly' => 'readonly'
            );

            echo form_input($data);
            echo form_error('direccion', '<div style="color: red">', '</div>');
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span3">
            <?php
            echo form_label('CIIU<span class="required">*</span>', 'ciiu');
            $data = array(
                'name' => 'ciiu',
                'id' => 'ciiu',
                'value' => $result->CIIU,
                'maxlength' => '12',
                'class' => 'span3',
                'readonly' => 'readonly'
            );

            echo form_input($data);
            echo form_error('ciiu', '<div style="color: red">', '</div>');
            ?>
        </div>


        <div class="span3">
            <?php
            echo form_label('Empresa<span class="required">*</span>', 'empresa');
            foreach ($tiposempresa as $row) {
                $selectcti[$row->CODTIPOEMPRESA] = $row->NOM_TIPO_EMP;
            }
            echo form_dropdown('empresa', $selectcti, $result->COD_TIPOEMPRESA, 'id="empresa" class="span3" disabled="disabled" data-placeholder="seleccione..." ');

            echo form_error('empresa', '<div style="color: red">', '</div>');
            ?>

        </div>
    </div>
    <div class="controls controls-row">
        <div class="span3">
            <?php
            echo form_label('Regional<span class="required">*</span>', 'regional_id');
            $selectr = array(
                '' => 'Seleccione...'
            );
            foreach ($regional as $row) {
                $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
            }
            echo form_dropdown('regional_id', $selectr, '', 'id="regional_id" class="span3" data-placeholder="seleccione..." ');

            echo form_error('regional_id', '<div style="color: red">', '</div>');
            ?>
        </div>
        <div class="span3">
            <?php
            echo form_label('Fiscalizador', 'fiscalizadorr');
            ?>
            <select name="fiscalizador" id="fiscalizador" class="span4">
                <option value="">Seleccione...</option>
            </select>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span3">
            <?php
            echo form_label('Motivo Reasignacion<span class="required">*</span>', 'motivo');
            $selectm = array('' => 'Seleccione...');
            foreach ($motivos as $row) {
                $selectm[$row->COD_MOTIVO_REASIGNACION] = $row->NOMBRE_MOTIVO;
            }

            echo form_dropdown('motivo', $selectm, '', 'id="motivo" class="span3" onclick="seleccion();" placeholder="seleccione..." ');


            echo form_error('motivo', '<div style="color: red">', '</div>');
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span3">
            <?php
            echo form_label('Observaciones y Comentarios<span class="required">*</span>', 'comentarios');
            $data = array(
                'name' => 'comentarios',
                'id' => 'comentarios',
                'value' => set_value('comentarios'),
                'maxlength' => '200',
                'rows' => '4',
                'class' => 'span6',
                'required' => 'required'
            );

            echo form_textarea($data);
            echo form_error('comentarios', '<div style="color: red">', '</div>');
            ?>
        </div>
    </div>

    <div class="controls controls-row">
        <p align="center">

            <?php echo anchor('conempresasprocobro', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'submit-button',
                'value' => 'Guardar',
                'type' => 'submit',
                'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                'class' => 'btn btn-success'
            );

            echo form_button($data);
            ?>

        </p>
    </div>
    <!--// |:::::Final formulario principal.-->  




    <!-- Se inicializa la ventana modal UGPP -->
    <div id="ugpp" class="modal hide fade" tabindex="-1" data-width="760" style="">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h2>Datos UGPP</h2>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span10">

<?php
echo form_label('Hallazgo', 'hallazgo');
$data = array(
    'name' => 'hallazgo',
    'id' => 'hallazgo',
    'value' => set_value('hallazgo'),
    'maxlength' => '20',
    'class' => 'span10',
);

echo form_input($data);
echo form_error('hallazgo', '<div style="color: red">', '</div>');
?>
                    <?php
                    echo form_label('Radicado', 'radicado');
                    $data = array(
                        'name' => 'radicado',
                        'id' => 'radicado',
                        'value' => set_value('radicado'),
                        'maxlength' => '20',
                        'class' => 'span10',
                    );

                    echo form_input($data);
                    echo form_error('radicado', '<div style="color: red">', '</div>');
                    ?>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <p align="center">

                <button type="Reset" data-dismiss="modal" class="btn" onclick="destruir();">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="ocultar();">Aceptar</button>
            </p>
        </div>
    </div>
    <!--Fin del modal UGPP-->

    <!-- Se inicializa la ventana modal OnBase -->
    <div id="onbase" class="modal hide fade" tabindex="-1" data-width="760" style="">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h2>Datos De Enlace Con ONBASE</h2>
        </div>
        <div class="modal-body">
            <div class="row-fluid">
                <div class="span10">
<?php
echo form_label('Numero De Radicacion<span class="required">*</span>', 'radicacion');
$data = array(
    'name' => 'radicacion',
    'id' => 'radicacion',
    'value' => set_value('radicacion'),
    'maxlength' => '12',
    'class' => 'span10',
);

echo form_input($data);
echo form_error('radicacion<span class="required">*</span>', '<div style="color: red">', '</div>');
?>
                    <?php
                    echo form_label('NIS<span class="required">*</span>', 'nis');
                    $data = array(
                        'name' => 'nis',
                        'id' => 'nis',
                        'value' => set_value('nis'),
                        'maxlength' => '12',
                        'class' => 'span10',
                    );

                    echo form_input($data);
                    echo form_error('nis', '<div style="color: red">', '</div>');
                    ?>
                    <?php
                    echo form_label('Fecha De Radicado<span class="required">*</span>', 'fechar');
                    $data = array(
                        'name' => 'fechar',
                        'id' => 'fechar',
                        'value' => set_value('fechar'),
                        'class' => 'input-block-level span10',
                        'placeholder' => 'dd/mm/aaaa'
                    );

                    echo form_input($data);
                    echo form_error('fechar', '<div style="color: red">', '</div>');
                    ?>
                    <?php
                    echo form_label('Enviado Por<span class="required">*</span>', 'enviado');
                    $data = array(
                        'name' => 'enviado',
                        'id' => 'enviado',
                        'value' => set_value('enviado'),
                        'maxlength' => '80',
                        'class' => 'span10',
                    );

                    echo form_input($data);
                    echo form_error('enviado', '<div style="color: red">', '</div>');
                    ?>
                    <?php
                    echo form_label('Cargo<span class="required">*</span>', 'cargo_id');
                    foreach ($cargos as $row) {
                        $selectc[$row->IDCARGO] = $row->NOMBRECARGO;
                    }
                    echo form_dropdown('cargo_id', $selectc, '', 'id="cargo_id" class="" data-placeholder="seleccione..." ');

                    echo form_error('cargo_id', '<div style="color: red">', '</div>');
                    ?>
                    <?php
                    echo form_label('Observaciones<span class="required">*</span>', 'observaciones');
                    $data = array(
                        'name' => 'observaciones',
                        'id' => 'observaciones',
                        'value' => set_value('observaciones'),
                        'maxlength' => '500',
                        'rows' => '8',
                        'class' => 'span10',
                    );

                    echo form_textarea($data);
                    echo form_error('observaciones', '<div style="color: red">', '</div>');
                    ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <p align="center">
                <button type="button" data-dismiss="modal" class="btn" onclick="destruir();">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="ocultar();">Aceptar</button>
            </p>
        </div>
    </div>
    <!--Final del Modal OnBase-->
                    <?php echo form_close(); ?> 
</div>


<script type="text/javascript">

    function fnc(v) {
        alert(v);
    }

    //style selects
    function format(state) {
        if (!state.id)
            return state.text; // optgroup
        return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) {
            return m;
        }
    });


    $(document).ready(function() {
        $(".chosen").select2();

    });

</script>

<script type="text/javascript">
    function ocultar() {
        $("#ugpp").modal('hide');
        $("#onbase").modal('hide');
    }

    function destruir() {
        $("#radicacion").val(null);
        $("#nis").val(null);
        $("#fechar").val(null);
        $("#enviado").val(null);
        $("#cargo_id").val(null);
        $("#observaciones").val(null);
        $("#hallazgo").val(null);
        $("#radicado").val(null);


    }
    //:::::Script que permite traer el valor del form_dropdown motivo
    function seleccion() {
        $("#motivo").change(function() {
            selection = $(this).val();
            if (selection == 7) {
                //alert("el evento onclick esta llamando la funcion, ugpp"); 
                $("#ugpp").modal('show');
                $("#radicacion").val(null);
                $("#nis").val(null);
                $("#fechar").val(null);
                $("#enviado").val(null);
                $("#cargo_id").val(null);
                $("#observaciones").val(null);
            } else {
                if (selection == 6) {
<?php $sel = 6; ?>
                    //alert("el evento onclick esta llamando la funcion, onbase");
                    $("#onbase").modal('show');
                    $("#hallazgo").val(null);
                    $("#radicado").val(null);

                    /*
                     *llamar contenido de las vistas via ajax
                     $('.modal-body').load('<?php echo base_url(); ?>index.php/bancos/add',function(result){
                     $('#myModal').modal({show:true});
                     });*/
                }
            }
        });
    }
</script>


<!--Script para select dependientes-->
<script type="text/javascript">
    $(document).ready(function() {
        $("#regional_id").change(function() {
            $("#regional_id option:selected").each(function() {
                regional_id = $('#regional_id').val();
                $.post("<?php echo base_url(); ?>index.php/conempresasprocobro/llenarfiscalizadores", {
                    regional_id: regional_id
                }, function(data) {
                    $("#fiscalizador").html(data);
                });
            });
        })
    });
</script>

<!--// |::::::: JS  Date Picker -->
<script type="text/javascript" language="javascript" charset="utf-8">

    $(document).ready(function() {

        $('#fechar').datepicker({
            defaultDate: "-1w",
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            maxDate: "0",
            minDate: "-5y",
            changeYear: true,
        });

    });
</script>

<!-- Script para dehabilitar el campo fiscalizador si se escoje un valor diferente a la regional asociada al usuario logueado -->
<script type="text/javascript">
    $('#regional_id').on('change', function() {
        var valor = $(this).val();
        var regional_user = $('#regional_user').val();
        if (valor != regional_user) {

            $('#fiscalizador').prop('disabled', 'disabled');

        } else {
            $('#fiscalizador').prop('disabled', false);
        }

        //alert(valor);
    });
</script>