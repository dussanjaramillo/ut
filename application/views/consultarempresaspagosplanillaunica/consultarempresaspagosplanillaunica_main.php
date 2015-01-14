<?php
    if( ! defined('BASEPATH') ) exit('No direct script access allowed');
    if ( isset($message )) {
        echo $message;
    }
?>


<h1> Consultar pagos planilla </h1>

<div class="center-form-large">

<!-- INICIO Form -->
    <?php
        $attributes = array(
            'class' => 'form-horizontal resetf'
        );
        echo form_open(current_url(), $attributes); ?>
    <?php //echo $custom_error; ?>

    <fieldset>
        <div class="row-fluid">

<!-- INPUT Tipo Documento -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Tipo', 'tipo_id', $attributes);
                    ?>
                    <div class="controls">
                        <?php
                            foreach($tipo_id as $row) {
                                $select[$row->CODTIPODOCUMENTO] = $row->NOMBRETIPODOC;
                            }
                            $attributes = " id='tipo_id'";
                            echo form_dropdown('tipo_id', $select,'', $attributes);
                            echo form_error('tipo_id','<div>','</div>');
                        ?>
                    </div>
                </div>
            </div>

<!-- INPUT Identificacion -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Numero Identificacion', 'n_id', $attributes);
                    ?>
                    <div class="controls">
                    <?php
                        $attributes = array(
                            'name'        => 'n_id',
                            'id'          => 'n_id',
                            'class'       => '',
                            'value'       => set_value('n_id')
                        );
                        echo form_input($attributes);
                        echo form_error('n_id','<div>','</div>');
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">

<!-- INPUT Planilla -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Planilla', 'n_planilla', $attributes);
                    ?>
                    <div class="controls">
                    <?php
                        $attributes = array(
                            'name'        => 'n_planilla',
                            'id'          => 'n_planilla',
                            'class'       => '',
                            'value'       => set_value('n_planilla')
                        );
                        echo form_input($attributes);
                        echo form_error('n_planilla','<div>','</div>');
                    ?>
                    </div>
                </div>
            </div>

<!-- INPUT Regional -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Regional', 'regional_id', $attributes);
                    ?>
                    <div class="controls">
                        <?php
                        $selectr = array(
                            '' => 'Seleccione...'
                        );
                        foreach($regional as $row) {
                            $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
                        }
                        $attributes = " id='regional_id'";
                        echo form_dropdown('regional_id', $selectr, '', $attributes);
                        echo form_error('regional_id','<div>','</div>');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">

<!-- INPUT Razon Social -->
            <div class="span12">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Razon Social', 'n_razo_soci', $attributes);
                    ?>
                    <div class="controls">
                    <?php
                        $attributes = array(
                            'name'        => 'n_razo_soci',
                            'id'          => 'n_razo_soci',
                            'class'       => '',
                            'value'       => set_value('n_razo_soci'),
                            'class'       => 'input-block-level'
                        );
                        echo form_input($attributes);
                        echo form_error('n_razo_soci','<div>','</div>');
                    ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">

<!-- INPUT DatePicker -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Fecha Inicial', 'n_date_init', $attributes);
                    ?>
                    <div class="controls input-append span6">
                    <?php
                        $attributes = array(
                            'name'        => 'n_date_init',
                            'id'          => 'n_date_init',
                            'value'       => set_value('n_date_init'),
                            'class'       => 'input-block-level',
                            'value'       => 'dd/mm/aaaa'
                        );
                        echo form_input($attributes);
                        ?><span class="add-on"><i class="icon-calendar"></i></span><?php
                        echo form_error('n_date_init','<div>','</div>');
                    ?>
                    </div>
                </div>
            </div>

<!-- INPUT DatePicker -->
            <div class="span6">
                <div class="control-group">
                    <?php
                        $attributes = array(
                            'class' => 'control-label'
                        );
                        echo form_label('Fecha Inicial', 'n_date_end', $attributes);
                    ?>
                    <div class="controls input-append span6">
                    <?php
                        $attributes = array(
                            'name'        => 'n_date_end',
                            'id'          => 'n_date_end',
                            'value'       => set_value('n_date_end'),
                            'class'       => 'input-block-level',
                            'value'       => 'dd/mm/aaaa'
                        );
                        echo form_input($attributes);
                        ?><span class="add-on"><i class="icon-calendar"></i></span><?php
                        echo form_error('n_date_end','<div>','</div>');
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
<!-- BUTTON Consultar -->
            <?php

                $data = array(
                       'name'       => 'button',
                       'id'         => 'consult_but_plan',
                       'value'      => 'Consultar',
                       // 'type'       => 'submit',
                       'content'    => '<i class="fa fa-search fa-lg"></i> Consultar',
                       'class'      => 'btn btn-success offset8'
                       );

                echo form_button($data);

            ?>

<!-- BUTTON Limpiar -->
            <?php
                $data = array(
                       'name'       => 'button',
                       'id'         => 'consult_dele',
                       'class'      => 'reset',
                       'value'      => 'Limpiar',
                       'content'    => '<i class="fa fa-trash fa-lg"></i> Limpiar',
                       'class'      => 'btn btn-danger '
                       );

                echo form_button($data);
            ?>
        </div>

        <br>
        <hr>

        <div id="cons_empr_plan_row_extr" class="row-fluid">

<!-- BUTTON Inactivar Periodos -->
            <?php
                $data = array(
                       'name'       => 'button',
                       'id'         => 'consult_but_plan',
                       'value'      => 'Inactivar Periodos',
                       // 'type'       => 'submit',
                       'content'    => '<i class="fa fa-searc fa-lg"></i> Inactivar Periodos',
                       'class'      => 'btn btn-default'
                       );

                echo form_button($data);
            ?>

<!-- BUTTON Generar Certificados -->
            <?php
                $data = array(
                       'name'       => 'button',
                       'id'         => 'consult_but_plan',
                       'value'      => 'Generar Certificados',
                       // 'type'       => 'submit',
                       'content'    => '<i class="fa fa-searc fa-lg"></i> Generar Certificados',
                       'class'      => 'btn btn-default'
                       );

                echo form_button($data);
            ?>

<!-- BUTTON Generar Devolucion -->
            <?php
                $data = array(
                       'name'       => 'button',
                       'id'         => 'consult_but_plan',
                       'value'      => 'Generar Devolucion',
                       // 'type'       => 'submit',
                       'content'    => '<i class="fa fa-searc fa-lg"></i> Generar Devolucion',
                       'class'      => 'btn btn-default'
                       );

                echo form_button($data);
            ?>
        </div>

    </fieldset>

<!-- FORM END -->
    <?php echo form_close(); ?>
<!-- DIV END - center-form-large -->
</div>

<br>

<!-- TABLE -->
<table id="tablaq">
    <thead>
        <tr>
            <th> NIT </th>
            <th> Razón social </th>
            <th> Planilla </th>
            <th> Estado </th>
            <th> Regional </th>
            <th> Valor</th>
            <th> Registro 1 </th>
            <th> Registro 2 </th>
            <th> Registro 3 </th>
            <th> Registro banco </th>
            <th> Sel </th>
        </tr>
    </thead>
<tbody></tbody>
    <tfoot>
        <tr>
            <th> NIT </th>
            <th> Razón social </th>
            <th> Planilla </th>
            <th> Estado </th>
            <th> Regional </th>
            <th> Valor</th>
            <th> Registro 1 </th>
            <th> Registro 2 </th>
            <th> Registro 3 </th>
            <th> Registro banco </th>
            <th> Sel </th>
        </tr>
    </tfoot>
</table>

<!-- | ::::::: JS -->
<script type="text/javascript" language="javascript" charset="utf-8">

$(document).ready(function() {
// |::::::: JS  Date Picker
    $('#n_date_init').datepicker();
    $('#n_date_end').datepicker();

// |::::::: JS - DataTable
    oTable = $('#tablaq').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo base_url(); ?>index.php/consultarempresaspagosplanillaunica/dataTable",
        "sServerMethod": "POST",
        "sPaginationType": "full_numbers",
        "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ]
    });

// |::::::: TableTools - Imprime en PDF, excel
    var oTableTools = new TableTools( oTable, {
        "aButtons": [
            "xls",
            "pdf",
            "print"
        ],
        "sSwfPath" : "<?php echo base_url(); ?>js/media/swf/copy_csv_xls_pdf.swf"
    } );

    $('#cons_empr_plan_row_extr').append( oTableTools.dom.container );

// |::::::: INPUT Identificacion
    $('#n_id').change(function(){
          oTable.fnFilter( $(this).val() );
    });

// |::::::: INPUT Planilla
    $('#n_planilla').change(function(){
          oTable.fnFilter( $(this).val() );
    });

// |::::::: INPUT Regional
    $('#regional_id').change(function(){
          oTable.fnFilter( $("#regional_id option:selected").text() );
    });

// |::::::: INPUT Razon Social
    $('#n_razo_soci').change(function(){
          oTable.fnFilter( $(this).val(), 1, null, null, null, true  );
    });

// |::::::: INPUT DatePicker
    $('#n_date_init').change( function() { oTable.fnDraw(); } );
    $('#n_date_end' ).change( function() { oTable.fnDraw(); } );

    var y = $(window).scrollTop();
    $("#consult_but_plan").on("click" ,function(){
        $(window).scrollTop(y+280).animate();
        // $(window).animate(scrollTop(y+280), 600);
        // $(window).animate({ scrollTop: y + 280 }, 600);
    });

// |::::::: Limpiar formulario
    $("#consult_dele").click(function() {
        $('.resetf')[0].reset();
        $('.search-query').val('');
    });

});

</script>

<?php
// BORRAR ::::::::::::::::::::::::::::::::::::::::::::::::::::
// echo 'EMPRESA';
// echo '<pre>'; print_r($dump_e); echo '</pre>';

// echo 'CARGA_EXTRACTO';
// echo '<pre>'; print_r($dump_r); echo '</pre>';

// echo 'PLANILLAUNICA_ENC';
// echo '<pre>'; print_r($dump_m); echo '</pre>';

// echo 'PLANILLAUNICA_DET';
// echo '<pre>'; print_r($dump_o); echo '</pre>';
// echo '<pre>'; print_r($this->datatables); echo '</pre>';

// BORRAR FIN ::::::::::::::::::::::::::::::::::::::::::::::::
?>

